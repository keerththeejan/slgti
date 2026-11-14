<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class HomeController
{
    public function index(): void
    {
        $slides = Model::fetchAll(
            "SELECT * FROM sliders
             WHERE status = 'published' AND display_location = 'homepage'
             AND (starts_at IS NULL OR starts_at <= NOW())
             AND (ends_at IS NULL OR ends_at >= NOW())
             ORDER BY sort_order ASC, id ASC"
        );
        if ($slides === []) {
            $slides = [[
                'eyebrow' => 'Sri Lanka German Training Institute',
                'title' => 'Skills for a',
                'title_highlight' => 'Brighter Future',
                'subtitle' => 'Industry-oriented technical and vocational training in Kilinochchi.',
                'image' => 'assets/images/hero-1.jpg',
                'button_text' => 'Explore programmes',
                'button_url' => '/programmes',
                'secondary_text' => 'Apply now',
                'secondary_url' => '/apply',
            ]];
        }
        $quickLinks = published_quick_links('homepage');
        $departments = Model::fetchAll("SELECT * FROM departments WHERE status = 'published' ORDER BY sort_order ASC");
        $levels = Model::fetchAll("SELECT * FROM programme_levels WHERE status = 'published' ORDER BY sort_order ASC");
        $programmes = Model::fetchAll(
            "SELECT p.*, d.name AS department_name, d.slug AS department_slug, l.name AS level_name, l.code AS level_code, l.qualification
             FROM programmes p
             INNER JOIN departments d ON d.id = p.department_id
             INNER JOIN programme_levels l ON l.id = p.level_id
             WHERE p.status = 'published'
             ORDER BY l.sort_order ASC, p.sort_order ASC"
        );
        $news = Model::fetchAll(
            "SELECT n.*, c.name AS category_name, c.slug AS category_slug
             FROM news n LEFT JOIN news_categories c ON c.id = n.category_id
             WHERE n.status = 'published' AND (n.published_at IS NULL OR n.published_at <= NOW()) ORDER BY n.published_at DESC LIMIT 3"
        );
        $events = Model::fetchAll(
            "SELECT * FROM events WHERE status = 'published' AND starts_at >= NOW() ORDER BY starts_at ASC LIMIT 2"
        );
        $testimonials = Model::fetchAll("SELECT * FROM testimonials WHERE status = 'published' AND approval_status = 'approved' AND display_location IN ('homepage', 'both') ORDER BY sort_order ASC, id ASC");
        $facilities = Model::fetchAll("SELECT * FROM facilities WHERE status = 'published' AND display_location IN ('homepage', 'both') ORDER BY sort_order ASC, id ASC");
        $aboutPage = Model::fetch("SELECT * FROM pages WHERE slug = 'about-slgti' AND status = 'published'");
        $photos = Model::fetchAll(
            "SELECT gi.*, g.title AS album, g.slug AS album_slug
             FROM gallery_images gi INNER JOIN galleries g ON g.id = gi.gallery_id
             WHERE gi.status = 'published' AND g.status = 'published'
             AND g.display_location IN ('homepage', 'gallery_homepage')
             ORDER BY gi.sort_order ASC LIMIT 6"
        );
        $stats = [
            ['value' => settings('stat_students', '1200'), 'label' => 'Students trained'],
            ['value' => settings('stat_programmes', '18'), 'label' => 'NVQ programmes'],
            ['value' => settings('stat_departments', '6'), 'label' => 'Departments'],
            ['value' => settings('stat_partners', '40'), 'label' => 'Industry partners'],
        ];

        view('home/index', compact(
            'slides',
            'quickLinks',
            'departments',
            'levels',
            'programmes',
            'news',
            'events',
            'testimonials',
            'facilities',
            'photos',
            'aboutPage',
            'stats'
        ) + [
            'metaTitle' => 'SLGTI | Sri Lanka German Training Institute, Kilinochchi',
            'metaDescription' => settings('meta_description'),
        ]);
    }
}
