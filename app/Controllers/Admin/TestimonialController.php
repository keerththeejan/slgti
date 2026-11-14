<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class TestimonialController
{
    public function index(): void
    {
        require_permission('content.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $approval = trim((string) ($_GET['approval'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(t.name LIKE ? OR t.role LIKE ? OR t.quote LIKE ? OR t.designation LIKE ? OR t.organization LIKE ?)';
            array_push($params, ...array_fill(0, 5, '%' . $q . '%'));
        }
        if (in_array($status, ['draft', 'published', 'archived'], true)) {
            $where[] = 't.status = ?';
            $params[] = $status;
        }
        if (in_array($approval, ['pending', 'approved', 'rejected'], true)) {
            $where[] = 't.approval_status = ?';
            $params[] = $approval;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM testimonials t WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT t.*, d.name AS department_name, p.title AS programme_name
             FROM testimonials t
             LEFT JOIN departments d ON d.id = t.department_id
             LEFT JOIN programmes p ON p.id = t.programme_id
             WHERE $clause ORDER BY t.sort_order ASC, t.id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/testimonials/index', [
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'approval' => $approval,
            'metaTitle' => 'Testimonials',
        ], 'layouts/admin');
    }

    public function approval(string $id): void
    {
        require_permission('content.manage');
        verify_csrf();
        $value = (string) ($_POST['approval_status'] ?? '');
        if (!in_array($value, ['approved', 'rejected'], true)) {
            flash('error', 'Choose a valid approval action.');
            redirect('/admin/testimonials');
        }
        $record = Model::fetch('SELECT id FROM testimonials WHERE id = ?', [(int) $id]);
        if (!$record) {
            abort(404);
        }
        Model::updateById('testimonials', (int) $id, [
            'approval_status' => $value,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        log_activity('testimonials.' . $value, 'Testimonial #' . (int) $id . ' ' . $value);
        flash('success', 'Testimonial ' . $value . '.');
        redirect('/admin/testimonials');
    }
}
