-- =====================================================
-- DATABASE MONEYTRACK
-- Aplikasi Pencatatan Keuangan
-- =====================================================

CREATE DATABASE IF NOT EXISTS moneytrack;

USE moneytrack;


-- =====================================================
-- 1. TABLE USERS
-- =====================================================

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);


-- =====================================================
-- 2. TABLE ACCOUNTS
-- Tempat menyimpan uang:
-- Cash, Bank, E-Wallet, dll
-- =====================================================

CREATE TABLE IF NOT EXISTS accounts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(100) NOT NULL,

    type ENUM(
        'cash',
        'bank',
        'ewallet',
        'other'
    ) NOT NULL DEFAULT 'cash',

    initial_balance DECIMAL(15,2) NOT NULL DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_accounts_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


-- =====================================================
-- 3. TABLE CATEGORIES
-- Kategori pemasukan dan pengeluaran
-- =====================================================

CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(100) NOT NULL,

    type ENUM(
        'income',
        'expense'
    ) NOT NULL,

    icon VARCHAR(50) DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_categories_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);


-- =====================================================
-- 4. TABLE TRANSACTIONS
-- Semua transaksi pemasukan dan pengeluaran
-- =====================================================

CREATE TABLE IF NOT EXISTS transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    account_id BIGINT UNSIGNED NOT NULL,

    category_id BIGINT UNSIGNED NOT NULL,

    type ENUM(
        'income',
        'expense'
    ) NOT NULL,

    amount DECIMAL(15,2) NOT NULL,

    description TEXT DEFAULT NULL,

    transaction_date DATE NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_transactions_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_transactions_account
        FOREIGN KEY (account_id)
        REFERENCES accounts(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_transactions_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
);


-- =====================================================
-- 5. DATA USER
-- Password sementara: password
-- Password ini masih plaintext untuk data dummy.
-- Untuk aplikasi asli gunakan password_hash() dari PHP.
-- =====================================================

INSERT INTO users (
    name,
    email,
    password
) VALUES (
    'Danu',
    'danu@gmail.com',
    'password'
);


-- =====================================================
-- 6. DATA ACCOUNTS
-- =====================================================

INSERT INTO accounts (
    user_id,
    name,
    type,
    initial_balance
) VALUES
(
    1,
    'Cash',
    'cash',
    500000
),
(
    1,
    'BCA',
    'bank',
    2500000
),
(
    1,
    'DANA',
    'ewallet',
    300000
);


-- =====================================================
-- 7. DATA CATEGORIES
-- =====================================================

INSERT INTO categories (
    user_id,
    name,
    type,
    icon
) VALUES
(
    1,
    'Salary',
    'income',
    'wallet'
),
(
    1,
    'Freelance',
    'income',
    'briefcase'
),
(
    1,
    'Business',
    'income',
    'building'
),
(
    1,
    'Food',
    'expense',
    'utensils'
),
(
    1,
    'Transport',
    'expense',
    'car'
),
(
    1,
    'Education',
    'expense',
    'book'
),
(
    1,
    'Shopping',
    'expense',
    'shopping-bag'
),
(
    1,
    'Bills',
    'expense',
    'receipt'
),
(
    1,
    'Entertainment',
    'expense',
    'film'
);


-- =====================================================
-- 8. DATA TRANSACTIONS
-- =====================================================

INSERT INTO transactions (
    user_id,
    account_id,
    category_id,
    type,
    amount,
    description,
    transaction_date
) VALUES

-- Salary masuk ke BCA
(
    1,
    2,
    1,
    'income',
    5000000,
    'Gaji bulanan',
    '2026-09-01'
),

-- Freelance masuk ke BCA
(
    1,
    2,
    2,
    'income',
    750000,
    'Project freelance',
    '2026-09-05'
),

-- Makan menggunakan Cash
(
    1,
    1,
    4,
    'expense',
    25000,
    'Makan siang',
    '2026-09-06'
),

-- Transport menggunakan DANA
(
    1,
    3,
    5,
    'expense',
    15000,
    'Transportasi',
    '2026-09-07'
),

-- Belanja
(
    1,
    2,
    7,
    'expense',
    250000,
    'Belanja kebutuhan',
    '2026-09-10'
),

-- Pendidikan
(
    1,
    2,
    6,
    'expense',
    100000,
    'Beli buku',
    '2026-09-12'
),

-- Makan
(
    1,
    1,
    4,
    'expense',
    20000,
    'Makan malam',
    '2026-09-15'
),

-- Entertainment
(
    1,
    3,
    9,
    'expense',
    50000,
    'Hiburan',
    '2026-09-18'
),

