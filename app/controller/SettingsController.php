<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Account.php';

/**
 * SettingsController (Phase 8 & Settings Management)
 * Handles profile settings, password security, categories, preferences, and CSV export
 */
class SettingsController
{
    private PDO $pdo;
    private User $userModel;
    private Category $categoryModel;
    private Account $accountModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
        $this->categoryModel = new Category($pdo);
        $this->accountModel = new Account($pdo);
    }

    /**
     * Render Pengaturan & Profil page
     */
    public function index()
    {
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $user = $this->userModel->findById($userId) ?: [
            'id' => 1,
            'name' => $_SESSION['user_name'] ?? 'Danu',
            'email' => $_SESSION['user_email'] ?? 'danu@gmail.com',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $stats = $this->userModel->getStats($userId);
        $totalBalance = $this->accountModel->getTotalBalance($userId);

        $categories = $this->categoryModel->getAllWithCountByUserId($userId);
        $incomeCategories = array_values(array_filter($categories, fn($c) => $c['type'] === 'income'));
        $expenseCategories = array_values(array_filter($categories, fn($c) => $c['type'] === 'expense'));

        $allowedTabs = ['profile', 'security', 'categories', 'preferences'];
        $activeTab = trim($_GET['tab'] ?? 'profile');
        if (!in_array($activeTab, $allowedTabs, true)) {
            $activeTab = 'profile';
        }

        $preferences = array_merge([
            'bill_reminder' => true,
            'compact_currency' => false,
            'default_landing' => 'dashboard',
        ], $_SESSION['_preferences'] ?? []);

        $flashSuccess = $_SESSION['_flash_success'] ?? null;
        $flashError = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_success'], $_SESSION['_flash_error']);

        $currentRoute = 'pengaturan';
        $userName = $user['name'];
        $userEmail = $user['email'];
        $pageTitle = 'Pengaturan';
        $contentView = __DIR__ . '/../../views/settings/index.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Update user profile (name & email)
     */
    public function updateProfile()
    {
        $this->validateCsrf('profile');
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($name === '' || mb_strlen($name) > 100) {
            $_SESSION['_flash_error'] = 'Nama lengkap wajib diisi (maksimal 100 karakter).';
            header('Location: ' . base_url('settings?tab=profile'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
            $_SESSION['_flash_error'] = 'Format alamat email tidak valid.';
            header('Location: ' . base_url('settings?tab=profile'));
            exit;
        }

        $existingUser = $this->userModel->findById($userId);
        if (!$existingUser) {
            $_SESSION['_flash_error'] = 'Data pengguna tidak ditemukan.';
            header('Location: ' . base_url('settings?tab=profile'));
            exit;
        }

        if ($this->userModel->emailExistsForOtherUser($email, $userId)) {
            $_SESSION['_flash_error'] = 'Alamat email tersebut sudah digunakan oleh akun lain.';
            header('Location: ' . base_url('settings?tab=profile'));
            exit;
        }

        $this->userModel->updateProfile($userId, $name, $email);

        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['_flash_success'] = 'Profil berhasil diperbarui.';

        header('Location: ' . base_url('settings?tab=profile'));
        exit;
    }

    /**
     * Update user password safely
     */
    public function updatePassword()
    {
        $this->validateCsrf('security');
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $_SESSION['_flash_error'] = 'Semua kolom kata sandi wajib diisi.';
            header('Location: ' . base_url('settings?tab=security'));
            exit;
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            $_SESSION['_flash_error'] = 'Data pengguna tidak ditemukan.';
            header('Location: ' . base_url('settings?tab=security'));
            exit;
        }

        $isCurrentValid = password_verify($currentPassword, $user['password']) || $user['password'] === $currentPassword;
        if (!$isCurrentValid) {
            $_SESSION['_flash_error'] = 'Kata sandi saat ini tidak sesuai.';
            header('Location: ' . base_url('settings?tab=security'));
            exit;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['_flash_error'] = 'Kata sandi baru minimal 6 karakter.';
            header('Location: ' . base_url('settings?tab=security'));
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['_flash_error'] = 'Konfirmasi kata sandi baru tidak cocok.';
            header('Location: ' . base_url('settings?tab=security'));
            exit;
        }

        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userModel->updatePassword($userId, $hashed);

        $_SESSION['_flash_success'] = 'Kata sandi akun berhasil diperbarui dan terenkripsi.';
        header('Location: ' . base_url('settings?tab=security'));
        exit;
    }

    /**
     * Store a new category for the authenticated user
     */
    public function storeCategory()
    {
        $this->validateCsrf('categories');
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? 'expense');
        $icon = trim($_POST['icon'] ?? 'tag');

        $allowedTypes = ['income', 'expense'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'expense';
        }

        $allowedIcons = [
            'wallet', 'briefcase', 'building', 'utensils', 'car',
            'book', 'shopping-bag', 'receipt', 'film', 'tag'
        ];
        if (!in_array($icon, $allowedIcons, true)) {
            $icon = 'tag';
        }

        if ($name === '' || mb_strlen($name) > 100) {
            $_SESSION['_flash_error'] = 'Nama kategori wajib diisi (maksimal 100 karakter).';
            header('Location: ' . base_url('settings?tab=categories'));
            exit;
        }

        if ($this->categoryModel->existsForUser($userId, $name, $type)) {
            $_SESSION['_flash_error'] = "Kategori \"{$name}\" sudah tersedia pada tipe tersebut.";
            header('Location: ' . base_url('settings?tab=categories'));
            exit;
        }

        $this->categoryModel->create($userId, $name, $type, $icon);

        $_SESSION['_flash_success'] = "Kategori \"{$name}\" berhasil ditambahkan.";
        header('Location: ' . base_url('settings?tab=categories'));
        exit;
    }

    /**
     * Delete an unused category owned by the authenticated user
     */
    public function deleteCategory()
    {
        $this->validateCsrf('categories');
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['_flash_error'] = 'ID kategori tidak valid.';
            header('Location: ' . base_url('settings?tab=categories'));
            exit;
        }

        $category = $this->categoryModel->getByIdAndUserId($id, $userId);
        if (!$category) {
            $_SESSION['_flash_error'] = 'Kategori tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('settings?tab=categories'));
            exit;
        }

        $txCount = $this->categoryModel->countTransactions($id, $userId);
        if ($txCount > 0) {
            $_SESSION['_flash_error'] = "Kategori \"{$category['name']}\" masih digunakan oleh {$txCount} transaksi dan tidak dapat dihapus.";
            header('Location: ' . base_url('settings?tab=categories'));
            exit;
        }

        $this->categoryModel->delete($id, $userId);

        $_SESSION['_flash_success'] = "Kategori \"{$category['name']}\" berhasil dihapus.";
        header('Location: ' . base_url('settings?tab=categories'));
        exit;
    }

    /**
     * Save display & notification preferences in session
     */
    public function updatePreferences()
    {
        $this->validateCsrf('preferences');

        $allowedLanding = ['dashboard', 'transactions', 'accounts'];
        $defaultLanding = trim($_POST['default_landing'] ?? 'dashboard');
        if (!in_array($defaultLanding, $allowedLanding, true)) {
            $defaultLanding = 'dashboard';
        }

        $_SESSION['_preferences'] = [
            'bill_reminder' => isset($_POST['bill_reminder']),
            'compact_currency' => isset($_POST['compact_currency']),
            'default_landing' => $defaultLanding,
        ];

        $_SESSION['_flash_success'] = 'Preferensi tampilan berhasil disimpan.';
        header('Location: ' . base_url('settings?tab=preferences'));
        exit;
    }

    /**
     * Export authenticated user's transactions as CSV
     */
    public function exportTransactionsCsv()
    {
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $stmt = $this->pdo->prepare("
            SELECT
                t.transaction_date,
                t.type,
                COALESCE(c.name, 'Umum') AS category_name,
                COALESCE(a.name, '-') AS account_name,
                t.description,
                t.amount
            FROM transactions t
            LEFT JOIN categories c ON c.id = t.category_id AND c.user_id = t.user_id
            LEFT JOIN accounts a ON a.id = t.account_id AND a.user_id = t.user_id
            WHERE t.user_id = :user_id
            ORDER BY t.transaction_date DESC, t.id DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $filename = 'moneytrack-transaksi-' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM for clean Excel rendering
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, ['Tanggal', 'Tipe', 'Kategori', 'Rekening', 'Keterangan', 'Nominal (IDR)']);

        foreach ($rows as $row) {
            $desc = (string)($row['description'] ?? '');
            if ($desc !== '' && in_array($desc[0], ['=', '+', '-', '@'], true)) {
                $desc = "'" . $desc;
            }

            fputcsv($out, [
                $row['transaction_date'],
                $row['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran',
                $row['category_name'],
                $row['account_name'],
                $desc,
                number_format((float)$row['amount'], 2, '.', ''),
            ]);
        }

        fclose($out);
        exit;
    }

    /**
     * CSRF token verification helper
     */
    private function validateCsrf(string $redirectTab = 'profile')
    {
        $token = $_POST['_csrf_token'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
            $_SESSION['_flash_error'] = 'Sesi keamanan kedaluwarsa. Silakan coba kembali.';
            header('Location: ' . base_url('settings?tab=' . urlencode($redirectTab)));
            exit;
        }
    }
}

