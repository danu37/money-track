<?php

require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Account.php';
require_once __DIR__ . '/../models/Category.php';

/**
 * ExpenseController (Pengeluaran Management)
 * Handles listing, analytics, category breakdown, monthly trend, and expense CRUD
 */
class ExpenseController
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
     * Display Pengeluaran page
     */
    public function index()
    {
        $userId = $_SESSION['user_id'] ?? 1;

        // Fetch user information
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['id' => 1, 'name' => 'Danu Anggo', 'email' => 'danu@gmail.com'];

        // Filter / search parameters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Fetch paginated expenses (type = 'expense')
        $pagination = $this->transactionModel->getPaginated(
            $userId,
            $page,
            8, // 8 items per page
            'expense',
            $search
        );

        // Fetch expense analytics (dynamic monthly calculation)
        $analytics = $this->transactionModel->getExpenseAnalytics($userId);

        // Fetch expense breakdown by category
        $categoryBreakdown = $this->transactionModel->getExpensesByCategory($userId);

        // Fetch 6-month monthly expense trend for comparison chart
        $monthlyTrend = $this->transactionModel->getMonthlyExpenseTrend($userId, 6);

        // Fetch accounts for modal dropdown
        $accounts = $this->accountModel->getAllByUserId($userId);

        // Fetch expense categories for modal dropdown
        $categories = $this->categoryModel->getAllByUserId($userId, 'expense');
        if (empty($categories)) {
            // Seed common expense categories if empty
            $this->seedDefaultExpenseCategories($userId);
            $categories = $this->categoryModel->getAllByUserId($userId, 'expense');
        }

        // Flash messages
        $flashSuccess = $_SESSION['_flash_success'] ?? null;
        $flashError = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_success'], $_SESSION['_flash_error']);

        $currentRoute = 'pengeluaran';
        $userName = $user['name'];
        $userEmail = $user['email'];
        $pageTitle = 'Pengeluaran';
        $contentView = __DIR__ . '/../../views/expenses/index.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Store new expense
     */
    public function store()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $description = trim($_POST['description'] ?? '');
        $accountId = (int)($_POST['account_id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $amount = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['amount'] ?? '0');
        $transactionDate = trim($_POST['transaction_date'] ?? date('Y-m-d'));

        // Validation
        $errors = [];
        if (empty($description)) {
            $errors[] = 'Keterangan pengeluaran wajib diisi.';
        }
        if ($amount <= 0) {
            $errors[] = 'Nominal pengeluaran harus lebih besar dari 0.';
        }
        if ($accountId <= 0) {
            $errors[] = 'Silakan pilih akun / dompet sumber.';
        }
        if ($categoryId <= 0) {
            $errors[] = 'Silakan pilih kategori pengeluaran.';
        }

        // Verify account ownership
        if ($accountId > 0) {
            $acc = $this->accountModel->getByIdAndUserId($accountId, $userId);
            if (!$acc) {
                $errors[] = 'Akun yang dipilih tidak valid.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['_flash_error'] = implode(' ', $errors);
            header('Location: ' . base_url('expenses'));
            exit;
        }

        // Create transaction with type 'expense'
        $this->transactionModel->create(
            $userId,
            $accountId,
            $categoryId,
            'expense',
            $amount,
            $description,
            $transactionDate
        );

        $_SESSION['_flash_success'] = "Pengeluaran \"{$description}\" sebesar " . formatRupiah($amount) . " berhasil dicatat.";
        header('Location: ' . base_url('expenses'));
        exit;
    }

    /**
     * Update existing expense
     */
    public function update()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $accountId = (int)($_POST['account_id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $amount = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['amount'] ?? '0');
        $transactionDate = trim($_POST['transaction_date'] ?? date('Y-m-d'));

        if ($id <= 0) {
            $_SESSION['_flash_error'] = 'ID pengeluaran tidak valid.';
            header('Location: ' . base_url('expenses'));
            exit;
        }

        // Ownership check
        $existing = $this->transactionModel->getByIdAndUserId($id, $userId);
        if (!$existing || $existing['type'] !== 'expense') {
            $_SESSION['_flash_error'] = 'Data pengeluaran tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('expenses'));
            exit;
        }

        // Validation
        $errors = [];
        if (empty($description)) {
            $errors[] = 'Keterangan pengeluaran wajib diisi.';
        }
        if ($amount <= 0) {
            $errors[] = 'Nominal pengeluaran harus lebih besar dari 0.';
        }
        if ($accountId <= 0) {
            $errors[] = 'Silakan pilih akun sumber.';
        }
        if ($categoryId <= 0) {
            $errors[] = 'Silakan pilih kategori pengeluaran.';
        }

        if (!empty($errors)) {
            $_SESSION['_flash_error'] = implode(' ', $errors);
            header('Location: ' . base_url('expenses'));
            exit;
        }

        $this->transactionModel->update(
            $id,
            $userId,
            $accountId,
            $categoryId,
            'expense',
            $amount,
            $description,
            $transactionDate
        );

        $_SESSION['_flash_success'] = "Pengeluaran \"{$description}\" berhasil diperbarui.";
        header('Location: ' . base_url('expenses'));
        exit;
    }

    /**
     * Delete expense
     */
    public function delete()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['_flash_error'] = 'ID pengeluaran tidak valid.';
            header('Location: ' . base_url('expenses'));
            exit;
        }

        $existing = $this->transactionModel->getByIdAndUserId($id, $userId);
        if (!$existing || $existing['type'] !== 'expense') {
            $_SESSION['_flash_error'] = 'Pengeluaran tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('expenses'));
            exit;
        }

        $this->transactionModel->delete($id, $userId);

        $_SESSION['_flash_success'] = "Pengeluaran \"{$existing['description']}\" berhasil dihapus.";
        header('Location: ' . base_url('expenses'));
        exit;
    }

    /**
     * JSON detail endpoint for prefilling edit and detail modals
     */
    public function detail()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? 1;
        $id = (int)($_GET['id'] ?? 0);

        $tx = $this->transactionModel->getByIdAndUserId($id, $userId);
        if (!$tx || $tx['type'] !== 'expense') {
            echo json_encode(['success' => false, 'message' => 'Pengeluaran tidak ditemukan.']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'expense' => [
                'id' => $tx['id'],
                'description' => $tx['description'],
                'amount' => (float)$tx['amount'],
                'amount_formatted' => formatRupiah((float)$tx['amount']),
                'account_id' => $tx['account_id'],
                'account_name' => $tx['account_name'] ?? 'Akun',
                'category_id' => $tx['category_id'],
                'category_name' => $tx['category_name'] ?? 'Kategori',
                'category_icon' => $tx['category_icon'] ?? 'tag',
                'transaction_date' => $tx['transaction_date'],
                'date_formatted' => formatIndonesianDate($tx['transaction_date']),
                'created_at' => formatIndonesianDate(substr($tx['created_at'], 0, 10)),
                'updated_at' => formatIndonesianDate(substr($tx['updated_at'], 0, 10))
            ]
        ]);
        exit;
    }

    /**
     * Helper to seed default expense categories if none exist
     */
    private function seedDefaultExpenseCategories(int $userId)
    {
        $defaults = [
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
            header('Location: ' . base_url('expenses'));
            exit;
        }
    }
}
