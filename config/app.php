<?php

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(403);
    exit('Forbidden');
}

return [
    'name' => 'Sri Lanka German Training Institute',
    'env' => 'local',
    'debug' => true,
    'url' => '',
    'timezone' => 'Asia/Colombo',
    'session_name' => 'SLGTISESSID',
    'upload_max' => 5 * 1024 * 1024,
    'per_page' => 9,
];
