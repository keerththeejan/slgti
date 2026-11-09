<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Model;
use App\Services\PermissionService;

final class AuthController
{
    public function show(): void
    {
        if (!empty($_SESSION['user_id'])) {
            redirect('/admin');
        }
        view('admin/login', ['metaTitle' => 'Admin sign in'], 'layouts/auth');
    }

    public function login(): void
    {
        verify_csrf();
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $ip = client_ip();

        $attempts = (int) Model::fetch(
            'SELECT COUNT(*) AS total FROM login_attempts WHERE ip_address = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)',
            [$ip]
        )['total'];
        if ($attempts >= 8) {
            flash('error', 'Too many sign-in attempts. Please wait 15 minutes and try again.');
            redirect('/admin/login');
        }

        $user = Model::fetch(
            'SELECT u.*, r.slug AS role_slug FROM users u INNER JOIN roles r ON r.id = u.role_id WHERE u.email = ? LIMIT 1',
            [$email]
        );
        if (!$user || $user['status'] !== 'active' || !password_verify($password, $user['password'])) {
            Model::insert('login_attempts', [
                'ip_address' => $ip,
                'email' => mb_substr($email, 0, 180),
                'attempted_at' => date('Y-m-d H:i:s'),
            ]);
            flash('error', 'Those sign-in details are not correct.');
            redirect('/admin/login');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role_slug'];
        $_SESSION['department_id'] = (int) ($user['department_id'] ?? 0);
        $_SESSION['permissions'] = PermissionService::forUser((int) $user['id'], (int) $user['role_id']);
        Model::query('UPDATE users SET last_login_at = NOW() WHERE id = ?', [(int) $user['id']]);
        Model::query('DELETE FROM login_attempts WHERE ip_address = ?', [$ip]);
        log_activity('auth.login', 'Signed in');
        redirect('/admin');
    }

    public function logout(): void
    {
        verify_csrf();
        log_activity('auth.logout', 'Signed out');
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
        }
        session_destroy();
        redirect('/admin/login');
    }
}
