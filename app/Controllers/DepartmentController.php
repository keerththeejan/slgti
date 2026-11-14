<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class DepartmentController
{
    public function index(): void
    {
        $departments = Model::fetchAll(
            "SELECT d.*, (SELECT COUNT(*) FROM programmes p WHERE p.department_id = d.id AND p.status = 'published') AS programme_count
             FROM departments d WHERE d.status = 'published' ORDER BY d.sort_order ASC"
        );
        view('departments/index', [
            'departments' => $departments,
            'metaTitle' => 'Academic Departments | SLGTI',
            'metaDescription' => 'Explore the six academic departments of the Sri Lanka German Training Institute.',
        ]);
    }

    public function show(string $slug): void
    {
        $department = Model::fetch("SELECT * FROM departments WHERE (slug = ? OR seo_slug = ?) AND status = 'published'", [$slug, $slug]);
        if (!$department) {
            $redirect = Model::fetch('SELECT department_id FROM department_slug_redirects WHERE old_slug = ?', [$slug]);
            if ($redirect) {
                $target = Model::fetch("SELECT slug, seo_slug FROM departments WHERE id = ? AND status = 'published'", [(int) $redirect['department_id']]);
                if ($target) {
                    $targetSlug = $target['seo_slug'] ?: $target['slug'];
                    header('Location: ' . absolute_url('departments/' . $targetSlug), true, 301);
                    exit;
                }
            }
            abort(404);
        }
        $programmes = Model::fetchAll(
            "SELECT p.*, l.name AS level_name, l.code AS level_code, l.qualification AS level_qualification
             FROM programmes p INNER JOIN programme_levels l ON l.id = p.level_id
             WHERE p.department_id = ? AND p.status = 'published'
             ORDER BY l.sort_order ASC, p.sort_order ASC",
            [(int) $department['id']]
        );
        $staff = Model::fetchAll(
            "SELECT * FROM staff WHERE department_id = ? AND status = 'published' AND display_location IN ('department', 'both') ORDER BY sort_order ASC, id ASC",
            [(int) $department['id']]
        );
        $requirements = Model::fetchAll(
            "SELECT id, requirement, slug, short_description FROM programme_requirements WHERE department_id = ? AND status = 'published' ORDER BY sort_order ASC, id ASC",
            [(int) $department['id']]
        );
        $pathSlug = $department['seo_slug'] ?: $department['slug'];
        $canonical = filter_var((string) $department['canonical_url'], FILTER_VALIDATE_URL)
            ? $department['canonical_url']
            : absolute_url('departments/' . $pathSlug);
        $structuredData = null;
        if (!empty($department['structured_data'])) {
            $structuredData = json_decode((string) $department['structured_data'], true);
        }
        if (!is_array($structuredData)) {
            $structuredData = [
                '@context' => 'https://schema.org',
                '@type' => 'EducationalOrganization',
                'name' => $department['name'],
                'description' => $department['summary'],
                'url' => $canonical,
                'image' => media_url($department['og_image'] ?: $department['image']),
            ];
        }
        $structuredData['breadcrumb'] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => absolute_url('')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Departments', 'item' => absolute_url('departments')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $department['name'], 'item' => $canonical],
            ],
        ];
        view('departments/show', [
            'department' => $department,
            'programmes' => $programmes,
            'staff' => $staff,
            'requirements' => $requirements,
            'programmeCount' => count($programmes),
            'staffCount' => count($staff),
            'metaTitle' => $department['seo_title'] ?: $department['name'] . ' | SLGTI',
            'metaDescription' => $department['meta_description'] ?: $department['summary'],
            'metaKeywords' => $department['meta_keywords'] ?: '',
            'metaCanonical' => $canonical,
            'metaRobots' => ($department['robots_index'] ?: 'index') . ', ' . ($department['robots_follow'] ?: 'follow'),
            'metaImage' => media_url($department['og_image'] ?: $department['image']),
            'metaTwitterImage' => media_url($department['twitter_image'] ?: ($department['og_image'] ?: $department['image'])),
            'metaOgTitle' => $department['og_title'] ?: ($department['seo_title'] ?: $department['name'] . ' | SLGTI'),
            'metaOgDescription' => $department['og_description'] ?: ($department['meta_description'] ?: $department['summary']),
            'structuredData' => $structuredData,
        ]);
    }
}
