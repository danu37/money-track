<?php
/**
 * UI Element: Transaction History / Riwayat Transaksi (Section 12)
 */
$transactions = $transactions ?? [
    [
        'id' => 1,
        'title' => 'GTR 5',
        'category' => 'Gadget & Gear',
        'account_name' => 'BCA',
        'type' => 'expense',
        'amount' => 200000,
        'date' => '28 Sep 2026',
        'icon' => 'watch',
        'icon_bg' => 'bg-orange-50 text-orange-600'
    ],
    [
        'id' => 2,
        'title' => 'Gaji Pokok',
        'category' => 'Salary Bulanan',
        'account_name' => 'BCA',
        'type' => 'income',
        'amount' => 5000000,
        'date' => '27 Sep 2026',
        'icon' => 'briefcase',
        'icon_bg' => 'bg-emerald-50 text-[#2FA89D]'
    ],
    [
        'id' => 3,
        'title' => 'Polo Shirt',
        'category' => 'T-shirts',
        'account_name' => 'DANA',
        'type' => 'expense',
        'amount' => 100000,
        'date' => '26 Sep 2026',
        'icon' => 'tag',
        'icon_bg' => 'bg-purple-50 text-purple-600'
    ],
    [
        'id' => 4,
        'title' => 'Biryani',
        'category' => 'Halal Biryani',
        'account_name' => 'Cash',
        'type' => 'expense',
        'amount' => 300000,
        'date' => '25 Sep 2026',
        'icon' => 'coffee',
        'icon_bg' => 'bg-amber-50 text-amber-600'
    ],
    [
        'id' => 5,
        'title' => 'Freelance App Design',
        'category' => 'Side Project',
        'account_name' => 'BCA',
        'type' => 'income',
        'amount' => 1200000,
        'date' => '24 Sep 2026',
        'icon' => 'layout',
        'icon_bg' => 'bg-emerald-50 text-[#2FA89D]'
    ],
    [
        'id' => 6,
        'title' => 'Taxi Fare',
        'category' => 'Uber',
        'account_name' => 'DANA',
        'type' => 'expense',
        'amount' => 200000,
        'date' => '23 Sep 2026',
        'icon' => 'car',
        'icon_bg' => 'bg-blue-50 text-blue-600'
    ],
    [
        'id' => 7,
        'title' => 'Keyboard',
        'category' => 'Gadget & Gear',
        'account_name' => 'BCA',
        'type' => 'expense',
        'amount' => 500000,
        'date' => '22 Sep 2026',
        'icon' => 'cpu',
        'icon_bg' => 'bg-indigo-50 text-indigo-600'
    ]
];
?>

