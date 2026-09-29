<?php
$currentRoute = 'ui-elements';
$pageTitle = 'Katalog UI Elemen';
?>

<div class="space-y-8 max-w-7xl mx-auto">
    
    <!-- Intro Banner -->
    <div class="bg-white border border-[#E8EAED] rounded-xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED] mb-2">
                    Design System &bull; AGENTS.md
                </span>
                <h2 class="text-xl font-bold text-[#2B2B2B]">Katalog Elemen UI MoneyTrack</h2>
                <p class="text-xs text-[#8A8F98] mt-1">
                    Berikut adalah setiap elemen antarmuka pengguna (UI) yang dibuat satu per satu secara presisi sesuai dengan pedoman visual <code class="bg-[#F5F6F8] px-1.5 py-0.5 rounded text-[#2FA89D]">AGENTS.md</code>.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('dashboard') ?>" class="px-3.5 py-2 text-xs font-medium bg-[#2FA89D] hover:bg-[#27958B] text-white rounded-lg shadow-sm transition-colors">
                    Lihat Dashboard Penuh
                </a>
                <a href="<?= base_url('login') ?>" class="px-3.5 py-2 text-xs font-medium bg-white hover:bg-gray-50 text-[#2B2B2B] border border-[#E8EAED] rounded-lg shadow-sm transition-colors">
                    Preview Auth Screen
                </a>
            </div>
        </div>

        <!-- Filter Selector -->
        <div class="mt-6 flex flex-wrap gap-2 border-t border-[#E8EAED] pt-4 text-xs font-medium">
            <a href="#section-auth" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                1. Auth Screen
            </a>
            <a href="#section-sidebar" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                2. Sidebar Gelap
            </a>
            <a href="#section-navbar" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                3. Dashboard Header
            </a>
            <a href="#section-saldo" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                4. Total Saldo
            </a>
            <a href="#section-target" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                5. Target Card
            </a>
            <a href="#section-bills" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                6. Tagihan Mendatang
            </a>
            <a href="#section-transactions" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                7. Riwayat Transaksi
            </a>
            <a href="#section-stats" class="px-3 py-1.5 rounded-lg bg-[#F5F6F8] text-[#2B2B2B] hover:bg-teal-50 hover:text-[#2FA89D] border border-[#E8EAED] transition-colors">
                8. Statistik Pengeluaran
            </a>
        </div>
    </div>

    <!-- 1. Authentication UI (Section 5) -->
    <section id="section-auth" class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 5 AGENTS.md</span>
                <h3 class="text-lg font-bold text-[#2B2B2B]">1. Elemen UI Otentikasi (Register & Login)</h3>
            </div>
            <span class="text-xs text-[#8A8F98]">Center narrow form, #F5F6F8 bg, teal accent, rounded-lg</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Register Form Preview Box -->
            <div class="bg-[#F5F6F8] p-6 rounded-xl border border-[#E8EAED] flex flex-col items-center">
                <span class="text-xs font-semibold text-[#8A8F98] uppercase mb-4 tracking-wider">Layar Pendaftaran (Register)</span>
                <div class="w-full max-w-[340px]">
                    <?php 
                    $old = [];
                    include __DIR__ . '/../auth/register.php'; 
                    ?>
                </div>
            </div>

            <!-- Login Form Preview Box -->
            <div class="bg-[#F5F6F8] p-6 rounded-xl border border-[#E8EAED] flex flex-col items-center">
                <span class="text-xs font-semibold text-[#8A8F98] uppercase mb-4 tracking-wider">Layar Masuk (Login)</span>
                <div class="w-full max-w-[340px]">
                    <?php 
                    include __DIR__ . '/../auth/login.php'; 
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Dark Sidebar Element (Section 7) -->
    <section id="section-sidebar" class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 7 AGENTS.md</span>
                <h3 class="text-lg font-bold text-[#2B2B2B]">2. Elemen Sidebar Gelap (Dark Vertical Sidebar)</h3>
            </div>
            <span class="text-xs text-[#8A8F98]">Width ~240px, #171717 charcoal, active teal pill, user profile</span>
        </div>

        <div class="bg-[#F5F6F8] p-6 rounded-xl border border-[#E8EAED] flex justify-center">
            <div class="w-[260px] h-[640px] relative rounded-xl overflow-hidden border border-[#242424] shadow-md">
                <div class="w-full h-full bg-[#171717] text-white flex flex-col">
                    <!-- Brand -->
                    <div class="h-16 flex items-center px-6 border-b border-[#242424] gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#2FA89D] flex items-center justify-center text-white">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight text-white">MoneyTrack</span>
                    </div>
                    <!-- Menu -->
                    <div class="flex-1 py-4 px-3 space-y-1 text-sm">
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg bg-[#2FA89D] text-white font-medium shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            <span>Overview</span>
                        </div>
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-gray-300 hover:bg-[#222222]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                            <span>Saldo</span>
                        </div>
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-gray-300 hover:bg-[#222222]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                            <span>Transaksi</span>
                        </div>
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-gray-300 hover:bg-[#222222]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                            <span>Tagihan</span>
                        </div>
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-gray-300 hover:bg-[#222222]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                            <span>Pengeluaran</span>
                        </div>
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-gray-300 hover:bg-[#222222]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle></svg>
                            <span>Target Keuangan</span>
                        </div>
                        <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-gray-300 hover:bg-[#222222]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Pengaturan</span>
                        </div>
                    </div>
                    <!-- Bottom -->
                    <div class="p-3 border-t border-[#242424] space-y-2">
                        <div class="flex items-center gap-3 px-3.5 py-1.5 text-xs text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            <span>Logout</span>
                        </div>
                        <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-[#1F1F1F]">
                            <div class="w-8 h-8 rounded-full bg-[#2FA89D]/20 text-[#2FA89D] flex items-center justify-center font-bold text-xs">D</div>
                            <div class="text-xs">
                                <p class="font-semibold text-white">Danu Anggo</p>
                                <span class="text-[10px] text-[#8A8F98]">View profile</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Dashboard Header Element (Section 8) -->
    <section id="section-navbar" class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 8 AGENTS.md</span>
                <h3 class="text-lg font-bold text-[#2B2B2B]">3. Elemen Dashboard Header (Navbar)</h3>
            </div>
            <span class="text-xs text-[#8A8F98]">Greeting, Current date, Notification bell, Search bar</span>
        </div>

        <div class="rounded-xl overflow-hidden border border-[#E8EAED]">
            <?php include __DIR__ . '/../layouts/navbar.php'; ?>
        </div>
    </section>

    <!-- 4, 5, 6: Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        <!-- 4. Total Saldo Card (Section 9) -->
        <section id="section-saldo" class="space-y-2 flex flex-col">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 9 AGENTS.md</span>
                <h3 class="text-base font-bold text-[#2B2B2B]">4. Elemen Total Saldo & Akun</h3>
            </div>
            <?php include __DIR__ . '/components/total_saldo_card.php'; ?>
        </section>

        <!-- 5. Target Card (Section 10) -->
        <section id="section-target" class="space-y-2 flex flex-col">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 10 AGENTS.md</span>
                <h3 class="text-base font-bold text-[#2B2B2B]">5. Elemen Target Keuangan</h3>
            </div>
            <?php include __DIR__ . '/components/target_card.php'; ?>
        </section>

        <!-- 6. Upcoming Bills (Section 11) -->
        <section id="section-bills" class="space-y-2 flex flex-col">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 11 AGENTS.md</span>
                <h3 class="text-base font-bold text-[#2B2B2B]">6. Elemen Tagihan Mendatang</h3>
            </div>
            <?php include __DIR__ . '/components/upcoming_bills_card.php'; ?>
        </section>

    </div>

    <!-- 7 & 8: Transactions & Statistics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- 7. Riwayat Transaksi (Section 12) -->
        <section id="section-transactions" class="lg:col-span-5 space-y-2 flex flex-col">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 12 AGENTS.md</span>
                <h3 class="text-base font-bold text-[#2B2B2B]">7. Elemen Riwayat Transaksi</h3>
            </div>
            <?php include __DIR__ . '/components/transactions_card.php'; ?>
        </section>

        <!-- 8. Monthly Statistics Chart (Section 13) -->
        <section id="section-stats" class="lg:col-span-7 space-y-2 flex flex-col">
            <div>
                <span class="text-[11px] font-bold text-[#2FA89D] uppercase tracking-wider">Bagian 13 & 23 AGENTS.md</span>
                <h3 class="text-base font-bold text-[#2B2B2B]">8. Elemen Statistik Pengeluaran Bulanan</h3>
            </div>
            <?php include __DIR__ . '/components/statistics_card.php'; ?>
        </section>

    </div>

</div>
