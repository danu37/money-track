<?php

/**
 * User Model (Section 16, 18, 19 of AGENTS.md)
 */
class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Check whether email is already used by another user
     */
    public function emailExistsForOtherUser(string $email, int $excludeUserId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM users
            WHERE email = :email AND id != :exclude_id
            LIMIT 1
        ");
        $stmt->execute([
            'email' => $email,
            'exclude_id' => $excludeUserId,
        ]);

        return (bool)$stmt->fetch();
    }

    /**
     * Update user profile (name & email)
     */
    public function updateProfile(int $id, string $name, string $email): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET name = :name, email = :email, updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
        ]);
    }

    /**
     * Update user password hash
     */
    public function updatePassword(int $id, string $hashedPassword): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET password = :password, updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'password' => $hashedPassword,
        ]);
    }

    /**
     * Get real account/activity counts for a user
     */
    public function getStats(int $userId): array
    {
        $counts = [
            'accounts' => 0,
            'transactions' => 0,
            'categories' => 0,
            'bills' => 0,
        ];

        $queries = [
            'accounts' => "SELECT COUNT(*) FROM accounts WHERE user_id = :user_id",
            'transactions' => "SELECT COUNT(*) FROM transactions WHERE user_id = :user_id",
            'categories' => "SELECT COUNT(*) FROM categories WHERE user_id = :user_id",
            'bills' => "SELECT COUNT(*) FROM bills WHERE user_id = :user_id",
        ];

        foreach ($queries as $key => $sql) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $counts[$key] = (int)$stmt->fetchColumn();
        }

        return $counts;
    }
}

