<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class ApplicationController
{
    public function create(): void
    {
        if (settings('apply_open', '1') !== '1') {
            flash('error', 'Online applications are currently closed. Please watch News & Events for the next intake.');
        }
        $programmes = Model::fetchAll(
            "SELECT p.id, p.title, p.department_id, p.duration, p.entry_requirements,
                    d.name AS department_name, l.name AS level_name, l.code AS level_code,
                    l.qualification
             FROM programmes p
             INNER JOIN departments d ON d.id = p.department_id AND d.status = 'published'
             INNER JOIN programme_levels l ON l.id = p.level_id AND l.status = 'published'
             WHERE p.status = 'published'
             ORDER BY d.sort_order ASC, p.sort_order ASC, p.title ASC"
        );
        $requirements = Model::fetchAll(
            "SELECT programme_id, requirement, short_description, eligibility_criteria, required_documents
             FROM programme_requirements
             WHERE status = 'published' AND programme_id IS NOT NULL
             ORDER BY sort_order ASC, id ASC"
        );
        $requirementsByProgramme = [];
        foreach ($requirements as $requirement) {
            $requirementsByProgramme[(int) $requirement['programme_id']][] = $requirement;
        }
        view('applications/create', [
            'departments' => Model::fetchAll("SELECT id, name FROM departments WHERE status = 'published' ORDER BY sort_order"),
            'programmes' => $programmes,
            'requirementsByProgramme' => $requirementsByProgramme,
            'applicationFunctions' => Model::fetchAll("SELECT * FROM application_functions WHERE status = 'active' AND publication_status = 'published' AND external_url IS NOT NULL AND external_url <> '' ORDER BY sort_order ASC, id ASC"),
            'applyOpen' => settings('apply_open', '1') === '1',
            'metaTitle' => 'Apply Now | SLGTI',
            'metaDescription' => 'Submit an application for an NVQ programme at the Sri Lanka German Training Institute.',
            'metaRobots' => 'noindex, nofollow',
        ]);
    }

    public function success(): void
    {
        view('applications/success', [
            'reference' => $_SESSION['application_reference'] ?? '',
            'metaTitle' => 'Application received | SLGTI',
            'metaDescription' => 'Your SLGTI application has been received.',
            'metaRobots' => 'noindex, nofollow',
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        if (settings('apply_open', '1') !== '1') {
            flash('error', 'Online applications are currently closed.');
            redirect('/apply');
        }
        if (trim((string) ($_POST['website'] ?? '')) !== '') {
            redirect('/apply');
        }

        $fields = ['full_name', 'nic', 'email', 'phone', 'gender', 'date_of_birth', 'address', 'district', 'programme_id', 'education_level', 'results', 'message'];
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = trim((string) ($_POST[$field] ?? ''));
        }
        $errors = [];
        if (strlen($data['full_name']) < 3) {
            $errors['full_name'] = 'Enter your full name.';
        }
        if (!preg_match('/^[0-9]{9}[VvXx]$|^[0-9]{12}$/', $data['nic'])) {
            $errors['nic'] = 'Enter a valid NIC number.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }
        if (!preg_match('/^[0-9+\s()-]{9,20}$/', $data['phone'])) {
            $errors['phone'] = 'Enter a valid phone number.';
        }
        if (!in_array($data['gender'], ['Female', 'Male', 'Other'], true)) {
            $errors['gender'] = 'Select a gender.';
        }
        if ($data['date_of_birth'] === '' || !strtotime($data['date_of_birth'])) {
            $errors['date_of_birth'] = 'Enter your date of birth.';
        }
        if (strlen($data['address']) < 5) {
            $errors['address'] = 'Enter your address.';
        }
        if ($data['district'] === '') {
            $errors['district'] = 'Enter your district.';
        }
        $programme = Model::fetch("SELECT id, title FROM programmes WHERE id = ? AND status = 'published'", [(int) $data['programme_id']]);
        if (!$programme) {
            $errors['programme_id'] = 'Select a programme.';
        }
        if ($data['education_level'] === '') {
            $errors['education_level'] = 'Select your highest education.';
        }
        if (strlen($data['results']) < 2) {
            $errors['results'] = 'Summarise your O/L or A/L results.';
        }
        $document = null;
        if (!empty($_FILES['document']['name'])) {
            try {
                $document = upload_private_file($_FILES['document']);
            } catch (\RuntimeException $exception) {
                $errors['document'] = $exception->getMessage();
            }
        }

        if ($errors) {
            set_old($data);
            $_SESSION['_errors'] = $errors;
            flash('error', 'Please correct the highlighted fields.');
            redirect('/apply');
        }

        $reference = 'SLGTI-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $applicationId = Model::insert('applications', [
            'reference_no' => $reference,
            'full_name' => $data['full_name'],
            'nic' => strtoupper($data['nic']),
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'address' => $data['address'],
            'district' => $data['district'],
            'programme_id' => (int) $programme['id'],
            'education_level' => $data['education_level'],
            'results' => $data['results'],
            'message' => $data['message'],
            'status' => 'pending',
            'ip_address' => client_ip(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if ($document) {
            Model::insert('application_documents', $document + [
                'application_id' => $applicationId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        log_activity('application.created', $reference . ' for ' . $programme['title']);
        $_SESSION['application_reference'] = $reference;
        flash('success', 'Application received. Your reference number is ' . $reference . '.');
        redirect('/apply/success');
    }
}
