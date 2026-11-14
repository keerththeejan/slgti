<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class TestimonialController
{
    public function index(): void
    {
        $department = max(0, (int) ($_GET['department_id'] ?? 0));
        $where = ["t.status = 'published'", "t.approval_status = 'approved'", "t.display_location IN ('testimonials', 'both')"];
        $params = [];
        if ($department > 0) {
            $where[] = 't.department_id = ?';
            $params[] = $department;
        }
        $clause = implode(' AND ', $where);
        view('testimonials/index', [
            'testimonials' => Model::fetchAll("SELECT t.*, d.name AS department_name, p.title AS programme_name FROM testimonials t LEFT JOIN departments d ON d.id = t.department_id LEFT JOIN programmes p ON p.id = t.programme_id WHERE $clause ORDER BY t.sort_order ASC, t.id ASC", $params),
            'metaTitle' => 'Testimonials | SLGTI',
            'metaDescription' => 'Stories from students, graduates and the SLGTI community.',
            'metaCanonical' => absolute_url('testimonials'),
        ]);
    }
}
