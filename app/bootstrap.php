<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(
    dirname(__DIR__)
);

$dotenv->safeLoad();

// Load Helpers
require_once __DIR__ . '/helpers/format.php';

// Session initialization
if (session_status() === PHP_SESSION_NONE) {
    $sessionName = $_ENV['SESSION_NAME'] ?? 'moneytrack_session';
    session_name($sessionName);
    session_start();
}