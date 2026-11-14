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
        if ($resource === 'news') {
            $this->newsIndex($config);
            return;
        }
        if ($resource === 'events') {
            $this->eventsIndex($config);
            return;
        }
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

    private function newsIndex(array $config): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $category = max(0, (int) ($_GET['category_id'] ?? 0));
        $author = max(0, (int) ($_GET['author_id'] ?? 0));
        $featured = ($_GET['featured'] ?? '') === '1';
        $from = trim((string) ($_GET['from'] ?? ''));
        $to = trim((string) ($_GET['to'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(n.title LIKE ? OR n.excerpt LIKE ? OR n.body LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }

        if (in_array($status, $config['status_filter'], true)) {
            $where[] = 'n.status = ?';
            $params[] = $status;
        }
        if ($category > 0) {
            $where[] = 'n.category_id = ?';
            $params[] = $category;
        }
        if ($author > 0) {
            $where[] = 'n.author_id = ?';
            $params[] = $author;
        }
        if ($featured) {
            $where[] = 'n.is_featured = 1';
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
            $where[] = 'n.published_at >= ?';
            $params[] = $from . ' 00:00:00';
        } else {
            $from = '';
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $where[] = 'n.published_at <= ?';
            $params[] = $to . ' 23:59:59';
        } else {
            $to = '';
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM news n WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT n.*, c.name AS category_name, u.name AS author_name
             FROM news n
             LEFT JOIN news_categories c ON c.id = n.category_id
             LEFT JOIN users u ON u.id = n.author_id
             WHERE $clause
             ORDER BY n.published_at DESC, n.id DESC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/news/index', [
            'resource' => 'news',
            'config' => $config,
            'rows' => $rows,
            'pager' => $pager,
            'q' => $q,
            'status' => $status,
            'category' => $category,
            'author' => $author,
            'featured' => $featured,
            'from' => $from,
            'to' => $to,
            'categories' => Model::fetchAll('SELECT id, name FROM news_categories ORDER BY name ASC'),
            'authors' => Model::fetchAll('SELECT id, name FROM users WHERE status = ? ORDER BY name ASC', ['active']),
            'metaTitle' => 'News',
        ], 'layouts/admin');
    }

    private function eventsIndex(array $config): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $category = max(0, (int) ($_GET['category_id'] ?? 0));
        $dateState = trim((string) ($_GET['date_state'] ?? ''));
        $from = trim((string) ($_GET['from'] ?? ''));
        $to = trim((string) ($_GET['to'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(e.title LIKE ? OR e.excerpt LIKE ? OR e.body LIKE ? OR e.venue LIKE ?)';
            array_push($params, '%' . $q . '%', '%' . $q . '%', '%' . $q . '%', '%' . $q . '%');
        }
        if (in_array($status, $config['status_filter'], true)) {
            $where[] = 'e.status = ?';
            $params[] = $status;
        }
        if ($category > 0) {
            $where[] = 'e.category_id = ?';
            $params[] = $category;
        }
        if ($dateState === 'upcoming') {
            $where[] = 'e.starts_at > NOW()';
        } elseif ($dateState === 'ongoing') {
            $where[] = 'e.starts_at <= NOW() AND (e.ends_at IS NULL OR e.ends_at >= NOW())';
        } elseif ($dateState === 'completed') {
            $where[] = 'COALESCE(e.ends_at, e.starts_at) < NOW()';
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
            $where[] = 'e.starts_at >= ?';
            $params[] = $from . ' 00:00:00';
        } else {
            $from = '';
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $where[] = 'e.starts_at <= ?';
            $params[] = $to . ' 23:59:59';
        } else {
            $to = '';
        }
        $clause = implode(' AND ', $where);
        $total = (int) Model::fetch("SELECT COUNT(*) AS total FROM events e WHERE $clause", $params)['total'];
        $pager = paginate($total, $page, 12);
        $rows = Model::fetchAll(
            "SELECT e.*, c.name AS category_name FROM events e
             LEFT JOIN event_categories c ON c.id = e.category_id
             WHERE $clause ORDER BY e.starts_at DESC, e.id DESC
             LIMIT {$pager['per_page']} OFFSET {$pager['offset']}",
            $params
        );
        view('admin/events/index', [
            'resource' => 'events', 'config' => $config, 'rows' => $rows, 'pager' => $pager,
            'q' => $q, 'status' => $status, 'category' => $category, 'dateState' => $dateState,
            'from' => $from, 'to' => $to,
            'categories' => Model::fetchAll('SELECT id, name FROM event_categories ORDER BY name ASC'),
            'metaTitle' => 'Events',
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
            if ($resource === 'programmes') {
                $oldSlugs = array_unique(array_filter([(string) ($record['slug'] ?? ''), (string) ($record['seo_slug'] ?? '')]));
                $newSlugs = [(string) ($data['slug'] ?? ''), (string) ($data['seo_slug'] ?? '')];
                foreach ($oldSlugs as $oldSlug) {
                    if (!in_array($oldSlug, $newSlugs, true) && !Model::fetch('SELECT id FROM programme_slug_redirects WHERE old_slug = ?', [$oldSlug])) {
                        Model::insert('programme_slug_redirects', ['programme_id' => (int) $record['id'], 'old_slug' => $oldSlug]);
                    }
                }
            }
            if ($resource === 'departments') {
                foreach (array_unique(array_filter([(string) ($record['slug'] ?? ''), (string) ($record['seo_slug'] ?? '')])) as $oldSlug) {
                    $newSlugs = [(string) ($data['slug'] ?? ''), (string) ($data['seo_slug'] ?? '')];
                    if (!in_array($oldSlug, $newSlugs, true)) {
                        $redirect = Model::fetch('SELECT id FROM department_slug_redirects WHERE old_slug = ?', [$oldSlug]);
                        if (!$redirect) {
                            Model::insert('department_slug_redirects', ['department_id' => (int) $record['id'], 'old_slug' => $oldSlug]);
                        }
                        if ($resource === 'requirements') {
                            $title = trim((string) ($data['requirement'] ?? ''));
                            $data['slug'] = trim((string) ($data['slug'] ?? '')) ?: slugify($title);
                            $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: ($title !== '' ? $title . ' | SLGTI' : '');
                            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt((string) ($data['short_description'] ?? $data['description'] ?? $title), 160);
                            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
                            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
                            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('requirements/' . $data['slug']);
                            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'index';
                            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
                            $ignore = (int) ($record['id'] ?? 0);
                            if (Model::fetch('SELECT id FROM programme_requirements WHERE slug = ? AND id != ?', [$data['slug'], $ignore])) $errors['slug'] = 'That requirement slug is already in use.';
                            if ($data['canonical_url'] !== '' && !filter_var($data['canonical_url'], FILTER_VALIDATE_URL)) $errors['canonical_url'] = 'Enter a valid canonical URL.';
                            if (($data['structured_data'] ?? '') !== '') {
                                json_decode((string) $data['structured_data'], true);
                                if (json_last_error() !== JSON_ERROR_NONE) $errors['structured_data'] = 'Structured data must be valid JSON.';
                            }
                            if ($resource === 'programme-levels') {
                                $name = trim((string) ($data['name'] ?? ''));
                                $data['seo_slug'] = trim((string) ($data['seo_slug'] ?? '')) ?: slugify($name);
                                $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: ($name !== '' ? $name . ' | SLGTI' : '');
                                $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt((string) ($data['short_description'] ?? $data['description'] ?? $name), 160);
                                $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
                                $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
                                $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('programme-levels/' . $data['seo_slug']);
                                $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'index';
                                $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
                                $ignore = (int) ($record['id'] ?? 0);
                                if (Model::fetch('SELECT id FROM programme_levels WHERE seo_slug = ? AND id != ?', [$data['seo_slug'], $ignore])) $errors['seo_slug'] = 'That SEO slug is already in use.';
                                foreach (['seo_title' => 'SEO title', 'meta_description' => 'Meta description'] as $fieldName => $label) {
                                    if (($data[$fieldName] ?? '') !== '' && Model::fetch('SELECT id FROM programme_levels WHERE ' . $fieldName . ' = ? AND id != ?', [$data[$fieldName], $ignore])) $errors[$fieldName] = 'This ' . strtolower($label) . ' is already used by another level.';
                                }
                                if (($data['canonical_url'] ?? '') !== '' && !filter_var($data['canonical_url'], FILTER_VALIDATE_URL)) $errors['canonical_url'] = 'Enter a valid canonical URL.';
                                if (($data['structured_data'] ?? '') !== '') {
                                    json_decode((string) $data['structured_data'], true);
                                    if (json_last_error() !== JSON_ERROR_NONE) $errors['structured_data'] = 'Structured data must be valid JSON.';
                                }
                            }
                            if (!empty($data['programme_id']) && !Model::fetch('SELECT id FROM programmes WHERE id = ?', [(int) $data['programme_id']])) $errors['programme_id'] = 'Choose a valid programme.';
                            if (!empty($data['department_id']) && !Model::fetch('SELECT id FROM departments WHERE id = ?', [(int) $data['department_id']])) $errors['department_id'] = 'Choose a valid department.';
                            foreach (['seo_title' => 'SEO title', 'meta_description' => 'Meta description'] as $fieldName => $label) {
                                if (($data[$fieldName] ?? '') !== '' && Model::fetch('SELECT id FROM programme_requirements WHERE ' . $fieldName . ' = ? AND id != ?', [$data[$fieldName], $ignore])) {
                                    $errors[$fieldName] = 'This ' . strtolower($label) . ' is already used by another requirement.';
                                }
                            }
                        }
                    }
                }
            }
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
            if ($resource === 'departments' && (int) Model::fetch('SELECT COUNT(*) AS total FROM programmes WHERE department_id = ?', [(int) $record['id']])['total'] > 0) {
                $this->fail('This department has programmes. Archive it instead of deleting it.');
            }
            if ($resource === 'programme-levels' && (int) Model::fetch('SELECT COUNT(*) AS total FROM programmes WHERE level_id = ?', [(int) $record['id']])['total'] > 0) {
                $this->fail('This level has programmes. Archive it instead of deleting it.');
            }
            if ($resource === 'galleries' && (int) Model::fetch('SELECT COUNT(*) AS total FROM gallery_images WHERE gallery_id = ?', [(int) $record['id']])['total'] > 0) {
                $this->fail('This album contains images. Remove its images before deleting the album.');
            }
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
            if ($type === 'document') {
                if (!empty($_FILES[$name]['name'])) {
                    try {
                        $data[$name] = upload_public_document($_FILES[$name]);
                    } catch (RuntimeException $exception) {
                        $errors[$name] = $exception->getMessage();
                    }
                } elseif ($record !== null && isset($_POST['remove_' . $name])) {
                    $data[$name] = null;
                }
                continue;
            }
            if ($type === 'file' || $type === 'slider_image') {
                if (!empty($_FILES[$name]['name'])) {
                    try {
                        $data[$name] = $type === 'slider_image'
                            ? upload_slider_image($_FILES[$name])
                            : upload_image($_FILES[$name]);
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
            if ($type === 'url' && $value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
                $errors[$name] = 'Enter a valid URL.';
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

        if ($resource === 'quick-links') {
            $url = trim((string) ($data['url'] ?? ''));
            $linkType = (string) ($data['link_type'] ?? '');
            if ($linkType === 'internal') {
                if ($url === '' || !str_starts_with($url, '/') || str_starts_with($url, '//') || preg_match('#^(javascript|data|vbscript):#i', $url)) {
                    $errors['url'] = 'Internal links must use a safe site-relative path.';
                }
            } elseif ($linkType === 'external') {
                if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $url)) {
                    $errors['url'] = 'External links must use a valid http or https URL.';
                }
            } else {
                $errors['link_type'] = 'Choose a valid link type.';
            }
            if ($url !== '' && Model::fetch('SELECT id FROM quick_links WHERE url = ? AND id != ?', [$url, (int) ($record['id'] ?? 0)])) {
                $errors['url'] = 'That URL is already used by another quick link.';
            }
            if (!preg_match('/^#[0-9A-Fa-f]{3,8}$/', (string) ($data['color'] ?? ''))) {
                $errors['color'] = 'Use a valid hexadecimal colour such as #0B5CAB.';
            }
            if (!preg_match('/^bi-[a-z0-9-]+$/', (string) ($data['icon'] ?? ''))) {
                $errors['icon'] = 'Use a valid Bootstrap icon class such as bi-link-45deg.';
            }
            if (!in_array($data['target'] ?? '', ['_self', '_blank'], true)) {
                $errors['target'] = 'Choose a valid link target.';
            }
            if ($linkType === 'external') {
                $data['target'] = '_blank';
            }
        }

        if ($resource === 'departments') {
            $name = trim((string) ($data['name'] ?? ''));
            $description = (string) ($data['description'] ?? '');
            $summary = trim((string) ($data['summary'] ?? ''));
            $data['seo_slug'] = trim((string) ($data['seo_slug'] ?? '')) ?: slugify($name);
            $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: ($name !== '' ? $name . ' | SLGTI' : '');
            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt($summary !== '' ? $summary : $description, 160);
            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('departments/' . $data['seo_slug']);
            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'index';
            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
            if ($data['canonical_url'] !== '' && !preg_match('#^https?://#i', (string) $data['canonical_url'])) {
                $errors['canonical_url'] = 'Canonical URL must use http or https.';
            }
            if ($resource === 'requirements' && !empty($record['slug']) && $record['slug'] !== ($data['slug'] ?? '') && !Model::fetch('SELECT id FROM requirement_slug_redirects WHERE old_slug = ?', [$record['slug']])) {
                Model::insert('requirement_slug_redirects', ['requirement_id' => (int) $record['id'], 'old_slug' => $record['slug']]);
            }
            if ($resource === 'programme-levels' && !empty($record['slug']) && $record['slug'] !== ($data['slug'] ?? '') && !Model::fetch('SELECT id FROM programme_level_slug_redirects WHERE old_slug = ?', [$record['slug']])) {
                Model::insert('programme_level_slug_redirects', ['level_id' => (int) $record['id'], 'old_slug' => $record['slug']]);
            }
            if (($data['structured_data'] ?? '') !== '') {
                json_decode((string) $data['structured_data'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $errors['structured_data'] = 'Structured data must be valid JSON.';
                }
            }
            $ignore = (int) ($record['id'] ?? 0);
            $duplicateSeoSlug = Model::fetch('SELECT id FROM departments WHERE seo_slug = ? AND id != ?', [$data['seo_slug'], $ignore]);
            if ($duplicateSeoSlug) {
                $errors['seo_slug'] = 'That SEO slug is already in use.';
            }
            foreach (['seo_title' => 'SEO title', 'meta_description' => 'Meta description'] as $fieldName => $label) {
                if ($data[$fieldName] !== '') {
                    $duplicate = Model::fetch('SELECT id FROM departments WHERE ' . $fieldName . ' = ? AND id != ?', [$data[$fieldName], $ignore]);
                    if ($duplicate) {
                        $errors[$fieldName] = 'This ' . strtolower($label) . ' is already used by another department.';
                    }
                }
            }
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

        if ($resource === 'menu-items') {
            $menuId = (int) ($data['menu_id'] ?? 0);
            $parentId = (int) ($data['parent_id'] ?? 0);
            $itemId = (int) ($record['id'] ?? 0);
            if ($menuId < 1 || !Model::fetch('SELECT id FROM menus WHERE id = ?', [$menuId])) {
                $errors['menu_id'] = 'Choose a valid menu.';
            }
            if ($parentId > 0) {
                $parent = Model::fetch('SELECT id, menu_id FROM menu_items WHERE id = ?', [$parentId]);
                if (!$parent || (int) $parent['menu_id'] !== $menuId || $parentId === $itemId) {
                    $errors['parent_id'] = 'Choose a parent item from the same menu.';
                } else {
                    $parents = Model::fetchAll('SELECT id, parent_id FROM menu_items WHERE menu_id = ?', [$menuId]);
                    $parentMap = [];
                    foreach ($parents as $parentRow) {
                        $parentMap[(int) $parentRow['id']] = (int) ($parentRow['parent_id'] ?? 0);
                    }
                    $visited = [];
                    $cursor = $parentId;
                    while ($cursor > 0 && isset($parentMap[$cursor]) && !isset($visited[$cursor])) {
                        if ($parentMap[$cursor] === $itemId) {
                            $errors['parent_id'] = 'An item cannot be placed inside one of its children.';
                            break;
                        }
                        $visited[$cursor] = true;
                        $cursor = $parentMap[$cursor];
                    }
                }
            }
        }
        if ($resource === 'news' && $record === null && empty($data['author_id'])) {
            $data['author_id'] = (int) ($_SESSION['user_id'] ?? 0) ?: null;
        }
        if ($resource === 'events') {
            if (!empty($data['registration_url']) && !filter_var($data['registration_url'], FILTER_VALIDATE_URL)) {
                $errors['registration_url'] = 'Enter a valid registration URL.';
            }
            if (!empty($data['ends_at']) && !empty($data['starts_at']) && strtotime((string) $data['ends_at']) < strtotime((string) $data['starts_at'])) {
                $errors['ends_at'] = 'The end date must be after the start date.';
            }
        }
        if ($resource === 'sliders') {
            foreach (['button_url', 'secondary_url'] as $urlField) {
                $url = trim((string) ($data[$urlField] ?? ''));
                if ($url !== '' && (preg_match('#^(javascript|data|vbscript):#i', $url) || (!str_starts_with($url, '/') && !filter_var($url, FILTER_VALIDATE_URL)))) {
                    $errors[$urlField] = 'Enter a valid site-relative or HTTP/HTTPS URL.';
                }
            }
            if (!preg_match('/^#[0-9A-Fa-f]{3,8}$/', (string) ($data['overlay_color'] ?? ''))) {
                $errors['overlay_color'] = 'Use a valid hexadecimal colour.';
            }
            $opacity = (int) ($data['overlay_opacity'] ?? 0);
            if ($opacity < 0 || $opacity > 100) {
                $errors['overlay_opacity'] = 'Overlay opacity must be between 0 and 100.';
            }
            if (!in_array($data['text_alignment'] ?? '', ['left', 'center', 'right'], true)) {
                $errors['text_alignment'] = 'Choose a valid text alignment.';
            }
            if (!in_array($data['text_position'] ?? '', ['left', 'center', 'right'], true)) {
                $errors['text_position'] = 'Choose a valid text position.';
            }
            if (!empty($data['starts_at']) && !empty($data['ends_at']) && strtotime((string) $data['ends_at']) < strtotime((string) $data['starts_at'])) {
                $errors['ends_at'] = 'The end date must be after the start date.';
            }
            if (empty($data['image_alt']) && !empty($data['title'])) {
                $data['image_alt'] = trim((string) $data['title']);
            }
        }
        if ($resource === 'videos') {
            $provider = (string) ($data['provider'] ?? '');
            $videoUrl = trim((string) ($data['video_url'] ?? ''));
            $parsed = $this->parseVideoUrl($videoUrl, $provider);
            if ($parsed === null) {
                $errors['video_url'] = 'Use a valid YouTube, Vimeo, or self-hosted video URL for the selected provider.';
            } else {
                $data['video_id'] = $parsed['id'];
                $data['embed_url'] = $parsed['embed'];
            }
            $title = trim((string) ($data['title'] ?? ''));
            $data['slug'] = trim((string) ($data['slug'] ?? '')) ?: slugify($title);
            $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: ($title !== '' ? $title . ' | SLGTI' : '');
            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt((string) ($data['description'] ?? $title), 160);
            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('videos/' . $data['slug']);
            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'index';
            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
            if ((int) ($data['sort_order'] ?? 0) < 0 || (int) ($data['sort_order'] ?? 0) > 999999) {
                $errors['sort_order'] = 'Order number must be between 0 and 999999.';
            }
            if (!empty($data['canonical_url']) && !filter_var($data['canonical_url'], FILTER_VALIDATE_URL)) {
                $errors['canonical_url'] = 'Enter a valid canonical URL.';
            }
        }
        if ($resource === 'testimonials') {
            $name = trim((string) ($data['name'] ?? ''));
            $data['slug'] = trim((string) ($data['slug'] ?? '')) ?: slugify($name);
            $data['image_alt'] = trim((string) ($data['image_alt'] ?? '')) ?: $name;
            $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: ($name !== '' ? $name . ' | SLGTI' : '');
            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt((string) ($data['quote'] ?? ''), 160);
            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('testimonials');
            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'noindex';
            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
            $rating = (int) ($data['rating'] ?? 0);
            if ($rating < 0 || $rating > 5) {
                $errors['rating'] = 'Rating must be between 0 and 5.';
            }
            $year = (int) ($data['graduation_year'] ?? 0);
            if ($year !== 0 && ($year < 1950 || $year > ((int) date('Y') + 10))) {
                $errors['graduation_year'] = 'Enter a valid graduation year.';
            }
            if ((int) ($data['sort_order'] ?? 0) < 0 || (int) ($data['sort_order'] ?? 0) > 999999) {
                $errors['sort_order'] = 'Order number must be between 0 and 999999.';
            }
        }
        if ($resource === 'staff') {
            $name = trim((string) ($data['name'] ?? ''));
            $data['slug'] = trim((string) ($data['slug'] ?? '')) ?: slugify($name);
            $data['image_alt'] = trim((string) ($data['image_alt'] ?? '')) ?: $name;
            $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: ($name !== '' ? $name . ' | SLGTI' : '');
            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt(strip_tags((string) ($data['bio'] ?? $data['position'] ?? $name)), 160);
            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('staff/' . $data['slug']);
            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'noindex';
            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
            $data['display_location'] = in_array($data['display_location'] ?? '', ['directory', 'department', 'both'], true) ? $data['display_location'] : 'directory';
            if ($data['canonical_url'] !== '' && !filter_var($data['canonical_url'], FILTER_VALIDATE_URL)) {
                $errors['canonical_url'] = 'Enter a valid canonical URL.';
            }
            if ((int) ($data['sort_order'] ?? 0) < 0 || (int) ($data['sort_order'] ?? 0) > 999999) {
                $errors['sort_order'] = 'Order number must be between 0 and 999999.';
            }
            if (!empty($data['profile_url']) && !filter_var($data['profile_url'], FILTER_VALIDATE_URL)) {
                $errors['profile_url'] = 'Enter a valid profile URL.';
            }
        }
        if ($resource === 'facilities') {
            $title = trim((string) ($data['title'] ?? ''));
            $data['slug'] = trim((string) ($data['slug'] ?? '')) ?: slugify($title);
            $data['image_alt'] = trim((string) ($data['image_alt'] ?? '')) ?: $title;
            $data['description'] = trim((string) ($data['description'] ?? '')) ?: (string) ($data['summary'] ?? '');
            $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: ($title !== '' ? $title . ' | SLGTI' : '');
            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt((string) ($data['summary'] ?? $data['description'] ?? $title), 160);
            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('facilities/' . $data['slug']);
            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'index';
            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
            $data['display_location'] = in_array($data['display_location'] ?? '', ['facilities', 'homepage', 'both'], true) ? $data['display_location'] : 'facilities';
            if ($data['canonical_url'] !== '' && !filter_var($data['canonical_url'], FILTER_VALIDATE_URL)) {
                $errors['canonical_url'] = 'Enter a valid canonical URL.';
            }
            if ((int) ($data['sort_order'] ?? 0) < 0 || (int) ($data['sort_order'] ?? 0) > 999999) {
                $errors['sort_order'] = 'Order number must be between 0 and 999999.';
            }
        }
        if ($resource === 'pages') {
            $title = trim((string) ($data['title'] ?? ''));
            $data['slug'] = trim((string) ($data['slug'] ?? '')) ?: slugify($title);
            $data['image_alt'] = trim((string) ($data['image_alt'] ?? '')) ?: $title;
            $data['meta_title'] = trim((string) ($data['meta_title'] ?? '')) ?: ($title !== '' ? $title . ' | SLGTI' : '');
            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt((string) ($data['excerpt'] ?? $data['body'] ?? $title), 160);
            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('page/' . $data['slug']);
            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['meta_title'];
            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'index';
            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
            $parentId = (int) ($data['parent_id'] ?? 0);
            $pageId = (int) ($record['id'] ?? 0);
            if ($parentId === $pageId) {
                $errors['parent_id'] = 'A page cannot be its own parent.';
            } elseif ($parentId > 0) {
                $parent = Model::fetch('SELECT id FROM pages WHERE id = ?', [$parentId]);
                if (!$parent) {
                    $errors['parent_id'] = 'Choose a valid parent page.';
                } else {
                    $parents = Model::fetchAll('SELECT id, parent_id FROM pages');
                    $map = [];
                    foreach ($parents as $parentRow) {
                        $map[(int) $parentRow['id']] = (int) ($parentRow['parent_id'] ?? 0);
                    }
                    $cursor = $parentId;
                    $visited = [];
                    while ($cursor > 0 && !isset($visited[$cursor])) {
                        if ($cursor === $pageId) {
                            $errors['parent_id'] = 'A page cannot be placed inside one of its descendants.';
                            break;
                        }
                        $visited[$cursor] = true;
                        $cursor = $map[$cursor] ?? 0;
                    }
                }
            }
            if ($data['canonical_url'] !== '' && !filter_var($data['canonical_url'], FILTER_VALIDATE_URL)) {
                $errors['canonical_url'] = 'Enter a valid canonical URL.';
            }
            if (($data['structured_data'] ?? '') !== '') {
                json_decode((string) $data['structured_data'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $errors['structured_data'] = 'Structured data must be valid JSON.';
                }
            }
            if ((int) ($data['sort_order'] ?? 0) < 0 || (int) ($data['sort_order'] ?? 0) > 999999) {
                $errors['sort_order'] = 'Order number must be between 0 and 999999.';
            }
        }
        if ($resource === 'faqs') {
            $question = trim((string) ($data['question'] ?? ''));
            $data['category'] = trim((string) ($data['category'] ?? '')) ?: 'General';
            $data['slug'] = trim((string) ($data['slug'] ?? '')) ?: slugify($question);
            $faqId = (int) ($record['id'] ?? 0);
            $duplicateParams = [$question];
            $duplicateSql = 'SELECT id FROM faqs WHERE LOWER(question) = LOWER(?)';
            if ($faqId > 0) {
                $duplicateSql .= ' AND id <> ?';
                $duplicateParams[] = $faqId;
            }
            if (Model::fetch($duplicateSql, $duplicateParams)) {
                $errors['question'] = 'An FAQ with this question already exists.';
            }
            if ((int) ($data['sort_order'] ?? 0) < 0 || (int) ($data['sort_order'] ?? 0) > 999999) {
                $errors['sort_order'] = 'Order number must be between 0 and 999999.';
            }
        }
        if ($resource === 'galleries') {
            $data['display_location'] = in_array($data['display_location'] ?? '', ['gallery', 'homepage', 'gallery_homepage'], true)
                ? $data['display_location']
                : 'gallery';
            $data['seo_title'] = trim((string) ($data['seo_title'] ?? '')) ?: trim((string) ($data['title'] ?? '')) . ' | SLGTI';
            $data['meta_description'] = trim((string) ($data['meta_description'] ?? '')) ?: excerpt((string) ($data['description'] ?? $data['title'] ?? ''), 160);
            $data['og_title'] = trim((string) ($data['og_title'] ?? '')) ?: $data['seo_title'];
            $data['og_description'] = trim((string) ($data['og_description'] ?? '')) ?: $data['meta_description'];
            $data['canonical_url'] = trim((string) ($data['canonical_url'] ?? '')) ?: absolute_url('gallery/' . ($data['slug'] ?? ''));
            $data['robots_index'] = in_array($data['robots_index'] ?? '', ['index', 'noindex'], true) ? $data['robots_index'] : 'index';
            $data['robots_follow'] = in_array($data['robots_follow'] ?? '', ['follow', 'nofollow'], true) ? $data['robots_follow'] : 'follow';
            if ($data['canonical_url'] !== '' && !filter_var($data['canonical_url'], FILTER_VALIDATE_URL)) {
                $errors['canonical_url'] = 'Enter a valid canonical URL.';
            }
            $ignore = (int) ($record['id'] ?? 0);
            if (Model::fetch('SELECT id FROM galleries WHERE slug = ? AND id != ?', [$data['slug'], $ignore])) {
                $errors['slug'] = 'That gallery slug is already in use.';
            }
            if (!empty($record['slug']) && $record['slug'] !== ($data['slug'] ?? '') && !Model::fetch('SELECT id FROM gallery_slug_redirects WHERE old_slug = ?', [$record['slug']])) {
                Model::insert('gallery_slug_redirects', ['gallery_id' => $ignore, 'old_slug' => $record['slug']]);
            }
            if (!empty($data['event_id']) && !Model::fetch('SELECT id FROM events WHERE id = ?', [(int) $data['event_id']])) {
                $errors['event_id'] = 'Choose a valid event.';
            }
            if ((int) ($data['sort_order'] ?? 0) < 0 || (int) ($data['sort_order'] ?? 0) > 999999) {
                $errors['sort_order'] = 'Order number must be between 0 and 999999.';
            }
            if (!empty($data['gallery_date']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $data['gallery_date'])) {
                $errors['gallery_date'] = 'Use a valid date in YYYY-MM-DD format.';
            }
        }
        if ($resource === 'gallery-images' && trim((string) ($data['alt_text'] ?? '')) === '') {
            $data['alt_text'] = trim((string) ($data['title'] ?? ''));
        }

        if ($record === null && in_array('created_at', $this->columns($config['table']), true) && !isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (in_array('updated_at', $this->columns($config['table']), true)) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return [$data, $errors];
    }

    private function parseVideoUrl(string $url, string $provider): ?array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $url)) {
            return null;
        }
        if ($provider === 'youtube') {
            $id = youtube_id($url);
            return ['id' => $id, 'embed' => $id ? 'https://www.youtube-nocookie.com/embed/' . $id : null];
        }
        if ($provider === 'vimeo' && preg_match('~vimeo\.com/(?:video/)?(\d+)~i', $url, $matches)) {
            return ['id' => $matches[1], 'embed' => 'https://player.vimeo.com/video/' . $matches[1]];
        }
        if ($provider === 'self_hosted') {
            return ['id' => null, 'embed' => null];
        }
        return null;
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
                'alt_text' => trim((string) ($_POST['alt_text'] ?? $title)) ?: $title,
                'sort_order' => (int) ($_POST['sort_order'] ?? 0),
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
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
