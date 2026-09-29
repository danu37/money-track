<?php

require_once __DIR__ . '/../models/Account.php';

/**
 * AccountController (Phase 4 of AGENTS.md)
 * Manages accounts (Saldo), balance calculation, and CRUD operations
 */
class AccountController
{
    private PDO $pdo;
    private Account $accountModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->accountModel = new Account($pdo);
    }

    /**
     * Display accounts list with calculated balances and metrics
     */
    public function index()
    {
        $userId = $_SESSION['user_id'] ?? 1;

        // Fetch user information
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['id' => 1, 'name' => 'Danu Anggo', 'email' => 'danu@gmail.com'];

        // Get all accounts with real calculated balances
        $accounts = $this->accountModel->getAllByUserId($userId);
        $totalBalance = $this->accountModel->getTotalBalance($userId);

        // Group counts & totals by type
        $statsByType = [
            'bank' => ['count' => 0, 'balance' => 0],
            'ewallet' => ['count' => 0, 'balance' => 0],
            'cash' => ['count' => 0, 'balance' => 0],
            'other' => ['count' => 0, 'balance' => 0],
        ];

        foreach ($accounts as $acc) {
            $t = $acc['type'] ?? 'other';
            if (isset($statsByType[$t])) {
                $statsByType[$t]['count']++;
                $statsByType[$t]['balance'] += (float)$acc['current_balance'];
            }
        }

        // Get flash messages
        $flashSuccess = $_SESSION['_flash_success'] ?? null;
        $flashError = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_success'], $_SESSION['_flash_error']);

        $currentRoute = 'saldo';
        $userName = $user['name'];
        $userEmail = $user['email'];
        $pageTitle = 'Kelola Saldo & Rekening';
        $contentView = __DIR__ . '/../../views/accounts/index.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Store newly created account
     */
    public function store()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? 'cash');
        $initialBalance = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['initial_balance'] ?? '0');

        $allowedTypes = ['cash', 'bank', 'ewallet', 'other'];
        if (!in_array($type, $allowedTypes)) {
            $type = 'cash';
        }

        if (empty($name)) {
            $_SESSION['_flash_error'] = 'Nama rekening wajib diisi.';
            header('Location: ' . base_url('accounts'));
            exit;
        }

        $this->accountModel->create($userId, $name, $type, max(0, $initialBalance));

        $_SESSION['_flash_success'] = "Rekening \"{$name}\" berhasil ditambahkan.";
        header('Location: ' . base_url('accounts'));
        exit;
    }

    /**
     * Update existing account
     */
    public function update()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? 'cash');
        $initialBalance = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['initial_balance'] ?? '0');

        $allowedTypes = ['cash', 'bank', 'ewallet', 'other'];
        if (!in_array($type, $allowedTypes)) {
            $type = 'cash';
        }

        if ($id <= 0 || empty($name)) {
            $_SESSION['_flash_error'] = 'Data rekening tidak valid.';
            header('Location: ' . base_url('accounts'));
            exit;
        }

        $existing = $this->accountModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Rekening tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('accounts'));
            exit;
        }

        $this->accountModel->update($id, $userId, $name, $type, max(0, $initialBalance));

        $_SESSION['_flash_success'] = "Rekening \"{$name}\" berhasil diperbarui.";
        header('Location: ' . base_url('accounts'));
        exit;
    }

    /**
     * Delete an account
     */
    public function delete()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['_flash_error'] = 'ID rekening tidak valid.';
            header('Location: ' . base_url('accounts'));
            exit;
        }

        $existing = $this->accountModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Rekening tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('accounts'));
            exit;
        }

        $this->accountModel->delete($id, $userId);

        $_SESSION['_flash_success'] = "Rekening \"{$existing['name']}\" berhasil dihapus.";
        header('Location: ' . base_url('accounts'));
        exit;
    }

    /**
     * Return account detail + recent transactions as JSON (for quick modal inspection)
     */
    public function detail()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? 1;
        $id = (int)($_GET['id'] ?? 0);

        $account = $this->accountModel->getByIdAndUserId($id, $userId);
        if (!$account) {
            echo json_encode(['success' => false, 'message' => 'Rekening tidak ditemukan.']);
            exit;
        }

        $transactions = $this->accountModel->getTransactions($id, $userId, 10);

        // Format dates and currency for display
        $formattedTx = [];
        foreach ($transactions as $tx) {
            $formattedTx[] = [
                'id' => $tx['id'],
                'amount' => (float)$tx['amount'],
                'amount_formatted' => formatRupiah($tx['amount']),
                'type' => $tx['type'],
                'description' => $tx['description'] ?: 'Transaksi',
                'date' => formatIndonesianDate($tx['transaction_date']),
                'category' => $tx['category_name'] ?: 'Umum',
                'icon' => $tx['category_icon'] ?: 'tag'
            ];
        }

        echo json_encode([
            'success' => true,
            'account' => [
                'id' => $account['id'],
                'name' => $account['name'],
                'type' => $account['type'],
                'initial_balance' => (float)$account['initial_balance'],
                'initial_balance_formatted' => formatRupiah($account['initial_balance']),
                'current_balance' => (float)$account['current_balance'],
                'current_balance_formatted' => formatRupiah($account['current_balance']),
                'total_income_formatted' => formatRupiah($account['total_income']),
                'total_expense_formatted' => formatRupiah($account['total_expense']),
                'transaction_count' => (int)$account['transaction_count'],
                'created_at' => formatIndonesianDate($account['created_at'])
            ],
            'transactions' => $formattedTx
        ]);
        exit;
    }

    /**
     * CSRF token verification helper
     */
    private function validateCsrf()
    {
        $token = $_POST['_csrf_token'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
            $_SESSION['_flash_error'] = 'Sesi keamanan kedaluwarsa. Silakan coba kembali.';
            header('Location: ' . base_url('accounts'));
            exit;
        }
    }
}
