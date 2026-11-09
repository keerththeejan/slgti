<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $config = require CONFIG_PATH . '/database.php';
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            (int) $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            self::fail($exception);
        }

        return self::$pdo;
    }

    private static function fail(PDOException $exception): never
    {
        $debug = (bool) config('debug');
        http_response_code(500);
        $message = $debug ? $exception->getMessage() : 'The website database is unavailable.';
        echo '<!doctype html><meta charset="utf-8"><title>Database setup</title>';
        echo '<body style="font-family:Segoe UI,sans-serif;background:#F4F8FC;color:#172B4D;padding:48px">';
        echo '<h1>SLGTI database connection</h1>';
        echo '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<p>Create the database <strong>webslgti</strong>, then import <code>database/schema.sql</code> and <code>database/seed.sql</code>.</p>';
        echo '</body>';
        exit;
    }
}
