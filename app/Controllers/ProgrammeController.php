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
        $sql = "SELECT p.*, d.name AS department_name, d.slug AS department_slug, l.name AS level_name, l.code AS level_code, l.slug AS level_slug, l.qualification
                FROM programmes p
                INNER JOIN departments d ON d.id = p.department_id
                INNER JOIN programme_levels l ON l.id = p.level_id
                WHERE p.status = 'published'";
        $params = [];
        if ($level !== '') {
            $sql .= ' AND l.slug = ?';
            $params[] = $level;
        }
        if ($department !== '') {
            $sql .= ' AND d.slug = ?';
            $params[] = $department;
        }
        $sql .= ' ORDER BY l.sort_order ASC, p.sort_order ASC';
        view('programmes/index', [
            'programmes' => Model::fetchAll($sql, $params),
            'levels' => Model::fetchAll('SELECT * FROM programme_levels ORDER BY sort_order ASC'),
            'departments' => Model::fetchAll("SELECT * FROM departments WHERE status = 'published' ORDER BY sort_order ASC"),
            'activeLevel' => $level,
            'activeDepartment' => $department,
            'metaTitle' => 'NVQ Programmes | SLGTI',
            'metaDescription' => 'Industry-oriented NVQ Level 4, 5 and 6 programmes at SLGTI Kilinochchi.',
        ]);
    }

    public function show(string $slug): void
    {
        $programme = Model::fetch(
            "SELECT p.*, d.name AS department_name, d.slug AS department_slug, l.name AS level_name, l.code AS level_code, l.qualification, l.description AS level_description
             FROM programmes p
             INNER JOIN departments d ON d.id = p.department_id
             INNER JOIN programme_levels l ON l.id = p.level_id
             WHERE p.slug = ? AND p.status = 'published'",
            [$slug]
        );
        if (!$programme) {
            abort(404);
        }
        $requirements = Model::fetchAll(
            'SELECT requirement FROM programme_requirements WHERE programme_id = ? ORDER BY sort_order ASC, id ASC',
            [(int) $programme['id']]
        );
        $related = Model::fetchAll(
            "SELECT id, title, slug, duration FROM programmes WHERE department_id = ? AND id != ? AND status = 'published' ORDER BY sort_order ASC LIMIT 3",
            [(int) $programme['department_id'], (int) $programme['id']]
        );
        view('programmes/show', [
            'programme' => $programme,
            'related' => $related,
            'requirements' => $requirements,
            'metaTitle' => $programme['title'] . ' | SLGTI',
            'metaDescription' => $programme['summary'],
        ]);
    }
}
