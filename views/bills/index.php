<?php
/**
 * Tagihan (Bills) Management Page (Section 11 of AGENTS.md)
 */

$searchQuery = $search ?? '';

// Helper for bill logos
function getBillLogoSvg(string $logo): string {
    switch ($logo) {
        case 'figma':
            return '<div class="w-9 h-9 rounded-xl bg-[#F24E1E]/10 text-[#F24E1E] flex items-center justify-center font-bold text-xs flex-shrink-0 border border-[#F24E1E]/20">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12a4 4 0 1 1 8 0 4 4 0 0 1-8 0zM4 12a4 4 0 0 1 8 0v4a4 4 0 1 1-8 0v-4zm0-8a4 4 0 0 1 8 0v4H8a4 4 0 0 1-4-4zm8 0a4 4 0 1 1 8 0 4 4 0 0 1-8 0zm-4 16a4 4 0 0 1 4-4v4a4 4 0 1 1-4 0z"/>
                </svg>
            </div>';
        case 'adobe':
            return '<div class="w-9 h-9 rounded-xl bg-[#FF0000]/10 text-[#FF0000] flex items-center justify-center font-bold text-xs flex-shrink-0 border border-[#FF0000]/20">
                <span class="font-extrabold text-sm tracking-tighter">Ai</span>
            </div>';
        case 'spotify':
            return '<div class="w-9 h-9 rounded-xl bg-[#1DB954]/10 text-[#1DB954] flex items-center justify-center font-bold text-xs flex-shrink-0 border border-[#1DB954]/20">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.586 14.424a.623.623 0 0 1-.858.207c-2.348-1.434-5.304-1.758-8.784-.963a.625.625 0 0 1-.277-1.22c3.805-.87 7.076-.503 9.712 1.118a.624.624 0 0 1 .207.858zm1.225-2.723a.78.78 0 0 1-1.074.257c-2.688-1.652-6.786-2.13-9.965-1.165a.781.781 0 0 1-.454-1.493c3.632-1.103 8.163-.574 11.236 1.327a.78.78 0 0 1 .257 1.074zm.105-2.835C14.693 8.94 9.38 8.766 6.305 9.7a.936.936 0 1 1-.546-1.791c3.528-1.07 9.395-.87 13.064 1.309a.936.936 0 0 1-1.047 1.554z"/>
                </svg>
            </div>';
        case 'wifi':
        case 'internet':
            return '<div class="w-9 h-9 rounded-xl bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center font-bold text-xs flex-shrink-0 border border-[#2FA89D]/20">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01"/>
                </svg>
            </div>';
        case 'netflix':
            return '<div class="w-9 h-9 rounded-xl bg-red-600/10 text-red-600 flex items-center justify-center font-bold text-xs flex-shrink-0 border border-red-600/20">
                <span class="font-black text-sm">N</span>
            </div>';
        default:
            return '<div class="w-9 h-9 rounded-xl bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center font-bold text-xs flex-shrink-0 border border-[#E8EAED]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>';
    }
}
?>

