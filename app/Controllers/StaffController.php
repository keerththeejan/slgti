<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class StaffController
{
    public function index(): void
    {
        $departmentId = max(0, (int) ($_GET['department_id'] ?? 0));
        $q = trim((string) ($_GET['q'] ?? ''));
        $where = ["s.status = 'published'", "s.display_location IN ('directory', 'both')"];
        $params = [];
        if ($departmentId > 0) {
            $where[] = 's.department_id = ?';
            $params[] = $departmentId;
        }
        if ($q !== '') {
            $where[] = '(s.name LIKE ? OR s.position LIKE ? OR s.category LIKE ? OR s.staff_id LIKE ?)';
            array_push($params, ...array_fill(0, 4, '%' . $q . '%'));
        }
        $clause = implode(' AND ', $where);
        view('staff/index', [
            'staff' => Model::fetchAll("SELECT s.*, d.name AS department_name FROM staff s LEFT JOIN departments d ON d.id = s.department_id WHERE $clause ORDER BY s.sort_order ASC, s.id ASC", $params),
            'departments' => Model::fetchAll("SELECT id, name FROM departments WHERE status = 'published' ORDER BY name ASC"),
            'departmentId' => $departmentId,
            'query' => $q,
            'metaTitle' => 'Staff Directory | SLGTI',
            'metaDescription' => 'Meet the academic and administrative staff of SLGTI.',
            'metaCanonical' => absolute_url('staff'),
        ]);
    }

    public function show(string $slug): void
    {
        $person = Model::fetch(
            "SELECT s.*, d.name AS department_name FROM staff s LEFT JOIN departments d ON d.id = s.department_id WHERE s.slug = ? AND s.status = 'published' AND s.display_location IN ('directory', 'both')",
            [$slug]
        );
        if (!$person) {
            abort(404);
        }
        view('staff/show', [
            'person' => $person,
            'metaTitle' => $person['seo_title'] ?: $person['name'] . ' | SLGTI',
            'metaDescription' => $person['meta_description'] ?: excerpt(strip_tags((string) $person['bio']), 160),
            'metaCanonical' => $person['canonical_url'] ?: absolute_url('staff/' . $person['slug']),
            'metaRobots' => ($person['robots_index'] ?: 'noindex') . ', ' . ($person['robots_follow'] ?: 'follow'),
            'metaImage' => $person['og_image'] ? media_url($person['og_image']) : media_url($person['image']),
        ]);
    }
}
