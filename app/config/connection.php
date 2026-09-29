<?php

/**
 * =====================================================
 * DATABASE CONNECTION
 * =====================================================
 */

$config = require __DIR__ . '/database.php';

$host = $config['host'];
$port = $config['port'];
$database = $config['database'];
$username = $config['username'];
$password = $config['password'];

$dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

try {

    $pdo = new PDO(
        $dsn,
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            PDO::ATTR_DEFAULT_FETCH_MODE =>
                PDO::FETCH_ASSOC,

            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

} catch (PDOException $e) {

    die(
        'Database connection failed: ' .
        $e->getMessage()
    );

}

return $pdo;