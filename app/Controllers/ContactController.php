<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Model;

final class ContactController
{
    public function store(): void
    {
        verify_csrf();
        if (trim((string) ($_POST['website'] ?? '')) !== '') {
            redirect('/contact');
        }

        $data = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'subject' => trim((string) ($_POST['subject'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
        ];
        $errors = [];
        if ($data['name'] === '' || strlen($data['name']) < 2) {
            $errors['name'] = 'Enter your name.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }
        if ($data['subject'] === '') {
            $errors['subject'] = 'Enter a subject.';
        }
        if (strlen($data['message']) < 10) {
            $errors['message'] = 'Enter a message of at least 10 characters.';
        }
        if ($errors) {
            set_old($data);
            $_SESSION['_errors'] = $errors;
            flash('error', 'Please correct the highlighted fields.');
            redirect('/contact');
        }

        Model::insert('contact_messages', $data + [
            'status' => 'new',
            'ip_address' => client_ip(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        log_activity('contact.created', 'Contact message from ' . $data['email']);
        flash('success', 'Thank you. Your message has been sent to SLGTI.');
        redirect('/contact');
    }
}