<div class="space-y-6">

    <!-- Page Title & "+ Tambah Tagihan" Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                    Jadwal Pembayaran
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#2B2B2B] tracking-tight">
                Tagihan
            </h1>
            <p class="text-xs text-[#8A8F98]">
                Kelola langganan bulanan dan tahunan agar terhindar dari keterlambatan pembayaran.
            </p>
        </div>

        <!-- Add Bill Button -->
        <button 
            type="button" 
            onclick="openAddBillModal()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Tagihan</span>
        </button>
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
            <button onclick="this.parentElement.remove()" class="text-[#2FA89D] hover:opacity-75 focus:outline-none">&times;</button>
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
            <button onclick="this.parentElement.remove()" class="text-[#E56B6F] hover:opacity-75 focus:outline-none">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Tagihan -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Tagihan Terdaftar</span>
                <span class="text-lg sm:text-xl font-bold text-[#2B2B2B]"><?= (int)$summary['total_count'] ?> Tagihan</span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5">Semua siklus pembayaran</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
            </div>
        </div>

        <!-- Tagihan Bulanan -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Estimasi Tagihan Bulanan</span>
                <span class="text-lg sm:text-xl font-bold text-[#2FA89D]"><?= formatRupiah($summary['monthly_amount']) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5">Jatuh tempo setiap bulan</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-[#2FA89D] flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>

        <!-- Total Komitmen Biaya -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Nilai Tagihan</span>
                <span class="text-lg sm:text-xl font-bold text-[#2B2B2B]"><?= formatRupiah($summary['total_amount']) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5">Akumulasi seluruh tagihan</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-[#2B2B2B]">Daftar Tagihan</span>
            <span class="text-xs text-[#8A8F98]">(&bull; <?= count($bills) ?> ditemukan)</span>
        </div>

        <!-- Search Form -->
        <form action="<?= base_url('bills') ?>" method="GET" class="flex items-center gap-2">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#8A8F98]">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="<?= e($searchQuery) ?>"
                    placeholder="Search here..." 
                    class="w-full pl-8 pr-3 py-1.5 bg-[#F5F6F8] border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <button 
                type="submit" 
                class="px-3 py-1.5 bg-[#F5F6F8] hover:bg-[#E8EAED] text-[#2B2B2B] text-xs font-medium rounded-lg border border-[#E8EAED] transition-colors focus:outline-none"
            >
                Cari
            </button>

            <?php if (!empty($searchQuery)): ?>
                <a 
                    href="<?= base_url('bills') ?>" 
                    class="px-2 py-1.5 text-xs text-[#E56B6F] hover:underline"
                    title="Hapus filter pencarian"
                >
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- White Rounded Content Card: Tagihan List / Table -->
    <div class="bg-white border border-[#E8EAED] rounded-xl overflow-hidden shadow-sm">
        
        <?php if (empty($bills)): ?>
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B] mb-1">Belum Ada Tagihan</h3>
                <p class="text-xs text-[#8A8F98] mb-4">
                    <?= !empty($searchQuery) ? 'Tidak ditemukan tagihan dengan kata kunci "' . e($searchQuery) . '".' : 'Mulai catat tagihan berlangganan Anda agar keuangan lebih terencana.' ?>
                </p>
                <button 
                    type="button" 
                    onclick="openAddBillModal()" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#2FA89D] text-white text-xs font-semibold rounded-lg shadow-sm hover:bg-[#27958B] transition-colors"
                >
                    + Tambah Tagihan Pertama
                </button>
            </div>
        <?php else: ?>
            
            <!-- Desktop / Tablet Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#F5F6F8] border-b border-[#E8EAED] text-[#8A8F98] font-medium uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4 w-24">Tanggal</th>
                            <th class="py-3 px-4 w-16">Logo</th>
                            <th class="py-3 px-4">Deskripsi Barang</th>
                            <th class="py-3 px-4">Terakhir di rubah</th>
                            <th class="py-3 px-4 text-right">Harga</th>
                            <th class="py-3 px-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E8EAED]">
                        <?php foreach ($bills as $bill): ?>
                            <?php 
                            $bTimestamp = strtotime($bill['billing_date']);
                            $bDay = date('d', $bTimestamp);
                            $bMonth = date('M', $bTimestamp);
                            ?>
                            <tr class="hover:bg-gray-50/75 transition-colors">
                                
                                <!-- 1. Tanggal (Date Box with month & day) -->
                                <td class="py-3 px-4">
                                    <div class="w-12 h-12 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex flex-col items-center justify-center text-center">
                                        <span class="text-xs font-bold text-[#2B2B2B] leading-none"><?= $bDay ?></span>
                                        <span class="text-[10px] font-semibold text-[#8A8F98] uppercase tracking-wider mt-0.5"><?= $bMonth ?></span>
                                    </div>
                                </td>

                                <!-- 2. Logo -->
                                <td class="py-3 px-4">
                                    <?= getBillLogoSvg($bill['logo'] ?? 'default') ?>
                                </td>

                                <!-- 3. Deskripsi Barang (Service Name + Short Description + Cycle badge) -->
                                <td class="py-3 px-4">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-[#2B2B2B] text-xs">
                                                <?= e($bill['service_name']) ?>
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                                                <?= e($bill['billing_cycle']) ?>
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-[#8A8F98] truncate max-w-md">
                                            <?= e($bill['description'] ?: 'Tidak ada deskripsi tambahan') ?>
                                        </p>
                                    </div>
                                </td>

                                <!-- 4. Terakhir di rubah -->
                                <td class="py-3 px-4 text-[#8A8F98]">
                                    <div class="flex items-center gap-1.5 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <span><?= formatIndonesianDate($bill['updated_at']) ?></span>
                                    </div>
                                </td>

                                <!-- 5. Harga -->
                                <td class="py-3 px-4 text-right">
                                    <span class="text-xs font-bold text-[#2B2B2B] block">
                                        <?= formatRupiah($bill['amount']) ?>
                                    </span>
                                    <span class="text-[10px] text-[#8A8F98]">
                                        <?= formatShortRupiah($bill['amount']) ?> / <?= strtolower($bill['billing_cycle']) ?>
                                    </span>
                                </td>

                                <!-- 6. Aksi -->
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <!-- Detail Button -->
                                        <button 
                                            type="button" 
                                            onclick="openDetailBillModal(<?= $bill['id'] ?>)"
                                            class="p-1.5 text-gray-500 hover:text-[#2FA89D] hover:bg-[#F5F6F8] rounded-md transition-colors focus:outline-none"
                                            title="Lihat Rincian Tagihan"
                                        >
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                            </svg>
                                        </button>

                                        <!-- Edit Button -->
                                        <button 
                                            type="button" 
                                            onclick='openEditBillModal(<?= json_encode([
                                                "id" => $bill["id"],
                                                "service_name" => $bill["service_name"],
                                                "description" => $bill["description"],
                                                "amount" => $bill["amount"],
                                                "billing_date" => $bill["billing_date"],
                                                "billing_cycle" => $bill["billing_cycle"],
                                                "logo" => $bill["logo"]
                                            ]) ?>)'
                                            class="p-1.5 text-gray-500 hover:text-[#2FA89D] hover:bg-[#F5F6F8] rounded-md transition-colors focus:outline-none"
                                            title="Ubah Tagihan"
                                        >
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            type="button" 
                                            onclick="openDeleteBillModal(<?= $bill['id'] ?>, '<?= e(addslashes($bill['service_name'])) ?>', '<?= formatRupiah($bill['amount']) ?>')"
                                            class="p-1.5 text-gray-500 hover:text-[#E56B6F] hover:bg-red-50 rounded-md transition-colors focus:outline-none"
                                            title="Hapus Tagihan"
                                        >
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile View: Clean Stacked Cards -->
            <div class="md:hidden divide-y divide-[#E8EAED]">
                <?php foreach ($bills as $bill): ?>
                    <?php 
                    $bTimestamp = strtotime($bill['billing_date']);
                    $bDay = date('d', $bTimestamp);
                    $bMonth = date('M', $bTimestamp);
                    ?>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Date Card -->
                                <div class="w-11 h-11 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex flex-col items-center justify-center text-center">
                                    <span class="text-xs font-bold text-[#2B2B2B] leading-none"><?= $bDay ?></span>
                                    <span class="text-[9px] font-semibold text-[#8A8F98] uppercase leading-tight"><?= $bMonth ?></span>
                                </div>
                                <!-- Logo -->
                                <?= getBillLogoSvg($bill['logo'] ?? 'default') ?>
                                <!-- Title -->
                                <div>
                                    <p class="font-bold text-xs text-[#2B2B2B]">
                                        <?= e($bill['service_name']) ?>
                                    </p>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-medium bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                                        <?= e($bill['billing_cycle']) ?>
                                    </span>
                                </div>
                            </div>
                            <!-- Price -->
                            <div class="text-right">
                                <span class="text-xs font-bold text-[#2B2B2B] block">
                                    <?= formatRupiah($bill['amount']) ?>
                                </span>
                                <span class="text-[10px] text-[#8A8F98]">
                                    <?= formatShortRupiah($bill['amount']) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Description & Last updated -->
                        <?php if (!empty($bill['description'])): ?>
                            <p class="text-xs text-[#8A8F98] line-clamp-2">
                                <?= e($bill['description']) ?>
                            </p>
                        <?php endif; ?>

                        <!-- Footer Actions -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                            <span class="text-[10px] text-[#8A8F98]">
                                Terakhir: <?= formatIndonesianDate($bill['updated_at']) ?>
                            </span>
                            <div class="flex items-center gap-2.5">
                                <button 
                                    type="button" 
                                    onclick="openDetailBillModal(<?= $bill['id'] ?>)" 
                                    class="text-xs font-medium text-[#8A8F98] hover:text-[#2B2B2B]"
                                >
                                    Detail
                                </button>
                                <span>&bull;</span>
                                <button 
                                    type="button" 
                                    onclick='openEditBillModal(<?= json_encode([
                                        "id" => $bill["id"],
                                        "service_name" => $bill["service_name"],
                                        "description" => $bill["description"],
                                        "amount" => $bill["amount"],
                                        "billing_date" => $bill["billing_date"],
                                        "billing_cycle" => $bill["billing_cycle"],
                                        "logo" => $bill["logo"]
                                    ]) ?>)'
                                    class="text-xs font-medium text-[#2FA89D] hover:underline"
                                >
                                    Ubah
                                </button>
                                <span>&bull;</span>
                                <button 
                                    type="button" 
                                    onclick="openDeleteBillModal(<?= $bill['id'] ?>, '<?= e(addslashes($bill['service_name'])) ?>', '<?= formatRupiah($bill['amount']) ?>')"
                                    class="text-xs font-medium text-[#E56B6F] hover:underline"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Table Footer Summary -->
            <div class="p-4 bg-[#F5F6F8] border-t border-[#E8EAED] flex items-center justify-between text-xs text-[#8A8F98]">
                <span>Menampilkan <strong class="text-[#2B2B2B]"><?= count($bills) ?></strong> tagihan aktif</span>
                <span>Total Biaya: <strong class="text-[#2B2B2B]"><?= formatRupiah($summary['total_amount']) ?></strong></span>
            </div>

        <?php endif; ?>

    </div>

