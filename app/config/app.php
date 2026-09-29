<?php

/**
 * =====================================================
 * APP CONFIGURATION
 * =====================================================
 */

return [

    'name' => $_ENV['APP_NAME'] ?? 'MoneyTrack',

    'env' => $_ENV['APP_ENV'] ?? 'local',

    'debug' => filter_var(
        $_ENV['APP_DEBUG'] ?? false,
        FILTER_VALIDATE_BOOLEAN
    ),

    'url' => $_ENV['APP_URL'] ?? 'http://localhost/moneytrack',

    'session_name' => $_ENV['SESSION_NAME'] ?? 'moneytrack_session',

];