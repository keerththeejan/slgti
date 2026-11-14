<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class StaffController
{
    public function index(): void
    {
        require_permission('staff.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $departmentId = max(0, (int) ($_GET['department_id'] ?? 0));
        $category = trim((string) ($_GET['category'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(s.name LIKE ? OR s.position LIKE ? OR s.staff_id LIKE ? OR s.email LIKE ? OR s.category LIKE ?)';
            array_push($params, ...array_fill(0, 5, '%' . $q . '%'));
        }
        if (in_array($status, ['draft', 'published', 'archived'], true)) {
            $where[] = 's.status = ?';
            $params[] = $status;
        }
        if ($departmentId > 0) {
            $where[] = 's.department_id = ?';
            $params[] = $departmentId;
        }
        if ($category !== '') {
            $where[] = 's.category = ?';
            $params[] = $category;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM staff s WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT s.*, d.name AS department_name
             FROM staff s LEFT JOIN departments d ON d.id = s.department_id
             WHERE $clause ORDER BY s.sort_order ASC, s.id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/staff/index', [
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'departmentId' => $departmentId,
            'category' => $category,
            'departments' => Model::fetchAll('SELECT id, name FROM departments ORDER BY name ASC'),
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM staff WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'metaTitle' => 'Staff Profiles',
        ], 'layouts/admin');
    }
}
