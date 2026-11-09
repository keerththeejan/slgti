<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class PageController
{
    public function about(): void
    {
        $page = $this->page('about-slgti');
        $principal = $this->page('principal-message');
        $staff = Model::fetchAll(
            "SELECT s.*, d.name AS department_name FROM staff s
             LEFT JOIN departments d ON d.id = s.department_id
             WHERE s.status = 'published' ORDER BY s.sort_order ASC"
        );
        $facilities = Model::fetchAll("SELECT * FROM facilities WHERE status = 'published' ORDER BY sort_order ASC");
        view('pages/about', [
            'page' => $page,
            'principal' => $principal,
            'staff' => $staff,
            'facilities' => $facilities,
            'metaTitle' => $page['meta_title'] ?: 'About SLGTI',
            'metaDescription' => $page['meta_description'] ?: $page['excerpt'],
        ]);
    }

    public function admissions(): void
    {
        $announcements = Model::fetchAll(
            "SELECT n.* FROM news n
             INNER JOIN news_categories c ON c.id = n.category_id
             WHERE n.status = 'published' AND c.slug = 'admission'
             ORDER BY n.published_at DESC LIMIT 6"
        );
        $levels = Model::fetchAll('SELECT * FROM programme_levels ORDER BY sort_order ASC');
        view('pages/admissions', [
            'announcements' => $announcements,
            'levels' => $levels,
            'metaTitle' => 'Admissions | SLGTI',
            'metaDescription' => 'Apply for NVQ Level 4, 5 and 6 programmes at the Sri Lanka German Training Institute in Kilinochchi.',
        ]);
    }

    public function students(): void
    {
        view('pages/students', [
            'calendar' => $this->page('academic-calendar'),
            'metaTitle' => 'Students | SLGTI',
            'metaDescription' => 'Student information, e-learning, academic support and campus services at SLGTI.',
        ]);
    }

    public function faq(): void
    {
        $faqs = Model::fetchAll("SELECT * FROM faqs WHERE status = 'published' ORDER BY sort_order ASC");
        view('pages/faq', [
            'faqs' => $faqs,
            'metaTitle' => 'FAQ | SLGTI',
            'metaDescription' => 'Answers to common questions about admissions, programmes and student life at SLGTI.',
        ]);
    }

    public function contact(): void
    {
        view('pages/contact', [
            'metaTitle' => 'Contact Us | SLGTI',
            'metaDescription' => 'Contact the Sri Lanka German Training Institute, Ariviyal Nagar, Kilinochchi.',
        ]);
    }

    public function privacy(): void
    {
        $this->renderLegal('privacy-policy', 'Privacy Policy');
    }

    public function terms(): void
    {
        $this->renderLegal('terms-of-use', 'Terms of Use');
    }

    public function accessibility(): void
    {
        $this->renderLegal('accessibility', 'Accessibility');
    }

    private function renderLegal(string $slug, string $title): void
    {
        $page = $this->page($slug);
        view('pages/simple', [
            'page' => $page,
            'metaTitle' => $page['meta_title'] ?: $title,
            'metaDescription' => $page['meta_description'] ?: excerpt($page['body']),
        ]);
    }

    public function show(string $slug): void
    {
        $page = Model::fetch("SELECT * FROM pages WHERE slug = ? AND status = 'published'", [$slug]);
        if (!$page) {
            abort(404);
        }
        view('pages/simple', [
            'page' => $page,
            'metaTitle' => $page['meta_title'] ?: $page['title'],
            'metaDescription' => $page['meta_description'] ?: excerpt($page['excerpt'] ?: $page['body']),
        ]);
    }

    private function page(string $slug): array
    {
        $page = Model::fetch("SELECT * FROM pages WHERE slug = ? AND status = 'published'", [$slug]);
        if (!$page) {
            abort(404);
        }
        return $page;
    }
}
