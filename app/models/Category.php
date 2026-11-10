<?php

/**
 * Category Model (Section 16, 19 of AGENTS.md)
 */
class Category
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all categories for a specific user, optionally filtered by type (income/expense)
     */
    public function getAllByUserId(int $userId, ?string $type = null): array
    {
        $sql = "SELECT * FROM categories WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if ($type !== null && in_array($type, ['income', 'expense'])) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }

        $sql .= " ORDER BY name ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get category by ID and user ID
     */
    public function getByIdAndUserId(int $id, int $userId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    /**
     * Create a new category
     */
    public function create(int $userId, string $name, string $type, ?string $icon = null): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (user_id, name, type, icon, created_at, updated_at)
            VALUES (:user_id, :name, :type, :icon, NOW(), NOW())
        ");
        $stmt->execute([
            'user_id' => $userId,
            'name' => $name,
            'type' => $type,
            'icon' => $icon
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Get all categories with transaction count for a specific user
     */
    public function getAllWithCountByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                c.*,
                COUNT(t.id) AS transaction_count
            FROM categories c
            LEFT JOIN transactions t
                ON t.category_id = c.id AND t.user_id = c.user_id
            WHERE c.user_id = :user_id
            GROUP BY c.id
            ORDER BY c.type ASC, c.name ASC
        ");
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check if category name and type already exist for user
     */
    public function existsForUser(int $userId, string $name, string $type): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM categories
            WHERE user_id = :user_id AND LOWER(name) = LOWER(:name) AND type = :type
            LIMIT 1
        ");
        $stmt->execute([
            'user_id' => $userId,
            'name' => $name,
            'type' => $type,
        ]);

        return (bool)$stmt->fetch();
    }

    /**
     * Count transactions using a category for a user
     */
    public function countTransactions(int $id, int $userId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM transactions
            WHERE category_id = :category_id AND user_id = :user_id
        ");
        $stmt->execute([
            'category_id' => $id,
            'user_id' => $userId,
        ]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Delete category owned by user
     */
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM categories
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
    }
}
