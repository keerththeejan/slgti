<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;
use RuntimeException;

final class MediaController
{
    public function index(): void
    {
        require_permission('media.manage');
        $q = trim((string) ($_GET['q'] ?? ''));
        $params = [];
        $where = '1=1';
        if ($q !== '') {
            $where = '(filename LIKE ? OR original_name LIKE ?)';
            $params = ['%' . $q . '%', '%' . $q . '%'];
        }
        $items = Model::fetchAll("SELECT * FROM media WHERE $where ORDER BY id DESC LIMIT 60", $params);
        view('admin/media/index', [
            'items' => $items,
            'q' => $q,
            'metaTitle' => 'Media library',
        ], 'layouts/admin');
    }

    public function store(): void
    {
        require_permission('media.manage');
        verify_csrf();
        if (empty($_FILES['image']['name'])) {
            flash('error', 'Choose an image to upload.');
            redirect('/admin/media');
        }
        try {
            upload_image($_FILES['image']);
        } catch (RuntimeException $exception) {
            flash('error', $exception->getMessage());
            redirect('/admin/media');
        }
        log_activity('media.uploaded', 'Uploaded an image');
        flash('success', 'Image added to the library.');
        redirect('/admin/media');
    }

    public function destroy(string $id): void
    {
        require_permission('media.manage');
        verify_csrf();
        $item = Model::fetch('SELECT * FROM media WHERE id = ?', [(int) $id]);
        if (!$item) {
            abort(404);
        }
        $path = ROOT_PATH . '/public/' . ltrim($item['path'], '/');
        if (is_file($path)) {
            unlink($path);
        }
        Model::deleteById('media', (int) $item['id']);
        log_activity('media.deleted', $item['path']);
        flash('success', 'Image removed.');
        redirect('/admin/media');
    }
}
