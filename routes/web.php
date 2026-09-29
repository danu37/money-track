<?php

/**
 * =====================================================
 * WEB ROUTES (Section 24)
 * =====================================================
 */

require_once __DIR__ . '/../app/controller/AuthController.php';
require_once __DIR__ . '/../app/controller/DashboardController.php';
require_once __DIR__ . '/../app/controller/AccountController.php';
require_once __DIR__ . '/../app/controller/TransactionController.php';
require_once __DIR__ . '/../app/controller/BillController.php';
require_once __DIR__ . '/../app/controller/ExpenseController.php';

// Instantiate controllers
$authController = new AuthController($pdo);
$dashboardController = new DashboardController($pdo);
$accountController = new AccountController($pdo);
$transactionController = new TransactionController($pdo);
$billController = new BillController($pdo);
$expenseController = new ExpenseController($pdo);

// Extract path
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Strip script directory prefix if running in subfolder
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseDir = rtrim(dirname($scriptName), '/');

$path = $requestUri;
if (!empty($baseDir) && str_starts_with($path, $baseDir)) {
    $path = substr($path, strlen($baseDir));
}
// Remove trailing slashes and ensure leading slash
$path = '/' . trim($path, '/');

// Support ?route=... parameter fallback
if (isset($_GET['route'])) {
    $path = '/' . trim($_GET['route'], '/');
}

// Route dispatching
switch (true) {
    // Root & Dashboard
    case ($path === '/' || $path === '/dashboard'):
        if ($requestMethod === 'GET') {
            $dashboardController->index();
        }
        break;

    // UI Elements Showcase (One-by-one inspection)
    case ($path === '/ui-elements' || $path === '/showcase'):
        if ($requestMethod === 'GET') {
            $dashboardController->showcase();
        }
        break;

    // Authentication: Login
    case ($path === '/login'):
        if ($requestMethod === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;

    // Authentication: Register
    case ($path === '/register'):
        if ($requestMethod === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;

    // Authentication: Logout
    case ($path === '/logout'):
        $authController->logout();
        break;

    // Authentication: Forgot Password
    case ($path === '/forgot-password'):
        $authController->showForgotPassword();
        break;

    // Authentication: Reset Password
    case ($path === '/reset-password'):
        $authController->showResetPassword();
        break;

    // Transaksi (Transactions) - Phase 6
    case ($path === '/transactions' || $path === '/transaksi'):
        if ($requestMethod === 'POST') {
            $transactionController->store();
        } else {
            $transactionController->index();
        }
        break;

    // Transaksi Update
    case ($path === '/transactions/update'):
        if ($requestMethod === 'POST') {
            $transactionController->update();
        } else {
            header('Location: ' . base_url('transactions'));
            exit;
        }
        break;

    // Transaksi Delete
    case ($path === '/transactions/delete'):
        if ($requestMethod === 'POST') {
            $transactionController->delete();
        } else {
            header('Location: ' . base_url('transactions'));
            exit;
        }
        break;

    // Transaksi Detail (JSON endpoint)
    case ($path === '/transactions/detail'):
        $transactionController->detail();
        break;

    // Accounts (Saldo) - Phase 4
    case ($path === '/accounts' || $path === '/saldo'):
        if ($requestMethod === 'POST') {
            $accountController->store();
        } else {
            $accountController->index();
        }
        break;

    // Accounts Update
    case ($path === '/accounts/update'):
        if ($requestMethod === 'POST') {
            $accountController->update();
        } else {
            header('Location: ' . base_url('accounts'));
            exit;
        }
        break;

    // Accounts Delete
    case ($path === '/accounts/delete'):
        if ($requestMethod === 'POST') {
            $accountController->delete();
        } else {
            header('Location: ' . base_url('accounts'));
            exit;
        }
        break;

    // Accounts Detail (AJAX endpoint)
    case ($path === '/accounts/detail'):
        $accountController->detail();
        break;

    // Tagihan (Bills) - Section 11
    case ($path === '/bills' || $path === '/tagihan'):
        if ($requestMethod === 'POST') {
            $billController->store();
        } else {
            $billController->index();
        }
        break;

    // Tagihan Update
    case ($path === '/bills/update'):
        if ($requestMethod === 'POST') {
            $billController->update();
        } else {
            header('Location: ' . base_url('bills'));
            exit;
        }
        break;

    // Tagihan Delete
    case ($path === '/bills/delete'):
        if ($requestMethod === 'POST') {
            $billController->delete();
        } else {
            header('Location: ' . base_url('bills'));
            exit;
        }
        break;

    // Tagihan Detail (JSON endpoint)
    case ($path === '/bills/detail'):
        $billController->detail();
        break;

    // Pengeluaran (Expenses)
    case ($path === '/expenses' || $path === '/pengeluaran'):
        if ($requestMethod === 'POST') {
            $expenseController->store();
        } else {
            $expenseController->index();
        }
        break;

    // Pengeluaran Update
    case ($path === '/expenses/update'):
        if ($requestMethod === 'POST') {
            $expenseController->update();
        } else {
            header('Location: ' . base_url('expenses'));
            exit;
        }
        break;

    // Pengeluaran Delete
    case ($path === '/expenses/delete'):
        if ($requestMethod === 'POST') {
            $expenseController->delete();
        } else {
            header('Location: ' . base_url('expenses'));
            exit;
        }
        break;

    // Pengeluaran Detail (JSON endpoint)
    case ($path === '/expenses/detail'):
        $expenseController->detail();
        break;

    // Fallback: 404 Not Found
    default:
        http_response_code(404);
        $pageTitle = '404 - Halaman Tidak Ditemukan';
        $content = '
            <div class="text-center py-16">
                <h1 class="text-4xl font-bold text-[#2FA89D] mb-2">404</h1>
                <p class="text-sm text-[#8A8F98] mb-6">Halaman yang Anda cari tidak ditemukan.</p>
                <a href="' . base_url('dashboard') . '" class="inline-flex px-4 py-2 bg-[#2FA89D] text-white text-xs font-medium rounded-lg hover:bg-[#27958B]">
                    Kembali ke Dashboard
                </a>
            </div>
        ';
        require __DIR__ . '/../views/layouts/app.php';
        break;
}
