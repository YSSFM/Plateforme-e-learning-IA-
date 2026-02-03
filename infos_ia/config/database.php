<?php
// config/database.php

declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'infos_ia');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Log interne (plus tard)
    http_response_code(500);
    exit('Erreur interne. Veuillez réessayer plus tard.');
}
