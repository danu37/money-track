<?php

/**
 * Goal Model (Target Keuangan - Section 10, 16, 19 of AGENTS.md)
 * Encapsulates financial goals CRUD, progress calculation, and user scoping
 */
class Goal
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->ensureTableExists();
    }

    /**
     * Ensure goals table exists in the database
     */
    private function ensureTableExists(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS goals (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                title VARCHAR(120) NOT NULL,
                description VARCHAR(255) DEFAULT NULL,
                category_tag VARCHAR(50) NOT NULL DEFAULT 'Tabungan',
                target_amount DECIMAL(15,2) NOT NULL,
                current_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
                monthly_target DECIMAL(15,2) NOT NULL DEFAULT 0,
                deadline_date DATE NOT NULL,
                icon VARCHAR(50) NOT NULL DEFAULT 'target',
                status ENUM('active', 'completed') NOT NULL DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_goals_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->pdo->exec($sql);
    }

    /**
     * Count total goals owned by user (without filters)
     */
    public function countAllByUserId(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM goals WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get goals for user with optional status filter and search query
     */
    public function getAllByUserId(int $userId, ?string $status = null, ?string $search = null): array
    {
        $sql = "SELECT * FROM goals WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if ($status !== null && in_array($status, ['active', 'completed'], true)) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search_title OR description LIKE :search_desc OR category_tag LIKE :search_cat)";
            $like = '%' . $search . '%';
            $params['search_title'] = $like;
            $params['search_desc'] = $like;
            $params['search_cat'] = $like;
        }

        $sql .= " ORDER BY status ASC, deadline_date ASC, id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get summary metrics for user's financial goals
     */
    public function getSummary(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                COUNT(*) AS total_count,
                COALESCE(SUM(CASE WHEN status = 'completed' OR current_amount >= target_amount THEN 1 ELSE 0 END), 0) AS completed_count,
                COALESCE(SUM(CASE WHEN status = 'active' AND current_amount < target_amount THEN 1 ELSE 0 END), 0) AS active_count,
                COALESCE(SUM(target_amount), 0) AS total_target,
                COALESCE(SUM(LEAST(current_amount, target_amount)), 0) AS total_saved_capped,
                COALESCE(SUM(current_amount), 0) AS total_saved,
                COALESCE(SUM(CASE WHEN status = 'active' THEN monthly_target ELSE 0 END), 0) AS total_monthly
            FROM goals
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $totalTarget = (float)($row['total_target'] ?? 0);
        $totalSaved = (float)($row['total_saved'] ?? 0);
        $totalSavedCapped = (float)($row['total_saved_capped'] ?? 0);
        $overallPercent = $totalTarget > 0 ? min(100, (int)round(($totalSavedCapped / $totalTarget) * 100)) : 0;

        return [
            'total_count' => (int)($row['total_count'] ?? 0),
            'completed_count' => (int)($row['completed_count'] ?? 0),
            'active_count' => (int)($row['active_count'] ?? 0),
            'total_target' => $totalTarget,
            'total_saved' => $totalSaved,
            'total_remaining' => max(0, $totalTarget - $totalSavedCapped),
            'total_monthly' => (float)($row['total_monthly'] ?? 0),
            'overall_percentage' => $overallPercent,
        ];
    }

    /**
     * Get nearest active goal for dashboard spotlight
     */
    public function getPrimaryGoal(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM goals
            WHERE user_id = :user_id
            ORDER BY (status = 'active') DESC, deadline_date ASC, id ASC
            LIMIT 1
        ");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Get single goal by ID and user ID
     */
    public function getByIdAndUserId(int $id, int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM goals
            WHERE id = :id AND user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    /**
     * Create a new financial goal
     */
    public function create(
        int $userId,
        string $title,
        ?string $description,
        string $categoryTag,
        float $targetAmount,
        float $currentAmount,
        float $monthlyTarget,
        string $deadlineDate,
        string $icon = 'target'
    ): int {
        $status = ($targetAmount > 0 && $currentAmount >= $targetAmount) ? 'completed' : 'active';

        $stmt = $this->pdo->prepare("
            INSERT INTO goals (
                user_id, title, description, category_tag,
                target_amount, current_amount, monthly_target,
                deadline_date, icon, status, created_at, updated_at
            ) VALUES (
                :user_id, :title, :description, :category_tag,
                :target_amount, :current_amount, :monthly_target,
                :deadline_date, :icon, :status, NOW(), NOW()
            )
        ");

        $stmt->execute([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description,
            'category_tag' => $categoryTag,
            'target_amount' => $targetAmount,
            'current_amount' => $currentAmount,
            'monthly_target' => $monthlyTarget,
            'deadline_date' => $deadlineDate,
            'icon' => $icon,
            'status' => $status,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update an existing goal (scoped by user_id)
     */
    public function update(
        int $id,
        int $userId,
        string $title,
        ?string $description,
        string $categoryTag,
        float $targetAmount,
        float $currentAmount,
        float $monthlyTarget,
        string $deadlineDate,
        string $icon = 'target'
    ): bool {
        $status = ($targetAmount > 0 && $currentAmount >= $targetAmount) ? 'completed' : 'active';

        $stmt = $this->pdo->prepare("
            UPDATE goals
            SET title = :title,
                description = :description,
                category_tag = :category_tag,
                target_amount = :target_amount,
                current_amount = :current_amount,
                monthly_target = :monthly_target,
                deadline_date = :deadline_date,
                icon = :icon,
                status = :status,
                updated_at = NOW()
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'title' => $title,
            'description' => $description,
            'category_tag' => $categoryTag,
            'target_amount' => $targetAmount,
            'current_amount' => $currentAmount,
            'monthly_target' => $monthlyTarget,
            'deadline_date' => $deadlineDate,
            'icon' => $icon,
            'status' => $status,
            'id' => $id,
            'user_id' => $userId,
        ]);
    }

    /**
     * Add savings deposit to an existing goal (scoped by user_id)
     */
    public function addDeposit(int $id, int $userId, float $depositAmount): ?array
    {
        $goal = $this->getByIdAndUserId($id, $userId);
        if (!$goal) {
            return null;
        }

        $newAmount = (float)$goal['current_amount'] + $depositAmount;
        $status = ($newAmount >= (float)$goal['target_amount']) ? 'completed' : 'active';

        $stmt = $this->pdo->prepare("
            UPDATE goals
            SET current_amount = :current_amount,
                status = :status,
                updated_at = NOW()
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([
            'current_amount' => $newAmount,
            'status' => $status,
            'id' => $id,
            'user_id' => $userId,
        ]);

        return $this->getByIdAndUserId($id, $userId);
    }

    /**
     * Delete a goal (scoped by user_id)
     */
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM goals
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
    }

    /**
     * Seed starter financial goals for first-time empty state
     */
    public function seedDefaultGoals(int $userId): void
    {
        $year = (int)date('Y');
        $defaults = [
            [
                'title' => 'Dana Darurat 6 Bulan',
                'description' => 'Cadangan dana aman untuk kebutuhan pokok keluarga minimal 6 bulan ke depan.',
                'category_tag' => 'Dana Darurat',
                'target_amount' => 30000000,
                'current_amount' => 22500000,
                'monthly_target' => 2500000,
                'deadline_date' => ($year + 1) . '-03-31',
                'icon' => 'shield',
            ],
            [
                'title' => 'DP Rumah Pertama',
                'description' => 'Tabungan uang muka hunian dan biaya administrasi akad KPR.',
                'category_tag' => 'Properti',
                'target_amount' => 120000000,
                'current_amount' => 78000000,
                'monthly_target' => 5000000,
                'deadline_date' => ($year + 1) . '-12-20',
                'icon' => 'home',
            ],
            [
                'title' => 'Upgrade Laptop Kerja',
                'description' => 'Perangkat produktivitas spesifikasi tinggi untuk pekerjaan desain & pengembangan.',
                'category_tag' => 'Teknologi',
                'target_amount' => 24000000,
                'current_amount' => 24000000,
                'monthly_target' => 2000000,
                'deadline_date' => date('Y-m-d'),
                'icon' => 'laptop',
            ],
            [
                'title' => 'Dana Liburan & Keluarga',
                'description' => 'Anggaran perjalanan dan akomodasi liburan akhir tahun bersama keluarga.',
                'category_tag' => 'Travel',
                'target_amount' => 15000000,
                'current_amount' => 6500000,
                'monthly_target' => 1500000,
                'deadline_date' => ($year + 1) . '-06-15',
                'icon' => 'plane',
            ],
        ];

        foreach ($defaults as $g) {
            $this->create(
                $userId,
                $g['title'],
                $g['description'],
                $g['category_tag'],
                $g['target_amount'],
                $g['current_amount'],
                $g['monthly_target'],
                $g['deadline_date'],
                $g['icon']
            );
        }
    }
}

