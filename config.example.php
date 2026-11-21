<?php
declare(strict_types=1);

// انسخ هذا الملف إلى config.php وعدّل القيم. لا ترفع config.php إلى مستودع عام.
const DB_HOST = 'localhost';
const DB_NAME = 'YOUR_DATABASE';
const DB_USER = 'YOUR_DB_USER';
const DB_PASS = 'YOUR_DB_PASSWORD';
const APP_KEY = 'REPLACE_WITH_A_LONG_RANDOM_SECRET_AT_LEAST_32_CHARS';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    return $pdo;
}
