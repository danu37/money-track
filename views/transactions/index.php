<?php
/**
 * Transaksi (Transactions) Page (Phase 6 of AGENTS.md)
 */

$filterType = $filterType ?? 'all';
$searchQuery = $search ?? '';
$currentPage = $pagination['page'] ?? 1;
$totalPages = $pagination['total_pages'] ?? 1;
$totalItems = $pagination['total'] ?? 0;
$items = $pagination['data'] ?? [];

// Helper function to build pagination query URL preserving filters
function paginationUrl(int $targetPage, string $currentType, string $currentSearch): string {
    $params = ['page' => $targetPage];
    if (!empty($currentType) && $currentType !== 'all') {
        $params['type'] = $currentType;
    }
    if (!empty($currentSearch)) {
        $params['search'] = $currentSearch;
    }
    return base_url('transactions?' . http_build_query($params));
}

// Helper function for category icons
function getCategoryIconSvg(string $icon): string {
    switch ($icon) {
        case 'briefcase':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>';
        case 'layout':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>';
        case 'coffee':
        case 'utensils':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>';
        case 'car':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>';
        case 'tag':
        case 'shopping-bag':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>';
        case 'receipt':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>';
        case 'watch':
        case 'cpu':
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect></svg>';
        default:
            return '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
    }
}
?>

