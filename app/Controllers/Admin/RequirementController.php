<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Model;
use PDOException;

final class RequirementController
{
    public function index(): void
    {
        require_permission('programmes.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $department = max(0, (int) ($_GET['department_id'] ?? 0));
        $programme = max(0, (int) ($_GET['programme_id'] ?? 0));
        $status = trim((string) ($_GET['status'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(r.requirement LIKE ? OR r.slug LIKE ? OR r.category LIKE ? OR r.short_description LIKE ? OR p.title LIKE ? OR d.name LIKE ?)';
            array_push($params, ...array_fill(0, 6, '%' . $q . '%'));
        }
        if ($department > 0) {
            $where[] = 'r.department_id = ?';
            $params[] = $department;
        }
        if ($programme > 0) {
            $where[] = 'r.programme_id = ?';
            $params[] = $programme;
        }
        if (in_array($status, ['published', 'draft', 'archived'], true)) {
            $where[] = 'r.status = ?';
            $params[] = $status;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM programme_requirements r LEFT JOIN programmes p ON p.id = r.programme_id LEFT JOIN departments d ON d.id = r.department_id WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT r.*, p.title AS programme_title, d.name AS department_name
             FROM programme_requirements r LEFT JOIN programmes p ON p.id = r.programme_id LEFT JOIN departments d ON d.id = r.department_id
             WHERE $clause ORDER BY r.sort_order ASC, r.id ASC LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/requirements/index', compact('rows', 'pager', 'q', 'department', 'programme', 'status') + [
            'departments' => Model::fetchAll('SELECT id, name FROM departments ORDER BY name ASC'),
            'programmes' => Model::fetchAll('SELECT id, title FROM programmes ORDER BY title ASC'),
            'metaTitle' => 'Requirements',
        ], 'layouts/admin');
    }

    public function reorder(): void
    {
        require_permission('programmes.manage');
        verify_csrf();
        $payload = json_decode((string) file_get_contents('php://input'), true);
        $ordered = $payload['requirements'] ?? null;
        if (!is_array($ordered) || $ordered === []) {
            json_response(['ok' => false, 'message' => 'A requirement order is required.'], 422);
        }
        $knownRows = Model::fetchAll('SELECT id FROM programme_requirements');
        $known = array_fill_keys(array_map(static fn (array $row): int => (int) $row['id'], $knownRows), true);
        $seen = [];
        foreach ($ordered as $id) {
            $id = (int) $id;
            if ($id < 1 || !isset($known[$id]) || isset($seen[$id])) {
                json_response(['ok' => false, 'message' => 'The requirement order is invalid. Refresh and try again.'], 422);
            }
            $seen[$id] = true;
        }
        if (count($seen) !== count($known)) {
            json_response(['ok' => false, 'message' => 'Some requirements are missing from the submitted order.'], 422);
        }
        $db = Database::connection();
        try {
            $db->beginTransaction();
            $statement = $db->prepare('UPDATE programme_requirements SET sort_order = ? WHERE id = ?');
            foreach (array_keys($seen) as $position => $id) $statement->execute([$position + 1, $id]);
            $db->commit();
        } catch (PDOException) {
            if ($db->inTransaction()) $db->rollBack();
            json_response(['ok' => false, 'message' => 'Requirement order could not be saved.'], 500);
        }
        log_activity('requirements.reordered', 'Requirement order updated');
        json_response(['ok' => true, 'message' => 'Requirement order saved.']);
    }
}
