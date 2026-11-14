<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class ApiController
{
    public function programmes(): void
    {
        $department = (int) ($_GET['department'] ?? 0);
        $params = [];
        $sql = "SELECT p.id, p.title, p.department_id, p.duration,
                       l.name AS level_name, l.code AS level_code, l.qualification
                FROM programmes p
                INNER JOIN programme_levels l ON l.id = p.level_id AND l.status = 'published'
                INNER JOIN departments d ON d.id = p.department_id AND d.status = 'published'
                WHERE p.status = 'published'";
        if ($department > 0) {
            $sql .= ' AND department_id = ?';
            $params[] = $department;
        }
        $sql .= ' ORDER BY title ASC';
        json_response(['programmes' => Model::fetchAll($sql, $params)]);
    }
}
