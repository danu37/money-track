<?php

/**
 * Bill Model (Section 11, 16, 19 of AGENTS.md)
 * Encapsulates Tagihan database operations, user scoping, and validation
 */
class Bill
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->ensureTableExists();
    }

    /**
     * Ensure bills table exists in the database
     */
    private function ensureTableExists(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS bills (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                service_name VARCHAR(100) NOT NULL,
                description VARCHAR(255) DEFAULT NULL,
                amount DECIMAL(15,2) NOT NULL,
                billing_date DATE NOT NULL,
                billing_cycle ENUM('Bulanan', 'Tahunan', 'Mingguan', 'Sekali') NOT NULL DEFAULT 'Bulanan',
                logo VARCHAR(50) DEFAULT 'default',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_bills_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $this->pdo->exec($sql);
    }

    /**
     * Get all bills for user, with optional search query
     */
    public function getAllByUserId(int $userId, ?string $search = null): array
    {
        $sql = "
            SELECT *
            FROM bills
            WHERE user_id = :user_id
        ";
        $params = ['user_id' => $userId];

        if (!empty($search)) {
            $sql .= " AND (service_name LIKE :search_name OR description LIKE :search_desc)";
            $params['search_name'] = '%' . $search . '%';
            $params['search_desc'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY billing_date ASC, id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get summary statistics of user bills
     */
    public function getSummary(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) AS total_count,
                COALESCE(SUM(amount), 0) AS total_amount,
                COALESCE(SUM(CASE WHEN billing_cycle = 'Bulanan' THEN amount ELSE 0 END), 0) AS monthly_amount,
                COALESCE(SUM(CASE WHEN billing_cycle = 'Tahunan' THEN amount ELSE 0 END), 0) AS yearly_amount
            FROM bills
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total_count' => 0,
            'total_amount' => 0,
            'monthly_amount' => 0,
            'yearly_amount' => 0
        ];
    }

    /**
     * Get single bill by ID and user ID
     */
    public function getByIdAndUserId(int $id, int $userId): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM bills
            WHERE id = :id AND user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    /**
     * Create a new bill
     */
    public function create(
        int $userId,
        string $serviceName,
        ?string $description,
        float $amount,
        string $billingDate,
        string $billingCycle = 'Bulanan',
        ?string $logo = 'default'
    ): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO bills (user_id, service_name, description, amount, billing_date, billing_cycle, logo, created_at, updated_at)
            VALUES (:user_id, :service_name, :description, :amount, :billing_date, :billing_cycle, :logo, NOW(), NOW())
        ");
        $stmt->execute([
            'user_id' => $userId,
            'service_name' => $serviceName,
            'description' => $description,
            'amount' => $amount,
            'billing_date' => $billingDate,
            'billing_cycle' => $billingCycle,
            'logo' => $logo ?: 'default'
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update an existing bill (scoped by user_id)
     */
    public function update(
        int $id,
        int $userId,
        string $serviceName,
        ?string $description,
        float $amount,
        string $billingDate,
        string $billingCycle = 'Bulanan',
        ?string $logo = 'default'
    ): bool {
        $stmt = $this->pdo->prepare("
            UPDATE bills
            SET service_name = :service_name,
                description = :description,
                amount = :amount,
                billing_date = :billing_date,
                billing_cycle = :billing_cycle,
                logo = :logo,
                updated_at = NOW()
            WHERE id = :id AND user_id = :user_id
        ");
        return $stmt->execute([
            'service_name' => $serviceName,
            'description' => $description,
            'amount' => $amount,
            'billing_date' => $billingDate,
            'billing_cycle' => $billingCycle,
            'logo' => $logo ?: 'default',
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    /**
     * Delete bill (scoped by user_id)
     */
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM bills
            WHERE id = :id AND user_id = :user_id
        ");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    /**
     * Seed initial sample bills for a user if empty
     * Matches Section 11 of AGENTS.md (Figma 900k, Adobe 500k, Spotify 86k, WiFi 375k)
     */
    public function seedDefaultBills(int $userId): void
    {
        $defaults = [
            [
                'service_name' => 'Figma',
                'description' => 'Langganan tool UI/UX Design tim & kolaborasi proyek',
                'amount' => 900000,
                'billing_date' => date('Y') . '-03-15',
                'billing_cycle' => 'Bulanan',
                'logo' => 'figma'
            ],
            [
                'service_name' => 'Adobe Creative Cloud',
                'description' => 'Paket semua aplikasi kreatif Illustrator, Photoshop & Premiere',
                'amount' => 500000,
                'billing_date' => date('Y') . '-06-16',
                'billing_cycle' => 'Tahunan',
                'logo' => 'adobe'
            ],
            [
                'service_name' => 'Spotify Family',
                'description' => 'Streaming musik bebas iklan untuk 6 anggota keluarga',
                'amount' => 86000,
                'billing_date' => date('Y') . '-06-22',
                'billing_cycle' => 'Bulanan',
                'logo' => 'spotify'
            ],
            [
                'service_name' => 'Internet & WiFi Fiber',
                'description' => 'Koneksi internet broadband rumah kecepatan hingga 100 Mbps',
                'amount' => 375000,
                'billing_date' => date('Y') . '-06-28',
                'billing_cycle' => 'Bulanan',
                'logo' => 'wifi'
            ],
        ];

        foreach ($defaults as $b) {
            $this->create(
                $userId,
                $b['service_name'],
                $b['description'],
                $b['amount'],
                $b['billing_date'],
                $b['billing_cycle'],
                $b['logo']
            );
        }
    }
}
