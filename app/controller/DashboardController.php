<?php

/**
 * DashboardController
 * Handles rendering the main dashboard and UI showcase with database integration
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
        $userId = $_SESSION['user_id'] ?? 1;

        // Fetch User Info
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch() ?: ['id' => 1, 'name' => 'Danu Anggo', 'email' => 'danu@gmail.com'];

        // Fetch Total Balance from Database (Section 17: Initial Balance + Income - Expense)
        $stmt = $this->pdo->prepare("
            SELECT 
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
                ) AS total_balance
            FROM accounts a
            WHERE a.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $dbTotal = $stmt->fetchColumn();
        $totalBalance = ($dbTotal !== null && $dbTotal > 0) ? (float)$dbTotal : 100000000;

        // Fetch Accounts
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
        $dbAccounts = $stmt->fetchAll();

        $accounts = [];
        if (!empty($dbAccounts)) {
            $colors = [
                'from-[#1F2937] to-[#111827]',
                'from-[#0F3935] to-[#171717]',
                'from-[#2FA89D] to-[#1C6962]',
            ];
            foreach ($dbAccounts as $i => $acc) {
                $accounts[] = [
                    'id' => $acc['id'],
                    'name' => $acc['name'],
                    'type' => $acc['type'],
                    'number' => '**** ' . str_pad($acc['id'] * 1234 % 9000 + 1000, 4, '0', STR_PAD_LEFT),
                    'balance' => (float)$acc['balance'],
                    'holder' => $user['name'],
                    'expires' => '12/28',
                    'color' => $colors[$i % count($colors)],
                    'badge' => strtoupper($acc['type'])
                ];
            }
        } else {
            // Default sample accounts matching AGENTS.md
            $accounts = [
                [
                    'id' => 1,
                    'name' => 'Credit Card',
                    'type' => 'bank',
                    'number' => '**** 2598',
                    'balance' => 28500000,
                    'holder' => 'Danu Anggo',
                    'expires' => '08/28',
                    'color' => 'from-[#1F2937] to-[#111827]',
                    'badge' => 'Mastercard'
                ],
                [
                    'id' => 2,
                    'name' => 'BCA Account',
                    'type' => 'bank',
                    'number' => '**** 8841',
                    'balance' => 54200000,
                    'holder' => 'Danu Anggo',
                    'expires' => '12/27',
                    'color' => 'from-[#0F3935] to-[#171717]',
                    'badge' => 'BCA'
                ],
                [
                    'id' => 3,
                    'name' => 'Dompet Tunai',
                    'type' => 'cash',
                    'number' => 'Cash Wallet',
                    'balance' => 17300000,
                    'holder' => 'Danu Anggo',
                    'expires' => 'N/A',
                    'color' => 'from-[#2FA89D] to-[#1C6962]',
                    'badge' => 'Cash'
                ]
            ];
        }

        // Fetch recent transactions
        $stmt = $this->pdo->prepare("
            SELECT 
                t.id,
                t.description AS title,
                c.name AS category,
                t.type,
                t.amount,
                t.transaction_date,
                c.icon
            FROM transactions t
            LEFT JOIN categories c ON c.id = t.category_id
            WHERE t.user_id = :user_id
            ORDER BY t.transaction_date DESC, t.id DESC
            LIMIT 7
        ");
        $stmt->execute(['user_id' => $userId]);
        $dbTx = $stmt->fetchAll();

        $transactions = [];
        if (!empty($dbTx)) {
            foreach ($dbTx as $tx) {
                $transactions[] = [
                    'id' => $tx['id'],
                    'title' => $tx['title'] ?: $tx['category'],
                    'category' => $tx['category'],
                    'type' => $tx['type'],
                    'amount' => (float)$tx['amount'],
                    'date' => formatIndonesianDate($tx['transaction_date']),
                    'icon' => $tx['icon'] ?: 'tag',
                    'icon_bg' => ($tx['type'] === 'income') ? 'bg-emerald-50 text-[#2FA89D]' : 'bg-gray-100 text-[#2B2B2B]'
                ];
            }
        }

        // Fetch upcoming bills from database
        require_once __DIR__ . '/../models/Bill.php';
        $billModel = new Bill($this->pdo);
        $dbBills = $billModel->getAllByUserId($userId);
        $upcomingBills = [];
        if (!empty($dbBills)) {
            foreach ($dbBills as $b) {
                $ts = strtotime($b['billing_date']);
                $upcomingBills[] = [
                    'day' => date('d', $ts),
                    'month' => date('M', $ts),
                    'name' => $b['service_name'],
                    'type' => $b['billing_cycle'],
                    'amount' => (float)$b['amount'],
                    'icon_bg' => 'bg-[#2FA89D]/10 text-[#2FA89D]',
                    'icon_type' => $b['logo']
                ];
            }
        }

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
        $userId = $_SESSION['user_id'] ?? 1;
        $userName = 'Danu Anggo';
        $currentRoute = 'ui-elements';
        $pageTitle = 'Katalog UI Elemen (One-by-One)';
        $contentView = __DIR__ . '/../../views/dashboard/ui_showcase.php';

        require __DIR__ . '/../../views/layouts/app.php';
    }
}
