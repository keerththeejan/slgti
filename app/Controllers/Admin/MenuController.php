<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Model;
use PDOException;

final class MenuController
{
    public function index(): void
    {
        require_admin();
        if (!can('content.manage')) {
            http_response_code(403);
            exit('You do not have permission to manage menus.');
        }

        $menus = Model::fetchAll('SELECT id, name, slug FROM menus ORDER BY name ASC');
        $menuId = (int) ($_GET['menu'] ?? ($menus[0]['id'] ?? 0));
        $validMenuIds = array_map(static fn (array $menu): int => (int) $menu['id'], $menus);
        if (!in_array($menuId, $validMenuIds, true)) {
            $menuId = (int) ($menus[0]['id'] ?? 0);
        }
        $items = $menuId > 0
            ? Model::fetchAll(
                'SELECT id, menu_id, parent_id, label, url, sort_order, status
                 FROM menu_items WHERE menu_id = ? ORDER BY sort_order ASC, id ASC',
                [$menuId]
            )
            : [];
        $tree = $this->tree($items);

        view('admin/menu-items/index', compact('menus', 'menuId', 'items', 'tree') + [
            'metaTitle' => 'Menu Items',
        ], 'layouts/admin');
    }

    public function reorder(): void
    {
        require_admin();
        if (!can('content.manage')) {
            json_response(['ok' => false, 'message' => 'You do not have permission to reorder menus.'], 403);
        }
        verify_csrf();
        $payload = json_decode((string) file_get_contents('php://input'), true);
        $menuId = (int) ($payload['menu_id'] ?? 0);
        $ordered = $payload['items'] ?? null;
        if ($menuId < 1 || !is_array($ordered) || $ordered === []) {
            json_response(['ok' => false, 'message' => 'A valid menu ordering is required.'], 422);
        }

        $stored = Model::fetchAll('SELECT id, parent_id FROM menu_items WHERE menu_id = ?', [$menuId]);
        $known = [];
        foreach ($stored as $item) {
            $known[(int) $item['id']] = (int) ($item['parent_id'] ?? 0);
        }
        $seen = [];
        foreach ($ordered as $item) {
            $id = (int) ($item['id'] ?? 0);
            $parentId = (int) ($item['parent_id'] ?? 0);
            if ($id < 1 || !isset($known[$id]) || ($parentId > 0 && !isset($known[$parentId])) || isset($seen[$id])) {
                json_response(['ok' => false, 'message' => 'The menu hierarchy is invalid. Refresh and try again.'], 422);
            }
            if ($parentId === $id || $this->isDescendant($parentId, $id, $known)) {
                json_response(['ok' => false, 'message' => 'A menu item cannot be placed inside itself.'], 422);
            }
            $seen[$id] = true;
        }
        if (count($seen) !== count($known)) {
            json_response(['ok' => false, 'message' => 'Some menu items are missing from the submitted order.'], 422);
        }

        $db = Database::connection();
        try {
            $db->beginTransaction();
            $statement = $db->prepare('UPDATE menu_items SET parent_id = ?, sort_order = ? WHERE id = ? AND menu_id = ?');
            foreach ($ordered as $item) {
                $statement->execute([
                    (int) ($item['parent_id'] ?? 0) ?: null,
                    max(0, (int) ($item['sort_order'] ?? 0)),
                    (int) $item['id'],
                    $menuId,
                ]);
            }
            $db->commit();
        } catch (PDOException) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            json_response(['ok' => false, 'message' => 'The menu order could not be saved.'], 500);
        }
        log_activity('menu-items.reordered', 'Menu #' . $menuId . ' order updated');
        json_response(['ok' => true, 'message' => 'Menu order saved.']);
    }

    private function tree(array $items, int $parentId = 0): array
    {
        $tree = [];
        foreach ($items as $item) {
            if ((int) ($item['parent_id'] ?? 0) !== $parentId) {
                continue;
            }
            $item['children'] = $this->tree($items, (int) $item['id']);
            $tree[] = $item;
        }
        return $tree;
    }

    private function isDescendant(int $candidate, int $ancestor, array $parents): bool
    {
        $visited = [];
        while ($candidate > 0 && isset($parents[$candidate]) && !isset($visited[$candidate])) {
            if ($parents[$candidate] === $ancestor) {
                return true;
            }
            $visited[$candidate] = true;
            $candidate = $parents[$candidate];
        }
        return false;
    }
}
