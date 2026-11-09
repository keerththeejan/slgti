<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class EventController
{
    public function index(): void
    {
        $events = Model::fetchAll(
            "SELECT e.*, c.name AS category_name FROM events e
             LEFT JOIN event_categories c ON c.id = e.category_id
             WHERE e.status = 'published' ORDER BY e.starts_at DESC"
        );
        view('events/index', [
            'events' => $events,
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
            'metaTitle' => $event['title'] . ' | SLGTI',
            'metaDescription' => $event['excerpt'],
        ]);
    }
}
