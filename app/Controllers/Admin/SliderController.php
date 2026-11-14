<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class SliderController
{
    public function index(): void
    {
        require_permission('slides.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $location = trim((string) ($_GET['location'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(title LIKE ? OR subtitle LIKE ? OR eyebrow LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }
        if (in_array($status, ['draft', 'published', 'archived'], true)) {
            $where[] = 'status = ?';
            $params[] = $status;
        }
        if ($location !== '') {
            $where[] = 'display_location = ?';
            $params[] = $location;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM sliders WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT * FROM sliders WHERE $clause ORDER BY sort_order ASC, id ASC LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/sliders/index', compact('rows', 'pager', 'q', 'status', 'location') + [
            'metaTitle' => 'Homepage Slides',
        ], 'layouts/admin');
    }

}
