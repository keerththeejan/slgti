<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Model;
use PDOException;

final class ProgrammeLevelController
{
    public function index(): void
    {
        require_permission('programmes.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(l.name LIKE ? OR l.code LIKE ? OR l.qualification LIKE ? OR l.description LIKE ?)';
            array_push($params, ...array_fill(0, 4, '%' . $q . '%'));
        }
        if (in_array($status, ['published', 'draft', 'archived'], true)) {
            $where[] = 'l.status = ?';
            $params[] = $status;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM programme_levels l WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT l.*, (SELECT COUNT(*) FROM programmes p WHERE p.level_id = l.id) AS programme_count
             FROM programme_levels l WHERE $clause ORDER BY l.sort_order ASC, l.id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/programme-levels/index', compact('rows', 'pager', 'q', 'status') + ['metaTitle' => 'Programme Levels'], 'layouts/admin');
    }

    public function reorder(): void
    {
        require_permission('programmes.manage');
        verify_csrf();
        $payload = json_decode((string) file_get_contents('php://input'), true);
        $ordered = $payload['levels'] ?? null;
        if (!is_array($ordered) || $ordered === []) json_response(['ok' => false, 'message' => 'A level order is required.'], 422);
        $knownRows = Model::fetchAll('SELECT id FROM programme_levels');
        $known = array_fill_keys(array_map(static fn (array $row): int => (int) $row['id'], $knownRows), true);
        $seen = [];
        foreach ($ordered as $id) {
            $id = (int) $id;
            if ($id < 1 || !isset($known[$id]) || isset($seen[$id])) json_response(['ok' => false, 'message' => 'The level order is invalid. Refresh and try again.'], 422);
            $seen[$id] = true;
        }
        if (count($seen) !== count($known)) json_response(['ok' => false, 'message' => 'Some levels are missing from the submitted order.'], 422);
        $db = Database::connection();
        try {
            $db->beginTransaction();
            $statement = $db->prepare('UPDATE programme_levels SET sort_order = ? WHERE id = ?');
            foreach (array_keys($seen) as $position => $id) $statement->execute([$position + 1, $id]);
            $db->commit();
        } catch (PDOException) {
            if ($db->inTransaction()) $db->rollBack();
            json_response(['ok' => false, 'message' => 'Level order could not be saved.'], 500);
        }
        log_activity('programme-levels.reordered', 'Programme level order updated');
        json_response(['ok' => true, 'message' => 'Programme level order saved.']);
    }
}
