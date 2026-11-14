<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class PageController
{
    public function index(): void
    {
        require_permission('pages.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(title LIKE ? OR slug LIKE ? OR excerpt LIKE ? OR body LIKE ? OR category LIKE ?)';
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
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM pages WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT p.*, parent.title AS parent_title
             FROM pages p LEFT JOIN pages parent ON parent.id = p.parent_id
             WHERE $clause ORDER BY p.sort_order ASC, p.id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/pages/index', [
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'category' => $category,
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM pages WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'metaTitle' => 'Website Pages',
        ], 'layouts/admin');
    }
}
