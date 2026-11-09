<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class GalleryController
{
    public function index(): void
    {
        $album = trim((string) ($_GET['album'] ?? ''));
        $q = trim((string) ($_GET['q'] ?? ''));
        $sql = "SELECT gi.*, g.title AS album, g.slug AS album_slug
                FROM gallery_images gi
                INNER JOIN galleries g ON g.id = gi.gallery_id
                WHERE gi.status = 'published' AND g.status = 'published'";
        $params = [];
        if ($album !== '') {
            $sql .= ' AND g.slug = ?';
            $params[] = $album;
        }
        if ($q !== '') {
            $sql .= ' AND gi.title LIKE ?';
            $params[] = '%' . $q . '%';
        }
        $sql .= ' ORDER BY g.sort_order ASC, gi.sort_order ASC';
        view('gallery/index', [
            'images' => Model::fetchAll($sql, $params),
            'albums' => Model::fetchAll("SELECT * FROM galleries WHERE status = 'published' ORDER BY sort_order ASC"),
            'videos' => Model::fetchAll("SELECT * FROM videos WHERE status = 'published' ORDER BY sort_order ASC"),
            'activeAlbum' => $album,
            'query' => $q,
            'metaTitle' => 'Gallery | SLGTI',
            'metaDescription' => 'Photographs and videos of workshops, laboratories and student life at SLGTI.',
        ]);
    }
}
