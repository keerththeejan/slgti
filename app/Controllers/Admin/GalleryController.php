<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class GalleryController
{
    public function index(): void
    {
        require_permission('gallery.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));
        $eventId = max(0, (int) ($_GET['event_id'] ?? 0));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(g.title LIKE ? OR g.description LIKE ? OR g.slug LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }
        if (in_array($status, ['published', 'draft', 'archived'], true)) {
            $where[] = 'g.status = ?';
            $params[] = $status;
        }
        if ($category !== '') {
            $where[] = 'g.category = ?';
            $params[] = $category;
        }
        if ($eventId > 0) {
            $where[] = 'g.event_id = ?';
            $params[] = $eventId;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM galleries g WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT g.*,
                    COALESCE(NULLIF(g.cover, ''), (SELECT gi.image FROM gallery_images gi WHERE gi.gallery_id = g.id ORDER BY gi.sort_order ASC, gi.id ASC LIMIT 1)) AS display_cover,
                    (SELECT COUNT(*) FROM gallery_images gi WHERE gi.gallery_id = g.id) AS image_count
             FROM galleries g WHERE $clause ORDER BY g.sort_order ASC, g.id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/galleries/index', compact('rows', 'pager', 'q', 'status', 'category', 'eventId') + [
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM galleries WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'events' => Model::fetchAll("SELECT id, title FROM events ORDER BY starts_at DESC, title ASC"),
            'metaTitle' => 'Gallery Albums',
        ], 'layouts/admin');
    }

}
