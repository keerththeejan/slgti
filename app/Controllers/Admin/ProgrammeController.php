<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Model;
use PDOException;

final class ProgrammeController
{
    public function index(): void
    {
        require_permission('programmes.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $department = max(0, (int) ($_GET['department_id'] ?? 0));
        $level = max(0, (int) ($_GET['level_id'] ?? 0));
        $status = trim((string) ($_GET['status'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(p.title LIKE ? OR p.slug LIKE ? OR p.summary LIKE ? OR d.name LIKE ? OR l.qualification LIKE ?)';
            array_push($params, ...array_fill(0, 5, '%' . $q . '%'));
        }
        if ($department > 0) {
            $where[] = 'p.department_id = ?';
            $params[] = $department;
        }
        if ($level > 0) {
            $where[] = 'p.level_id = ?';
            $params[] = $level;
        }
        if (in_array($status, ['published', 'draft', 'archived'], true)) {
            $where[] = 'p.status = ?';
            $params[] = $status;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM programmes p INNER JOIN departments d ON d.id = p.department_id INNER JOIN programme_levels l ON l.id = p.level_id WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT p.*, d.name AS department_name, l.name AS level_name, l.code AS level_code, l.qualification
             FROM programmes p INNER JOIN departments d ON d.id = p.department_id INNER JOIN programme_levels l ON l.id = p.level_id
             WHERE $clause ORDER BY p.department_id ASC, p.sort_order ASC, p.id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/programmes/index', compact('rows', 'pager', 'q', 'department', 'level', 'status') + [
            'departments' => Model::fetchAll('SELECT id, name FROM departments ORDER BY name ASC'),
            'levels' => Model::fetchAll('SELECT id, code, name FROM programme_levels ORDER BY sort_order ASC, id ASC'),
            'metaTitle' => 'Programmes',
        ], 'layouts/admin');
    }

    public function reorder(): void
    {
        require_permission('programmes.manage');
        verify_csrf();
        $payload = json_decode((string) file_get_contents('php://input'), true);
        $ordered = $payload['programmes'] ?? null;
        $departmentId = (int) ($payload['department_id'] ?? 0);
        if (!is_array($ordered) || $ordered === [] || $departmentId < 1) {
            json_response(['ok' => false, 'message' => 'A department-specific programme order is required.'], 422);
        }
        $known = Model::fetchAll('SELECT id FROM programmes WHERE department_id = ?', [$departmentId]);
        $known = array_fill_keys(array_map(static fn (array $row): int => (int) $row['id'], $known), true);
        $seen = [];
        foreach ($ordered as $id) {
            $id = (int) $id;
            if ($id < 1 || !isset($known[$id]) || isset($seen[$id])) {
                json_response(['ok' => false, 'message' => 'The programme order is invalid. Refresh and try again.'], 422);
            }
            $seen[$id] = true;
        }
        if (count($seen) !== count($known)) {
            json_response(['ok' => false, 'message' => 'Some programmes are missing from the submitted order.'], 422);
        }
        $db = Database::connection();
        try {
            $db->beginTransaction();
            $statement = $db->prepare('UPDATE programmes SET sort_order = ? WHERE id = ? AND department_id = ?');
            foreach (array_keys($seen) as $position => $id) {
                $statement->execute([$position + 1, $id, $departmentId]);
            }
            $db->commit();
        } catch (PDOException) {
            if ($db->inTransaction()) $db->rollBack();
            json_response(['ok' => false, 'message' => 'Programme order could not be saved.'], 500);
        }
        log_activity('programmes.reordered', 'Programme order updated');
        json_response(['ok' => true, 'message' => 'Programme order saved.']);
    }
}
