<?php

require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Account.php';
require_once __DIR__ . '/../models/Category.php';

/**
 * TransactionController (Phase 6 of AGENTS.md)
 * Handles transaction listing, filtering, search, pagination, and CRUD operations
 */
class TransactionController
{
    private PDO $pdo;
    private Transaction $transactionModel;
    private Account $accountModel;
    private Category $categoryModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->transactionModel = new Transaction($pdo);
        $this->accountModel = new Account($pdo);
        $this->categoryModel = new Category($pdo);
    }

    /**
     * Display paginated transactions with tab filters, search, and metrics
     */
    public function index()
    {
        $userId = $_SESSION['user_id'] ?? 1;

        // Fetch user information
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['id' => 1, 'name' => 'Danu Anggo', 'email' => 'danu@gmail.com'];

        // Get filter inputs
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $type = isset($_GET['type']) ? trim($_GET['type']) : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Normalize filter type
        $filterType = null;
        if ($type === 'income' || $type === 'revenue') {
            $filterType = 'income';
        } elseif ($type === 'expense') {
            $filterType = 'expense';
        }

        // Fetch paginated transactions
        $pagination = $this->transactionModel->getPaginated(
            $userId,
            $page,
            8, // 8 items per page for clean desktop & mobile cards
            $filterType,
            $search
        );

        // Fetch user financial summary
        $summary = $this->transactionModel->getSummary($userId);

        // Fetch user accounts for modal dropdown
        $accounts = $this->accountModel->getAllByUserId($userId);

        // Fetch user categories for modal dropdown
        $categories = $this->categoryModel->getAllByUserId($userId);
        if (empty($categories)) {
            // Seed common categories for user if empty
            $this->seedDefaultCategories($userId);
            $categories = $this->categoryModel->getAllByUserId($userId);
        }

        // Flash messages
        $flashSuccess = $_SESSION['_flash_success'] ?? null;
        $flashError = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_success'], $_SESSION['_flash_error']);

        $currentRoute = 'transaksi';
        $userName = $user['name'];
        $userEmail = $user['email'];
        $pageTitle = 'Transaksi';
        $contentView = __DIR__ . '/../../views/transactions/index.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Store new transaction
     */
    public function store()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $description = trim($_POST['description'] ?? '');
        $type = trim($_POST['type'] ?? 'expense');
        $accountId = (int)($_POST['account_id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $amount = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['amount'] ?? '0');
        $transactionDate = trim($_POST['transaction_date'] ?? date('Y-m-d'));

        // Validation
        if (empty($description)) {
            $_SESSION['_flash_error'] = 'Deskripsi transaksi wajib diisi.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        if ($amount <= 0) {
            $_SESSION['_flash_error'] = 'Nominal transaksi harus lebih dari 0.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        if (!in_array($type, ['income', 'expense'])) {
            $type = 'expense';
        }

        // Verify account ownership
        $account = $this->accountModel->getByIdAndUserId($accountId, $userId);
        if (!$account) {
            $_SESSION['_flash_error'] = 'Rekening pembayaran tidak valid.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        // Verify category ownership
        $category = $this->categoryModel->getByIdAndUserId($categoryId, $userId);
        if (!$category) {
            $_SESSION['_flash_error'] = 'Kategori transaksi tidak valid.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        $this->transactionModel->create(
            $userId,
            $accountId,
            $categoryId,
            $type,
            $amount,
            $description,
            $transactionDate
        );

        $_SESSION['_flash_success'] = "Transaksi \"{$description}\" berhasil dicatat.";
        header('Location: ' . base_url('transactions'));
        exit;
    }

    /**
     * Update existing transaction
     */
    public function update()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $type = trim($_POST['type'] ?? 'expense');
        $accountId = (int)($_POST['account_id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $amount = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['amount'] ?? '0');
        $transactionDate = trim($_POST['transaction_date'] ?? date('Y-m-d'));

        if ($id <= 0 || empty($description) || $amount <= 0) {
            $_SESSION['_flash_error'] = 'Data transaksi tidak valid.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        // Verify existing transaction ownership
        $existing = $this->transactionModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Transaksi tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        // Verify account & category ownership
        if (!$this->accountModel->getByIdAndUserId($accountId, $userId) || 
            !$this->categoryModel->getByIdAndUserId($categoryId, $userId)) {
            $_SESSION['_flash_error'] = 'Rekening atau kategori tidak valid.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        $this->transactionModel->update(
            $id,
            $userId,
            $accountId,
            $categoryId,
            $type,
            $amount,
            $description,
            $transactionDate
        );

        $_SESSION['_flash_success'] = "Transaksi \"{$description}\" berhasil diperbarui.";
        header('Location: ' . base_url('transactions'));
        exit;
    }

    /**
     * Delete transaction
     */
    public function delete()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['_flash_error'] = 'ID transaksi tidak valid.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        $existing = $this->transactionModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Transaksi tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('transactions'));
            exit;
        }

        $this->transactionModel->delete($id, $userId);

        $_SESSION['_flash_success'] = "Transaksi \"{$existing['description']}\" berhasil dihapus.";
        header('Location: ' . base_url('transactions'));
        exit;
    }

    /**
     * JSON detail endpoint for prefilling edit modal
     */
    public function detail()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? 1;
        $id = (int)($_GET['id'] ?? 0);

        $tx = $this->transactionModel->getByIdAndUserId($id, $userId);
        if (!$tx) {
            echo json_encode(['success' => false, 'message' => 'Transaksi tidak ditemukan.']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'transaction' => [
                'id' => $tx['id'],
                'description' => $tx['description'],
                'amount' => (float)$tx['amount'],
                'type' => $tx['type'],
                'account_id' => $tx['account_id'],
                'category_id' => $tx['category_id'],
                'transaction_date' => $tx['transaction_date']
            ]
        ]);
        exit;
    }

    /**
     * Helper to seed default categories if none exist
     */
    private function seedDefaultCategories(int $userId)
    {
        $defaults = [
            ['name' => 'Salary', 'type' => 'income', 'icon' => 'briefcase'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => 'layout'],
            ['name' => 'Business', 'type' => 'income', 'icon' => 'building'],
            ['name' => 'Food & Dining', 'type' => 'expense', 'icon' => 'coffee'],
            ['name' => 'Transportation', 'type' => 'expense', 'icon' => 'car'],
            ['name' => 'Shopping', 'type' => 'expense', 'icon' => 'tag'],
            ['name' => 'Bills & Utilities', 'type' => 'expense', 'icon' => 'receipt'],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => 'film'],
            ['name' => 'Gadget & Gear', 'type' => 'expense', 'icon' => 'watch']
        ];

        foreach ($defaults as $cat) {
            $this->categoryModel->create($userId, $cat['name'], $cat['type'], $cat['icon']);
        }
    }

    /**
     * Validate CSRF token
     */
    private function validateCsrf()
    {
        $token = $_POST['_csrf_token'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
            $_SESSION['_flash_error'] = 'Sesi keamanan kedaluwarsa. Silakan coba kembali.';
            header('Location: ' . base_url('transactions'));
            exit;
        }
    }
}