<div class="space-y-6">

    <!-- Top Header: Title & "+ Tambah Transaksi" button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                    Manajemen Keuangan
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#2B2B2B] tracking-tight">
                Transaksi
            </h1>
            <p class="text-xs text-[#8A8F98]">
                Catat dan pantau semua aliran dana pemasukan dan pengeluaran Anda.
            </p>
        </div>

        <!-- Add Transaction Button -->
        <button 
            type="button" 
            onclick="openAddTxModal()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Transaksi</span>
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
        <!-- Total Pemasukan (Revenue) -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Revenue (Pemasukan)</span>
                <span class="text-lg sm:text-xl font-bold text-[#2FA89D]"><?= formatRupiah($summary['total_income']) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5">Semua waktu</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-[#2FA89D] flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            </div>
        </div>

        <!-- Total Pengeluaran (Expenses) -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Expenses (Pengeluaran)</span>
                <span class="text-lg sm:text-xl font-bold text-[#2B2B2B]"><?= formatRupiah($summary['total_expense']) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5">Semua waktu</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-red-50 text-[#E56B6F] flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Transaksi</span>
                <span class="text-lg sm:text-xl font-bold text-[#2B2B2B]"><?= (int)$summary['total_count'] ?> catatan</span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5">Tercatat di sistem</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter Bar: All / Revenue / Expenses Tabs & Search Form -->
    <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Tabs: All / Revenue / Expenses -->
        <div class="flex items-center bg-[#F5F6F8] p-1 rounded-lg border border-[#E8EAED] text-xs">
            <a 
                href="<?= base_url('transactions?type=all' . (!empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '')) ?>" 
                class="px-4 py-1.5 rounded-md font-medium transition-all <?= ($filterType === 'all') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-[#8A8F98] hover:text-[#2B2B2B]' ?>"
            >
                All
            </a>
            <a 
                href="<?= base_url('transactions?type=income' . (!empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '')) ?>" 
                class="px-4 py-1.5 rounded-md font-medium transition-all <?= ($filterType === 'income') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-[#8A8F98] hover:text-[#2B2B2B]' ?>"
            >
                Revenue
            </a>
            <a 
                href="<?= base_url('transactions?type=expense' . (!empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '')) ?>" 
                class="px-4 py-1.5 rounded-md font-medium transition-all <?= ($filterType === 'expense') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-[#8A8F98] hover:text-[#2B2B2B]' ?>"
            >
                Expenses
            </a>
        </div>

        <!-- Search Input Form -->
        <form action="<?= base_url('transactions') ?>" method="GET" class="flex items-center gap-2">
            <?php if (!empty($filterType) && $filterType !== 'all'): ?>
                <input type="hidden" name="type" value="<?= e($filterType) ?>">
            <?php endif; ?>
            
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
                    placeholder="Cari transaksi / vendor..." 
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
                    href="<?= base_url('transactions' . ($filterType !== 'all' ? '?type=' . e($filterType) : '')) ?>" 
                    class="px-2 py-1.5 text-xs text-[#E56B6F] hover:underline"
                    title="Hapus pencarian"
                >
                    Reset
                </a>
            <?php endif; ?>
        </form>

    </div>

    <!-- Transactions List Container -->
    <div class="bg-white border border-[#E8EAED] rounded-xl overflow-hidden shadow-sm">
        
        <?php if (empty($items)): ?>
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B] mb-1">Tidak Ada Transaksi</h3>
                <p class="text-xs text-[#8A8F98] mb-4">
                    <?= !empty($searchQuery) ? 'Tidak ditemukan transaksi yang cocok dengan kata kunci "' . e($searchQuery) . '".' : 'Belum ada transaksi pada kategori ini.' ?>
                </p>
                <button 
                    type="button" 
                    onclick="openAddTxModal()" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#2FA89D] text-white text-xs font-semibold rounded-lg shadow-sm hover:bg-[#27958B] transition-colors"
                >
                    + Catat Transaksi Baru
                </button>
            </div>
        <?php else: ?>
            
            <!-- Desktop / Tablet Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#F5F6F8] border-b border-[#E8EAED] text-[#8A8F98] font-medium uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4">Item / Transaksi</th>
                            <th class="py-3 px-4">Kategori</th>
                            <th class="py-3 px-4">Rekening / Metode</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4 text-right">Jumlah</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E8EAED]">
                        <?php foreach ($items as $tx): ?>
                            <?php $isIncome = ($tx['type'] === 'income'); ?>
                            <tr class="hover:bg-gray-50/75 transition-colors">
                                
                                <!-- Product / Item name & Category icon -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg <?= $isIncome ? 'bg-emerald-50 text-[#2FA89D]' : 'bg-gray-100 text-[#2B2B2B]' ?> flex items-center justify-center flex-shrink-0">
                                            <?= getCategoryIconSvg($tx['category_icon'] ?? 'tag') ?>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-[#2B2B2B] text-xs">
                                                <?= e($tx['description']) ?>
                                            </p>
                                            <span class="text-[10px] text-[#8A8F98]">
                                                ID: #<?= $tx['id'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category / Vendor -->
                                <td class="py-3 px-4 text-[#2B2B2B]">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-[#F5F6F8] border border-[#E8EAED]">
                                        <?= e($tx['category_name']) ?>
                                    </span>
                                </td>

                                <!-- Payment method / Account -->
                                <td class="py-3 px-4 text-[#2B2B2B]">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full <?= ($tx['account_type'] === 'bank') ? 'bg-blue-500' : (($tx['account_type'] === 'ewallet') ? 'bg-[#2FA89D]' : 'bg-amber-500') ?>"></span>
                                        <span class="font-medium"><?= e($tx['account_name']) ?></span>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="py-3 px-4 text-[#8A8F98]">
                                    <?= formatIndonesianDate($tx['transaction_date']) ?>
                                </td>

                                <!-- Amount (Formatted Rupiah) -->
                                <td class="py-3 px-4 text-right font-bold <?= $isIncome ? 'text-[#2FA89D]' : 'text-[#2B2B2B]' ?>">
                                    <?= ($isIncome ? '+ ' : '- ') . formatRupiah($tx['amount']) ?>
                                </td>

                                <!-- Actions -->
                                <td class="py-3 px-4 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <!-- Edit Button -->
                                        <button 
                                            type="button" 
                                            onclick='openEditTxModal(<?= json_encode([
                                                "id" => $tx["id"],
                                                "description" => $tx["description"],
                                                "type" => $tx["type"],
                                                "amount" => $tx["amount"],
                                                "account_id" => $tx["account_id"],
                                                "category_id" => $tx["category_id"],
                                                "transaction_date" => $tx["transaction_date"]
                                            ]) ?>)'
                                            class="p-1.5 text-gray-500 hover:text-[#2FA89D] hover:bg-[#F5F6F8] rounded-md transition-colors focus:outline-none"
                                            title="Ubah Transaksi"
                                        >
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            type="button" 
                                            onclick="openDeleteTxModal(<?= $tx['id'] ?>, '<?= e(addslashes($tx['description'])) ?>', '<?= formatRupiah($tx['amount']) ?>')"
                                            class="p-1.5 text-gray-500 hover:text-[#E56B6F] hover:bg-red-50 rounded-md transition-colors focus:outline-none"
                                            title="Hapus Transaksi"
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

            <!-- Mobile Card View -->
            <div class="sm:hidden divide-y divide-[#E8EAED]">
                <?php foreach ($items as $tx): ?>
                    <?php $isIncome = ($tx['type'] === 'income'); ?>
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg <?= $isIncome ? 'bg-emerald-50 text-[#2FA89D]' : 'bg-gray-100 text-[#2B2B2B]' ?> flex items-center justify-center flex-shrink-0">
                                    <?= getCategoryIconSvg($tx['category_icon'] ?? 'tag') ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-xs text-[#2B2B2B] leading-tight">
                                        <?= e($tx['description']) ?>
                                    </p>
                                    <p class="text-[10px] text-[#8A8F98]">
                                        <?= e($tx['category_name']) ?> &bull; <?= formatIndonesianDate($tx['transaction_date']) ?>
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs font-bold <?= $isIncome ? 'text-[#2FA89D]' : 'text-[#2B2B2B]' ?>">
                                <?= ($isIncome ? '+ ' : '- ') . formatShortRupiah($tx['amount']) ?>
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-[11px] pt-1 border-t border-gray-100 text-[#8A8F98]">
                            <span>Via: <strong class="text-[#2B2B2B]"><?= e($tx['account_name']) ?></strong></span>
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button" 
                                    onclick='openEditTxModal(<?= json_encode([
                                        "id" => $tx["id"],
                                        "description" => $tx["description"],
                                        "type" => $tx["type"],
                                        "amount" => $tx["amount"],
                                        "account_id" => $tx["account_id"],
                                        "category_id" => $tx["category_id"],
                                        "transaction_date" => $tx["transaction_date"]
                                    ]) ?>)'
                                    class="text-[#2FA89D] hover:underline"
                                >
                                    Ubah
                                </button>
                                <span>&bull;</span>
                                <button 
                                    type="button" 
                                    onclick="openDeleteTxModal(<?= $tx['id'] ?>, '<?= e(addslashes($tx['description'])) ?>', '<?= formatRupiah($tx['amount']) ?>')"
                                    class="text-[#E56B6F] hover:underline"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination Bar (Preserves Filter & Search) -->
            <div class="p-4 bg-[#F5F6F8] border-t border-[#E8EAED] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#8A8F98]">
                <div>
                    Menampilkan <span class="font-semibold text-[#2B2B2B]"><?= count($items) ?></span> dari <span class="font-semibold text-[#2B2B2B]"><?= $totalItems ?></span> transaksi
                </div>

                <!-- Page Buttons -->
                <?php if ($totalPages > 1): ?>
                    <div class="flex items-center gap-1">
                        <!-- Prev Page -->
                        <?php if ($currentPage > 1): ?>
                            <a 
                                href="<?= paginationUrl($currentPage - 1, $filterType, $searchQuery) ?>"
                                class="px-2.5 py-1.5 rounded-lg bg-white border border-[#E8EAED] text-[#2B2B2B] hover:bg-gray-100 transition-colors"
                            >
                                &larr; Prev
                            </a>
                        <?php else: ?>
                            <span class="px-2.5 py-1.5 rounded-lg bg-gray-100 border border-[#E8EAED] text-gray-400 cursor-not-allowed">
                                &larr; Prev
                            </span>
                        <?php endif; ?>

                        <!-- Numbered Pages -->
                        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                            <?php if ($p === $currentPage): ?>
                                <span class="px-3 py-1.5 rounded-lg bg-[#2FA89D] text-white font-semibold shadow-xs">
                                    <?= $p ?>
                                </span>
                            <?php else: ?>
                                <a 
                                    href="<?= paginationUrl($p, $filterType, $searchQuery) ?>"
                                    class="px-3 py-1.5 rounded-lg bg-white border border-[#E8EAED] text-[#2B2B2B] hover:bg-gray-100 transition-colors"
                                >
                                    <?= $p ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <?php if ($currentPage < $totalPages): ?>
                            <a 
                                href="<?= paginationUrl($currentPage + 1, $filterType, $searchQuery) ?>"
                                class="px-2.5 py-1.5 rounded-lg bg-white border border-[#E8EAED] text-[#2B2B2B] hover:bg-gray-100 transition-colors"
                            >
                                Next &rarr;
                            </a>
                        <?php else: ?>
                            <span class="px-2.5 py-1.5 rounded-lg bg-gray-100 border border-[#E8EAED] text-gray-400 cursor-not-allowed">
                                Next &rarr;
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</div>

