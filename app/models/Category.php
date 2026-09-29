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
}