-- Freelance
(
    1,
    2,
    2,
    'income',
    300000,
    'Project kecil',
    '2026-09-20'
),

-- Bills
(
    1,
    2,
    8,
    'expense',
    150000,
    'Bayar tagihan',
    '2026-09-22'
),

-- Transport
(
    1,
    3,
    5,
    'expense',
    20000,
    'Transportasi',
    '2026-09-24'
),

-- Makan
(
    1,
    1,
    4,
    'expense',
    30000,
    'Makan',
    '2026-09-25'
);


-- =====================================================
-- 9. CEK DATA
-- =====================================================

SELECT * FROM users;

SELECT * FROM accounts;

SELECT * FROM categories;

SELECT * FROM transactions;


-- =====================================================
-- 10. TOTAL INCOME
-- =====================================================

SELECT
    COALESCE(SUM(amount), 0) AS total_income
FROM transactions
WHERE user_id = 1
AND type = 'income';


-- =====================================================
-- 11. TOTAL EXPENSE
-- =====================================================

SELECT
    COALESCE(SUM(amount), 0) AS total_expense
FROM transactions
WHERE user_id = 1
AND type = 'expense';


-- =====================================================
-- 12. SALDO SETIAP ACCOUNT
-- =====================================================

SELECT
    a.id,
    a.name,
    a.type,
    a.initial_balance,

    a.initial_balance
    + COALESCE(
        SUM(
            CASE
                WHEN t.type = 'income'
                    THEN t.amount

                WHEN t.type = 'expense'
                    THEN -t.amount

                ELSE 0
            END
        ),
        0
    ) AS balance

FROM accounts a

LEFT JOIN transactions t
    ON t.account_id = a.id

WHERE a.user_id = 1

GROUP BY
    a.id,
    a.name,
    a.type,
    a.initial_balance;


-- =====================================================
-- 13. TOTAL BALANCE
-- =====================================================

SELECT
    SUM(
        a.initial_balance
        + COALESCE(
            (
                SELECT SUM(
                    CASE
                        WHEN t.type = 'income'
                            THEN t.amount

                        WHEN t.type = 'expense'
                            THEN -t.amount

                        ELSE 0
                    END
                )
                FROM transactions t
                WHERE t.account_id = a.id
            ),
            0
        )
    ) AS total_balance

FROM accounts a

WHERE a.user_id = 1;



-- =====================================================
-- 14. TABLE BILLS (TAGIHAN MENDATANG)
-- =====================================================

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
    CONSTRAINT fk_bills_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- Data Tagihan Awal (Section 11)
INSERT INTO bills (user_id, service_name, description, amount, billing_date, billing_cycle, logo) VALUES
(1, 'Figma', 'Langganan tool UI/UX Design tim & kolaborasi proyek', 900000, '2026-03-15', 'Bulanan', 'figma'),
(1, 'Adobe Creative Cloud', 'Paket semua aplikasi kreatif Illustrator, Photoshop & Premiere', 500000, '2026-06-16', 'Tahunan', 'adobe'),
(1, 'Spotify Family', 'Streaming musik bebas iklan untuk 6 anggota keluarga', 86000, '2026-06-22', 'Bulanan', 'spotify'),
(1, 'Internet & WiFi Fiber', 'Koneksi internet broadband rumah kecepatan hingga 100 Mbps', 375000, '2026-06-28', 'Bulanan', 'wifi');


-- =====================================================
-- 15. TABLE GOALS (TARGET KEUANGAN)
-- =====================================================

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
    CONSTRAINT fk_goals_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- Data Target Keuangan Awal (Section 10)
INSERT INTO goals (user_id, title, description, category_tag, target_amount, current_amount, monthly_target, deadline_date, icon, status) VALUES
(1, 'Dana Darurat 6 Bulan', 'Cadangan dana aman untuk kebutuhan pokok keluarga minimal 6 bulan ke depan.', 'Dana Darurat', 30000000, 22500000, 2500000, '2027-03-31', 'shield', 'active'),
(1, 'DP Rumah Pertama', 'Tabungan uang muka hunian dan biaya administrasi akad KPR.', 'Properti', 120000000, 78000000, 5000000, '2027-12-20', 'home', 'active'),
(1, 'Upgrade Laptop Kerja', 'Perangkat produktivitas spesifikasi tinggi untuk pekerjaan desain & pengembangan.', 'Teknologi', 24000000, 24000000, 2000000, '2026-11-10', 'laptop', 'completed'),
(1, 'Dana Liburan & Keluarga', 'Anggaran perjalanan dan akomodasi liburan akhir tahun bersama keluarga.', 'Travel', 15000000, 6500000, 1500000, '2027-06-15', 'plane', 'active');