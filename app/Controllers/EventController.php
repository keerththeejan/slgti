<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class EventController
{
    public function calendar(): void
    {
        $month = trim((string) ($_GET['month'] ?? date('Y-m')));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }
        $start = $month . '-01';
        $end = date('Y-m-t', strtotime($start));
        $events = Model::fetchAll(
            "SELECT e.*, c.name AS category_name FROM events e
             LEFT JOIN event_categories c ON c.id = e.category_id
             WHERE e.status = 'published' AND e.starts_at <= ? AND (e.ends_at IS NULL OR e.ends_at >= ?)
             ORDER BY e.starts_at ASC",
            [$end . ' 23:59:59', $start . ' 00:00:00']
        );
        view('events/calendar', [
            'events' => $events,
            'month' => $month,
            'metaTitle' => 'Events Calendar | SLGTI',
            'metaDescription' => 'Monthly institutional events calendar for SLGTI.',
        ]);
    }

    public function index(): void
    {
        $category = trim((string) ($_GET['category'] ?? ''));
        $state = trim((string) ($_GET['state'] ?? ''));
        $where = ["e.status = 'published'", '(e.starts_at >= NOW() OR e.ends_at >= NOW())'];
        $params = [];
        if ($category !== '') {
            $where[] = 'c.slug = ?';
            $params[] = $category;
        }
        if ($state === 'completed') {
            $where = ["e.status = 'published'", 'COALESCE(e.ends_at, e.starts_at) < NOW()'];
        } elseif ($state === 'ongoing') {
            $where = ["e.status = 'published'", 'e.starts_at <= NOW()', '(e.ends_at IS NULL OR e.ends_at >= NOW())'];
        }
        $events = Model::fetchAll(
            "SELECT e.*, c.name AS category_name FROM events e
             LEFT JOIN event_categories c ON c.id = e.category_id
             WHERE " . implode(' AND ', $where) . " ORDER BY e.starts_at ASC",
            $params
        );
        view('events/index', [
            'events' => $events,
            'categories' => Model::fetchAll('SELECT * FROM event_categories ORDER BY name ASC'),
            'activeCategory' => $category,
            'activeState' => $state,
            'metaTitle' => 'Events | SLGTI',
            'metaDescription' => 'Institutional events, student activities and training programmes at SLGTI.',
        ]);
    }

    public function show(string $slug): void
    {
        $event = Model::fetch(
            "SELECT e.*, c.name AS category_name FROM events e
             LEFT JOIN event_categories c ON c.id = e.category_id
             WHERE e.slug = ? AND e.status = 'published'",
            [$slug]
        );
        if (!$event) {
            abort(404);
        }
        view('events/show', [
            'event' => $event,
            'galleries' => Model::fetchAll(
                "SELECT g.*, COUNT(gi.id) AS image_count
                 FROM galleries g LEFT JOIN gallery_images gi ON gi.gallery_id = g.id AND gi.status = 'published'
                 WHERE g.event_id = ? AND g.status = 'published' AND g.display_location IN ('gallery', 'gallery_homepage')
                 GROUP BY g.id ORDER BY g.sort_order ASC, g.id ASC",
                [(int) $event['id']]
            ),
            'metaTitle' => $event['title'] . ' | SLGTI',
            'metaDescription' => $event['excerpt'],
        ]);
    }
}
