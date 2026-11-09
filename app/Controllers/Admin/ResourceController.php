<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;
use PDOException;
use RuntimeException;

final class ResourceController
{
    public function index(string $resource): void
    {
        $config = $this->config($resource);
        $this->authorize($config, true);
        $q = trim((string) ($_GET['q'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $parts = [];
            foreach ($config['search'] as $column) {
                $parts[] = '`' . ident($column) . '` LIKE ?';
                $params[] = '%' . $q . '%';
            }
            $where = '(' . implode(' OR ', $parts) . ')';
        }
        $status = trim((string) ($_GET['status'] ?? ''));
        if (!empty($config['status_filter']) && in_array($status, $config['status_filter'], true)) {
            $where .= ' AND status = ?';
            $params[] = $status;
        }
        $this->limitDepartment($resource, $where, $params);
        $table = ident($config['table']);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM `$table` WHERE $where", $params)['total'];
        $pager = paginate($total, $page, 12);
        $order = $config['order'];
        $rows = Model::fetchAll(
            "SELECT * FROM `$table` WHERE $where ORDER BY $order LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/resource/index', [
            'resource' => $resource,
            'config' => $config,
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'metaTitle' => $config['title'],
        ], 'layouts/admin');
    }

    public function create(string $resource): void
    {
        $config = $this->config($resource);
        $this->authorize($config, false);
        if (($config['creatable'] ?? true) === false) {
            redirect('/admin/' . $resource);
        }
        view('admin/resource/form', [
            'resource' => $resource,
            'config' => $config,
            'record' => null,
            'options' => $this->options($config),
            'metaTitle' => 'New ' . $config['title'],
        ], 'layouts/admin');
    }

    public function store(string $resource): void
    {
        $config = $this->config($resource);
        $this->authorize($config, false);
        verify_csrf();
        if ($resource === 'gallery-images' && is_array($_FILES['image']['name'] ?? null)) {
            $this->storeGalleryBatch();
            return;
        }
        [$data, $errors] = $this->collect($resource, $config, null);
        $this->forceDepartment($resource, $data);
        if ($errors) {
            set_old($_POST);
            $_SESSION['_errors'] = $errors;
            flash('error', 'Please correct the form.');
            redirect('/admin/' . $resource . '/create');
        }
        try {
            $id = Model::insert($config['table'], $data);
            $this->syncUserRole($resource, $id, $data);
        } catch (PDOException) {
            flash('error', 'The record could not be saved. Check for a duplicate name or slug.');
            set_old($_POST);
            redirect('/admin/' . $resource . '/create');
        }
        log_activity($resource . '.created', $config['title'] . ' #' . $id);
        flash('success', $config['title'] . ' saved.');
        redirect('/admin/' . $resource);
    }

    public function edit(string $resource, string $id): void
    {
        $config = $this->config($resource);
        $this->authorize($config, true);
        $record = $this->find($config, (int) $id);
        view('admin/resource/form', [
            'resource' => $resource,
            'config' => $config,
            'record' => $record,
            'options' => $this->options($config),
            'metaTitle' => 'Edit ' . $config['title'],
        ], 'layouts/admin');
    }

    public function update(string $resource, string $id): void
    {
        $config = $this->config($resource);
        $this->authorize($config, false);
        verify_csrf();
        $record = $this->find($config, (int) $id);
        [$data, $errors] = $this->collect($resource, $config, $record);
        $this->forceDepartment($resource, $data);
        if ($errors) {
            set_old($_POST);
            $_SESSION['_errors'] = $errors;
            flash('error', 'Please correct the form.');
            redirect('/admin/' . $resource . '/' . $record['id'] . '/edit');
        }
        if ($resource === 'users' && (int) $record['id'] === (int) $_SESSION['user_id'] && ($data['status'] ?? 'active') !== 'active') {
            flash('error', 'You cannot disable your own account.');
            redirect('/admin/users/' . $record['id'] . '/edit');
        }
        try {
            Model::updateById($config['table'], (int) $record['id'], $data);
            $this->syncUserRole($resource, (int) $record['id'], $data);
        } catch (PDOException) {
            flash('error', 'The record could not be updated. Check for a duplicate name or slug.');
            redirect('/admin/' . $resource . '/' . $record['id'] . '/edit');
        }
        log_activity($resource . '.updated', $config['title'] . ' #' . $record['id']);
        flash('success', $config['title'] . ' updated.');
        redirect('/admin/' . $resource);
    }

    public function destroy(string $resource, string $id): void
    {
        $config = $this->config($resource);
        $this->authorize($config, false);
        verify_csrf();
        $record = $this->find($config, (int) $id);
        if ($resource === 'users' && (int) $record['id'] === (int) $_SESSION['user_id']) {
            $this->fail('You cannot delete your own account.');
        }
        try {
            Model::deleteById($config['table'], (int) $record['id']);
        } catch (PDOException) {
            $this->fail('This record is linked to other content and cannot be deleted.');
        }
        log_activity($resource . '.deleted', $config['title'] . ' #' . $record['id']);
        if (wants_json()) {
            json_response(['ok' => true]);
        }
        flash('success', 'Record deleted.');
        redirect('/admin/' . $resource);
    }

    private function config(string $resource): array
    {
        $all = admin_resources();
        if (!isset($all[$resource])) {
            abort(404);
        }
        return $all[$resource];
    }

    private function authorize(array $config, bool $read): void
    {
        require_admin();
        $permission = $config['permission'];
        if ($read && !empty($config['view_permission']) && can($config['view_permission'])) {
            return;
        }
        if (!can($permission)) {
            http_response_code(403);
            exit('You do not have permission to manage this content.');
        }
    }

    private function find(array $config, int $id): array
    {
        $row = Model::fetch('SELECT * FROM `' . ident($config['table']) . '` WHERE id = ?', [$id]);
        if (!$row || !$this->recordInDepartment($config['table'], $row)) {
            abort(404);
        }
        return $row;
    }

    private function options(array $config): array
    {
        $options = [];
        foreach ($config['fields'] as $name => $field) {
            if (($field['type'] ?? '') !== 'select' || empty($field['source'])) {
                continue;
            }
            $label = $field['source_label'] ?? 'name';
            if (!in_array($label, ['name', 'title', 'label', 'email'], true)) {
                $label = 'name';
            }
            $rows = Model::fetchAll('SELECT id, `' . ident($label) . '` AS label FROM `' . ident($field['source']) . '` ORDER BY label ASC');
            $options[$name] = $rows;
        }
        return $options;
    }

    private function collect(string $resource, array $config, ?array $record): array
    {
        $data = [];
        $errors = [];
        foreach ($config['fields'] as $name => $field) {
            $type = $field['type'];
            if ($type === 'file') {
                if (!empty($_FILES[$name]['name'])) {
                    try {
                        $data[$name] = upload_image($_FILES[$name]);
                    } catch (RuntimeException $exception) {
                        $errors[$name] = $exception->getMessage();
                    }
                } elseif ($record === null && ($field['required'] ?? false)) {
                    $errors[$name] = 'An image is required.';
                }
                continue;
            }
            if ($type === 'checkbox') {
                $data[$name] = isset($_POST[$name]) ? 1 : 0;
                continue;
            }
            if ($type === 'password') {
                $password = (string) ($_POST[$name] ?? '');
                if ($password === '' && $record === null) {
                    $errors[$name] = 'Enter a password of at least 8 characters.';
                } elseif ($password !== '' && strlen($password) < 8) {
                    $errors[$name] = 'Use at least 8 characters.';
                } elseif ($password !== '') {
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
                continue;
            }

            $value = trim((string) ($_POST[$name] ?? ''));
            if ($type === 'slug') {
                $source = $_POST['title'] ?? $_POST['name'] ?? $_POST['question'] ?? $_POST['label'] ?? $value;
                $value = slugify($value !== '' ? $value : (string) $source);
            }
            if (($field['required'] ?? false) && $value === '') {
                $errors[$name] = 'This field is required.';
            }
            if ($type === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$name] = 'Enter a valid email address.';
            }
            if ($type === 'number') {
                $data[$name] = $value === '' ? 0 : (int) $value;
                continue;
            }
            if ($type === 'datetime') {
                $data[$name] = $value === '' ? null : str_replace('T', ' ', $value) . (strlen($value) === 16 ? ':00' : '');
                continue;
            }
            if ($type === 'select' && $value === '') {
                $data[$name] = null;
                continue;
            }
            if ($type === 'editor') {
                $data[$name] = clean_html($value);
                continue;
            }
            $data[$name] = $value;
        }

        if ($resource === 'users' && isset($data['email'])) {
            $ignore = (int) ($record['id'] ?? 0);
            $existing = Model::fetch('SELECT id FROM users WHERE email = ? AND id != ?', [$data['email'], $ignore]);
            if ($existing) {
                $errors['email'] = 'That email is already in use.';
            }
        }

        if (isset($data['slug'])) {
            $ignore = (int) ($record['id'] ?? 0);
            $existing = Model::fetch(
                'SELECT id FROM `' . ident($config['table']) . '` WHERE slug = ? AND id != ?',
                [$data['slug'], $ignore]
            );
            if ($existing) {
                $data['slug'] .= '-' . ($ignore ?: time());
            }
        }

        if ($record === null && in_array('created_at', $this->columns($config['table']), true) && !isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return [$data, $errors];
    }

    private function columns(string $table): array
    {
        static $cache = [];
        if (!isset($cache[$table])) {
            $rows = Model::fetchAll('SHOW COLUMNS FROM `' . ident($table) . '`');
            $cache[$table] = array_column($rows, 'Field');
        }
        return $cache[$table];
    }

    private function limitDepartment(string $resource, string &$where, array &$params): void
    {
        if (($_SESSION['role'] ?? '') !== 'department_manager') {
            return;
        }
        $dept = (int) ($_SESSION['department_id'] ?? 0);
        if ($resource === 'departments') {
            $where .= ' AND id = ?';
            $params[] = $dept;
        }
        if (in_array($resource, ['programmes', 'staff'], true)) {
            $where .= ' AND department_id = ?';
            $params[] = $dept;
        }
    }

    private function forceDepartment(string $resource, array &$data): void
    {
        if (($_SESSION['role'] ?? '') !== 'department_manager') {
            return;
        }
        if (in_array($resource, ['programmes', 'staff'], true)) {
            $data['department_id'] = (int) $_SESSION['department_id'];
        }
    }

    private function recordInDepartment(string $table, array $row): bool
    {
        if (($_SESSION['role'] ?? '') !== 'department_manager') {
            return true;
        }
        $dept = (int) ($_SESSION['department_id'] ?? 0);
        if ($table === 'departments') {
            return (int) $row['id'] === $dept;
        }
        if (in_array($table, ['programmes', 'staff'], true)) {
            return (int) ($row['department_id'] ?? 0) === $dept;
        }
        return true;
    }

    private function syncUserRole(string $resource, int $id, array $data): void
    {
        if ($resource !== 'users' || !isset($data['role_id'])) {
            return;
        }
        Model::query('DELETE FROM user_roles WHERE user_id = ?', [$id]);
        Model::insert('user_roles', [
            'user_id' => $id,
            'role_id' => (int) $data['role_id'],
        ]);
    }

    private function storeGalleryBatch(): void
    {
        $galleryId = (int) ($_POST['gallery_id'] ?? 0);
        $title = trim((string) ($_POST['title'] ?? ''));
        $status = ($_POST['status'] ?? '') === 'draft' ? 'draft' : 'published';
        $names = $_FILES['image']['name'] ?? [];
        $saved = 0;
        if ($galleryId < 1 || $title === '') {
            flash('error', 'Choose an album and a caption before uploading.');
            redirect('/admin/gallery-images/create');
        }
        foreach ($names as $index => $name) {
            if ($name === '') {
                continue;
            }
            $file = [
                'name' => $name,
                'type' => $_FILES['image']['type'][$index] ?? '',
                'tmp_name' => $_FILES['image']['tmp_name'][$index] ?? '',
                'error' => $_FILES['image']['error'][$index] ?? UPLOAD_ERR_NO_FILE,
                'size' => $_FILES['image']['size'][$index] ?? 0,
            ];
            try {
                $path = upload_image($file);
            } catch (\RuntimeException $exception) {
                flash('error', $exception->getMessage());
                redirect('/admin/gallery-images/create');
            }
            Model::insert('gallery_images', [
                'gallery_id' => $galleryId,
                'title' => $title,
                'image' => $path,
                'sort_order' => (int) ($_POST['sort_order'] ?? 0),
                'status' => $status,
            ]);
            $saved++;
        }
        if ($saved === 0) {
            flash('error', 'Choose at least one image.');
            redirect('/admin/gallery-images/create');
        }
        log_activity('gallery-images.created', $saved . ' images uploaded');
        flash('success', $saved . ' images added to the album.');
        redirect('/admin/gallery-images');
    }

    private function fail(string $message): never
    {
        if (wants_json()) {
            json_response(['ok' => false, 'message' => $message], 422);
        }
        flash('error', $message);
        redirect($_SERVER['HTTP_REFERER'] ?? '/admin');
    }
}
