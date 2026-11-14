<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class InstitutionalDashboardController
{
    public function index(): void
    {
        $stats = [
            ['value' => settings('stat_students', '1200'), 'label' => 'Students trained', 'icon' => 'bi-people'],
            ['value' => settings('stat_programmes', '18'), 'label' => 'NVQ programmes', 'icon' => 'bi-mortarboard'],
            ['value' => settings('stat_departments', '6'), 'label' => 'Departments', 'icon' => 'bi-building'],
            ['value' => settings('stat_partners', '40'), 'label' => 'Industry partners', 'icon' => 'bi-diagram-3'],
        ];
        $programmes = Model::fetchAll(
            "SELECT p.title, p.slug, l.code AS level_code, d.name AS department_name
             FROM programmes p
             INNER JOIN programme_levels l ON l.id = p.level_id
             INNER JOIN departments d ON d.id = p.department_id
             WHERE p.status = 'published'
             ORDER BY l.sort_order ASC, p.sort_order ASC
             LIMIT 8"
        );
        $notices = Model::fetchAll(
            "SELECT n.title, n.slug, n.published_at, c.name AS category_name
             FROM news n LEFT JOIN news_categories c ON c.id = n.category_id
             WHERE n.status = 'published'
             ORDER BY n.is_featured DESC, n.published_at DESC
             LIMIT 5"
        );
        $events = Model::fetchAll(
            "SELECT title, slug, starts_at, venue
             FROM events
             WHERE status = 'published' AND starts_at >= NOW()
             ORDER BY starts_at ASC
             LIMIT 4"
        );

        view('dashboard/index', compact('stats', 'programmes', 'notices', 'events') + [
            'metaTitle' => 'Institutional Dashboard | SLGTI',
            'metaDescription' => 'Programmes, notices, events and institutional information from SLGTI.',
        ]);
    }
}
