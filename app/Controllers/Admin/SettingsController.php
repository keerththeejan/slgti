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
            'metaTitle' => 'Website settings',
        ], 'layouts/admin');
    }

    public function update(): void
    {
        require_permission('settings.manage');
        verify_csrf();
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
        log_activity('settings.updated', 'Website settings updated');
        flash('success', 'Settings saved.');
        redirect('/admin/settings');
    }
}
