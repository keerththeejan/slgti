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
             WHERE s.status = 'published' AND s.display_location IN ('directory', 'both') ORDER BY s.sort_order ASC, s.id ASC"
        );
        $facilities = Model::fetchAll("SELECT * FROM facilities WHERE status = 'published' AND display_location IN ('facilities', 'both') ORDER BY sort_order ASC, id ASC");
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
        $levels = Model::fetchAll("SELECT * FROM programme_levels WHERE status = 'published' ORDER BY sort_order ASC");
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
        $q = trim((string) ($_GET['q'] ?? ''));
        $category = trim((string) ($_GET['category'] ?? ''));
        $where = ["status = 'published'"];
        $params = [];
        if ($q !== '') {
            $where[] = '(question LIKE ? OR answer LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%');
        }
        if ($category !== '') {
            $where[] = 'category = ?';
            $params[] = $category;
        }
        $faqs = Model::fetchAll('SELECT * FROM faqs WHERE ' . implode(' AND ', $where) . ' ORDER BY sort_order ASC, id ASC', $params);
        $faqSchema = [];
        foreach ($faqs as $faq) {
            $faqSchema[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($faq['answer'])],
            ];
        }
        view('pages/faq', [
            'faqs' => $faqs,
            'q' => $q,
            'category' => $category,
            'categories' => Model::fetchAll("SELECT DISTINCT category FROM faqs WHERE status = 'published' AND category IS NOT NULL AND category <> '' ORDER BY category ASC"),
            'metaTitle' => 'FAQ | SLGTI',
            'metaDescription' => 'Answers to common questions about admissions, programmes and student life at SLGTI.',
            'metaCanonical' => absolute_url('faq'),
            'structuredData' => ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faqSchema],
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
        $page = Model::fetch(
            "SELECT p.*, parent.title AS parent_title, parent.slug AS parent_slug
             FROM pages p LEFT JOIN pages parent ON parent.id = p.parent_id
             WHERE p.slug = ? AND p.status = 'published'",
            [$slug]
        );
        if (!$page) {
            abort(404);
        }
        $children = Model::fetchAll(
            "SELECT title, slug, excerpt FROM pages WHERE parent_id = ? AND status = 'published' ORDER BY sort_order ASC, id ASC",
            [$page['id']]
        );
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page['title'],
            'url' => $page['canonical_url'] ?: absolute_url('page/' . $page['slug']),
            'description' => $page['meta_description'] ?: excerpt($page['excerpt'] ?: $page['body']),
        ];
        view('pages/simple', [
            'page' => $page,
            'children' => $children,
            'metaTitle' => $page['meta_title'] ?: $page['title'],
            'metaDescription' => $page['meta_description'] ?: excerpt($page['excerpt'] ?: $page['body']),
            'metaCanonical' => $page['canonical_url'] ?: absolute_url('page/' . $page['slug']),
            'metaImage' => $page['og_image'] ? media_url($page['og_image']) : ($page['image'] ? media_url($page['image']) : absolute_url('assets/images/hero-1.jpg')),
            'metaTwitterImage' => $page['twitter_image'] ? media_url($page['twitter_image']) : null,
            'metaOgTitle' => $page['og_title'] ?: ($page['meta_title'] ?: $page['title']),
            'metaOgDescription' => $page['og_description'] ?: ($page['meta_description'] ?: excerpt($page['excerpt'] ?: $page['body'])),
            'metaRobots' => ($page['robots_index'] ?: 'index') . ', ' . ($page['robots_follow'] ?: 'follow'),
            'structuredData' => $structuredData,
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
