<?php

/**
 * MoneyTrack - Front Controller Entry Point
 */

require_once __DIR__ . '/../app/bootstrap.php';

// Application and database bootstrap
$app = require __DIR__ . '/../app/config/app.php';
$pdo = require __DIR__ . '/../app/config/connection.php';

// Load web routes
require_once __DIR__ . '/../routes/web.php';