<?php
/**
 * UI Element: Transaction History / Riwayat Transaksi (Section 12)
 */
$transactions = $transactions ?? [
    [
        'id' => 1,
        'title' => 'GTR 5',
        'category' => 'Gadget & Gear',
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
        'type' => 'expense',
        'amount' => 500000,
        'date' => '22 Sep 2026',
        'icon' => 'cpu',
        'icon_bg' => 'bg-indigo-50 text-indigo-600'
    ]
];
?>

<!-- Transaction History Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
    
    <!-- Top Header & Tabs (Section 12: All, Revenue, Expenses) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h3 class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                Aktivitas Finansial
            </h3>
            <span class="text-base font-bold text-[#2B2B2B]">Riwayat Transaksi</span>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center bg-[#F5F6F8] p-1 rounded-lg border border-[#E8EAED] text-xs">
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

    <!-- Transaction List (Compact Items) -->
    <div class="space-y-2.5 overflow-y-auto max-h-[380px] pr-1" id="transactions-list">
        <?php foreach ($transactions as $tx): ?>
            <div 
                class="tx-item flex items-center justify-between p-2.5 rounded-lg hover:bg-[#F5F6F8] transition-colors border border-transparent hover:border-[#E8EAED]"
                data-type="<?= e($tx['type']) ?>"
            >
                <!-- Left: Category Icon & Details -->
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Icon -->
                    <div class="w-9 h-9 rounded-lg <?= $tx['icon_bg'] ?> flex items-center justify-center flex-shrink-0">
                        <?php if ($tx['icon'] === 'watch' || $tx['icon'] === 'cpu'): ?>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                                <rect x="9" y="9" width="6" height="6"></rect>
                            </svg>
                        <?php elseif ($tx['icon'] === 'briefcase'): ?>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        <?php elseif ($tx['icon'] === 'tag'): ?>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        <?php elseif ($tx['icon'] === 'coffee'): ?>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                <line x1="6" y1="1" x2="6" y2="4"></line>
                                <line x1="10" y1="1" x2="10" y2="4"></line>
                                <line x1="14" y1="1" x2="14" y2="4"></line>
                            </svg>
                        <?php elseif ($tx['icon'] === 'car'): ?>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"></path>
                                <circle cx="7" cy="17" r="2"></circle>
                                <path d="M9 17h6"></path>
                                <circle cx="17" cy="17" r="2"></circle>
                            </svg>
                        <?php else: ?>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                <polyline points="2 17 12 22 22 17"></polyline>
                                <polyline points="2 12 12 17 22 12"></polyline>
                            </svg>
                        <?php endif; ?>
                    </div>

                    <!-- Names -->
                    <div class="truncate">
                        <p class="text-xs font-semibold text-[#2B2B2B] truncate">
                            <?= e($tx['title']) ?>
                        </p>
                        <p class="text-[11px] text-[#8A8F98] truncate">
                            <?= e($tx['category']) ?>
                        </p>
                    </div>
                </div>

                <!-- Right: Amount & Date -->
                <div class="text-right flex-shrink-0 pl-3">
                    <p class="text-xs font-bold <?= ($tx['type'] === 'income') ? 'text-[#2FA89D]' : 'text-[#2B2B2B]' ?>">
                        <?= ($tx['type'] === 'income' ? '+ ' : '- ') . formatShortRupiah($tx['amount']) ?>
                    </p>
                    <p class="text-[10px] text-[#8A8F98]">
                        <?= e($tx['date']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Bottom link to full transactions -->
    <div class="mt-4 pt-3 border-t border-[#E8EAED] text-center">
        <a href="<?= base_url('transactions') ?>" class="text-xs font-medium text-[#2FA89D] hover:underline">
            Lihat Semua Transaksi &rarr;
        </a>
    </div>

</div>
