<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class SearchController
{
    public function index(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $results = [];
        if (strlen($q) >= 2) {
            $like = '%' . $q . '%';
            foreach (Model::fetchAll("SELECT title, slug, excerpt FROM news WHERE status = 'published' AND (title LIKE ? OR excerpt LIKE ? OR body LIKE ?) ORDER BY published_at DESC LIMIT 8", [$like, $like, $like]) as $row) {
                $results[] = ['type' => 'News', 'title' => $row['title'], 'url' => '/news/' . $row['slug'], 'text' => $row['excerpt']];
            }
            foreach (Model::fetchAll("SELECT title, slug, summary FROM programmes WHERE status = 'published' AND (title LIKE ? OR summary LIKE ?) LIMIT 8", [$like, $like]) as $row) {
                $results[] = ['type' => 'Programme', 'title' => $row['title'], 'url' => '/programmes/' . $row['slug'], 'text' => $row['summary']];
            }
            foreach (Model::fetchAll("SELECT name, slug, summary FROM departments WHERE status = 'published' AND (name LIKE ? OR summary LIKE ?) LIMIT 6", [$like, $like]) as $row) {
                $results[] = ['type' => 'Department', 'title' => $row['name'], 'url' => '/departments/' . $row['slug'], 'text' => $row['summary']];
            }
            foreach (Model::fetchAll("SELECT title, slug, excerpt FROM pages WHERE status = 'published' AND (title LIKE ? OR excerpt LIKE ? OR body LIKE ?) LIMIT 6", [$like, $like, $like]) as $row) {
                $results[] = ['type' => 'Page', 'title' => $row['title'], 'url' => '/page/' . $row['slug'], 'text' => $row['excerpt']];
            }
            foreach (Model::fetchAll("SELECT title, slug, excerpt FROM events WHERE status = 'published' AND (title LIKE ? OR excerpt LIKE ?) LIMIT 6", [$like, $like]) as $row) {
                $results[] = ['type' => 'Event', 'title' => $row['title'], 'url' => '/events/' . $row['slug'], 'text' => $row['excerpt']];
            }
        }
        view('search/index', [
            'q' => $q,
            'results' => $results,
            'metaTitle' => 'Search | SLGTI',
            'metaDescription' => 'Search programmes, departments and news at SLGTI.',
        ]);
    }
}
