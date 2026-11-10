<?php
/**
 * Pengaturan & Profil Page (Phase 8 of AGENTS.md)
 */

$activeTab = $activeTab ?? 'profile';
$userFirstName = explode(' ', trim($user['name'] ?? 'Danu'))[0] ?: 'Danu';
$userInitial = strtoupper(substr(trim($user['name'] ?? 'D'), 0, 1));
$joinedDate = !empty($user['created_at']) ? formatIndonesianDate($user['created_at']) : '-';
$updatedDate = !empty($user['updated_at']) ? formatIndonesianDate($user['updated_at']) : '-';

$iconOptions = [
    'wallet' => 'Dompet',
    'briefcase' => 'Kerja',
    'building' => 'Bisnis',
    'utensils' => 'Makan',
    'car' => 'Transport',
    'book' => 'Edukasi',
    'shopping-bag' => 'Belanja',
    'receipt' => 'Tagihan',
    'film' => 'Hiburan',
    'tag' => 'Lainnya',
];

function getSettingsCategoryIconSvg(string $icon): string
{
    switch ($icon) {
        case 'wallet':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path></svg>';
        case 'briefcase':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>';
        case 'building':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"></path></svg>';
        case 'utensils':
        case 'coffee':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>';
        case 'car':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>';
        case 'book':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>';
        case 'shopping-bag':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
        case 'receipt':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>';
        case 'film':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line></svg>';
        default:
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>';
    }
}
?>

