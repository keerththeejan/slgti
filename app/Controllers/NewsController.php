<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class NewsController
{
    public function index(): void
    {
        $category = trim((string) ($_GET['category'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = "n.status = 'published' AND (n.published_at IS NULL OR n.published_at <= NOW())";
        $params = [];
        if ($category !== '') {
            $where .= ' AND c.slug = ?';
            $params[] = $category;
        }
        $total = (int) Model::fetch(
            "SELECT COUNT(*) AS total FROM news n LEFT JOIN news_categories c ON c.id = n.category_id WHERE $where",
            $params
        )['total'];
        $pager = paginate($total, $page, 6);
        $items = Model::fetchAll(
            "SELECT n.*, c.name AS category_name, c.slug AS category_slug
             FROM news n LEFT JOIN news_categories c ON c.id = n.category_id
             WHERE $where ORDER BY n.published_at DESC LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('news/index', [
            'items' => $items,
            'categories' => Model::fetchAll('SELECT * FROM news_categories ORDER BY name ASC'),
            'activeCategory' => $category,
            'pager' => $pager,
            'metaTitle' => 'News & Events | SLGTI',
            'metaDescription' => 'Announcements, admission updates and campus news from SLGTI Kilinochchi.',
        ]);
    }

    public function show(string $slug): void
    {
        $item = Model::fetch(
            "SELECT n.*, c.name AS category_name, c.slug AS category_slug
             FROM news n LEFT JOIN news_categories c ON c.id = n.category_id
             WHERE n.slug = ? AND n.status = 'published' AND (n.published_at IS NULL OR n.published_at <= NOW())",
            [$slug]
        );
        if (!$item) {
            abort(404);
        }
        $related = Model::fetchAll(
            "SELECT id, title, slug, image, published_at FROM news WHERE status = 'published' AND (published_at IS NULL OR published_at <= NOW()) AND id != ? ORDER BY published_at DESC LIMIT 3",
            [(int) $item['id']]
        );
        view('news/show', [
            'item' => $item,
            'related' => $related,
            'metaTitle' => ($item['meta_title'] ?: $item['title']) . ' | SLGTI',
            'metaDescription' => $item['meta_description'] ?: $item['excerpt'],
        ]);
    }
}
