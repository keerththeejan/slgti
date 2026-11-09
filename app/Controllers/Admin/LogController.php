<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class LogController
{
    public function index(): void
    {
        require_permission('logs.view');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $total = (int) Model::fetch('SELECT COUNT(*) AS total FROM activity_logs')['total'];
        $pager = paginate($total, $page, 20);
        $logs = Model::fetchAll(
            "SELECT l.*, u.name AS user_name FROM activity_logs l
             LEFT JOIN users u ON u.id = l.user_id
             ORDER BY l.id DESC LIMIT {$pager['per_page']} OFFSET {$pager['offset']}"
        );
        view('admin/logs/index', [
            'logs' => $logs,
            'pager' => $pager,
            'metaTitle' => 'Activity log',
        ], 'layouts/admin');
    }
}
