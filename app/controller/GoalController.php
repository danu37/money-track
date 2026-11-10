<?php

require_once __DIR__ . '/../models/Goal.php';
require_once __DIR__ . '/../models/User.php';

/**
 * GoalController (Target Keuangan)
 * Manages listing, creating, depositing savings, editing, inspecting, and deleting goals
 */
class GoalController
{
    private PDO $pdo;
    private Goal $goalModel;
    private User $userModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->goalModel = new Goal($pdo);
        $this->userModel = new User($pdo);
    }

    /**
     * Display Target Keuangan page
     */
    public function index()
    {
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $user = $this->userModel->findById($userId) ?: [
            'id' => 1,
            'name' => $_SESSION['user_name'] ?? 'Danu Anggo',
            'email' => $_SESSION['user_email'] ?? 'danu@gmail.com',
        ];

        $statusParam = trim($_GET['status'] ?? 'all');
        $filterStatus = in_array($statusParam, ['active', 'completed'], true) ? $statusParam : null;
        $search = trim($_GET['search'] ?? '');

        // Seed starter goals only once if user has zero goals in DB
        $seedSessionKey = '_goals_seeded_' . $userId;
        if ($this->goalModel->countAllByUserId($userId) === 0 && empty($_SESSION[$seedSessionKey])) {
            $this->goalModel->seedDefaultGoals($userId);
            $_SESSION[$seedSessionKey] = true;
        }

        $goals = $this->goalModel->getAllByUserId($userId, $filterStatus, $search);
        $summary = $this->goalModel->getSummary($userId);

        $flashSuccess = $_SESSION['_flash_success'] ?? null;
        $flashError = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_success'], $_SESSION['_flash_error']);

        $currentRoute = 'target';
        $userName = $user['name'];
        $userEmail = $user['email'];
        $pageTitle = 'Target Keuangan';
        $contentView = __DIR__ . '/../../views/goals/index.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }

    /**
     * Store a newly created financial goal
     */
    public function store()
    {
        $this->validateCsrf();
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryTag = trim($_POST['category_tag'] ?? 'Tabungan');
        $targetAmount = $this->parseAmount($_POST['target_amount'] ?? '0');
        $currentAmount = max(0, $this->parseAmount($_POST['current_amount'] ?? '0'));
        $monthlyTarget = max(0, $this->parseAmount($_POST['monthly_target'] ?? '0'));
        $deadlineDate = trim($_POST['deadline_date'] ?? date('Y-m-d', strtotime('+6 months')));
        $icon = $this->sanitizeIcon(trim($_POST['icon'] ?? 'target'));

        if ($title === '' || mb_strlen($title) > 120) {
            $_SESSION['_flash_error'] = 'Nama target keuangan wajib diisi (maksimal 120 karakter).';
            header('Location: ' . base_url('goals'));
            exit;
        }

        if ($targetAmount <= 0) {
            $_SESSION['_flash_error'] = 'Nominal target dana harus lebih besar dari 0.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        if (!$this->isValidDate($deadlineDate)) {
            $_SESSION['_flash_error'] = 'Tanggal target pencapaian tidak valid.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        if ($categoryTag === '') {
            $categoryTag = 'Tabungan';
        }

        $this->goalModel->create(
            $userId,
            $title,
            $description,
            mb_substr($categoryTag, 0, 50),
            $targetAmount,
            $currentAmount,
            $monthlyTarget,
            $deadlineDate,
            $icon
        );

        $_SESSION['_goals_seeded_' . $userId] = true;
        $_SESSION['_flash_success'] = "Target \"{$title}\" berhasil ditambahkan.";
        header('Location: ' . base_url('goals'));
        exit;
    }

    /**
     * Update an existing goal
     */
    public function update()
    {
        $this->validateCsrf();
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryTag = trim($_POST['category_tag'] ?? 'Tabungan');
        $targetAmount = $this->parseAmount($_POST['target_amount'] ?? '0');
        $currentAmount = max(0, $this->parseAmount($_POST['current_amount'] ?? '0'));
        $monthlyTarget = max(0, $this->parseAmount($_POST['monthly_target'] ?? '0'));
        $deadlineDate = trim($_POST['deadline_date'] ?? date('Y-m-d'));
        $icon = $this->sanitizeIcon(trim($_POST['icon'] ?? 'target'));

        if ($id <= 0 || $title === '' || $targetAmount <= 0 || !$this->isValidDate($deadlineDate)) {
            $_SESSION['_flash_error'] = 'Data target keuangan tidak valid.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        $existing = $this->goalModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Target tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        $this->goalModel->update(
            $id,
            $userId,
            mb_substr($title, 0, 120),
            $description,
            mb_substr($categoryTag ?: 'Tabungan', 0, 50),
            $targetAmount,
            $currentAmount,
            $monthlyTarget,
            $deadlineDate,
            $icon
        );

        $_SESSION['_flash_success'] = "Target \"{$title}\" berhasil diperbarui.";
        header('Location: ' . base_url('goals'));
        exit;
    }

    /**
     * Add savings deposit to a goal
     */
    public function deposit()
    {
        $this->validateCsrf();
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $id = (int)($_POST['id'] ?? 0);
        $amount = $this->parseAmount($_POST['amount'] ?? '0');

        if ($id <= 0 || $amount <= 0) {
            $_SESSION['_flash_error'] = 'Nominal setoran tabungan harus lebih besar dari 0.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        $updated = $this->goalModel->addDeposit($id, $userId, $amount);
        if (!$updated) {
            $_SESSION['_flash_error'] = 'Target tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        $formatted = formatRupiah($amount);
        if ($updated['status'] === 'completed') {
            $_SESSION['_flash_success'] = "Selamat! Setoran {$formatted} berhasil dan target \"{$updated['title']}\" telah tercapai 100%.";
        } else {
            $_SESSION['_flash_success'] = "Setoran {$formatted} berhasil ditambahkan ke \"{$updated['title']}\".";
        }

        header('Location: ' . base_url('goals'));
        exit;
    }

    /**
     * Delete a goal
     */
    public function delete()
    {
        $this->validateCsrf();
        $userId = (int)($_SESSION['user_id'] ?? 1);

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['_flash_error'] = 'ID target tidak valid.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        $existing = $this->goalModel->getByIdAndUserId($id, $userId);
        if (!$existing) {
            $_SESSION['_flash_error'] = 'Target tidak ditemukan atau Anda tidak memiliki akses.';
            header('Location: ' . base_url('goals'));
            exit;
        }

        $this->goalModel->delete($id, $userId);
        $_SESSION['_goals_seeded_' . $userId] = true;

        $_SESSION['_flash_success'] = "Target \"{$existing['title']}\" berhasil dihapus.";
        header('Location: ' . base_url('goals'));
        exit;
    }

    /**
     * JSON detail endpoint for modal inspection & edit prefill
     */
    public function detail()
    {
        header('Content-Type: application/json');
        $userId = (int)($_SESSION['user_id'] ?? 1);
        $id = (int)($_GET['id'] ?? 0);

        $goal = $this->goalModel->getByIdAndUserId($id, $userId);
        if (!$goal) {
            echo json_encode(['success' => false, 'message' => 'Target keuangan tidak ditemukan.']);
            exit;
        }

        $targetAmount = (float)$goal['target_amount'];
        $currentAmount = (float)$goal['current_amount'];
        $monthlyTarget = (float)$goal['monthly_target'];
        $remaining = max(0, $targetAmount - $currentAmount);
        $percentage = $targetAmount > 0 ? min(100, (int)round(($currentAmount / $targetAmount) * 100)) : 0;
        $estMonths = ($remaining > 0 && $monthlyTarget > 0) ? (int)ceil($remaining / $monthlyTarget) : 0;

        echo json_encode([
            'success' => true,
            'goal' => [
                'id' => (int)$goal['id'],
                'title' => $goal['title'],
                'description' => $goal['description'] ?: 'Belum ada catatan tambahan.',
                'raw_description' => $goal['description'] ?? '',
                'category_tag' => $goal['category_tag'],
                'target_amount' => $targetAmount,
                'target_amount_formatted' => formatRupiah($targetAmount),
                'current_amount' => $currentAmount,
                'current_amount_formatted' => formatRupiah($currentAmount),
                'monthly_target' => $monthlyTarget,
                'monthly_target_formatted' => formatRupiah($monthlyTarget),
                'remaining_formatted' => formatRupiah($remaining),
                'percentage' => $percentage,
                'estimated_months' => $estMonths,
                'deadline_date' => $goal['deadline_date'],
                'deadline_date_formatted' => formatIndonesianDate($goal['deadline_date']),
                'icon' => $goal['icon'],
                'status' => $goal['status'],
                'created_at' => formatIndonesianDate($goal['created_at']),
            ],
        ]);
        exit;
    }

    private function parseAmount(string $raw): float
    {
        return (float)str_replace(['.', ',', ' '], ['', '.', ''], $raw);
    }

    private function sanitizeIcon(string $icon): string
    {
        $allowed = ['target', 'shield', 'home', 'laptop', 'plane', 'trending-up', 'graduation-cap', 'car'];
        return in_array($icon, $allowed, true) ? $icon : 'target';
    }

    private function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt && $dt->format('Y-m-d') === $date;
    }

    private function validateCsrf(): void
    {
        $token = $_POST['_csrf_token'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
            $_SESSION['_flash_error'] = 'Sesi keamanan kedaluwarsa. Silakan coba kembali.';
            header('Location: ' . base_url('goals'));
            exit;
        }
    }
}

