<?php

/**
 * Transaction Model (Section 16, 19 of AGENTS.md)
 * Encapsulates transaction queries, joins with accounts and categories, and authorization checks
 */
class Transaction
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get paginated transactions with filtering and search
     */
    public function getPaginated(
        int $userId,
        int $page = 1,
        int $perPage = 8,
        ?string $type = null,
        ?string $search = null
    ): array {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;

        $whereClause = "t.user_id = :user_id";
        $params = ['user_id' => $userId];

        // Type filter: income vs expense
        if ($type === 'income' || $type === 'revenue') {
            $whereClause .= " AND t.type = 'income'";
        } elseif ($type === 'expense') {
            $whereClause .= " AND t.type = 'expense'";
        }

        // Search filter: description, category name, or account name
        if (!empty($search)) {
            $whereClause .= " AND (t.description LIKE :search_desc OR c.name LIKE :search_cat OR a.name LIKE :search_acc)";
            $params['search_desc'] = '%' . $search . '%';
            $params['search_cat'] = '%' . $search . '%';
            $params['search_acc'] = '%' . $search . '%';
        }

        // Count total rows matching criteria
        $countSql = "
            SELECT COUNT(*)
            FROM transactions t
            INNER JOIN accounts a ON a.id = t.account_id
            INNER JOIN categories c ON c.id = t.category_id
            WHERE {$whereClause}
        ";
        $countStmt = $this->pdo->prepare($countSql);
        $countStmt->execute($params);
        $totalRows = (int)$countStmt->fetchColumn();
        $totalPages = (int)ceil($totalRows / $perPage);

        // Fetch records
        $sql = "
            SELECT 
                t.id,
                t.user_id,
                t.account_id,
                t.category_id,
                t.type,
                t.amount,
                t.description,
                t.transaction_date,
                t.created_at,
                t.updated_at,
                a.name AS account_name,
                a.type AS account_type,
                c.name AS category_name,
                c.icon AS category_icon
            FROM transactions t
            INNER JOIN accounts a ON a.id = t.account_id
            INNER JOIN categories c ON c.id = t.category_id
            WHERE {$whereClause}
            ORDER BY t.transaction_date DESC, t.id DESC
            LIMIT :offset, :limit
        ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(':' . $key, $val);
        }
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $records,
            'total' => $totalRows,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => max(1, $totalPages)
        ];
    }

    /**
     * Get overall financial statistics for the user (total income, total expense)
     */
    public function getSummary(int $userId): array
    {
        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) AS total_income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS total_expense,
                COUNT(*) AS total_count
            FROM transactions
            WHERE user_id = :user_id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total_income' => 0,
            'total_expense' => 0,
            'total_count' => 0
        ];
    }

    /**
     * Get single transaction by ID and user ID
     */
    public function getByIdAndUserId(int $id, int $userId): ?array
    {
        $sql = "
            SELECT 
                t.*,
                a.name AS account_name,
                c.name AS category_name,
                c.icon AS category_icon
            FROM transactions t
            INNER JOIN accounts a ON a.id = t.account_id
            INNER JOIN categories c ON c.id = t.category_id
            WHERE t.id = :id AND t.user_id = :user_id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create a new transaction
     */
    public function create(
        int $userId,
        int $accountId,
        int $categoryId,
        string $type,
        float $amount,
        ?string $description,
        string $transactionDate
    ): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO transactions (user_id, account_id, category_id, type, amount, description, transaction_date, created_at, updated_at)
            VALUES (:user_id, :account_id, :category_id, :type, :amount, :description, :transaction_date, NOW(), NOW())
        ");

        $stmt->execute([
            'user_id' => $userId,
            'account_id' => $accountId,
            'category_id' => $categoryId,
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'transaction_date' => $transactionDate
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update an existing transaction (scoped by user_id)
     */
    public function update(
        int $id,
        int $userId,
        int $accountId,
        int $categoryId,
        string $type,
        float $amount,
        ?string $description,
        string $transactionDate
    ): bool {
        $stmt = $this->pdo->prepare("
            UPDATE transactions
            SET account_id = :account_id,
                category_id = :category_id,
                type = :type,
                amount = :amount,
                description = :description,
                transaction_date = :transaction_date,
                updated_at = NOW()
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'account_id' => $accountId,
            'category_id' => $categoryId,
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'transaction_date' => $transactionDate,
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    /**
     * Delete a transaction (scoped by user_id)
     */
    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM transactions
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    /**
     * Get detailed expense analytics (current month, last month, % change, weekly average)
     */
    public function getExpenseAnalytics(int $userId): array
    {
        // Current month expenses
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(amount), 0) AS current_month, COUNT(*) AS count_month
            FROM transactions
            WHERE user_id = :user_id 
              AND type = 'expense'
              AND YEAR(transaction_date) = YEAR(CURRENT_DATE())
              AND MONTH(transaction_date) = MONTH(CURRENT_DATE())
        ");
        $stmt->execute(['user_id' => $userId]);
        $curr = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentMonthExpense = (float)($curr['current_month'] ?? 0);
        $currentMonthCount = (int)($curr['count_month'] ?? 0);

        // Previous month expenses
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(amount), 0) AS last_month
            FROM transactions
            WHERE user_id = :user_id 
              AND type = 'expense'
              AND transaction_date >= DATE_SUB(DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
              AND transaction_date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
        ");
        $stmt->execute(['user_id' => $userId]);
        $lastMonthExpense = (float)$stmt->fetchColumn();

        // Calculate percentage change
        $percentageChange = 0;
        $trend = 'same'; // 'up', 'down', 'same'
        if ($lastMonthExpense > 0) {
            $diff = $currentMonthExpense - $lastMonthExpense;
            $percentageChange = round(($diff / $lastMonthExpense) * 100, 1);
            $trend = ($diff > 0) ? 'up' : 'down';
        } elseif ($currentMonthExpense > 0) {
            $percentageChange = 100;
            $trend = 'up';
        }

        // Weekly average (current month divided by ~4.3 weeks or total expenses over weeks)
        $weeklyAverage = $currentMonthExpense > 0 ? round($currentMonthExpense / 4, 0) : 0;

        // Top spending category
        $stmt = $this->pdo->prepare("
            SELECT c.name, COALESCE(SUM(t.amount), 0) AS total
            FROM transactions t
            INNER JOIN categories c ON c.id = t.category_id
            WHERE t.user_id = :user_id AND t.type = 'expense'
            GROUP BY c.id, c.name
            ORDER BY total DESC
            LIMIT 1
        ");
        $stmt->execute(['user_id' => $userId]);
        $topCat = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['name' => 'Belum ada', 'total' => 0];

        return [
            'current_month' => $currentMonthExpense,
            'last_month' => $lastMonthExpense,
            'percentage_change' => abs($percentageChange),
            'trend' => $trend,
            'weekly_average' => (float)$weeklyAverage,
            'month_count' => $currentMonthCount,
            'top_category' => $topCat['name'],
            'top_category_amount' => (float)$topCat['total']
        ];
    }

    /**
     * Get expense breakdown aggregated by category
     */
    public function getExpensesByCategory(int $userId): array
    {
        // First get total expense across all categories
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(amount), 0)
            FROM transactions
            WHERE user_id = :user_id AND type = 'expense'
        ");
        $stmt->execute(['user_id' => $userId]);
        $totalExpense = (float)$stmt->fetchColumn();

        $stmt = $this->pdo->prepare("
            SELECT 
                c.id AS category_id,
                c.name AS category_name,
                c.icon AS category_icon,
                COUNT(t.id) AS transaction_count,
                COALESCE(SUM(t.amount), 0) AS total_amount
            FROM categories c
            INNER JOIN transactions t ON t.category_id = c.id AND t.user_id = c.user_id AND t.type = 'expense'
            WHERE c.user_id = :user_id
            GROUP BY c.id, c.name, c.icon
            ORDER BY total_amount DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        $palette = [
            '#2FA89D', // MoneyTrack Teal
            '#3B82F6', // Blue
            '#F59E0B', // Amber
            '#8B5CF6', // Purple
            '#EC4899', // Pink
            '#10B981', // Emerald
            '#6366F1', // Indigo
            '#E56B6F', // Danger Coral
        ];

        foreach ($rows as $i => $row) {
            $amount = (float)$row['total_amount'];
            $pct = $totalExpense > 0 ? round(($amount / $totalExpense) * 100, 1) : 0;
            $result[] = [
                'id' => $row['category_id'],
                'name' => $row['category_name'],
                'icon' => $row['category_icon'] ?: 'tag',
                'amount' => $amount,
                'percentage' => $pct,
                'count' => (int)$row['transaction_count'],
                'color' => $palette[$i % count($palette)]
            ];
        }

        return $result;
    }

    /**
     * Get monthly expense trend data for the comparison chart (past 6 months)
     */
    public function getMonthlyExpenseTrend(int $userId, int $months = 6): array
    {
        $monthsData = [];
        $monthNames = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
            '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
            '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        // Generate past N months in chronological order
        for ($i = $months - 1; $i >= 0; $i--) {
            $dt = new DateTime("first day of -{$i} month");
            $key = $dt->format('Y-m');
            $mNum = $dt->format('m');
            $monthsData[$key] = [
                'month_key' => $key,
                'month_label' => $monthNames[$mNum] ?? $dt->format('M'),
                'year' => $dt->format('Y'),
                'amount' => 0
            ];
        }

        $startDate = array_key_first($monthsData) . '-01';
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE_FORMAT(transaction_date, '%Y-%m') AS m_key,
                COALESCE(SUM(amount), 0) AS total_amount
            FROM transactions
            WHERE user_id = :user_id 
              AND type = 'expense'
              AND transaction_date >= :start_date
            GROUP BY m_key
            ORDER BY m_key ASC
        ");
        $stmt->execute([
            'user_id' => $userId,
            'start_date' => $startDate
        ]);
        $dbResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dbResults as $row) {
            $key = $row['m_key'];
            if (isset($monthsData[$key])) {
                $monthsData[$key]['amount'] = (float)$row['total_amount'];
            }
        }

        return array_values($monthsData);
    }
}
