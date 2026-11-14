<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class VideoController
{
    public function index(): void
    {
        $category = trim((string) ($_GET['category'] ?? ''));
        $q = trim((string) ($_GET['q'] ?? ''));
        $where = ["status = 'published'"];
        $params = [];
        if ($category !== '') {
            $where[] = 'category = ?';
            $params[] = $category;
        }
        if ($q !== '') {
            $where[] = '(title LIKE ? OR description LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%');
        }
        $clause = implode(' AND ', $where);
        view('videos/index', [
            'videos' => Model::fetchAll("SELECT * FROM videos WHERE $clause ORDER BY sort_order ASC, id ASC", $params),
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM videos WHERE status = 'published' AND category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'category' => $category,
            'query' => $q,
            'metaTitle' => 'Videos | SLGTI',
            'metaDescription' => 'Institutional videos, training programmes and student activities from SLGTI.',
        ]);
    }

    public function show(string $slug): void
    {
        $video = Model::fetch("SELECT * FROM videos WHERE slug = ? AND status = 'published'", [$slug]);
        if (!$video) {
            abort(404);
        }
        $related = Model::fetchAll(
            'SELECT * FROM videos WHERE status = ? AND id != ? AND category = ? ORDER BY sort_order ASC, id ASC LIMIT 4',
            ['published', (int) $video['id'], (string) $video['category']]
        );
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $video['title'],
            'description' => $video['meta_description'] ?: ($video['description'] ?: $video['title']),
            'thumbnailUrl' => $video['thumbnail'] ? media_url($video['thumbnail']) : $this->thumbnail($video),
            'uploadDate' => $video['video_date'] ?: $video['created_at'],
            'embedUrl' => $video['embed_url'] ?: null,
            'contentUrl' => $video['provider'] === 'self_hosted' ? $video['video_url'] : null,
        ];
        view('videos/show', [
            'video' => $video,
            'related' => $related,
            'structuredData' => array_filter($structuredData),
            'metaTitle' => $video['seo_title'] ?: $video['title'] . ' | SLGTI',
            'metaDescription' => $video['meta_description'] ?: excerpt($video['description'] ?: $video['title'], 160),
            'metaCanonical' => $video['canonical_url'] ?: absolute_url('videos/' . $video['slug']),
            'metaRobots' => ($video['robots_index'] ?: 'index') . ', ' . ($video['robots_follow'] ?: 'follow'),
            'metaImage' => $video['thumbnail'] ? media_url($video['thumbnail']) : $this->thumbnail($video),
        ]);
    }

    private function thumbnail(array $video): string
    {
        return ($video['provider'] ?? '') === 'youtube' && !empty($video['video_id'])
            ? 'https://i.ytimg.com/vi/' . rawurlencode((string) $video['video_id']) . '/hqdefault.jpg'
            : asset('images/placeholder.svg');
    }
}
