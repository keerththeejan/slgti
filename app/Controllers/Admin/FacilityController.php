<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class FacilityController
{
    public function index(): void
    {
        require_permission('content.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));
        $availability = trim((string) ($_GET['availability'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(title LIKE ? OR summary LIKE ? OR category LIKE ? OR location LIKE ? OR equipment LIKE ?)';
            array_push($params, ...array_fill(0, 5, '%' . $q . '%'));
        }
        if (in_array($status, ['draft', 'published', 'archived'], true)) {
            $where[] = 'status = ?';
            $params[] = $status;
        }
        if ($category !== '') {
            $where[] = 'category = ?';
            $params[] = $category;
        }
        if (in_array($availability, ['available', 'limited', 'unavailable'], true)) {
            $where[] = 'availability_status = ?';
            $params[] = $availability;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM facilities WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll("SELECT * FROM facilities WHERE $clause ORDER BY sort_order ASC, id ASC LIMIT {$pager['per_page']} OFFSET {$pager['offset']}", $params);
        view('admin/facilities/index', [
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'category' => $category,
            'availability' => $availability,
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM facilities WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'metaTitle' => 'Campus Facilities',
        ], 'layouts/admin');
    }
}