<!-- ===================================================== -->
<!-- 1. ADD TRANSACTION MODAL                              -->
<!-- ===================================================== -->
<div id="add-tx-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Tambah Transaksi Baru</h3>
            </div>
            <button type="button" onclick="closeAddTxModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <!-- Form -->
        <form action="<?= base_url('transactions') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Type Selector: Income vs Expense -->
            <div>
                <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Jenis Transaksi <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center gap-2 p-2.5 border rounded-lg cursor-pointer text-xs font-semibold transition-all border-[#E8EAED] hover:bg-gray-50 has-checked:border-[#2FA89D] has-checked:bg-teal-50 has-checked:text-[#2FA89D]">
                        <input type="radio" name="type" value="expense" checked class="hidden" onchange="toggleCategoryType(this.value, 'add')">
                        <svg class="w-4 h-4 text-[#E56B6F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                        <span>Pengeluaran</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-2.5 border rounded-lg cursor-pointer text-xs font-semibold transition-all border-[#E8EAED] hover:bg-gray-50 has-checked:border-[#2FA89D] has-checked:bg-teal-50 has-checked:text-[#2FA89D]">
                        <input type="radio" name="type" value="income" class="hidden" onchange="toggleCategoryType(this.value, 'add')">
                        <svg class="w-4 h-4 text-[#2FA89D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                        <span>Pemasukan</span>
                    </label>
                </div>
            </div>

            <!-- Description / Item Name -->
            <div>
                <label for="add-tx-desc" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nama Transaksi / Barang <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="add-tx-desc" 
                    name="description" 
                    required 
                    placeholder="Contoh: GTR 5, Polo Shirt, Makan Siang, Gaji" 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Amount (Nominal) -->
            <div>
                <label for="add-tx-amount" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nominal Transaksi (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-[#8A8F98]">Rp</span>
                    <input 
                        type="number" 
                        step="any"
                        id="add-tx-amount" 
                        name="amount" 
                        required 
                        min="1"
                        placeholder="100.000" 
                        class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                </div>
            </div>

            <!-- Account / Payment Method -->
            <div>
                <label for="add-tx-account" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Metode / Rekening Pembayaran <span class="text-red-500">*</span></label>
                <select 
                    id="add-tx-account" 
                    name="account_id" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>">
                            <?= e($acc['name']) ?> (Saldo: <?= formatRupiah($acc['current_balance']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category -->
            <div>
                <label for="add-tx-category" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Kategori Transaksi <span class="text-red-500">*</span></label>
                <select 
                    id="add-tx-category" 
                    name="category_id" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" data-type="<?= $cat['type'] ?>">
                            <?= e($cat['name']) ?> (<?= ucfirst($cat['type']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date -->
            <div>
                <label for="add-tx-date" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tanggal Transaksi <span class="text-red-500">*</span></label>
                <input 
                    type="date" 
                    id="add-tx-date" 
                    name="transaction_date" 
                    required 
                    value="<?= date('Y-m-d') ?>"
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeAddTxModal()" 
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

    </div>
</div>

<!-- ===================================================== -->
<!-- 2. EDIT TRANSACTION MODAL                             -->
<!-- ===================================================== -->
<div id="edit-tx-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Ubah Transaksi</h3>
            </div>
            <button type="button" onclick="closeEditTxModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <!-- Form -->
        <form action="<?= base_url('transactions/update') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" id="edit-tx-id" name="id" value="">

            <!-- Type -->
            <div>
                <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Jenis Transaksi <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center justify-center gap-2 p-2.5 border rounded-lg cursor-pointer text-xs font-semibold transition-all border-[#E8EAED] hover:bg-gray-50 has-checked:border-[#2FA89D] has-checked:bg-teal-50 has-checked:text-[#2FA89D]">
                        <input type="radio" id="edit-type-expense" name="type" value="expense" class="hidden">
                        <span>Pengeluaran</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-2.5 border rounded-lg cursor-pointer text-xs font-semibold transition-all border-[#E8EAED] hover:bg-gray-50 has-checked:border-[#2FA89D] has-checked:bg-teal-50 has-checked:text-[#2FA89D]">
                        <input type="radio" id="edit-type-income" name="type" value="income" class="hidden">
                        <span>Pemasukan</span>
                    </label>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="edit-tx-desc" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nama Transaksi / Barang <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="edit-tx-desc" 
                    name="description" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Amount -->
            <div>
                <label for="edit-tx-amount" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nominal Transaksi (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-[#8A8F98]">Rp</span>
                    <input 
                        type="number" 
                        step="any"
                        id="edit-tx-amount" 
                        name="amount" 
                        required 
                        min="1"
                        class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                </div>
            </div>

            <!-- Account -->
            <div>
                <label for="edit-tx-account" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Metode / Rekening <span class="text-red-500">*</span></label>
                <select 
                    id="edit-tx-account" 
                    name="account_id" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>">
                            <?= e($acc['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Category -->
            <div>
                <label for="edit-tx-category" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Kategori Transaksi <span class="text-red-500">*</span></label>
                <select 
                    id="edit-tx-category" 
                    name="category_id" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" data-type="<?= $cat['type'] ?>">
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date -->
            <div>
                <label for="edit-tx-date" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tanggal Transaksi <span class="text-red-500">*</span></label>
                <input 
                    type="date" 
                    id="edit-tx-date" 
                    name="transaction_date" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeEditTxModal()" 
                    class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                >
                    Perbarui Transaksi
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================================================== -->
<!-- 3. DELETE TRANSACTION CONFIRMATION MODAL              -->
<!-- ===================================================== -->
<div id="delete-tx-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <div class="p-6 text-center space-y-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-[#E56B6F] flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </div>

            <div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Hapus Transaksi?</h3>
                <p class="text-xs text-[#8A8F98] mt-1.5 leading-relaxed">
                    Apakah Anda yakin ingin menghapus transaksi <strong id="delete-tx-name" class="text-[#2B2B2B]"></strong> sebesar <span id="delete-tx-amount" class="font-bold text-[#2FA89D]"></span>? Tindakan ini akan memperbarui saldo rekening terkait.
                </p>
            </div>

            <!-- Delete Form -->
            <form action="<?= base_url('transactions/delete') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="delete-tx-id" name="id" value="">

                <div class="pt-2 flex items-center justify-center gap-2.5">
                    <button 
                        type="button" 
                        onclick="closeDeleteTxModal()" 
                        class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#E56B6F] hover:bg-red-600 rounded-lg shadow-sm transition-colors focus:outline-none"
                    >
                        Ya, Hapus Transaksi
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Modal Control Scripts -->
<script src="<?= asset('js/transaction.js') ?>"></script>
