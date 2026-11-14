<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class GalleryImageController
{
    public function index(): void
    {
        require_permission('gallery.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $galleryId = max(0, (int) ($_GET['gallery_id'] ?? 0));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(gi.title LIKE ? OR gi.alt_text LIKE ? OR gi.credits LIKE ? OR g.title LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }
        if (in_array($status, ['draft', 'published'], true)) {
            $where[] = 'gi.status = ?';
            $params[] = $status;
        }
        if ($galleryId > 0) {
            $where[] = 'gi.gallery_id = ?';
            $params[] = $galleryId;
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch(
            "SELECT COUNT(*) AS total FROM gallery_images gi INNER JOIN galleries g ON g.id = gi.gallery_id WHERE $clause",
            $params
        )['total'];
        $pager = paginate($total, $page, 18);
        $rows = Model::fetchAll(
            "SELECT gi.*, g.title AS album_title, g.slug AS album_slug
             FROM gallery_images gi INNER JOIN galleries g ON g.id = gi.gallery_id
             WHERE $clause ORDER BY gi.sort_order ASC, gi.id ASC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        foreach ($rows as &$row) {
            $file = ROOT_PATH . '/public/' . ltrim((string) $row['image'], '/');
            $row['file_size'] = is_file($file) ? (int) filesize($file) : 0;
            $dimensions = is_file($file) ? @getimagesize($file) : false;
            $row['width'] = is_array($dimensions) ? (int) ($dimensions[0] ?? 0) : 0;
            $row['height'] = is_array($dimensions) ? (int) ($dimensions[1] ?? 0) : 0;
        }
        unset($row);
        view('admin/gallery-images/index', [
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'galleryId' => $galleryId,
            'galleries' => Model::fetchAll('SELECT id, title FROM galleries ORDER BY title ASC'),
            'metaTitle' => 'Gallery Images',
        ], 'layouts/admin');
    }
}
