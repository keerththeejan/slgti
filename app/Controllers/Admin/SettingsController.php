<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class SettingsController
{
    public function index(): void
    {
        require_permission('settings.manage');
        $rows = Model::fetchAll('SELECT * FROM settings ORDER BY setting_group ASC, setting_key ASC');
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['setting_group']][] = $row;
        }
        view('admin/settings/index', [
            'grouped' => $grouped,
            'aboutPage' => Model::fetch('SELECT * FROM pages WHERE slug = ?', ['about-slgti']),
            'managedKeys' => ['site_logo', 'site_logo_alt', 'site_logo_width', 'site_logo_height', 'favicon_16', 'favicon_32', 'favicon_48', 'favicon_apple', 'favicon_android', 'favicon_large', 'footer_logo', 'footer_visible', 'footer_copyright', 'footer_website', 'footer_text', 'phone', 'phone_alt', 'fax', 'email', 'address', 'location_label', 'hours', 'facebook_url', 'youtube_url', 'linkedin_url'],
            'metaTitle' => 'Website settings',
        ], 'layouts/admin');
    }

    public function update(): void
    {
        require_permission('settings.manage');
        verify_csrf();
        $section = (string) ($_POST['section'] ?? 'other');
        try {
            if ($section === 'favicon') {
                $this->updateFavicon();
            } elseif ($section === 'logo') {
                $this->updateLogo();
            } elseif ($section === 'footer') {
                $this->updateFooter();
            } elseif ($section === 'about') {
                $this->updateAbout();
            } else {
                $this->updateOther();
            }
        } catch (\RuntimeException $exception) {
            flash('error', $exception->getMessage());
            redirect('/admin/settings');
        }
        log_activity('settings.updated', 'Website settings updated');
        flash('success', 'Settings saved.');
        redirect('/admin/settings');
    }

    private function updateOther(): void
    {
        $allowed = Model::fetchAll('SELECT setting_key FROM settings');
        $keys = array_column($allowed, 'setting_key');
        $posted = $_POST['settings'] ?? [];
        if (!is_array($posted)) {
            $posted = [];
        }
        foreach ($keys as $key) {
            if (!array_key_exists($key, $posted)) {
                continue;
            }
            $value = trim((string) $posted[$key]);
            Model::query('UPDATE settings SET setting_value = ? WHERE setting_key = ?', [$value, $key]);
        }
    }

    private function updateLogo(): void
    {
        $alt = trim((string) ($_POST['site_logo_alt'] ?? ''));
        $width = max(80, min(500, (int) ($_POST['site_logo_width'] ?? 220)));
        $height = max(24, min(180, (int) ($_POST['site_logo_height'] ?? 52)));
        $this->setSetting('site_logo_alt', $alt !== '' ? $alt : 'Sri Lanka German Training Institute');
        $this->setSetting('site_logo_width', (string) $width);
        $this->setSetting('site_logo_height', (string) $height);
        if (!empty($_POST['remove_site_logo'])) {
            $this->setSetting('site_logo', '');
        } elseif (!empty($_FILES['site_logo']['name'])) {
            $this->setSetting('site_logo', upload_image($_FILES['site_logo']));
        }
    }

    private function updateFavicon(): void
    {
        $keys = ['favicon_16', 'favicon_32', 'favicon_48', 'favicon_apple', 'favicon_android', 'favicon_large'];
        if (!empty($_POST['remove_favicon'])) {
            foreach ($keys as $key) {
                $this->setSetting($key, '');
            }
            return;
        }
        if (empty($_FILES['favicon']['name'])) {
            throw new \RuntimeException('Choose a favicon to upload or select remove.');
        }
        foreach (upload_favicon($_FILES['favicon']) as $key => $path) {
            $this->setSetting($key, $path);
        }
    }

    private function updateFooter(): void
    {
        $keys = ['footer_text', 'footer_copyright', 'footer_website', 'phone', 'phone_alt', 'fax', 'email', 'address', 'location_label', 'hours', 'facebook_url', 'youtube_url', 'linkedin_url'];
        foreach ($keys as $key) {
            $value = trim((string) ($_POST[$key] ?? ''));
            if (($key === 'footer_website' || str_ends_with($key, '_url')) && $value !== '' && (!$this->isHttpUrl($value))) {
                throw new \RuntimeException('Enter a valid URL for ' . str_replace('_', ' ', $key) . '.');
            }
            $this->setSetting($key, $value);
        }
        $this->setSetting('footer_visible', isset($_POST['footer_visible']) ? '1' : '0');
        if (!empty($_POST['remove_footer_logo'])) {
            $this->setSetting('footer_logo', '');
        } elseif (!empty($_FILES['footer_logo']['name'])) {
            $this->setSetting('footer_logo', upload_image($_FILES['footer_logo']));
        }
    }

    private function updateAbout(): void
    {
        $page = Model::fetch('SELECT * FROM pages WHERE slug = ?', ['about-slgti']);
        if (!$page) {
            throw new \RuntimeException('The About Us page record could not be found.');
        }
        $title = trim((string) ($_POST['title'] ?? ''));
        $excerpt = trim((string) ($_POST['excerpt'] ?? ''));
        $body = clean_html((string) ($_POST['body'] ?? ''));
        if ($title === '' || $body === '') {
            throw new \RuntimeException('About Us title and content are required.');
        }
        $data = [
            'title' => $title,
            'excerpt' => $excerpt,
            'body' => $body,
            'image_alt' => trim((string) ($_POST['image_alt'] ?? '')) ?: $title,
            'status' => in_array($_POST['status'] ?? '', ['draft', 'published'], true) ? $_POST['status'] : 'published',
        ];
        if (!empty($_POST['remove_image'])) {
            $data['image'] = null;
        } elseif (!empty($_FILES['image']['name'])) {
            $data['image'] = upload_image($_FILES['image']);
        }
        Model::updateById('pages', (int) $page['id'], $data);
    }

    private function setSetting(string $key, string $value): void
    {
        Model::query('UPDATE settings SET setting_value = ? WHERE setting_key = ?', [$value, $key]);
    }

    private function isHttpUrl(string $value): bool
    {
        $parts = parse_url($value);
        return filter_var($value, FILTER_VALIDATE_URL) !== false
            && in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true)
            && empty($parts['user'])
            && empty($parts['pass']);
    }
}
