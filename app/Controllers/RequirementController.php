<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class RequirementController
{
    public function index(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));
        $where = ["r.status = 'published'"];
        $params = [];
        if ($q !== '') {
            $where[] = '(r.requirement LIKE ? OR r.short_description LIKE ? OR r.description LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }
        if ($category !== '') {
            $where[] = 'r.category = ?';
            $params[] = $category;
        }
        $rows = Model::fetchAll(
            'SELECT r.*, p.title AS programme_title, d.name AS department_name
             FROM programme_requirements r LEFT JOIN programmes p ON p.id = r.programme_id AND p.status = \'published\'
             LEFT JOIN departments d ON d.id = r.department_id AND d.status = \'published\'
             WHERE ' . implode(' AND ', $where) . ' AND (r.programme_id IS NULL OR p.id IS NOT NULL) AND (r.department_id IS NULL OR d.id IS NOT NULL)
             ORDER BY r.is_featured DESC, r.sort_order ASC, r.id ASC',
            $params
        );
        view('requirements/index', [
            'requirements' => $rows,
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM programme_requirements WHERE status = 'published' AND category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'q' => $q,
            'category' => $category,
            'metaTitle' => 'Admissions Requirements | SLGTI',
            'metaDescription' => 'Review admission and programme requirements for SLGTI training pathways.',
        ]);
    }

    public function show(string $slug): void
    {
        $requirement = Model::fetch(
            "SELECT r.*, p.title AS programme_title, p.slug AS programme_slug, p.seo_slug AS programme_seo_slug, d.name AS department_name, d.slug AS department_slug
             FROM programme_requirements r
             LEFT JOIN programmes p ON p.id = r.programme_id AND p.status = 'published'
             LEFT JOIN departments d ON d.id = r.department_id AND d.status = 'published'
             WHERE r.slug = ? AND r.status = 'published'",
            [$slug]
        );
        if (!$requirement) {
            $redirect = Model::fetch('SELECT requirement_id FROM requirement_slug_redirects WHERE old_slug = ?', [$slug]);
            if ($redirect) {
                $target = Model::fetch("SELECT slug FROM programme_requirements WHERE id = ? AND status = 'published'", [(int) $redirect['requirement_id']]);
                if ($target) {
                    header('Location: ' . absolute_url('requirements/' . $target['slug']), true, 301);
                    exit;
                }
            }
            abort(404);
        }
        $canonical = filter_var((string) $requirement['canonical_url'], FILTER_VALIDATE_URL) ? $requirement['canonical_url'] : absolute_url('requirements/' . $requirement['slug']);
        view('requirements/show', [
            'requirement' => $requirement,
            'metaTitle' => $requirement['seo_title'] ?: $requirement['requirement'] . ' | SLGTI',
            'metaDescription' => $requirement['meta_description'] ?: ($requirement['short_description'] ?: $requirement['requirement']),
            'metaCanonical' => $canonical,
            'metaRobots' => ($requirement['robots_index'] ?: 'index') . ', ' . ($requirement['robots_follow'] ?: 'follow'),
            'metaImage' => media_url($requirement['og_image'] ?: $requirement['image']),
            'metaTwitterImage' => media_url($requirement['twitter_image'] ?: ($requirement['og_image'] ?: $requirement['image'])),
            'metaOgTitle' => $requirement['og_title'] ?: ($requirement['seo_title'] ?: $requirement['requirement'] . ' | SLGTI'),
            'metaOgDescription' => $requirement['og_description'] ?: ($requirement['meta_description'] ?: $requirement['short_description']),
            'structuredData' => $this->structuredData($requirement, $canonical),
        ]);
    }

    private function structuredData(array $requirement, string $canonical): array
    {
        $custom = !empty($requirement['structured_data']) ? json_decode((string) $requirement['structured_data'], true) : null;
        $data = is_array($custom) ? $custom : ['@context' => 'https://schema.org', '@type' => 'WebPage', 'name' => $requirement['requirement'], 'description' => $requirement['short_description'] ?: $requirement['requirement'], 'url' => $canonical];
        $data['breadcrumb'] = ['@type' => 'BreadcrumbList', 'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => absolute_url('')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Requirements', 'item' => absolute_url('requirements')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $requirement['requirement'], 'item' => $canonical],
        ]];
        return $data;
    }
}
