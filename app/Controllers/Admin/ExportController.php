<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class ExportController
{
    public function applications(): void
    {
        require_permission('applications.manage');
        $status = trim((string) ($_GET['status'] ?? ''));
        $where = '1=1';
        $params = [];
        if (in_array($status, ['pending', 'reviewing', 'accepted', 'rejected'], true)) {
            $where = 'a.status = ?';
            $params[] = $status;
        }
        $rows = Model::fetchAll(
            "SELECT a.reference_no, a.full_name, a.nic, a.email, a.phone, a.gender, a.date_of_birth, a.address, a.district,
                    p.title AS programme, a.education_level, a.results, a.status, a.created_at
             FROM applications a
             LEFT JOIN programmes p ON p.id = a.programme_id
             WHERE $where
             ORDER BY a.id DESC",
            $params
        );
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="slgti-applications.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Reference', 'Name', 'NIC', 'Email', 'Phone', 'Gender', 'Date of birth', 'Address', 'District', 'Programme', 'Education', 'Results', 'Status', 'Received']);
        foreach ($rows as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        log_activity('applications.exported', 'Exported ' . count($rows) . ' applications');
        exit;
    }

    public function document(string $id): void
    {
        require_admin();
        if (!can('applications.view') && !can('applications.manage')) {
            http_response_code(403);
            exit('You do not have permission to download this document.');
        }
        $doc = Model::fetch('SELECT * FROM application_documents WHERE id = ?', [(int) $id]);
        if (!$doc) {
            abort(404);
        }
        $path = STORAGE_PATH . '/' . ltrim($doc['path'], '/');
        if (!is_file($path)) {
            abort(404);
        }
        header('Content-Type: ' . $doc['mime']);
        header('Content-Disposition: attachment; filename="' . basename($doc['original_name']) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
