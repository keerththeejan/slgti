<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;
use App\Middleware\AuthMiddleware;

final class DashboardController
{
    public function index(): void
    {
        AuthMiddleware::admin();
        $counts = [
            'news' => (int) Model::fetch("SELECT COUNT(*) AS total FROM news WHERE status = 'published'")['total'],
            'news_total' => (int) Model::fetch('SELECT COUNT(*) AS total FROM news')['total'],
            'news_draft' => (int) Model::fetch("SELECT COUNT(*) AS total FROM news WHERE status = 'draft'")['total'],
            'news_archived' => (int) Model::fetch("SELECT COUNT(*) AS total FROM news WHERE status = 'archived'")['total'],
            'news_featured' => (int) Model::fetch('SELECT COUNT(*) AS total FROM news WHERE is_featured = 1')['total'],
            'events' => (int) Model::fetch("SELECT COUNT(*) AS total FROM events WHERE status = 'published'")['total'],
            'events_total' => (int) Model::fetch('SELECT COUNT(*) AS total FROM events')['total'],
            'events_upcoming' => (int) Model::fetch("SELECT COUNT(*) AS total FROM events WHERE status = 'published' AND starts_at > NOW()")['total'],
            'events_ongoing' => (int) Model::fetch("SELECT COUNT(*) AS total FROM events WHERE status = 'published' AND starts_at <= NOW() AND (ends_at IS NULL OR ends_at >= NOW())")['total'],
            'events_completed' => (int) Model::fetch("SELECT COUNT(*) AS total FROM events WHERE status = 'published' AND COALESCE(ends_at, starts_at) < NOW()")['total'],
            'events_draft' => (int) Model::fetch("SELECT COUNT(*) AS total FROM events WHERE status = 'draft'")['total'],
            'programmes' => (int) Model::fetch("SELECT COUNT(*) AS total FROM programmes WHERE status = 'published'")['total'],
            'departments' => (int) Model::fetch("SELECT COUNT(*) AS total FROM departments WHERE status = 'published'")['total'],
            'departments_total' => (int) Model::fetch('SELECT COUNT(*) AS total FROM departments')['total'],
            'departments_inactive' => (int) Model::fetch("SELECT COUNT(*) AS total FROM departments WHERE status != 'published'")['total'],
            'albums' => (int) Model::fetch("SELECT COUNT(*) AS total FROM galleries WHERE status = 'published'")['total'],
            'staff' => (int) Model::fetch("SELECT COUNT(*) AS total FROM staff WHERE status = 'published'")['total'],
            'applications' => (int) Model::fetch('SELECT COUNT(*) AS total FROM applications')['total'],
            'pending' => (int) Model::fetch("SELECT COUNT(*) AS total FROM applications WHERE status = 'pending'")['total'],
            'messages' => (int) Model::fetch("SELECT COUNT(*) AS total FROM contact_messages WHERE status = 'new'")['total'],
            'media' => (int) Model::fetch('SELECT COUNT(*) AS total FROM media')['total'],
        ];
        $activity = Model::fetchAll(
            'SELECT l.action, l.description, l.created_at, u.name AS user_name
             FROM activity_logs l LEFT JOIN users u ON u.id = l.user_id
             ORDER BY l.id DESC LIMIT 6'
        );
        $recentApplications = Model::fetchAll(
            'SELECT a.*, p.title AS programme FROM applications a LEFT JOIN programmes p ON p.id = a.programme_id ORDER BY a.id DESC LIMIT 6'
        );
        $recentMessages = Model::fetchAll('SELECT * FROM contact_messages ORDER BY id DESC LIMIT 5');
        $recentNews = Model::fetchAll(
            "SELECT id, title, slug, image, status, published_at
             FROM news ORDER BY COALESCE(published_at, created_at) DESC, id DESC LIMIT 5"
        );
        $recentEvents = Model::fetchAll("SELECT id, title, slug, image, starts_at, status FROM events ORDER BY starts_at DESC, id DESC LIMIT 5");
        $chartRows = Model::fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
             FROM applications
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
             GROUP BY ym ORDER BY ym ASC"
        );
        $indexed = [];
        foreach ($chartRows as $row) {
            $indexed[$row['ym']] = (int) $row['total'];
        }
        $labels = [];
        $series = [];
        for ($i = 5; $i >= 0; $i--) {
            $stamp = strtotime(date('Y-m-01') . " -$i months");
            $key = date('Y-m', $stamp);
            $labels[] = date('M Y', $stamp);
            $series[] = $indexed[$key] ?? 0;
        }
        $newsRows = Model::fetchAll(
            "SELECT DATE_FORMAT(published_at, '%Y-%m') AS ym, COUNT(*) AS total
             FROM news
             WHERE status = 'published' AND published_at >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
             GROUP BY ym ORDER BY ym ASC"
        );
        $newsIndexed = [];
        foreach ($newsRows as $row) {
            $newsIndexed[$row['ym']] = (int) $row['total'];
        }
        $newsSeries = [];
        foreach ($labels as $index => $label) {
            $stamp = strtotime(date('Y-m-01') . ' -' . (5 - $index) . ' months');
            $newsSeries[] = $newsIndexed[date('Y-m', $stamp)] ?? 0;
        }
        view('admin/dashboard', [
            'counts' => $counts,
            'recentApplications' => $recentApplications,
            'recentMessages' => $recentMessages,
            'recentNews' => $recentNews,
            'recentEvents' => $recentEvents,
            'activity' => $activity,
            'chartLabels' => $labels,
            'chartSeries' => $series,
            'newsSeries' => $newsSeries,
            'metaTitle' => 'Dashboard',
        ], 'layouts/admin');
    }
}
