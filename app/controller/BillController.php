<?php

require_once __DIR__ . '/../models/Bill.php';

/**
 * BillController (Tagihan Management)
 * Handles listing, adding, viewing detail, editing, and deleting recurring bills
 */
class BillController
{
    private PDO $pdo;
    private Bill $billModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->billModel = new Bill($pdo);
    }

    /**
     * Display Tagihan page
     */
    public function index()
    {
        $userId = $_SESSION['user_id'] ?? 1;

        // Fetch user information
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['id' => 1, 'name' => 'Danu Anggo', 'email' => 'danu@gmail.com'];

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Fetch bills
        $bills = $this->billModel->getAllByUserId($userId, $search);
        if (empty($bills) && empty($search)) {
            // Seed initial sample bills if completely empty
            $this->billModel->seedDefaultBills($userId);
            $bills = $this->billModel->getAllByUserId($userId, $search);
        }

        // Summary statistics
        $summary = $this->billModel->getSummary($userId);

        // Flash messages
        $flashSuccess = $_SESSION['_flash_success'] ?? null;
        $flashError = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_success'], $_SESSION['_flash_error']);

        $currentRoute = 'tagihan';
        $userName = $user['name'];
        $userEmail = $user['email'];
        $currentDate = date('d M Y');
        $pageTitle = 'Tagihan';
        $contentView = __DIR__ . '/../../views/bills/index.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Store newly created bill
     */
    public function store()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $serviceName = trim($_POST['service_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $amount = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['amount'] ?? '0');
        $billingDate = trim($_POST['billing_date'] ?? date('Y-m-d'));
        $billingCycle = trim($_POST['billing_cycle'] ?? 'Bulanan');
        $logo = trim($_POST['logo'] ?? 'default');

        if (empty($serviceName)) {
            $_SESSION['_flash_error'] = 'Nama layanan/perusahaan wajib diisi.';
            header('Location: ' . base_url('bills'));
            exit;
        }

        if ($amount <= 0) {
            $_SESSION['_flash_error'] = 'Nominal tagihan harus lebih besar dari 0.';
            header('Location: ' . base_url('bills'));
            exit;
        }

        $allowedCycles = ['Bulanan', 'Tahunan', 'Mingguan', 'Sekali'];
        if (!in_array($billingCycle, $allowedCycles)) {
            $billingCycle = 'Bulanan';
        }

        $this->billModel->create(
            $userId,
            $serviceName,
            $description,
            $amount,
            $billingDate,
            $billingCycle,
            $logo
        );

        $_SESSION['_flash_success'] = "Tagihan \"{$serviceName}\" berhasil ditambahkan.";
        header('Location: ' . base_url('bills'));
        exit;
    }

    /**
     * Update existing bill
     */
    public function update()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);
        $serviceName = trim($_POST['service_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $amount = (float)str_replace(['.', ',', ' '], ['', '.', ''], $_POST['amount'] ?? '0');
        $billingDate = trim($_POST['billing_date'] ?? date('Y-m-d'));
        $billingCycle = trim($_POST['billing_cycle'] ?? 'Bulanan');
        $logo = trim($_POST['logo'] ?? 'default');

        if ($id <= 0 || empty($serviceName) || $amount <= 0) {
            $_SESSION['_flash_error'] = 'Data tagihan tidak valid.';
            header('Location: ' . base_url('bills'));
            exit;
        }

        $existing = $this->billModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Tagihan tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('bills'));
            exit;
        }

        $allowedCycles = ['Bulanan', 'Tahunan', 'Mingguan', 'Sekali'];
        if (!in_array($billingCycle, $allowedCycles)) {
            $billingCycle = 'Bulanan';
        }

        $this->billModel->update(
            $id,
            $userId,
            $serviceName,
            $description,
            $amount,
            $billingDate,
            $billingCycle,
            $logo
        );

        $_SESSION['_flash_success'] = "Tagihan \"{$serviceName}\" berhasil diperbarui.";
        header('Location: ' . base_url('bills'));
        exit;
    }

    /**
     * Delete bill
     */
    public function delete()
    {
        $this->validateCsrf();
        $userId = $_SESSION['user_id'] ?? 1;

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['_flash_error'] = 'ID tagihan tidak valid.';
            header('Location: ' . base_url('bills'));
            exit;
        }

        $existing = $this->billModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Tagihan tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('bills'));
            exit;
        }

        $this->billModel->delete($id, $userId);

        $_SESSION['_flash_success'] = "Tagihan \"{$existing['service_name']}\" berhasil dihapus.";
        header('Location: ' . base_url('bills'));
        exit;
    }

    /**
     * JSON detail endpoint for prefilling edit modal or viewing detail
     */
    public function detail()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? 1;
        $id = (int)($_GET['id'] ?? 0);

        $bill = $this->billModel->getByIdAndUserId($id, $userId);
        if (!$bill) {
            echo json_encode(['success' => false, 'message' => 'Tagihan tidak ditemukan.']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'bill' => [
                'id' => $bill['id'],
                'service_name' => $bill['service_name'],
                'description' => $bill['description'],
                'amount' => (float)$bill['amount'],
                'amount_formatted' => formatRupiah($bill['amount']),
                'billing_date' => $bill['billing_date'],
                'billing_date_formatted' => formatIndonesianDate($bill['billing_date']),
                'billing_cycle' => $bill['billing_cycle'],
                'logo' => $bill['logo'],
                'created_at' => formatIndonesianDate($bill['created_at']),
                'updated_at' => formatIndonesianDate($bill['updated_at'])
            ]
        ]);
        exit;
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
            header('Location: ' . base_url('bills'));
            exit;
        }
    }
}
