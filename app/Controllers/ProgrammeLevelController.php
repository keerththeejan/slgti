<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class ProgrammeLevelController
{
    public function index(): void
    {
        $levels = Model::fetchAll(
            "SELECT l.*, (SELECT COUNT(*) FROM programmes p WHERE p.level_id = l.id AND p.status = 'published') AS programme_count
             FROM programme_levels l WHERE l.status = 'published' ORDER BY l.is_featured DESC, l.sort_order ASC, l.id ASC"
        );
        view('programme-levels/index', [
            'levels' => $levels,
            'metaTitle' => 'Programme Levels | SLGTI',
            'metaDescription' => 'Explore SLGTI qualification and NVQ programme levels.',
        ]);
    }

    public function show(string $slug): void
    {
        $level = Model::fetch(
            "SELECT l.* FROM programme_levels l WHERE (l.slug = ? OR l.seo_slug = ?) AND l.status = 'published'",
            [$slug, $slug]
        );
        if (!$level) {
            $redirect = Model::fetch('SELECT level_id FROM programme_level_slug_redirects WHERE old_slug = ?', [$slug]);
            if ($redirect) {
                $target = Model::fetch("SELECT slug, seo_slug FROM programme_levels WHERE id = ? AND status = 'published'", [(int) $redirect['level_id']]);
                if ($target) {
                    header('Location: ' . absolute_url('programme-levels/' . ($target['seo_slug'] ?: $target['slug'])), true, 301);
                    exit;
                }
            }
            abort(404);
        }
        $programmes = Model::fetchAll(
            "SELECT p.*, d.name AS department_name, d.slug AS department_slug
             FROM programmes p INNER JOIN departments d ON d.id = p.department_id
             WHERE p.level_id = ? AND p.status = 'published' AND d.status = 'published'
             ORDER BY p.sort_order ASC, p.id ASC",
            [(int) $level['id']]
        );
        $canonical = filter_var((string) $level['canonical_url'], FILTER_VALIDATE_URL) ? $level['canonical_url'] : absolute_url('programme-levels/' . ($level['seo_slug'] ?: $level['slug']));
        view('programme-levels/show', [
            'level' => $level,
            'programmes' => $programmes,
            'metaTitle' => $level['seo_title'] ?: $level['name'] . ' | SLGTI',
            'metaDescription' => $level['meta_description'] ?: ($level['short_description'] ?: $level['description']),
            'metaCanonical' => $canonical,
            'metaRobots' => ($level['robots_index'] ?: 'index') . ', ' . ($level['robots_follow'] ?: 'follow'),
            'metaImage' => media_url($level['og_image'] ?: $level['image']),
            'metaTwitterImage' => media_url($level['twitter_image'] ?: ($level['og_image'] ?: $level['image'])),
            'metaOgTitle' => $level['og_title'] ?: ($level['seo_title'] ?: $level['name'] . ' | SLGTI'),
            'metaOgDescription' => $level['og_description'] ?: ($level['meta_description'] ?: $level['description']),
            'structuredData' => $this->structuredData($level, $canonical),
        ]);
    }

    private function structuredData(array $level, string $canonical): array
    {
        $custom = !empty($level['structured_data']) ? json_decode((string) $level['structured_data'], true) : null;
        $data = is_array($custom) ? $custom : ['@context' => 'https://schema.org', '@type' => 'EducationalOccupationalCredential', 'name' => $level['name'], 'description' => $level['description'], 'url' => $canonical];
        $data['breadcrumb'] = ['@type' => 'BreadcrumbList', 'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => absolute_url('')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Programme levels', 'item' => absolute_url('programme-levels')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $level['name'], 'item' => $canonical],
        ]];
        return $data;
    }
}