</div>

<!-- ===================================================== -->
<!-- 1. ADD BILL MODAL                                     -->
<!-- ===================================================== -->
<div id="add-bill-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Tambah Tagihan Baru</h3>
            </div>
            <button type="button" onclick="closeAddBillModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <!-- Form -->
        <form action="<?= base_url('bills') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Service / Company Name -->
            <div>
                <label for="add-bill-service" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nama Layanan / Perusahaan <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="add-bill-service" 
                    name="service_name" 
                    required 
                    placeholder="Contoh: Figma, Adobe, Spotify, Netflix, Listrik PLN" 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Amount & Cycle Grid -->
            <div class="grid grid-cols-2 gap-3">
                <!-- Amount -->
                <div>
                    <label for="add-bill-amount" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Harga / Nominal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-[#8A8F98]">Rp</span>
                        <input 
                            type="number" 
                            step="any"
                            id="add-bill-amount" 
                            name="amount" 
                            required 
                            min="1"
                            placeholder="500.000" 
                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                        >
                    </div>
                </div>

                <!-- Billing Cycle -->
                <div>
                    <label for="add-bill-cycle" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Siklus Pembayaran <span class="text-red-500">*</span></label>
                    <select 
                        id="add-bill-cycle" 
                        name="billing_cycle" 
                        required 
                        class="w-full px-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                        <option value="Bulanan">Bulanan</option>
                        <option value="Tahunan">Tahunan</option>
                        <option value="Mingguan">Mingguan</option>
                        <option value="Sekali">Sekali</option>
                    </select>
                </div>
            </div>

            <!-- Billing Date & Logo Grid -->
            <div class="grid grid-cols-2 gap-3">
                <!-- Date -->
                <div>
                    <label for="add-bill-date" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                    <input 
                        type="date" 
                        id="add-bill-date" 
                        name="billing_date" 
                        required 
                        value="<?= date('Y-m-d') ?>"
                        class="w-full px-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                </div>

                <!-- Logo Preset -->
                <div>
                    <label for="add-bill-logo" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Ikon / Logo</label>
                    <select 
                        id="add-bill-logo" 
                        name="logo" 
                        class="w-full px-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                        <option value="figma">Figma</option>
                        <option value="adobe">Adobe</option>
                        <option value="spotify">Spotify</option>
                        <option value="wifi">Internet / WiFi</option>
                        <option value="netflix">Netflix</option>
                        <option value="default">Default / Umum</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="add-bill-desc" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Deskripsi Barang / Layanan</label>
                <textarea 
                    id="add-bill-desc" 
                    name="description" 
                    rows="2" 
                    placeholder="Contoh: Langganan bulanan software desain untuk tim"
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                ></textarea>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeAddBillModal()" 
                    class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                >
                    Simpan Tagihan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================================================== -->
<!-- 2. EDIT BILL MODAL                                    -->
<!-- ===================================================== -->
<div id="edit-bill-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Ubah Tagihan</h3>
            </div>
            <button type="button" onclick="closeEditBillModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <!-- Form -->
        <form action="<?= base_url('bills/update') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" id="edit-bill-id" name="id" value="">

            <!-- Service Name -->
            <div>
                <label for="edit-bill-service" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nama Layanan / Perusahaan <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="edit-bill-service" 
                    name="service_name" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Amount & Cycle -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit-bill-amount" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Harga / Nominal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-[#8A8F98]">Rp</span>
                        <input 
                            type="number" 
                            step="any"
                            id="edit-bill-amount" 
                            name="amount" 
                            required 
                            min="1"
                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                        >
                    </div>
                </div>

                <div>
                    <label for="edit-bill-cycle" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Siklus Pembayaran <span class="text-red-500">*</span></label>
                    <select 
                        id="edit-bill-cycle" 
                        name="billing_cycle" 
                        required 
                        class="w-full px-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                        <option value="Bulanan">Bulanan</option>
                        <option value="Tahunan">Tahunan</option>
                        <option value="Mingguan">Mingguan</option>
                        <option value="Sekali">Sekali</option>
                    </select>
                </div>
            </div>

            <!-- Date & Logo -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit-bill-date" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                    <input 
                        type="date" 
                        id="edit-bill-date" 
                        name="billing_date" 
                        required 
                        class="w-full px-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                </div>

                <div>
                    <label for="edit-bill-logo" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Ikon / Logo</label>
                    <select 
                        id="edit-bill-logo" 
                        name="logo" 
                        class="w-full px-3 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                        <option value="figma">Figma</option>
                        <option value="adobe">Adobe</option>
                        <option value="spotify">Spotify</option>
                        <option value="wifi">Internet / WiFi</option>
                        <option value="netflix">Netflix</option>
                        <option value="default">Default / Umum</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="edit-bill-desc" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Deskripsi Barang / Layanan</label>
                <textarea 
                    id="edit-bill-desc" 
                    name="description" 
                    rows="2" 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                ></textarea>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeEditBillModal()" 
                    class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                >
                    Perbarui Tagihan
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================================================== -->
<!-- 3. DETAIL BILL MODAL                                  -->
<!-- ===================================================== -->
<div id="detail-bill-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <h3 class="text-sm font-bold text-[#2B2B2B]" id="detail-bill-title">Rincian Tagihan</h3>
            <button type="button" onclick="closeDetailBillModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <div class="p-6 space-y-4">
            <div class="text-center py-2" id="detail-bill-loading">
                <p class="text-xs text-[#8A8F98]">Memuat data tagihan...</p>
            </div>

            <div id="detail-bill-body" class="hidden space-y-4">
                <div class="bg-[#F5F6F8] border border-[#E8EAED] rounded-xl p-4 text-center">
                    <span class="text-[10px] uppercase font-bold text-[#2FA89D] tracking-wider block mb-1" id="detail-bill-cycle-badge">Siklus</span>
                    <h4 class="text-base font-bold text-[#2B2B2B]" id="detail-bill-name">Nama</h4>
                    <div class="text-2xl font-bold text-[#2B2B2B] mt-2 mb-1" id="detail-bill-amount">Rp 0</div>
                    <span class="text-xs text-[#8A8F98]" id="detail-bill-date">Jatuh Tempo: -</span>
                </div>

                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-[10px] text-[#8A8F98] uppercase block">Deskripsi Barang / Layanan:</span>
                        <p class="text-[#2B2B2B] font-medium mt-0.5" id="detail-bill-desc">-</p>
                    </div>
                    <div class="pt-2 border-t border-[#E8EAED] flex items-center justify-between text-[11px] text-[#8A8F98]">
                        <span>Dibuat: <strong id="detail-bill-created" class="text-[#2B2B2B]">-</strong></span>
                        <span>Terakhir diubah: <strong id="detail-bill-updated" class="text-[#2B2B2B]">-</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-3 bg-[#F5F6F8] border-t border-[#E8EAED] text-right">
            <button 
                type="button" 
                onclick="closeDetailBillModal()" 
                class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-white border border-[#E8EAED] hover:bg-gray-100 rounded-lg transition-colors focus:outline-none"
            >
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- ===================================================== -->
<!-- 4. DELETE BILL CONFIRMATION MODAL                     -->
<!-- ===================================================== -->
<div id="delete-bill-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <div class="p-6 text-center space-y-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-[#E56B6F] flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </div>

            <div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Hapus Tagihan?</h3>
                <p class="text-xs text-[#8A8F98] mt-1.5 leading-relaxed">
                    Apakah Anda yakin ingin menghapus tagihan <strong id="delete-bill-name" class="text-[#2B2B2B]"></strong> sebesar <span id="delete-bill-amount" class="font-bold text-[#2FA89D]"></span>? Tagihan tidak akan muncul lagi di daftar dan jadwal pengeluaran mendatang.
                </p>
            </div>

            <!-- Delete Form -->
            <form action="<?= base_url('bills/delete') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="delete-bill-id" name="id" value="">

                <div class="pt-2 flex items-center justify-center gap-2.5">
                    <button 
                        type="button" 
                        onclick="closeDeleteBillModal()" 
                        class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#E56B6F] hover:bg-red-600 rounded-lg shadow-sm transition-colors focus:outline-none"
                    >
                        Ya, Hapus Tagihan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Modal Control Scripts -->
<script src="<?= asset('js/bill.js') ?>"></script>
