<?php

/**
 * =====================================================
 * MONEYTRACK HELPER FUNCTIONS - FORMATTING & URLS
 * =====================================================
 */

if (!function_exists('formatRupiah')) {
    /**
     * Format number as Indonesian Rupiah
     * Example: 100000000 -> Rp 100.000.000
     */
    function formatRupiah(float|int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('formatShortRupiah')) {
    /**
     * Format number into compact Rupiah representation
     * Example: 900000 -> 900k, 1500000 -> 1.5M
     */
    function formatShortRupiah(float|int $amount): string
    {
        $abs = abs($amount);
        $prefix = $amount < 0 ? '-' : '';

        if ($abs >= 1_000_000_000) {
            $formatted = round($abs / 1_000_000_000, 1);
            return $prefix . ($formatted == (int)$formatted ? (int)$formatted : $formatted) . 'B';
        }

        if ($abs >= 1_000_000) {
            $formatted = round($abs / 1_000_000, 1);
            return $prefix . ($formatted == (int)$formatted ? (int)$formatted : $formatted) . 'M';
        }

        if ($abs >= 1_000) {
            $formatted = round($abs / 1_000, 1);
            return $prefix . ($formatted == (int)$formatted ? (int)$formatted : $formatted) . 'k';
        }

        return $prefix . (string)$amount;
    }
}

if (!function_exists('formatIndonesianDate')) {
    /**
     * Format date in Indonesian representation
     * Example: 2026-09-29 -> 29 Sep 2026
     */
    function formatIndonesianDate(string $dateString): string
    {
        $timestamp = strtotime($dateString);
        if (!$timestamp) return $dateString;

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $day = date('j', $timestamp);
        $month = $months[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp);

        return "{$day} {$month} {$year}";
    }
}

if (!function_exists('base_url')) {
    /**
     * Determine application base URL dynamically
     */
    function base_url(string $path = ''): string
    {
        static $baseUrl = null;

        if ($baseUrl === null) {
            // Check if configured in .env
            $envUrl = $_ENV['APP_URL'] ?? '';

            // Detect from server script directory
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            $scriptDir = rtrim($scriptDir, '/');

            // If running via Laragon auto-virtualhost (e.g. app-money-track.test) or direct public folder
            if (!empty($envUrl) && !str_contains($envUrl, 'localhost')) {
                $baseUrl = rtrim($envUrl, '/');
            } else {
                $baseUrl = $scriptDir;
            }
        }

        $path = ltrim($path, '/');
        return empty($path) ? ($baseUrl ?: '/') : ($baseUrl . '/' . $path);
    }
}

if (!function_exists('asset')) {
    /**
     * Return asset path with versioning for cache busting
     */
    function asset(string $path): string
    {
        $cleanPath = ltrim($path, '/');
        $fullPath = __DIR__ . '/../../public/assets/' . $cleanPath;
        $version = file_exists($fullPath) ? '?v=' . filemtime($fullPath) : '';

        return base_url('assets/' . $cleanPath) . $version;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generate or return session CSRF token
     */
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Output CSRF input tag
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML output safely
     */
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
