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
        $urls = ['/', '/about', '/programmes', '/departments', '/admissions', '/apply', '/students', '/news', '/events', '/gallery', '/videos', '/testimonials', '/staff', '/facilities', '/faq', '/contact', '/privacy', '/terms', '/accessibility'];
        foreach (Model::fetchAll("SELECT slug, updated_at FROM pages WHERE status = 'published' AND robots_index = 'index'") as $row) {
            $urls[] = '/page/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug, seo_slug, updated_at FROM departments WHERE status = 'published'") as $row) {
            $urls[] = '/departments/' . ($row['seo_slug'] ?: $row['slug']);
        }
        foreach (Model::fetchAll("SELECT slug, seo_slug FROM programmes WHERE status = 'published'") as $row) {
            $urls[] = '/programmes/' . ($row['seo_slug'] ?: $row['slug']);
        }
        foreach (Model::fetchAll("SELECT slug, seo_slug FROM programme_levels WHERE status = 'published'") as $row) {
            $urls[] = '/programme-levels/' . ($row['seo_slug'] ?: $row['slug']);
        }
        foreach (Model::fetchAll("SELECT slug FROM programme_requirements WHERE status = 'published' AND slug IS NOT NULL") as $row) {
            $urls[] = '/requirements/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM news WHERE status = 'published'") as $row) {
            $urls[] = '/news/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM events WHERE status = 'published'") as $row) {
            $urls[] = '/events/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM galleries WHERE status = 'published' AND display_location IN ('gallery', 'gallery_homepage')") as $row) {
            $urls[] = '/gallery/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM videos WHERE status = 'published' AND slug IS NOT NULL") as $row) {
            $urls[] = '/videos/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM staff WHERE status = 'published' AND display_location IN ('directory', 'both') AND slug IS NOT NULL") as $row) {
            $urls[] = '/staff/' . $row['slug'];
        }
        foreach (Model::fetchAll("SELECT slug FROM facilities WHERE status = 'published' AND display_location IN ('facilities', 'both') AND slug IS NOT NULL") as $row) {
            $urls[] = '/facilities/' . $row['slug'];
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
