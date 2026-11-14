<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class FaqController
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
            $where[] = '(question LIKE ? OR answer LIKE ? OR category LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }
        if (in_array($status, ['draft', 'published'], true)) {
            $where[] = 'status = ?';
            $params[] = $status;
        }
        if ($category !== '') {
            $where[] = 'category = ?';
            $params[] = $category;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM faqs WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT * FROM faqs WHERE $clause ORDER BY sort_order ASC, id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/faqs/index', [
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'category' => $category,
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM faqs WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'metaTitle' => 'FAQs',
        ], 'layouts/admin');
    }
}