<!-- Transaction History Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between flex-1 h-full">
    
    <!-- Top Header & Tabs (Section 12: All, Revenue, Expenses) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div>
            <h3 class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                Aktivitas Finansial
            </h3>
            <span class="text-base font-bold text-[#2B2B2B]">Riwayat Transaksi</span>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center bg-[#F5F6F8] p-1 rounded-lg border border-[#E8EAED] text-xs self-start sm:self-auto">
            <button 
                type="button" 
                class="tx-tab-btn active px-3 py-1 rounded-md font-medium text-white bg-[#2FA89D] shadow-sm transition-all focus:outline-none"
                data-tab="all"
            >
                All
            </button>
            <button 
                type="button" 
                class="tx-tab-btn px-3 py-1 rounded-md font-medium text-[#8A8F98] hover:text-[#2B2B2B] transition-all focus:outline-none"
                data-tab="income"
            >
                Revenue
            </button>
            <button 
                type="button" 
                class="tx-tab-btn px-3 py-1 rounded-md font-medium text-[#8A8F98] hover:text-[#2B2B2B] transition-all focus:outline-none"
                data-tab="expense"
            >
                Expenses
            </button>
        </div>
    </div>

    <!-- Transaction List -->
    <?php if (empty($transactions)): ?>
        <div class="flex-1 flex flex-col items-center justify-center py-10 text-center">
            <div class="w-11 h-11 rounded-full bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center mb-3">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="17 1 21 5 17 9"></polyline>
                    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                    <polyline points="7 23 3 19 7 15"></polyline>
                    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                </svg>
            </div>
            <p class="text-xs font-semibold text-[#2B2B2B]">Belum Ada Transaksi</p>
            <p class="text-[11px] text-[#8A8F98] mt-0.5 mb-3">Mulai catat pemasukan atau pengeluaran pertama Anda.</p>
            <button
                type="button"
                onclick="if (typeof openQuickTxModal === 'function') { openQuickTxModal(); } else { window.location.href = '<?= base_url('transactions') ?>'; }"
                class="px-3.5 py-1.5 rounded-lg bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-medium transition-colors focus:outline-none"
            >
                + Catat Transaksi
            </button>
        </div>
    <?php else: ?>
        <div class="space-y-2 overflow-y-auto max-h-[360px] pr-1 flex-1" id="transactions-list">
            <?php foreach ($transactions as $idx => $tx): ?>
                <?php
                $txIcon = $tx['icon'] ?? 'tag';
                $txAccount = $tx['account_name'] ?? '';
                ?>
                <div 
                    class="tx-item flex items-center justify-between p-2.5 rounded-lg hover:bg-[#F5F6F8] transition-colors border border-transparent hover:border-[#E8EAED] mt-enter-soft"
                    style="--mt-delay: <?= 240 + (min($idx, 6) * 25) ?>ms;"
                    data-type="<?= e($tx['type']) ?>"
                    data-search="<?= e(strtolower(($tx['title'] ?? '') . ' ' . ($tx['category'] ?? '') . ' ' . $txAccount)) ?>"
                >
                    <!-- Left: Category Icon & Details -->
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg <?= $tx['icon_bg'] ?> flex items-center justify-center flex-shrink-0">
                            <?php if ($txIcon === 'watch' || $txIcon === 'cpu'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                                    <rect x="9" y="9" width="6" height="6"></rect>
                                </svg>
                            <?php elseif ($txIcon === 'briefcase'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            <?php elseif ($txIcon === 'building'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"></path>
                                </svg>
                            <?php elseif ($txIcon === 'coffee'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                    <line x1="6" y1="1" x2="6" y2="4"></line>
                                    <line x1="10" y1="1" x2="10" y2="4"></line>
                                    <line x1="14" y1="1" x2="14" y2="4"></line>
                                </svg>
                            <?php elseif ($txIcon === 'car'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"></path>
                                    <circle cx="7" cy="17" r="2"></circle>
                                    <path d="M9 17h6"></path>
                                    <circle cx="17" cy="17" r="2"></circle>
                                </svg>
                            <?php elseif ($txIcon === 'film'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                                    <line x1="7" y1="2" x2="7" y2="22"></line>
                                    <line x1="17" y1="2" x2="17" y2="22"></line>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                </svg>
                            <?php elseif ($txIcon === 'receipt'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16l3-2 3 2 3-2 3 2 3-2V4a2 2 0 0 0-2-2z"></path>
                                    <line x1="8" y1="9" x2="16" y2="9"></line>
                                    <line x1="8" y1="13" x2="14" y2="13"></line>
                                </svg>
                            <?php elseif ($txIcon === 'layout'): ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                    <polyline points="2 17 12 22 22 17"></polyline>
                                    <polyline points="2 12 12 17 22 12"></polyline>
                                </svg>
                            <?php else: ?>
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                            <?php endif; ?>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-[#2B2B2B] truncate">
                                <?= e($tx['title']) ?>
                            </p>
                            <p class="text-[11px] text-[#8A8F98] truncate">
                                <?= e($tx['category']) ?><?= $txAccount !== '' ? ' &bull; ' . e($txAccount) : '' ?>
                            </p>
                        </div>
                    </div>

                    <!-- Right: Amount & Date -->
                    <div class="text-right flex-shrink-0 pl-3">
                        <p class="text-xs font-bold tabular-nums <?= ($tx['type'] === 'income') ? 'text-[#2FA89D]' : 'text-[#2B2B2B]' ?>">
                            <?= ($tx['type'] === 'income' ? '+ ' : '- ') . formatShortRupiah($tx['amount']) ?>
                        </p>
                        <p class="text-[10px] text-[#8A8F98]">
                            <?= e($tx['date']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>

            <div id="tx-empty-state" class="hidden py-10 text-center text-xs text-[#8A8F98]">
                Tidak ada transaksi yang sesuai dengan filter atau pencarian saat ini.
            </div>
        </div>
    <?php endif; ?>

    <!-- Bottom link bar -->
    <div class="mt-4 pt-3 border-t border-[#E8EAED] flex items-center justify-between text-xs">
        <button
            type="button"
            onclick="if (typeof openQuickTxModal === 'function') { openQuickTxModal(); } else { window.location.href = '<?= base_url('transactions') ?>'; }"
            class="font-medium text-[#8A8F98] hover:text-[#2FA89D] transition-colors focus:outline-none"
        >
            + Catat Baru
        </button>
        <a href="<?= base_url('transactions') ?>" class="font-medium text-[#2FA89D] hover:underline">
            Lihat Semua Transaksi &rarr;
        </a>
    </div>

</div>
