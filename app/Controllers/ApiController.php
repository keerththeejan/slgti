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
        $sql = "SELECT id, title, department_id FROM programmes WHERE status = 'published'";
        if ($department > 0) {
            $sql .= ' AND department_id = ?';
            $params[] = $department;
        }
        $sql .= ' ORDER BY title ASC';
        json_response(['programmes' => Model::fetchAll($sql, $params)]);
    }
}
