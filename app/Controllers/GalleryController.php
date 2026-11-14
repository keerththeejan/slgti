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
                WHERE gi.status = 'published' AND g.status = 'published'
                AND g.display_location IN ('gallery', 'gallery_homepage')";
        $params = [];
        if ($album !== '') {
            $sql .= ' AND g.slug = ?';
            $params[] = $album;
        }
        if ($q !== '') {
            $sql .= ' AND gi.title LIKE ?';
            $params[] = '%' . $q . '%';
        }
        $sql .= ' ORDER BY g.sort_order ASC, gi.sort_order ASC, gi.id ASC';
        view('gallery/index', [
            'images' => Model::fetchAll($sql, $params),
            'albums' => Model::fetchAll("SELECT g.*, (SELECT COUNT(*) FROM gallery_images gi WHERE gi.gallery_id = g.id AND gi.status = 'published') AS image_count FROM galleries g WHERE g.status = 'published' AND g.display_location IN ('gallery', 'gallery_homepage') ORDER BY g.sort_order ASC, g.id ASC"),
            'videos' => Model::fetchAll("SELECT * FROM videos WHERE status = 'published' ORDER BY sort_order ASC"),
            'activeAlbum' => $album,
            'query' => $q,
            'metaTitle' => 'Gallery | SLGTI',
            'metaDescription' => 'Photographs and videos of workshops, laboratories and student life at SLGTI.',
        ]);
    }

    public function show(string $slug): void
    {
        $gallery = Model::fetch(
            "SELECT g.*, e.title AS event_title, e.slug AS event_slug, e.starts_at AS event_starts_at
             FROM galleries g LEFT JOIN events e ON e.id = g.event_id
             WHERE g.slug = ? AND g.status = 'published'
             AND g.display_location IN ('gallery', 'gallery_homepage')",
            [$slug]
        );
        if (!$gallery) {
            $redirect = Model::fetch('SELECT gallery_id FROM gallery_slug_redirects WHERE old_slug = ?', [$slug]);
            if ($redirect) {
                $current = Model::fetch('SELECT slug FROM galleries WHERE id = ? AND status = \'published\'', [(int) $redirect['gallery_id']]);
                if ($current) {
                    header('Location: ' . url('gallery/' . $current['slug']), true, 301);
                    exit;
                }
            }
            abort(404);
        }
        $images = Model::fetchAll(
            "SELECT * FROM gallery_images WHERE gallery_id = ? AND status = 'published' ORDER BY sort_order ASC, id ASC",
            [(int) $gallery['id']]
        );
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'ImageGallery',
            'name' => $gallery['title'],
            'description' => $gallery['meta_description'] ?: $gallery['description'],
            'url' => absolute_url('gallery/' . $gallery['slug']),
            'image' => array_values(array_filter(array_map(static fn (array $image): string => media_url($image['image']), $images))),
        ];
        view('gallery/show', [
            'gallery' => $gallery,
            'images' => $images,
            'metaTitle' => $gallery['seo_title'] ?: $gallery['title'] . ' | SLGTI',
            'metaDescription' => $gallery['meta_description'] ?: excerpt($gallery['description'], 160),
            'metaCanonical' => $gallery['canonical_url'] ?: absolute_url('gallery/' . $gallery['slug']),
            'metaRobots' => ($gallery['robots_index'] ?: 'index') . ', ' . ($gallery['robots_follow'] ?: 'follow'),
            'metaImage' => $gallery['og_image'] ? media_url($gallery['og_image']) : ($gallery['cover'] ? media_url($gallery['cover']) : absolute_url('assets/images/hero-1.jpg')),
            'metaTwitterImage' => $gallery['twitter_image'] ? media_url($gallery['twitter_image']) : null,
            'metaOgTitle' => $gallery['og_title'] ?: $gallery['seo_title'] ?: $gallery['title'],
            'metaOgDescription' => $gallery['og_description'] ?: $gallery['meta_description'],
            'structuredData' => $structuredData,
        ]);
    }
}
