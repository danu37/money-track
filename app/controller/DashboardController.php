<?php

/**
 * DashboardController
 * Handles rendering the main dashboard (Overview) and UI showcase with real MySQL data
 */
class DashboardController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $userId = (int)($_SESSION['user_id'] ?? 1);

        // Fetch User Info
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['id' => 1, 'name' => 'Danu Anggo', 'email' => 'danu@gmail.com'];

        // Fetch Accounts with real balances
        $stmt = $this->pdo->prepare("
            SELECT 
                a.id,
                a.name,
                a.type,
                a.initial_balance,
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
                ), 0) AS balance
            FROM accounts a
            WHERE a.user_id = :user_id
            ORDER BY a.id ASC
        ");
        $stmt->execute(['user_id' => $userId]);
        $dbAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $accounts = [];
        $totalBalance = 0.0;
        if (!empty($dbAccounts)) {
            $colors = [
                'from-[#1F2937] to-[#111827]',
                'from-[#0F3935] to-[#171717]',
                'from-[#2FA89D] to-[#1C6962]',
            ];
            foreach ($dbAccounts as $i => $acc) {
                $bal = (float)$acc['balance'];
                $totalBalance += $bal;
                $accounts[] = [
                    'id' => (int)$acc['id'],
                    'name' => $acc['name'],
                    'type' => $acc['type'],
                    'number' => '**** ' . str_pad(($acc['id'] * 1234 % 9000) + 1000, 4, '0', STR_PAD_LEFT),
                    'balance' => $bal,
                    'holder' => $user['name'],
                    'expires' => '12/28',
                    'color' => $colors[$i % count($colors)],
                    'badge' => strtoupper($acc['type']),
                ];
            }
        } else {
            $totalBalance = 0.0;
        }

        // Fetch Income/Expense summary
        require_once __DIR__ . '/../models/Transaction.php';
        $transactionModel = new Transaction($this->pdo);
        $txSummary = $transactionModel->getSummary($userId);

        // Fetch categories for quick transaction modal (seed defaults if empty)
        require_once __DIR__ . '/../models/Category.php';
        $categoryModel = new Category($this->pdo);
        $categories = $categoryModel->getAllByUserId($userId);
        if (empty($categories)) {
            $defaults = [
                ['name' => 'Salary', 'type' => 'income', 'icon' => 'briefcase'],
                ['name' => 'Freelance', 'type' => 'income', 'icon' => 'layout'],
                ['name' => 'Food & Dining', 'type' => 'expense', 'icon' => 'coffee'],
                ['name' => 'Transportation', 'type' => 'expense', 'icon' => 'car'],
                ['name' => 'Shopping', 'type' => 'expense', 'icon' => 'tag'],
                ['name' => 'Bills & Utilities', 'type' => 'expense', 'icon' => 'receipt'],
            ];
            foreach ($defaults as $cat) {
                $categoryModel->create($userId, $cat['name'], $cat['type'], $cat['icon']);
            }
            $categories = $categoryModel->getAllByUserId($userId);
        }

        // Fetch recent transactions
        $stmt = $this->pdo->prepare("
            SELECT 
                t.id,
                t.description AS title,
                c.name AS category,
                a.name AS account_name,
                t.type,
                t.amount,
                t.transaction_date,
                c.icon
            FROM transactions t
            LEFT JOIN categories c ON c.id = t.category_id
            LEFT JOIN accounts a ON a.id = t.account_id
            WHERE t.user_id = :user_id
            ORDER BY t.transaction_date DESC, t.id DESC
            LIMIT 8
        ");
        $stmt->execute(['user_id' => $userId]);
        $dbTx = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $transactions = [];
        foreach ($dbTx as $tx) {
            $transactions[] = [
                'id' => (int)$tx['id'],
                'title' => $tx['title'] ?: ($tx['category'] ?: 'Transaksi'),
                'category' => $tx['category'] ?: 'Umum',
                'account_name' => $tx['account_name'] ?: '',
                'type' => $tx['type'],
                'amount' => (float)$tx['amount'],
                'date' => formatIndonesianDate($tx['transaction_date']),
                'icon' => $tx['icon'] ?: 'tag',
                'icon_bg' => ($tx['type'] === 'income') ? 'bg-emerald-50 text-[#2FA89D]' : 'bg-[#F5F6F8] text-[#2B2B2B]',
            ];
        }

        // Fetch upcoming bills from database
        require_once __DIR__ . '/../models/Bill.php';
        $billModel = new Bill($this->pdo);
        $dbBills = $billModel->getAllByUserId($userId);
        $upcomingBills = [];
        $totalBillsAmount = 0.0;
        foreach ($dbBills as $b) {
            $amt = (float)$b['amount'];
            $totalBillsAmount += $amt;
            $ts = strtotime($b['billing_date']);
            $upcomingBills[] = [
                'day' => date('d', $ts),
                'month' => date('M', $ts),
                'name' => $b['service_name'],
                'type' => $b['billing_cycle'],
                'amount' => $amt,
                'icon_bg' => 'bg-[#2FA89D]/10 text-[#2FA89D]',
                'icon_type' => $b['logo'],
            ];
        }
        $totalBillsCount = count($upcomingBills);

        // Fetch financial goals summary from database
        require_once __DIR__ . '/../models/Goal.php';
        $goalModel = new Goal($this->pdo);
        $goalSummary = $goalModel->getSummary($userId);
        $primaryGoal = $goalModel->getPrimaryGoal($userId);

        if ($goalSummary['total_count'] > 0) {
            $targetAmount = $goalSummary['total_target'];
            $currentSaved = $goalSummary['total_saved'];
            $monthlyTarget = $goalSummary['total_monthly'];
            $targetMonth = $primaryGoal ? date('M, Y', strtotime($primaryGoal['deadline_date'])) : date('M, Y');
            $targetCaption = 'Akumulasi ' . $goalSummary['total_count'] . ' Target Keuangan';
        } else {
            $targetAmount = 0;
            $currentSaved = 0;
            $monthlyTarget = 0;
            $targetMonth = date('M, Y');
            $targetCaption = 'Belum ada target aktif';
        }

        // Real Monthly Expenses Statistics from database
        $stmtYear = $this->pdo->prepare("
            SELECT YEAR(MAX(transaction_date)) AS max_year, MONTH(MAX(transaction_date)) AS max_month
            FROM transactions
            WHERE user_id = :user_id
        ");
        $stmtYear->execute(['user_id' => $userId]);
        $latestRow = $stmtYear->fetch(PDO::FETCH_ASSOC);

        $statsYear = !empty($latestRow['max_year']) ? (int)$latestRow['max_year'] : (int)date('Y');
        $maxMonthInYear = !empty($latestRow['max_month']) ? max(6, (int)$latestRow['max_month']) : max(6, (int)date('n'));
        $maxMonthInYear = min(12, $maxMonthInYear);

        $stmtMonthly = $this->pdo->prepare("
            SELECT MONTH(transaction_date) AS m, COALESCE(SUM(amount), 0) AS total
            FROM transactions
            WHERE user_id = :user_id
              AND type = 'expense'
              AND YEAR(transaction_date) = :year
            GROUP BY MONTH(transaction_date)
        ");
        $stmtMonthly->execute([
            'user_id' => $userId,
            'year' => $statsYear,
        ]);
        $monthlyMap = [];
        foreach ($stmtMonthly->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $monthlyMap[(int)$row['m']] = (float)$row['total'];
        }

        $indoMonths = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $monthlyStats = [];
        for ($m = 1; $m <= $maxMonthInYear; $m++) {
            $monthlyStats[] = [
                'month' => $indoMonths[$m],
                'amount' => $monthlyMap[$m] ?? 0.0,
            ];
        }

        $activeNonZero = array_values(array_filter(array_column($monthlyStats, 'amount'), fn($v) => $v > 0));
        $lastEntry = end($monthlyStats) ?: ['month' => $indoMonths[(int)date('n')], 'amount' => 0];
        $prevEntry = count($monthlyStats) >= 2 ? $monthlyStats[count($monthlyStats) - 2] : null;

        $statsCurrentMonthLabel = $lastEntry['month'];
        $statsCurrentAmount = (float)$lastEntry['amount'];
        $statsAverageAmount = !empty($activeNonZero) ? (array_sum($activeNonZero) / count($activeNonZero)) : 0.0;

        $statsTrendText = 'Stabil';
        $statsTrendPositive = true;
        if ($prevEntry && $prevEntry['amount'] > 0) {
            $diffPct = round((($statsCurrentAmount - $prevEntry['amount']) / $prevEntry['amount']) * 100);
            if ($diffPct <= 0) {
                $statsTrendText = 'Hemat ' . abs($diffPct) . '%';
                $statsTrendPositive = true;
            } else {
                $statsTrendText = 'Naik ' . $diffPct . '%';
                $statsTrendPositive = false;
            }
        } elseif ($statsCurrentAmount > 0) {
            $statsTrendText = count($activeNonZero) . ' bln aktif';
        } else {
            $statsTrendText = 'Belum ada data';
        }

        // Notifications for Navbar Bell
        $notifications = [];
        foreach (array_slice($upcomingBills, 0, 3) as $billItem) {
            $notifications[] = [
                'title' => 'Tagihan ' . $billItem['name'],
                'subtitle' => $billItem['day'] . ' ' . $billItem['month'] . ' • ' . formatRupiah($billItem['amount']),
                'href' => base_url('bills'),
                'type' => 'bill',
            ];
        }
        if ($goalSummary['total_count'] > 0) {
            $notifications[] = [
                'title' => 'Progres Target Keuangan',
                'subtitle' => $goalSummary['overall_percentage'] . '% tercapai dari ' . $goalSummary['total_count'] . ' target',
                'href' => base_url('goals'),
                'type' => 'goal',
            ];
        }

        // Flash alerts for quick transaction creation on Overview
        $flashSuccess = $_SESSION['_flash_success'] ?? null;
        $flashError = $_SESSION['_flash_error'] ?? null;
        unset($_SESSION['_flash_success'], $_SESSION['_flash_error']);

        // Pass variables to view
        $currentRoute = 'overview';
        $userName = $user['name'];
        $userEmail = $user['email'];
        $pageTitle = 'Overview Dashboard';
        $contentView = __DIR__ . '/../../views/dashboard/index.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }

    public function showcase()
    {
        $userId = (int)($_SESSION['user_id'] ?? 1);
        $userName = $_SESSION['user_name'] ?? 'Danu Anggo';
        $currentRoute = 'ui-elements';
        $pageTitle = 'Katalog UI Elemen (One-by-One)';
        $contentView = __DIR__ . '/../../views/dashboard/ui_showcase.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }
}
