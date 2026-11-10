<?php
$currentRoute = $currentRoute ?? 'overview';
$userName = $userName ?? 'Danu Anggo';
$userEmail = $userEmail ?? 'danu@gmail.com';
?>

<!-- Dark Vertical Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-[240px] bg-[#171717] text-white flex flex-col transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 border-r border-[#242424] mt-sidebar-enter">
    
    <!-- Top Branding / Logo -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-[#242424]">
        <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-lg bg-[#2FA89D] flex items-center justify-center text-white shadow-sm transition-transform group-hover:scale-105">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <span class="text-lg font-bold tracking-tight text-white">
                MoneyTrack
            </span>
        </a>

        <!-- Mobile close button -->
        <button id="sidebar-close-btn" class="lg:hidden text-gray-400 hover:text-white p-1 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu (Section 7) -->
    <div class="flex-1 py-5 px-3 overflow-y-auto space-y-1">
        
        <!-- Overview (Dashboard) -->
        <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'overview') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <span>Overview</span>
        </a>

        <!-- Saldo (Accounts) -->
        <a href="<?= base_url('accounts') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'saldo') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            <span>Saldo</span>
        </a>

        <!-- Transaksi -->
        <a href="<?= base_url('transactions') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'transaksi') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="17 1 21 5 17 9"></polyline>
                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                <polyline points="7 23 3 19 7 15"></polyline>
                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
            </svg>
            <span>Transaksi</span>
        </a>

        <!-- Tagihan (Bills) -->
        <a href="<?= base_url('bills') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'tagihan') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span>Tagihan</span>
        </a>

        <!-- Pengeluaran (Expenses) -->
        <a href="<?= base_url('expenses') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'pengeluaran') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="19" x2="12" y2="5"></line>
                <polyline points="5 12 12 5 19 12"></polyline>
            </svg>
            <span>Pengeluaran</span>
        </a>

        <!-- Target Keuangan (Goals) -->
        <a href="<?= base_url('goals') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'target') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <circle cx="12" cy="12" r="6"></circle>
                <circle cx="12" cy="12" r="2"></circle>
            </svg>
            <span>Target Keuangan</span>
        </a>

        <!-- Pengaturan (Settings) -->
        <a href="<?= base_url('settings') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'pengaturan') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-300 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
            <span>Pengaturan</span>
        </a>

        <!-- UI Elements Showcase link for easy testing -->
        <a href="<?= base_url('ui-elements') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors <?= ($currentRoute === 'ui-elements') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-gray-400 hover:bg-[#222222] hover:text-white' ?>">
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                <polyline points="2 17 12 22 22 17"></polyline>
                <polyline points="2 12 12 17 22 12"></polyline>
            </svg>
            <span class="flex items-center justify-between w-full">
                UI Showcase
                <span class="text-[10px] px-1.5 py-0.5 rounded bg-[#2FA89D]/20 text-[#2FA89D] uppercase tracking-wider font-semibold">One-by-one</span>
            </span>
        </a>

    </div>

    <!-- Bottom Section: Logout & User Profile (Section 7) -->
    <div class="p-3 border-t border-[#242424] space-y-2">
        <!-- Logout Button -->
        <form action="<?= base_url('logout') ?>" method="POST" class="w-full">
            <?= csrf_field() ?>
            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-lg text-sm font-medium text-gray-400 hover:text-[#E56B6F] hover:bg-[#222222] transition-colors focus:outline-none">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout</span>
            </button>
        </form>

        <!-- User Profile Card -->
        <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-[#1F1F1F]">
            <!-- User Avatar -->
            <div class="w-9 h-9 rounded-full bg-[#2FA89D]/20 border border-[#2FA89D]/40 flex items-center justify-center text-[#2FA89D] font-bold text-sm flex-shrink-0">
                <?= strtoupper(substr($userName, 0, 1)) ?>
            </div>
            <!-- Name & View Profile -->
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">
                    <?= e($userName) ?>
                </p>
                <a href="<?= base_url('profile') ?>" class="text-[11px] text-[#8A8F98] hover:text-[#2FA89D] transition-colors block">
                    View profile
                </a>
            </div>
        </div>
    </div>

</aside>
