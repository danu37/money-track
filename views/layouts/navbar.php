<?php
$userName = $userName ?? 'Danu';
$currentDate = $currentDate ?? date('d M Y');
$notifications = $notifications ?? [];
$searchAction = base_url('transactions');
?>

<!-- Dashboard Header / Navbar (Section 8) -->
<header class="h-16 bg-white border-b border-[#E8EAED] px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm mt-navbar-enter">
    
    <!-- Left: Hamburger toggle (mobile) + Greeting & Date -->
    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
        <!-- Mobile Sidebar Toggle -->
        <button id="sidebar-open-btn" type="button" class="lg:hidden p-1.5 rounded-md text-[#2B2B2B] hover:bg-gray-100 focus:outline-none" aria-label="Buka menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Greeting & Subtext -->
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <h1 class="text-base sm:text-lg font-semibold text-[#2B2B2B] truncate">
                    Hello <?= e(explode(' ', $userName)[0]) ?>
                </h1>
                <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-[#8A8F98]"></span>
                <span class="hidden sm:inline-block text-xs text-[#8A8F98]">
                    <?= e($currentDate) ?>
                </span>
            </div>
            <p class="sm:hidden text-[11px] text-[#8A8F98]">
                <?= e($currentDate) ?>
            </p>
        </div>
    </div>

    <!-- Right: Search Field + Notification Bell -->
    <div class="flex items-center gap-2.5 sm:gap-3">
        
        <!-- Interactive Search Field -->
        <form action="<?= e($searchAction) ?>" method="GET" class="relative hidden sm:block" id="navbar-search-form">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#8A8F98]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <input 
                type="text"
                name="search"
                id="global-search-input"
                placeholder="Cari transaksi / tagihan..."
                autocomplete="off"
                class="w-48 md:w-64 pl-9 pr-7 py-1.5 bg-[#F5F6F8] border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:bg-white focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-all"
            >
            <button
                type="button"
                id="global-search-clear"
                class="hidden absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none"
                aria-label="Bersihkan pencarian"
            >
                &times;
            </button>
        </form>

        <!-- Notification Bell Icon with Interactive Dropdown -->
        <div class="relative" id="notification-wrapper">
            <button
                id="notification-btn"
                type="button"
                aria-expanded="false"
                aria-label="Notifikasi"
                class="w-9 h-9 rounded-lg border border-[#E8EAED] bg-white flex items-center justify-center text-[#2B2B2B] hover:bg-[#F5F6F8] hover:border-[#2FA89D]/40 focus:outline-none transition-colors relative"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <?php if (!empty($notifications)): ?>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-[#2FA89D] rounded-full ring-2 ring-white"></span>
                <?php endif; ?>
            </button>

            <!-- Dropdown Panel -->
            <div
                id="notification-dropdown"
                class="hidden absolute right-0 mt-2 w-72 sm:w-80 bg-white border border-[#E8EAED] rounded-xl shadow-lg overflow-hidden z-50 animate-in"
            >
                <div class="px-4 py-3 border-b border-[#E8EAED] bg-[#F5F6F8] flex items-center justify-between">
                    <span class="text-xs font-bold text-[#2B2B2B]">Pengingat & Info</span>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                        <?= count($notifications) ?> baru
                    </span>
                </div>

                <?php if (empty($notifications)): ?>
                    <div class="p-5 text-center text-xs text-[#8A8F98]">
                        Tidak ada pengingat tagihan saat ini.
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-[#E8EAED] max-h-72 overflow-y-auto">
                        <?php foreach ($notifications as $notif): ?>
                            <a href="<?= e($notif['href']) ?>" class="flex items-start gap-3 px-4 py-3 hover:bg-[#F5F6F8] transition-colors">
                                <div class="w-7 h-7 rounded-lg <?= ($notif['type'] ?? '') === 'goal' ? 'bg-emerald-50 text-[#2FA89D]' : 'bg-[#F0F9F8] text-[#2FA89D]' ?> flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <?php if (($notif['type'] ?? '') === 'goal'): ?>
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <circle cx="12" cy="12" r="4"></circle>
                                        </svg>
                                    <?php else: ?>
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-[#2B2B2B] truncate"><?= e($notif['title']) ?></p>
                                    <p class="text-[11px] text-[#8A8F98] truncate"><?= e($notif['subtitle']) ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="px-4 py-2.5 border-t border-[#E8EAED] bg-[#F5F6F8] text-center">
                    <a href="<?= base_url('bills') ?>" class="text-[11px] font-semibold text-[#2FA89D] hover:underline">
                        Kelola Semua Tagihan &rarr;
                    </a>
                </div>
            </div>
        </div>

    </div>

</header>
