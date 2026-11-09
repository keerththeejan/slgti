<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class SeoController
{
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: " . absolute_url('/sitemap.xml') . "\n";
        exit;
    }

    public function sitemap(): void
    {
        $urls = ['/', '/about', '/programmes', '/departments', '/admissions', '/apply', '/students', '/news', '/events', '/gallery', '/faq', '/contact', '/privacy', '/terms', '/accessibility'];
        foreach (Model::fetchAll("SELECT slug, updated_at FROM pages WHERE status = 'published'") as $row) {
            $urls[] = '/page/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM departments WHERE status = 'published'") as $row) {
            $urls[] = '/departments/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM programmes WHERE status = 'published'") as $row) {
            $urls[] = '/programmes/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM news WHERE status = 'published'") as $row) {
            $urls[] = '/news/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM events WHERE status = 'published'") as $row) {
            $urls[] = '/events/' . $row['slug'];
        }
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach (array_unique($urls) as $path) {
            echo '<url><loc>' . htmlspecialchars(absolute_url($path), ENT_QUOTES, 'UTF-8') . '</loc></url>';
        }
        echo '</urlset>';
        exit;
    }
}
