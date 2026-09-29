<?php

/**
 * Account Model (Section 16, 17, 19 of AGENTS.md)
 * Interacts with accounts table and calculates financial balances from transactions
 */
class Account
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all accounts for a specific user with real calculated balances
     * Real balance = initial_balance + income - expense
     */
    public function getAllByUserId(int $userId): array
    {
        $sql = "
            SELECT 
                a.id,
                a.user_id,
                a.name,
                a.type,
                a.initial_balance,
                a.created_at,
                a.updated_at,
                COALESCE(
                    SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END), 0
                ) AS total_income,
                COALESCE(
                    SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END), 0
                ) AS total_expense,
                COUNT(t.id) AS transaction_count,
                a.initial_balance + COALESCE(
                    SUM(
                        CASE 
                            WHEN t.type = 'income' THEN t.amount 
                            WHEN t.type = 'expense' THEN -t.amount 
                            ELSE 0 
                        END
                    ), 0
                ) AS current_balance
            FROM accounts a
            LEFT JOIN transactions t ON t.account_id = a.id
            WHERE a.user_id = :user_id
            GROUP BY 
                a.id,
                a.user_id,
                a.name,
                a.type,
                a.initial_balance,
                a.created_at,
                a.updated_at
            ORDER BY a.id ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single account by ID and user ID
     */
    public function getByIdAndUserId(int $id, int $userId): ?array
    {
        $sql = "
            SELECT 
                a.id,
                a.user_id,
                a.name,
                a.type,
                a.initial_balance,
                a.created_at,
                a.updated_at,
                COALESCE(
                    SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END), 0
                ) AS total_income,
                COALESCE(
                    SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END), 0
                ) AS total_expense,
                COUNT(t.id) AS transaction_count,
                a.initial_balance + COALESCE(
                    SUM(
                        CASE 
                            WHEN t.type = 'income' THEN t.amount 
                            WHEN t.type = 'expense' THEN -t.amount 
                            ELSE 0 
                        END
                    ), 0
                ) AS current_balance
            FROM accounts a
            LEFT JOIN transactions t ON t.account_id = a.id
            WHERE a.id = :id AND a.user_id = :user_id
            GROUP BY 
                a.id,
                a.user_id,
                a.name,
                a.type,
                a.initial_balance,
                a.created_at,
                a.updated_at
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Calculate total balance across all accounts for the user
     */
    public function getTotalBalance(int $userId): float
    {
        $sql = "
            SELECT 
                COALESCE(
                    SUM(
                        a.initial_balance + COALESCE((
                            SELECT SUM(
                                CASE 
                                    WHEN t.type = 'income' THEN t.amount 
                                    WHEN t.type = 'expense' THEN -t.amount 
                                    ELSE 0 
                                END
                            )
                            FROM transactions t 
                            WHERE t.account_id = a.id
                        ), 0)
                    ), 0
                ) AS total_balance
            FROM accounts a
            WHERE a.user_id = :user_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return (float) $stmt->fetchColumn();
    }

    /**
     * Create a new account
     */
    public function create(int $userId, string $name, string $type, float $initialBalance): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO accounts (user_id, name, type, initial_balance, created_at, updated_at)
            VALUES (:user_id, :name, :type, :initial_balance, NOW(), NOW())
        ");

        $stmt->execute([
            'user_id' => $userId,
            'name' => $name,
            'type' => $type,
            'initial_balance' => $initialBalance
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update an existing account (scoped by user_id)
     */
    public function update(int $id, int $userId, string $name, string $type, float $initialBalance): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE accounts 
            SET name = :name,
                type = :type,
                initial_balance = :initial_balance,
                updated_at = NOW()
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'name' => $name,
            'type' => $type,
            'initial_balance' => $initialBalance,
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    /**
     * Delete an account (scoped by user_id)
     */
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM accounts 
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    /**
     * Get recent transactions for a specific account
     */
    public function getTransactions(int $accountId, int $userId, int $limit = 10): array
    {
        $sql = "
            SELECT 
                t.id,
                t.amount,
                t.type,
                t.description,
                t.transaction_date,
                c.name AS category_name,
                c.icon AS category_icon
            FROM transactions t
            LEFT JOIN categories c ON c.id = t.category_id
            WHERE t.account_id = :account_id AND t.user_id = :user_id
            ORDER BY t.transaction_date DESC, t.id DESC
            LIMIT :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':account_id', $accountId, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
