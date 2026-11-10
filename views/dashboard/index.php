<?php
$txSummary = $txSummary ?? ['total_income' => 0, 'total_expense' => 0];
$totalIncome = (float)($txSummary['total_income'] ?? 0);
$totalExpense = (float)($txSummary['total_expense'] ?? 0);
$netCashflow = $totalIncome - $totalExpense;
$modalAccounts = $accounts ?? [];
$modalCategories = $categories ?? [];
$flashSuccess = $flashSuccess ?? null;
$flashError = $flashError ?? null;
?>

<!-- Main Dashboard View (Section 6) -->
<div class="space-y-6 mt-dashboard-page">

    <!-- Flash Feedback Alerts -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="bg-[#F0F9F8] border border-[#D6F0ED] text-[#1F7A71] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between gap-3 shadow-sm mt-enter">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#2FA89D] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span><?= e($flashSuccess) ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#2FA89D] hover:text-[#1F7A71] text-base leading-none focus:outline-none">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="bg-red-50 border border-red-200 text-[#E56B6F] px-4 py-3 rounded-xl text-xs font-medium flex items-center justify-between gap-3 shadow-sm mt-enter">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#E56B6F] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><?= e($flashError) ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#E56B6F] hover:text-red-700 text-base leading-none focus:outline-none">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Compact Cashflow Summary & Quick Action Bar -->
    <div class="bg-white border border-[#E8EAED] rounded-xl px-4 py-3.5 sm:px-5 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4 mt-enter">
        
        <!-- 3 Compact Cashflow Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6 divide-y sm:divide-y-0 sm:divide-x divide-[#E8EAED]">
            
            <!-- Total Pemasukan -->
            <div class="flex items-center gap-3 pt-1 sm:pt-0">
                <div class="w-9 h-9 rounded-lg bg-[#F0F9F8] text-[#2FA89D] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-medium text-[#8A8F98] block">Total Pemasukan</span>
                    <span class="text-sm font-bold text-[#2FA89D] tabular-nums truncate block"><?= formatRupiah($totalIncome) ?></span>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="flex items-center gap-3 pt-2.5 sm:pt-0 sm:pl-6">
                <div class="w-9 h-9 rounded-lg bg-[#FEF2F2] text-[#E56B6F] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-medium text-[#8A8F98] block">Total Pengeluaran</span>
                    <span class="text-sm font-bold text-[#2B2B2B] tabular-nums truncate block"><?= formatRupiah($totalExpense) ?></span>
                </div>
            </div>

            <!-- Arus Kas Bersih -->
            <div class="flex items-center gap-3 pt-2.5 sm:pt-0 sm:pl-6">
                <div class="w-9 h-9 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-medium text-[#8A8F98] block">Arus Kas Bersih</span>
                    <span class="text-sm font-bold <?= $netCashflow >= 0 ? 'text-[#2FA89D]' : 'text-[#E56B6F]' ?> tabular-nums truncate block">
                        <?= ($netCashflow >= 0 ? '+ ' : '- ') . formatRupiah(abs($netCashflow)) ?>
                    </span>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap items-center gap-2 pt-2 lg:pt-0 border-t lg:border-t-0 border-[#E8EAED]">
            <a href="<?= base_url('accounts') ?>" class="px-3 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] border border-[#E8EAED] rounded-lg transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#8A8F98]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
                <span>Kelola Saldo</span>
            </a>

            <a href="<?= base_url('settings/export') ?>" class="hidden sm:inline-flex px-3 py-2 text-xs font-medium text-[#2B2B2B] bg-white hover:bg-[#F5F6F8] border border-[#E8EAED] rounded-lg transition-colors items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#8A8F98]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Ekspor CSV</span>
            </a>

            <button
                type="button"
                onclick="openQuickTxModal()"
                class="flex-1 sm:flex-none px-3.5 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors inline-flex items-center justify-center gap-1.5 focus:outline-none"
            >
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Catat Transaksi</span>
            </button>
        </div>

    </div>

    <!-- Top Section: Overview Cards Grid (Total Saldo, Target, Upcoming Bills) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mt-dashboard-top items-stretch">
        
        <!-- 1. Total Saldo & Selected Account Card -->
        <?php include __DIR__ . '/components/total_saldo_card.php'; ?>

        <!-- 2. Target Card -->
        <?php include __DIR__ . '/components/target_card.php'; ?>

        <!-- 3. Upcoming Bills (spans full width cleanly on md tablet, 1 col on xl desktop) -->
        <div class="md:col-span-2 xl:col-span-1 flex flex-col">
            <?php include __DIR__ . '/components/upcoming_bills_card.php'; ?>
        </div>

    </div>

    <!-- Bottom Section: Transactions History & Monthly Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-dashboard-bottom items-stretch">
        
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

<!-- ===================================================== -->
<!-- QUICK ADD TRANSACTION MODAL (Placed outside animated container) -->
<!-- ===================================================== -->
<div id="quick-tx-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#2B2B2B]">Catat Transaksi Cepat</h3>
                    <p class="text-[11px] text-[#8A8F98]">Saldo & grafik overview diperbarui otomatis</p>
                </div>
            </div>
            <button type="button" onclick="closeQuickTxModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold leading-none focus:outline-none">&times;</button>
        </div>

        <?php if (empty($modalAccounts)): ?>
            <div class="p-6 text-center space-y-3">
                <div class="w-11 h-11 rounded-full bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center mx-auto">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                </div>
                <p class="text-xs font-semibold text-[#2B2B2B]">Rekening Belum Tersedia</p>
                <p class="text-xs text-[#8A8F98]">Tambahkan minimal satu rekening saldo sebelum mencatat transaksi.</p>
                <div class="pt-2 flex items-center justify-center gap-2">
                    <button type="button" onclick="closeQuickTxModal()" class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] rounded-lg">Tutup</button>
                    <a href="<?= base_url('accounts') ?>" class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg">Buat Rekening</a>
                </div>
            </div>
        <?php else: ?>
            <form action="<?= base_url('transactions') ?>" method="POST" class="p-6 space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect_to" value="dashboard">
                <input type="hidden" name="type" id="quick-tx-type" value="expense">

                <!-- Segmented Type Switcher -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Jenis Transaksi</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            type="button"
                            id="quick-type-btn-expense"
                            onclick="setQuickTxType('expense')"
                            class="flex items-center justify-center gap-2 p-2.5 border rounded-lg text-xs font-semibold transition-all border-[#2FA89D] bg-[#F0F9F8] text-[#2FA89D] focus:outline-none"
                        >
                            <svg class="w-4 h-4 text-[#E56B6F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                <polyline points="17 18 23 18 23 12"></polyline>
                            </svg>
                            <span>Pengeluaran</span>
                        </button>
                        <button
                            type="button"
                            id="quick-type-btn-income"
                            onclick="setQuickTxType('income')"
                            class="flex items-center justify-center gap-2 p-2.5 border rounded-lg text-xs font-semibold transition-all border-[#E8EAED] bg-white text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none"
                        >
                            <svg class="w-4 h-4 text-[#2FA89D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                <polyline points="17 6 23 6 23 12"></polyline>
                            </svg>
                            <span>Pemasukan</span>
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="quick-tx-desc" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Keterangan Transaksi <span class="text-[#E56B6F]">*</span></label>
                    <input
                        type="text"
                        id="quick-tx-desc"
                        name="description"
                        required
                        placeholder="Contoh: Makan Siang, Kopi, Gaji Bulanan"
                        class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                </div>

                <!-- Amount & Date -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="quick-tx-amount" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nominal (Rp) <span class="text-[#E56B6F]">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-[#8A8F98]">Rp</span>
                            <input
                                type="number"
                                id="quick-tx-amount"
                                name="amount"
                                required
                                min="1"
                                step="1"
                                placeholder="50000"
                                class="w-full pl-9 pr-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] tabular-nums focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="quick-tx-date" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tanggal <span class="text-[#E56B6F]">*</span></label>
                        <input
                            type="date"
                            id="quick-tx-date"
                            name="transaction_date"
                            required
                            value="<?= date('Y-m-d') ?>"
                            class="w-full px-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                        >
                    </div>
                </div>

                <!-- Account -->
                <div>
                    <label for="quick-tx-account" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Rekening <span class="text-[#E56B6F]">*</span></label>
                    <select
                        id="quick-tx-account"
                        name="account_id"
                        required
                        class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                        <?php foreach ($modalAccounts as $acc): ?>
                            <option value="<?= (int)$acc['id'] ?>">
                                <?= e($acc['name']) ?> (<?= formatRupiah((float)$acc['balance']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label for="quick-tx-category" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Kategori <span class="text-[#E56B6F]">*</span></label>
                    <select
                        id="quick-tx-category"
                        name="category_id"
                        required
                        class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                        <?php foreach ($modalCategories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" data-type="<?= e($cat['type']) ?>">
                                <?= e($cat['name']) ?> (<?= $cat['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Footer -->
                <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                    <button
                        type="button"
                        onclick="closeQuickTxModal()"
                        class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                    >
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        <?php endif; ?>

    </div>
</div>
