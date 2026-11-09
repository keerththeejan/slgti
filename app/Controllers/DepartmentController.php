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
        $department = Model::fetch("SELECT * FROM departments WHERE slug = ? AND status = 'published'", [$slug]);
        if (!$department) {
            abort(404);
        }
        $programmes = Model::fetchAll(
            "SELECT p.*, l.name AS level_name, l.code AS level_code
             FROM programmes p INNER JOIN programme_levels l ON l.id = p.level_id
             WHERE p.department_id = ? AND p.status = 'published'
             ORDER BY l.sort_order ASC, p.sort_order ASC",
            [(int) $department['id']]
        );
        $staff = Model::fetchAll(
            "SELECT * FROM staff WHERE department_id = ? AND status = 'published' ORDER BY sort_order ASC",
            [(int) $department['id']]
        );
        view('departments/show', [
            'department' => $department,
            'programmes' => $programmes,
            'staff' => $staff,
            'metaTitle' => $department['name'] . ' | SLGTI',
            'metaDescription' => $department['summary'],
        ]);
    }
}
