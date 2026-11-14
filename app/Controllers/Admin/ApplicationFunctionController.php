<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;

final class ApplicationFunctionController
{
    public function index(): void
    {
        require_permission('applications.manage');
        view('admin/applications/index', [
            'functions' => Model::fetchAll('SELECT * FROM application_functions ORDER BY sort_order ASC, id ASC'),
            'submissions' => (int) Model::fetch('SELECT COUNT(*) AS total FROM applications')['total'],
            'metaTitle' => 'Application functions',
        ], 'layouts/admin');
    }

    public function create(): void
    {
        require_permission('applications.manage');
        view('admin/applications/function-form', ['record' => null, 'metaTitle' => 'New application function'], 'layouts/admin');
    }

    public function store(): void
    {
        require_permission('applications.manage');
        verify_csrf();
        $data = $this->validated($_POST);
        if ($data['errors']) {
            $this->fail($data['values'], $data['errors'], '/admin/applications/functions/create');
        }
        Model::insert('application_functions', $data['values']);
        flash('success', 'Application function created.');
        redirect('/admin/applications');
    }

    public function edit(int $id): void
    {
        require_permission('applications.manage');
        $record = Model::fetch('SELECT * FROM application_functions WHERE id = ?', [$id]);
        if (!$record) {
            abort(404);
        }
        view('admin/applications/function-form', ['record' => $record, 'metaTitle' => 'Edit application function'], 'layouts/admin');
    }

    public function update(int $id): void
    {
        require_permission('applications.manage');
        verify_csrf();
        if (!Model::fetch('SELECT id FROM application_functions WHERE id = ?', [$id])) {
            abort(404);
        }
        $data = $this->validated($_POST, $id);
        if ($data['errors']) {
            $this->fail($data['values'], $data['errors'], '/admin/applications/functions/' . $id . '/edit');
        }
        Model::updateById('application_functions', $id, $data['values']);
        flash('success', 'Application function updated.');
        redirect('/admin/applications');
    }

    public function destroy(int $id): void
    {
        require_permission('applications.manage');
        verify_csrf();
        Model::deleteById('application_functions', $id);
        flash('success', 'Application function deleted.');
        redirect('/admin/applications');
    }

    private function validated(array $input, int $id = 0): array
    {
        $values = [
            'name' => trim((string) ($input['name'] ?? '')),
            'description' => clean_html(trim((string) ($input['description'] ?? ''))),
            'icon' => trim((string) ($input['icon'] ?? 'bi-box-arrow-up-right')),
            'application_type' => trim((string) ($input['application_type'] ?? 'External application')),
            'external_url' => trim((string) ($input['external_url'] ?? '')),
            'category' => trim((string) ($input['category'] ?? '')),
            'open_new_tab' => isset($input['open_new_tab']) ? 1 : 0,
            'status' => in_array($input['status'] ?? '', ['active', 'inactive'], true) ? $input['status'] : 'inactive',
            'publication_status' => in_array($input['publication_status'] ?? '', ['draft', 'published'], true) ? $input['publication_status'] : 'draft',
            'sort_order' => max(0, min(999999, (int) ($input['sort_order'] ?? 0))),
        ];
        $errors = [];
        if ($values['name'] === '') {
            $errors['name'] = 'Enter an application function name.';
        }
        if (!preg_match('/^bi-[a-z0-9-]+$/', $values['icon'])) {
            $errors['icon'] = 'Enter a valid Bootstrap icon class.';
        }
        if ($values['external_url'] !== '') {
            $parts = parse_url($values['external_url']);
            if (!filter_var($values['external_url'], FILTER_VALIDATE_URL) || !in_array(strtolower((string) ($parts['scheme'] ?? '')), ['http', 'https'], true) || !empty($parts['user']) || !empty($parts['pass'])) {
                $errors['external_url'] = 'Use an approved HTTP or HTTPS URL without embedded credentials.';
            }
        } elseif ($values['publication_status'] === 'published' && $values['status'] === 'active') {
            $errors['external_url'] = 'An active published function requires an external URL.';
        }
        if ($values['external_url'] !== '' && Model::fetch('SELECT id FROM application_functions WHERE external_url = ? AND id <> ?', [$values['external_url'], $id])) {
            $errors['external_url'] = 'This external URL is already assigned to another function.';
        }
        return ['values' => $values, 'errors' => $errors];
    }

    private function fail(array $values, array $errors, string $path): never
    {
        set_old($values);
        $_SESSION['_errors'] = $errors;
        flash('error', 'Please correct the highlighted fields.');
        redirect($path);
    }
}
