<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Model;
use PDOException;

final class QuickLinkController
{
    public function index(): void
    {
        require_permission('slides.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $linkType = trim((string) ($_GET['link_type'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(title LIKE ? OR url LIKE ? OR category LIKE ? OR subtitle LIKE ?)';
            array_push($params, ...array_fill(0, 4, '%' . $q . '%'));
        }
        if ($category !== '') {
            $where[] = 'category = ?';
            $params[] = $category;
        }
        if (in_array($status, ['published', 'draft', 'archived'], true)) {
            $where[] = 'status = ?';
            $params[] = $status;
        }
        if (in_array($linkType, ['internal', 'external'], true)) {
            $where[] = 'link_type = ?';
            $params[] = $linkType;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM quick_links WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT * FROM quick_links WHERE $clause ORDER BY sort_order ASC, id ASC LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        $categories = Model::fetchAll('SELECT DISTINCT category FROM quick_links WHERE category <> \'\' ORDER BY category ASC');
        view('admin/quick-links/index', compact('rows', 'pager', 'q', 'category', 'status', 'linkType', 'categories') + [
            'metaTitle' => 'Quick Links',
        ], 'layouts/admin');
    }

    public function reorder(): void
    {
        require_permission('slides.manage');
        verify_csrf();
        $payload = json_decode((string) file_get_contents('php://input'), true);
        $ordered = $payload['quick_links'] ?? null;
        if (!is_array($ordered) || $ordered === []) {
            json_response(['ok' => false, 'message' => 'A quick link order is required.'], 422);
        }
        $knownRows = Model::fetchAll('SELECT id FROM quick_links');
        $known = array_fill_keys(array_map(static fn (array $row): int => (int) $row['id'], $knownRows), true);
        $seen = [];
        foreach ($ordered as $id) {
            $id = (int) $id;
            if ($id < 1 || !isset($known[$id]) || isset($seen[$id])) {
                json_response(['ok' => false, 'message' => 'The quick link order is invalid. Refresh and try again.'], 422);
            }
            $seen[$id] = true;
        }
        if (count($seen) !== count($known)) {
            json_response(['ok' => false, 'message' => 'Some quick links are missing from the submitted order.'], 422);
        }
        $db = Database::connection();
        try {
            $db->beginTransaction();
            $statement = $db->prepare('UPDATE quick_links SET sort_order = ?, updated_at = ? WHERE id = ?');
            $now = date('Y-m-d H:i:s');
            foreach (array_keys($seen) as $position => $id) {
                $statement->execute([$position + 1, $now, $id]);
            }
            $db->commit();
        } catch (PDOException) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            json_response(['ok' => false, 'message' => 'Quick link order could not be saved.'], 500);
        }
        log_activity('quick-links.reordered', 'Quick link order updated');
        json_response(['ok' => true, 'message' => 'Quick link order saved.']);
    }
}
