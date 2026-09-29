<!-- Main Dashboard View (Section 6) -->
<div class="space-y-6">

    <!-- Top Section: Overview Cards Grid (Total Saldo, Target, Upcoming Bills) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        <!-- 1. Total Saldo & Selected Account Card -->
        <?php include __DIR__ . '/components/total_saldo_card.php'; ?>

        <!-- 2. Target Card -->
        <?php include __DIR__ . '/components/target_card.php'; ?>

        <!-- 3. Upcoming Bills (Tagihan Mendatang) -->
        <?php include __DIR__ . '/components/upcoming_bills_card.php'; ?>

    </div>

    <!-- Bottom Section: Transactions History & Monthly Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- 4. Riwayat Transaksi (5 cols on lg) -->
        <div class="lg:col-span-5 flex flex-col">
            <?php include __DIR__ . '/components/transactions_card.php'; ?>
        </div>

        <!-- 5. Monthly Expenses Statistics Chart (7 cols on lg) -->
        <div class="lg:col-span-7 flex flex-col">
            <?php include __DIR__ . '/components/statistics_card.php'; ?>
        </div>

    </div>

</div>
