<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class FacilityController
{
    public function index(): void
    {
        $category = trim((string) ($_GET['category'] ?? ''));
        $where = ["status = 'published'", "display_location IN ('facilities', 'both')"];
        $params = [];
        if ($category !== '') {
            $where[] = 'category = ?';
            $params[] = $category;
        }
        $clause = implode(' AND ', $where);
        view('facilities/index', [
            'facilities' => Model::fetchAll("SELECT * FROM facilities WHERE $clause ORDER BY sort_order ASC, id ASC", $params),
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM facilities WHERE status = 'published' AND category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'category' => $category,
            'metaTitle' => 'Facilities | SLGTI',
            'metaDescription' => 'Explore workshops, laboratories and campus facilities at SLGTI.',
            'metaCanonical' => absolute_url('facilities'),
        ]);
    }

    public function show(string $slug): void
    {
        $facility = Model::fetch("SELECT * FROM facilities WHERE slug = ? AND status = 'published' AND display_location IN ('facilities', 'both')", [$slug]);
        if (!$facility) {
            abort(404);
        }
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOccupationalProgram',
            'name' => $facility['title'],
            'description' => $facility['meta_description'] ?: ($facility['description'] ?: $facility['summary']),
            'url' => $facility['canonical_url'] ?: absolute_url('facilities/' . $facility['slug']),
            'image' => $facility['image'] ? media_url($facility['image']) : null,
        ];
        view('facilities/show', [
            'facility' => $facility,
            'structuredData' => array_filter($structuredData),
            'metaTitle' => $facility['seo_title'] ?: $facility['title'] . ' | SLGTI',
            'metaDescription' => $facility['meta_description'] ?: excerpt($facility['description'] ?: $facility['summary'], 160),
            'metaCanonical' => $facility['canonical_url'] ?: absolute_url('facilities/' . $facility['slug']),
            'metaRobots' => ($facility['robots_index'] ?: 'index') . ', ' . ($facility['robots_follow'] ?: 'follow'),
            'metaImage' => $facility['og_image'] ? media_url($facility['og_image']) : media_url($facility['image']),
        ]);
    }
}
