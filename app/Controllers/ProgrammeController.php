<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class ProgrammeController
{
    public function index(): void
    {
        $level = trim((string) ($_GET['level'] ?? ''));
        $department = trim((string) ($_GET['department'] ?? ''));
        $query = trim((string) ($_GET['q'] ?? ''));
        $duration = trim((string) ($_GET['duration'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $levels = Model::fetchAll("SELECT * FROM programme_levels WHERE status = 'published' ORDER BY sort_order ASC");
        $selectedLevel = null;
        foreach ($levels as $availableLevel) {
            if ($availableLevel['slug'] === $level) {
                $selectedLevel = $availableLevel;
                break;
            }
        }
        if ($level !== '' && $selectedLevel === null) {
            $level = '';
        }
        $sql = "SELECT p.*, d.name AS department_name, d.slug AS department_slug, l.name AS level_name, l.code AS level_code, l.slug AS level_slug, l.qualification
                FROM programmes p
                INNER JOIN departments d ON d.id = p.department_id
                INNER JOIN programme_levels l ON l.id = p.level_id
                WHERE p.status = 'published' AND l.status = 'published'";
        $params = [];
        if ($level !== '') {
            $sql .= ' AND l.slug = ?';
            $params[] = $level;
        }
        if ($department !== '') {
            $sql .= ' AND d.slug = ?';
            $params[] = $department;
        }
        if ($query !== '') {
            $sql .= ' AND (p.title LIKE ? OR p.slug LIKE ? OR p.summary LIKE ? OR l.code LIKE ?)';
            array_push($params, "%{$query}%", "%{$query}%", "%{$query}%", "%{$query}%");
        }
        if ($duration !== '') {
            $sql .= ' AND p.duration LIKE ?';
            $params[] = "%{$duration}%";
        }
        $countSql = preg_replace('/^SELECT p\.\*.*?FROM programmes p/s', 'SELECT COUNT(*) AS total FROM programmes p', $sql);
        $total = (int) Model::fetch($countSql, $params)['total'];
        $pager = paginate($total, $page, 9);
        $sql .= " ORDER BY l.sort_order ASC, p.sort_order ASC, p.id ASC LIMIT {$pager['per_page']} OFFSET {$pager['offset']}";
        view('programmes/index', [
            'programmes' => Model::fetchAll($sql, $params),
            'levels' => $levels,
            'departments' => Model::fetchAll("SELECT * FROM departments WHERE status = 'published' ORDER BY sort_order ASC"),
            'activeLevel' => $level,
            'activeDepartment' => $department,
            'query' => $query,
            'duration' => $duration,
            'pager' => $pager,
            'selectedLevel' => $selectedLevel,
            'metaTitle' => $level !== '' ? 'NVQ ' . strtoupper(str_replace('nvq-', '', $level)) . ' Programmes | SLGTI' : 'NVQ Programmes | SLGTI',
            'metaDescription' => 'Industry-oriented NVQ Level 4, 5 and 6 programmes at SLGTI Kilinochchi.',
        ]);
    }

    public function show(string $slug): void
    {
        $programme = Model::fetch(
            "SELECT p.*, d.name AS department_name, d.slug AS department_slug, l.name AS level_name, l.code AS level_code, l.slug AS level_slug, l.seo_slug AS level_seo_slug, l.qualification, l.description AS level_description
             FROM programmes p
             INNER JOIN departments d ON d.id = p.department_id
             INNER JOIN programme_levels l ON l.id = p.level_id
             WHERE (p.slug = ? OR p.seo_slug = ?) AND p.status = 'published' AND l.status = 'published'",
            [$slug, $slug]
        );
        if (!$programme) {
            $redirect = Model::fetch('SELECT programme_id FROM programme_slug_redirects WHERE old_slug = ?', [$slug]);
            if ($redirect) {
                $target = Model::fetch("SELECT slug, seo_slug FROM programmes WHERE id = ? AND status = 'published'", [(int) $redirect['programme_id']]);
                if ($target) {
                    header('Location: ' . absolute_url('programmes/' . ($target['seo_slug'] ?: $target['slug'])), true, 301);
                    exit;
                }
            }
            abort(404);
        }
        $requirements = Model::fetchAll(
            "SELECT requirement, slug FROM programme_requirements WHERE programme_id = ? AND status = 'published' ORDER BY sort_order ASC, id ASC",
            [(int) $programme['id']]
        );
        $related = Model::fetchAll(
            "SELECT id, title, slug, seo_slug, duration FROM programmes WHERE department_id = ? AND id != ? AND status = 'published' ORDER BY sort_order ASC LIMIT 3",
            [(int) $programme['department_id'], (int) $programme['id']]
        );
        view('programmes/show', [
            'programme' => $programme,
            'related' => $related,
            'requirements' => $requirements,
            'metaTitle' => $programme['seo_title'] ?: $programme['title'] . ' | SLGTI',
            'metaDescription' => $programme['meta_description'] ?: $programme['summary'],
            'metaKeywords' => $programme['meta_keywords'] ?: '',
            'metaCanonical' => filter_var((string) $programme['canonical_url'], FILTER_VALIDATE_URL) ? $programme['canonical_url'] : absolute_url('programmes/' . ($programme['seo_slug'] ?: $programme['slug'])),
            'metaRobots' => ($programme['robots_index'] ?: 'index') . ', ' . ($programme['robots_follow'] ?: 'follow'),
            'metaImage' => media_url($programme['og_image'] ?: $programme['image']),
            'metaTwitterImage' => media_url($programme['twitter_image'] ?: ($programme['og_image'] ?: $programme['image'])),
            'metaOgTitle' => $programme['og_title'] ?: ($programme['seo_title'] ?: $programme['title'] . ' | SLGTI'),
            'metaOgDescription' => $programme['og_description'] ?: ($programme['meta_description'] ?: $programme['summary']),
            'structuredData' => $this->structuredData($programme),
            'brochure' => $programme['brochure_path'] ?: '',
        ]);
    }

    private function structuredData(array $programme): array
    {
        $canonical = filter_var((string) $programme['canonical_url'], FILTER_VALIDATE_URL)
            ? $programme['canonical_url']
            : absolute_url('programmes/' . ($programme['seo_slug'] ?: $programme['slug']));
        $custom = !empty($programme['structured_data']) ? json_decode((string) $programme['structured_data'], true) : null;
        if (is_array($custom)) {
            $structured = $custom;
        } else {
            $structured = [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $programme['title'],
            'description' => $programme['summary'],
            'url' => $canonical,
            'provider' => ['@type' => 'EducationalOrganization', 'name' => 'Sri Lanka German Training Institute', 'url' => absolute_url('')],
            'image' => media_url($programme['og_image'] ?: $programme['image']),
            'occupationalCategory' => $programme['qualification'],
            ];
        }
        $structured['breadcrumb'] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => absolute_url('')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Programmes', 'item' => absolute_url('programmes')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $programme['title'], 'item' => $canonical],
            ],
        ];
        return $structured;
    }
}
