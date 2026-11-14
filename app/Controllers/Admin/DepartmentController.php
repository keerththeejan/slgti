<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Model;
use PDOException;

final class DepartmentController
{
    public function index(): void
    {
        require_permission('departments.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(d.name LIKE ? OR d.slug LIKE ? OR d.summary LIKE ? OR d.head_name LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }
        if (in_array($status, ['published', 'draft', 'archived'], true)) {
            $where[] = 'd.status = ?';
            $params[] = $status;
        }
        $departments = Model::fetchAll(
            'SELECT d.*, (SELECT COUNT(*) FROM programmes p WHERE p.department_id = d.id) AS programme_count
             FROM departments d WHERE ' . implode(' AND ', $where) . ' ORDER BY d.sort_order ASC, d.id ASC',
            $params
        );
        view('admin/departments/index', compact('departments', 'q', 'status') + [
            'metaTitle' => 'Departments',
        ], 'layouts/admin');
    }

    public function reorder(): void
    {
        require_permission('departments.manage');
        verify_csrf();
        $payload = json_decode((string) file_get_contents('php://input'), true);
        $ordered = $payload['departments'] ?? null;
        if (!is_array($ordered) || $ordered === []) {
            json_response(['ok' => false, 'message' => 'A department order is required.'], 422);
        }
        $known = array_column(Model::fetchAll('SELECT id FROM departments'), 'id');
        $known = array_fill_keys(array_map('intval', $known), true);
        $seen = [];
        foreach ($ordered as $position => $id) {
            $id = (int) $id;
            if ($id < 1 || !isset($known[$id]) || isset($seen[$id])) {
                json_response(['ok' => false, 'message' => 'The department order is invalid. Refresh and try again.'], 422);
            }
            $seen[$id] = true;
        }
        if (count($seen) !== count($known)) {
            json_response(['ok' => false, 'message' => 'Some departments are missing from the submitted order.'], 422);
        }
        $db = Database::connection();
        try {
            $db->beginTransaction();
            $statement = $db->prepare('UPDATE departments SET sort_order = ? WHERE id = ?');
            foreach (array_keys($seen) as $position => $id) {
                $statement->execute([$position + 1, $id]);
            }
            $db->commit();
        } catch (PDOException) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            json_response(['ok' => false, 'message' => 'Department order could not be saved.'], 500);
        }
        log_activity('departments.reordered', 'Department order updated');
        json_response(['ok' => true, 'message' => 'Department order saved.']);
    }
}
