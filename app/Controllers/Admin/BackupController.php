<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

final class BackupController
{
    public function index(): void
    {
        require_permission('backups.manage');
        view('admin/backups/index', [
            'files' => $this->files(),
            'metaTitle' => 'Backups',
        ], 'layouts/admin');
    }

    public function create(): void
    {
        require_permission('backups.manage');
        verify_csrf();
        $binary = $this->mysqldump();
        if ($binary === null) {
            flash('error', 'mysqldump was not found in the WAMP MySQL folder.');
            redirect('/admin/backups');
        }
        $db = require CONFIG_PATH . '/database.php';
        $name = 'backup-' . date('Ymd-His') . '.sql';
        $destination = STORAGE_PATH . '/backups/' . $name;
        $command = escapeshellarg($binary)
            . ' --host=' . escapeshellarg($db['host'])
            . ' --port=' . (int) $db['port']
            . ' --user=' . escapeshellarg($db['username'])
            . ' --password=' . escapeshellarg($db['password'])
            . ' --single-transaction --skip-lock-tables '
            . escapeshellarg($db['database']);
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', $destination, 'w'],
            2 => ['pipe', 'w'],
        ];
        $process = proc_open($command, $descriptors, $pipes, ROOT_PATH);
        if (!is_resource($process)) {
            flash('error', 'The backup process could not start.');
            redirect('/admin/backups');
        }
        fclose($pipes[0]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $code = proc_close($process);
        if ($code !== 0 || !is_file($destination) || filesize($destination) < 100) {
            if (is_file($destination)) {
                unlink($destination);
            }
            flash('error', 'Backup failed. ' . trim((string) $error));
            redirect('/admin/backups');
        }
        log_activity('backup.created', $name);
        flash('success', 'Backup created.');
        redirect('/admin/backups');
    }

    public function download(string $file): void
    {
        require_permission('backups.manage');
        $path = $this->safe($file);
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function destroy(string $file): void
    {
        require_permission('backups.manage');
        verify_csrf();
        $path = $this->safe($file);
        unlink($path);
        log_activity('backup.deleted', basename($path));
        flash('success', 'Backup deleted.');
        redirect('/admin/backups');
    }

    private function files(): array
    {
        $paths = glob(STORAGE_PATH . '/backups/backup-*.sql') ?: [];
        rsort($paths);
        return array_map(static function (string $path): array {
            return [
                'name' => basename($path),
                'size' => filesize($path) ?: 0,
                'time' => filemtime($path) ?: time(),
            ];
        }, $paths);
    }

    private function safe(string $file): string
    {
        $file = basename($file);
        if (!preg_match('/^backup-\d{8}-\d{6}\.sql$/', $file)) {
            abort(404);
        }
        $path = STORAGE_PATH . '/backups/' . $file;
        if (!is_file($path)) {
            abort(404);
        }
        return $path;
    }

    private function mysqldump(): ?string
    {
        $matches = glob('C:\\wamp64\\bin\\mysql\\*\\bin\\mysqldump.exe') ?: [];
        rsort($matches);
        return $matches[0] ?? null;
    }
}