<div class="space-y-6">

    <!-- Page Title & Quick Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                    Pengaturan Akun
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#2B2B2B] tracking-tight">
                Pengaturan & Profil
            </h1>
            <p class="text-xs text-[#8A8F98]">
                Kelola identitas akun, keamanan kata sandi, kategori transaksi, dan preferensi MoneyTrack Anda.
            </p>
        </div>

        <a
            href="<?= base_url('dashboard') ?>"
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-[#F5F6F8] text-[#2B2B2B] border border-[#E8EAED] text-xs font-semibold rounded-lg shadow-sm transition-colors"
        >
            <svg class="w-4 h-4 text-[#8A8F98]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            <span>Kembali ke Overview</span>
        </a>
    </div>

    <!-- Flash Alerts -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="p-3.5 rounded-lg bg-teal-50 border border-teal-200 text-xs font-medium text-[#2FA89D] flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span><?= e($flashSuccess) ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#2FA89D] hover:opacity-75 focus:outline-none">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="p-3.5 rounded-lg bg-red-50 border border-red-200 text-xs font-medium text-[#E56B6F] flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><?= e($flashError) ?></span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#E56B6F] hover:opacity-75 focus:outline-none">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Main Settings 2-Column Workspace -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Profile Spotlight & Vertical Settings Menu -->
        <div class="lg:col-span-4 space-y-5">

            <!-- Profile Spotlight Card -->
            <div class="bg-white border border-[#E8EAED] rounded-xl shadow-sm overflow-hidden">
                <!-- Sleek Charcoal Top Strip matching Sidebar / Account Card -->
                <div class="h-20 bg-gradient-to-br from-[#171717] via-[#1F2423] to-[#0F3935] relative overflow-hidden px-5 flex items-center justify-end">
                    <div class="absolute -right-5 -bottom-6 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
                    <div class="absolute left-6 -top-8 w-24 h-24 rounded-full bg-[#2FA89D]/10 pointer-events-none"></div>
                    <span class="relative z-10 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-white/10 text-teal-300 border border-white/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                        Akun Aktif
                    </span>
                </div>

                <!-- Avatar + User Info -->
                <div class="px-5 pb-5">
                    <div class="-mt-8 mb-3 flex items-end justify-between relative z-10">
                        <div
                            id="profile-card-avatar"
                            class="w-16 h-16 rounded-full bg-[#2FA89D] text-white font-bold text-xl flex items-center justify-center ring-4 ring-white shadow-sm"
                        >
                            <?= e($userInitial) ?>
                        </div>
                        <span class="text-[11px] text-[#8A8F98]">
                            Sejak <?= e($joinedDate) ?>
                        </span>
                    </div>

                    <div class="mb-4">
                        <h2 id="profile-card-name" class="text-base font-bold text-[#2B2B2B] truncate">
                            <?= e($user['name']) ?>
                        </h2>
                        <p id="profile-card-email" class="text-xs text-[#8A8F98] truncate">
                            <?= e($user['email']) ?>
                        </p>
                    </div>

                    <!-- Real Balance Summary Pill -->
                    <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] mb-3 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Saldo Aktif</span>
                            <span class="text-sm font-bold text-[#2B2B2B]"><?= formatRupiah($totalBalance) ?></span>
                        </div>
                        <a href="<?= base_url('accounts') ?>" class="text-[11px] font-semibold text-[#2FA89D] hover:underline">
                            Detail &rarr;
                        </a>
                    </div>

                    <!-- Mini Activity Stats Grid -->
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                            <span class="text-sm font-bold text-[#2B2B2B] block"><?= (int)$stats['accounts'] ?></span>
                            <span class="text-[10px] text-[#8A8F98]">Rekening</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                            <span class="text-sm font-bold text-[#2B2B2B] block"><?= (int)$stats['transactions'] ?></span>
                            <span class="text-[10px] text-[#8A8F98]">Transaksi</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                            <span class="text-sm font-bold text-[#2FA89D] block"><?= (int)$stats['categories'] ?></span>
                            <span class="text-[10px] text-[#8A8F98]">Kategori</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Navigation Tabs Card -->
            <div class="bg-white border border-[#E8EAED] rounded-xl p-2 shadow-sm space-y-1" id="settings-nav">
                <button
                    type="button"
                    data-settings-tab="profile"
                    class="settings-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-left transition-all border <?= $activeTab === 'profile' ? 'bg-[#F0F9F8] text-[#2FA89D] border-[#D6F0ED]' : 'border-transparent text-[#2B2B2B] hover:bg-[#F5F6F8]' ?>"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $activeTab === 'profile' ? 'bg-[#2FA89D] text-white' : 'bg-[#F5F6F8] text-[#8A8F98]' ?>">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold block">Profil Saya</span>
                            <span class="text-[11px] text-[#8A8F98] block">Nama tampilan & email akun</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

                <button
                    type="button"
                    data-settings-tab="security"
                    class="settings-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-left transition-all border <?= $activeTab === 'security' ? 'bg-[#F0F9F8] text-[#2FA89D] border-[#D6F0ED]' : 'border-transparent text-[#2B2B2B] hover:bg-[#F5F6F8]' ?>"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $activeTab === 'security' ? 'bg-[#2FA89D] text-white' : 'bg-[#F5F6F8] text-[#8A8F98]' ?>">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold block">Keamanan & Sandi</span>
                            <span class="text-[11px] text-[#8A8F98] block">Perbarui kata sandi akun</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

                <button
                    type="button"
                    data-settings-tab="categories"
                    class="settings-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-left transition-all border <?= $activeTab === 'categories' ? 'bg-[#F0F9F8] text-[#2FA89D] border-[#D6F0ED]' : 'border-transparent text-[#2B2B2B] hover:bg-[#F5F6F8]' ?>"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $activeTab === 'categories' ? 'bg-[#2FA89D] text-white' : 'bg-[#F5F6F8] text-[#8A8F98]' ?>">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold block">Kategori Keuangan</span>
                            <span class="text-[11px] text-[#8A8F98] block">Atur kategori & ikon transaksi</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

                <button
                    type="button"
                    data-settings-tab="preferences"
                    class="settings-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-left transition-all border <?= $activeTab === 'preferences' ? 'bg-[#F0F9F8] text-[#2FA89D] border-[#D6F0ED]' : 'border-transparent text-[#2B2B2B] hover:bg-[#F5F6F8]' ?>"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $activeTab === 'preferences' ? 'bg-[#2FA89D] text-white' : 'bg-[#F5F6F8] text-[#8A8F98]' ?>">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="4" y1="21" x2="4" y2="14"></line>
                                <line x1="4" y1="10" x2="4" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12" y2="3"></line>
                                <line x1="20" y1="21" x2="20" y2="16"></line>
                                <line x1="20" y1="12" x2="20" y2="3"></line>
                                <line x1="1" y1="14" x2="7" y2="14"></line>
                                <line x1="9" y1="8" x2="15" y2="8"></line>
                                <line x1="17" y1="16" x2="23" y2="16"></line>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-semibold block">Preferensi & Data</span>
                            <span class="text-[11px] text-[#8A8F98] block">Tampilan, notifikasi & ekspor CSV</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>

        </div>

        <!-- Right Column: Active Settings Content Panels -->
        <div class="lg:col-span-8 space-y-6">

            <!-- =================================================== -->
            <!-- PANEL 1: PROFIL SAYA                                -->
            <!-- =================================================== -->
            <section id="panel-profile" class="settings-panel <?= $activeTab === 'profile' ? '' : 'hidden' ?> bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-5 border-b border-[#E8EAED]">
                    <div>
                        <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Informasi Pribadi</span>
                        <h2 class="text-base font-bold text-[#2B2B2B]">Detail Profil Akun</h2>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-[#F5F6F8] text-[#8A8F98] border border-[#E8EAED]">
                        Diperbarui: <?= e($updatedDate) ?>
                    </span>
                </div>

                <!-- Live Greeting Preview Strip -->
                <div class="mb-6 p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E8EAED] flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div id="live-preview-avatar" class="w-10 h-10 rounded-full bg-[#2FA89D]/15 border border-[#2FA89D]/30 text-[#2FA89D] font-bold text-sm flex items-center justify-center flex-shrink-0">
                            <?= e($userInitial) ?>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-medium text-[#8A8F98] uppercase tracking-wider block">Pratinjau Sapaan Header</span>
                            <p class="text-sm font-semibold text-[#2B2B2B] truncate">
                                Hello <span id="live-preview-firstname" class="text-[#2FA89D]"><?= e($userFirstName) ?></span>
                            </p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-block text-[11px] text-[#8A8F98]">Sinkron otomatis ke sidebar & navbar</span>
                </div>

                <form action="<?= base_url('settings/profile') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="profile-name" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                                Nama Lengkap
                            </label>
                            <input
                                type="text"
                                id="profile-name"
                                name="name"
                                required
                                maxlength="100"
                                value="<?= e($user['name']) ?>"
                                placeholder="Nama lengkap Anda"
                                class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
                            >
                        </div>

                        <div>
                            <label for="profile-email" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                                Alamat Email
                            </label>
                            <input
                                type="email"
                                id="profile-email"
                                name="email"
                                required
                                maxlength="150"
                                value="<?= e($user['email']) ?>"
                                placeholder="nama@email.com"
                                class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                            <span class="text-[10px] font-medium text-[#8A8F98] uppercase block">ID Pengguna</span>
                            <span class="text-xs font-semibold text-[#2B2B2B] font-mono">#MT-<?= str_pad((string)$user['id'], 4, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                            <span class="text-[10px] font-medium text-[#8A8F98] uppercase block">Mata Uang Utama</span>
                            <span class="text-xs font-semibold text-[#2B2B2B]">IDR — Rupiah Indonesia (Rp)</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-[#E8EAED] flex items-center justify-end gap-3">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
                        >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </section>

            <!-- =================================================== -->
            <!-- PANEL 2: KEAMANAN & KATA SANDI                      -->
            <!-- =================================================== -->
            <section id="panel-security" class="settings-panel <?= $activeTab === 'security' ? '' : 'hidden' ?> bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 mb-5 border-b border-[#E8EAED]">
                    <div>
                        <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Perlindungan Akun</span>
                        <h2 class="text-base font-bold text-[#2B2B2B]">Keamanan & Ubah Kata Sandi</h2>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                        Proteksi CSRF Aktif
                    </span>
                </div>

                <!-- Security Posture Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                    <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#8A8F98] block uppercase">Hashing Sandi</span>
                            <span class="text-xs font-semibold text-[#2B2B2B]">Bcrypt / Default</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#8A8F98] block uppercase">Isolasi Data</span>
                            <span class="text-xs font-semibold text-[#2B2B2B]">User-Scoped PDO</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#8A8F98] block uppercase">Status Sesi</span>
                            <span class="text-xs font-semibold text-[#2FA89D]">Terautentikasi</span>
                        </div>
                    </div>
                </div>

                <form action="<?= base_url('settings/password') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <div>
                        <label for="current-password" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                            Kata Sandi Saat Ini
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="current-password"
                                name="current_password"
                                required
                                placeholder="Masukkan kata sandi saat ini"
                                class="w-full px-3.5 py-2.5 pr-10 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
                            >
                            <button type="button" data-toggle-password="current-password" class="absolute inset-y-0 right-0 px-3 text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none" aria-label="Tampilkan sandi">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="new-password" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="new-password"
                                    name="new_password"
                                    required
                                    minlength="6"
                                    placeholder="Minimal 6 karakter"
                                    class="w-full px-3.5 py-2.5 pr-10 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
                                >
                                <button type="button" data-toggle-password="new-password" class="absolute inset-y-0 right-0 px-3 text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none" aria-label="Tampilkan sandi">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="confirm-password" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="confirm-password"
                                    name="confirm_password"
                                    required
                                    minlength="6"
                                    placeholder="Ulangi kata sandi baru"
                                    class="w-full px-3.5 py-2.5 pr-10 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
                                >
                                <button type="button" data-toggle-password="confirm-password" class="absolute inset-y-0 right-0 px-3 text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none" aria-label="Tampilkan sandi">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Password Strength Meter -->
                    <div class="p-3.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#8A8F98] font-medium">Kekuatan Kata Sandi</span>
                            <span id="password-strength-label" class="font-semibold text-[#8A8F98]">Belum diisi</span>
                        </div>
                        <div class="h-1.5 w-full bg-[#E8EAED] rounded-full overflow-hidden">
                            <div id="password-strength-bar" class="h-full w-0 bg-[#2FA89D] transition-all duration-300 rounded-full"></div>
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 pt-1 text-[11px] text-[#8A8F98]">
                            <span id="rule-length" class="inline-flex items-center gap-1">&bull; Min. 6 karakter</span>
                            <span id="rule-mix" class="inline-flex items-center gap-1">&bull; Kombinasi huruf & angka</span>
                            <span id="rule-match" class="inline-flex items-center gap-1">&bull; Konfirmasi cocok</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-[#E8EAED] flex items-center justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
                        >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </section>

            <!-- =================================================== -->
            <!-- PANEL 3: KATEGORI KEUANGAN                          -->
            <!-- =================================================== -->
            <section id="panel-categories" class="settings-panel <?= $activeTab === 'categories' ? '' : 'hidden' ?> space-y-5">

                <!-- Add Category Card -->
                <div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="pb-4 mb-5 border-b border-[#E8EAED]">
                        <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Kustomisasi Pencatatan</span>
                        <h2 class="text-base font-bold text-[#2B2B2B]">Tambah Kategori Baru</h2>
                    </div>

                    <form action="<?= base_url('settings/categories') ?>" method="POST" class="space-y-4">
                        <?= csrf_field() ?>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                            <div class="sm:col-span-7">
                                <label for="cat-name" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                                    Nama Kategori
                                </label>
                                <input
                                    type="text"
                                    id="cat-name"
                                    name="name"
                                    required
                                    maxlength="100"
                                    placeholder="Contoh: Investasi, Kopi, Kesehatan..."
                                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
                                >
                            </div>

                            <div class="sm:col-span-5">
                                <span class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tipe Kategori</span>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="expense" checked class="peer sr-only">
                                        <span class="flex items-center justify-center py-2.5 px-3 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] text-xs font-medium text-[#8A8F98] peer-checked:bg-[#2B2B2B] peer-checked:text-white peer-checked:border-[#2B2B2B] transition-all">
                                            Pengeluaran
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="income" class="peer sr-only">
                                        <span class="flex items-center justify-center py-2.5 px-3 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] text-xs font-medium text-[#8A8F98] peer-checked:bg-[#2FA89D] peer-checked:text-white peer-checked:border-[#2FA89D] transition-all">
                                            Pemasukan
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Interactive Icon Picker -->
                        <div>
                            <span class="block text-xs font-medium text-[#2B2B2B] mb-2">Pilih Ikon Kategori</span>
                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                                <?php $firstIcon = true; foreach ($iconOptions as $iconKey => $iconLabel): ?>
                                    <label class="cursor-pointer group" title="<?= e($iconLabel) ?>">
                                        <input type="radio" name="icon" value="<?= e($iconKey) ?>" <?= $firstIcon ? 'checked' : '' ?> class="peer sr-only">
                                        <div class="p-2.5 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] text-[#8A8F98] flex flex-col items-center justify-center gap-1 peer-checked:bg-[#F0F9F8] peer-checked:text-[#2FA89D] peer-checked:border-[#2FA89D] group-hover:border-[#2FA89D]/50 transition-all">
                                            <?= getSettingsCategoryIconSvg($iconKey) ?>
                                            <span class="text-[9px] font-medium truncate max-w-full"><?= e($iconLabel) ?></span>
                                        </div>
                                    </label>
                                <?php $firstIcon = false; endforeach; ?>
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
                            >
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Tambah Kategori</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Categories List Grid: Income & Expense -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Income Categories -->
                    <div class="bg-white border border-[#E8EAED] rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-[10px] font-medium text-[#2FA89D] uppercase tracking-wider block">Revenue</span>
                                <h3 class="text-sm font-bold text-[#2B2B2B]">Kategori Pemasukan</h3>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                                <?= count($incomeCategories) ?>
                            </span>
                        </div>

                        <?php if (empty($incomeCategories)): ?>
                            <p class="text-xs text-[#8A8F98] italic py-4 text-center">Belum ada kategori pemasukan.</p>
                        <?php else: ?>
                            <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                                <?php foreach ($incomeCategories as $cat): ?>
                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center flex-shrink-0">
                                                <?= getSettingsCategoryIconSvg($cat['icon'] ?: 'tag') ?>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-[#2B2B2B] truncate"><?= e($cat['name']) ?></p>
                                                <p class="text-[10px] text-[#8A8F98]"><?= (int)$cat['transaction_count'] ?> transaksi</p>
                                            </div>
                                        </div>

                                        <?php if ((int)$cat['transaction_count'] === 0): ?>
                                            <button
                                                type="button"
                                                onclick="openDeleteCatModal(<?= (int)$cat['id'] ?>, <?= e(json_encode((string)$cat['name'])) ?>)"
                                                class="p-1.5 rounded-md text-[#8A8F98] hover:text-[#E56B6F] hover:bg-red-50 transition-colors focus:outline-none"
                                                title="Hapus kategori"
                                            >
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-[10px] px-2 py-0.5 rounded bg-white border border-[#E8EAED] text-[#8A8F98]" title="Kategori sedang digunakan transaksi">
                                                Aktif
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Expense Categories -->
                    <div class="bg-white border border-[#E8EAED] rounded-xl p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-[10px] font-medium text-[#8A8F98] uppercase tracking-wider block">Expenses</span>
                                <h3 class="text-sm font-bold text-[#2B2B2B]">Kategori Pengeluaran</h3>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F5F6F8] text-[#2B2B2B] border border-[#E8EAED]">
                                <?= count($expenseCategories) ?>
                            </span>
                        </div>

                        <?php if (empty($expenseCategories)): ?>
                            <p class="text-xs text-[#8A8F98] italic py-4 text-center">Belum ada kategori pengeluaran.</p>
                        <?php else: ?>
                            <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                                <?php foreach ($expenseCategories as $cat): ?>
                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-white text-[#2B2B2B] border border-[#E8EAED] flex items-center justify-center flex-shrink-0">
                                                <?= getSettingsCategoryIconSvg($cat['icon'] ?: 'tag') ?>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-[#2B2B2B] truncate"><?= e($cat['name']) ?></p>
                                                <p class="text-[10px] text-[#8A8F98]"><?= (int)$cat['transaction_count'] ?> transaksi</p>
                                            </div>
                                        </div>

                                        <?php if ((int)$cat['transaction_count'] === 0): ?>
                                            <button
                                                type="button"
                                                onclick="openDeleteCatModal(<?= (int)$cat['id'] ?>, <?= e(json_encode((string)$cat['name'])) ?>)"
                                                class="p-1.5 rounded-md text-[#8A8F98] hover:text-[#E56B6F] hover:bg-red-50 transition-colors focus:outline-none"
                                                title="Hapus kategori"
                                            >
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-[10px] px-2 py-0.5 rounded bg-white border border-[#E8EAED] text-[#8A8F98]" title="Kategori sedang digunakan transaksi">
                                                Aktif
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </section>

            <!-- =================================================== -->
            <!-- PANEL 4: PREFERENSI & EKSPOR DATA                   -->
            <!-- =================================================== -->
            <section id="panel-preferences" class="settings-panel <?= $activeTab === 'preferences' ? '' : 'hidden' ?> space-y-5">

                <!-- Preferences Card -->
                <div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="pb-4 mb-5 border-b border-[#E8EAED]">
                        <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Pengalaman Pengguna</span>
                        <h2 class="text-base font-bold text-[#2B2B2B]">Preferensi Tampilan & Notifikasi</h2>
                    </div>

                    <form action="<?= base_url('settings/preferences') ?>" method="POST" class="space-y-4">
                        <?= csrf_field() ?>

                        <!-- Toggle 1: Bill Reminder -->
                        <label class="flex items-center justify-between p-3.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] cursor-pointer">
                            <div class="pr-4">
                                <span class="text-xs font-semibold text-[#2B2B2B] block">Pengingat Tagihan Mendatang</span>
                                <span class="text-[11px] text-[#8A8F98]">Tampilkan indikator pengingat tagihan jatuh tempo pada beranda.</span>
                            </div>
                            <div class="relative inline-flex items-center flex-shrink-0">
                                <input type="checkbox" name="bill_reminder" class="sr-only peer" <?= !empty($preferences['bill_reminder']) ? 'checked' : '' ?>>
                                <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-[#2FA89D] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5 shadow-inner"></div>
                            </div>
                        </label>

                        <!-- Toggle 2: Compact Currency -->
                        <label class="flex items-center justify-between p-3.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] cursor-pointer">
                            <div class="pr-4">
                                <span class="text-xs font-semibold text-[#2B2B2B] block">Ringkasan Nominal Singkat</span>
                                <span class="text-[11px] text-[#8A8F98]">Gunakan format ringkas (seperti 900k / 1.5M) pada daftar transaksi cepat.</span>
                            </div>
                            <div class="relative inline-flex items-center flex-shrink-0">
                                <input type="checkbox" name="compact_currency" class="sr-only peer" <?= !empty($preferences['compact_currency']) ? 'checked' : '' ?>>
                                <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-[#2FA89D] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5 shadow-inner"></div>
                            </div>
                        </label>

                        <!-- Select: Default Landing -->
                        <div class="p-3.5 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-xs font-semibold text-[#2B2B2B] block">Halaman Utama Favorit</span>
                                <span class="text-[11px] text-[#8A8F98]">Pilih halaman ringkasan utama yang sering Anda buka.</span>
                            </div>
                            <select
                                name="default_landing"
                                class="px-3 py-2 bg-white border border-[#E8EAED] rounded-lg text-xs font-medium text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                            >
                                <option value="dashboard" <?= ($preferences['default_landing'] ?? 'dashboard') === 'dashboard' ? 'selected' : '' ?>>Overview Dashboard</option>
                                <option value="transactions" <?= ($preferences['default_landing'] ?? '') === 'transactions' ? 'selected' : '' ?>>Riwayat Transaksi</option>
                                <option value="accounts" <?= ($preferences['default_landing'] ?? '') === 'accounts' ? 'selected' : '' ?>>Saldo & Rekening</option>
                            </select>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-colors focus:outline-none"
                            >
                                Simpan Preferensi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Data Export Card -->
                <div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#2B2B2B]">Ekspor Riwayat Transaksi (CSV)</h3>
                            <p class="text-xs text-[#8A8F98] mt-0.5">
                                Unduh <?= (int)$stats['transactions'] ?> catatan transaksi Anda dari database dalam format CSV yang siap dibuka di Excel.
                            </p>
                        </div>
                    </div>

                    <a
                        href="<?= base_url('settings/export') ?>"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#171717] hover:bg-[#2B2B2B] text-white text-xs font-semibold rounded-lg shadow-sm transition-colors flex-shrink-0"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Unduh CSV</span>
                    </a>
                </div>

                <!-- Session Logout Card -->
                <div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-[#2B2B2B]">Keluar dari Sesi</h3>
                        <p class="text-xs text-[#8A8F98] mt-0.5">
                            Akhiri sesi login Anda di perangkat ini secara aman.
                        </p>
                    </div>
                    <form action="<?= base_url('logout') ?>" method="POST">
                        <?= csrf_field() ?>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 text-[#E56B6F] border border-red-200 text-xs font-semibold rounded-lg transition-colors focus:outline-none"
                        >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Logout Sekarang</span>
                        </button>
                    </form>
                </div>

            </section>

        </div>
    </div>

</div>

<!-- Delete Category Confirmation Modal -->
<div id="delete-cat-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-sm overflow-hidden p-6 text-center animate-in">
        <div class="w-12 h-12 rounded-full bg-red-50 text-[#E56B6F] flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
        </div>
        <h3 class="text-base font-bold text-[#2B2B2B] mb-1">Hapus Kategori?</h3>
        <p class="text-xs text-[#8A8F98] mb-5">
            Kategori <strong id="delete-cat-name" class="text-[#2B2B2B]"></strong> akan dihapus secara permanen.
        </p>

        <form action="<?= base_url('settings/categories/delete') ?>" method="POST" class="flex items-center justify-center gap-2">
            <?= csrf_field() ?>
            <input type="hidden" id="delete-cat-id" name="id" value="">

            <button type="button" onclick="closeDeleteCatModal()" class="flex-1 px-4 py-2 text-xs font-medium text-[#8A8F98] hover:text-[#2B2B2B] bg-[#F5F6F8] rounded-lg border border-[#E8EAED]">
                Batal
            </button>
            <button type="submit" class="flex-1 px-4 py-2 bg-[#E56B6F] hover:bg-red-600 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script src="<?= asset('js/settings.js') ?>"></script>
