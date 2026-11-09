<?php

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(403);
    exit('Forbidden');
}

return [
    'host' => '127.0.0.1',
    'port' => 3306,
    'database' => 'webslgti',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8mb4',
];
