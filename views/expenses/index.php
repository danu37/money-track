<?php
/**
 * Pengeluaran (Expenses) Page
 * Matches MoneyTrack Design System (Section 2, 3, 12, 13 of AGENTS.md)
 */

$searchQuery = $search ?? '';
$currentPage = $pagination['page'] ?? 1;
$totalPages = $pagination['total_pages'] ?? 1;
$totalItems = $pagination['total'] ?? 0;
$items = $pagination['data'] ?? [];

// Helper function to build pagination query URL preserving search
function expensePaginationUrl(int $targetPage, string $currentSearch): string {
    $params = ['page' => $targetPage];
    if (!empty($currentSearch)) {
        $params['search'] = $currentSearch;
    }
    return base_url('expenses?' . http_build_query($params));
}

// Helper function for category icons
function getExpenseCategoryIconSvg(string $icon): string {
    switch ($icon) {
        case 'coffee':
        case 'utensils':
            return '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>';
        case 'car':
            return '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>';
        case 'tag':
        case 'shopping-bag':
            return '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>';
        case 'receipt':
            return '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>';
        case 'film':
            return '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>';
        case 'watch':
        case 'cpu':
            return '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect></svg>';
        default:
            return '<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
    }
}

// Chart SVG calculations
$trendData = $monthlyTrend ?? [];
$maxTrendAmount = 0;
foreach ($trendData as $m) {
    if ($m['amount'] > $maxTrendAmount) {
        $maxTrendAmount = $m['amount'];
    }
}
$ceilingTrend = ($maxTrendAmount > 0) ? ceil($maxTrendAmount / 1000000) * 1000000 : 1000000;

$chartWidth = 560;
$chartHeight = 180;
$paddingX = 35;
$paddingY = 25;
$usableWidth = $chartWidth - ($paddingX * 2);
$usableHeight = $chartHeight - ($paddingY * 2);

$points = [];
$countTrend = count($trendData);
foreach ($trendData as $i => $stat) {
    $x = $paddingX + ($countTrend > 1 ? ($i / ($countTrend - 1)) * $usableWidth : $usableWidth / 2);
    $y = $chartHeight - $paddingY - (($stat['amount'] / $ceilingTrend) * $usableHeight);
    $points[] = [
        'x' => round($x, 1),
        'y' => round($y, 1),
        'month' => $stat['month_label'],
        'amount' => $stat['amount'],
        'formatted' => formatRupiah($stat['amount'])
    ];
}

$pathD = '';
$areaD = '';
if (!empty($points)) {
    $pathD = "M {$points[0]['x']} {$points[0]['y']}";
    for ($i = 0; $i < count($points) - 1; $i++) {
        $p0 = ($i > 0) ? $points[$i - 1] : $points[$i];
        $p1 = $points[$i];
        $p2 = $points[$i + 1];
        $p3 = ($i + 2 < count($points)) ? $points[$i + 2] : $p2;

        $cp1x = $p1['x'] + ($p2['x'] - $p0['x']) / 6;
        $cp1y = $p1['y'] + ($p2['y'] - $p0['y']) / 6;
        $cp2x = $p2['x'] - ($p3['x'] - $p1['x']) / 6;
        $cp2y = $p2['y'] - ($p3['y'] - $p1['y']) / 6;

        $pathD .= " C " . round($cp1x, 1) . " " . round($cp1y, 1) . ", " . round($cp2x, 1) . " " . round($cp2y, 1) . ", {$p2['x']} {$p2['y']}";
    }

    $lastX = $points[count($points) - 1]['x'];
    $firstX = $points[0]['x'];
    $bottomY = $chartHeight - $paddingY;
    $areaD = $pathD . " L {$lastX} {$bottomY} L {$firstX} {$bottomY} Z";
}
?>

<div class="space-y-6">

    <!-- Top Header: Title & "+ Tambah Pengeluaran" button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                    Manajemen Pengeluaran
                </span>
                <span class="text-xs text-[#8A8F98]">
                    <?= formatIndonesianDate(date('Y-m-d')) ?>
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#2B2B2B] tracking-tight">
                Pengeluaran
            </h1>
            <p class="text-xs text-[#8A8F98]">
                Pantau pola pengeluaran, perbandingan tren bulanan, dan alokasi anggaran Anda.
            </p>
        </div>

        <!-- Add Expense Button -->
        <button 
            type="button" 
            onclick="openAddExpenseModal()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Pengeluaran</span>
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

    <!-- ===================================================== -->
    <!-- 1. ANALYTICS SUMMARY CARDS                            -->
    <!-- ===================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Pengeluaran Bulan Ini -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-[#8A8F98]">Bulan Ini</span>
                <?php if ($analytics['trend'] === 'up'): ?>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#E56B6F] bg-red-50 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"></polyline></svg>
                        +<?= $analytics['percentage_change'] ?>%
                    </span>
                <?php elseif ($analytics['trend'] === 'down'): ?>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#2FA89D] bg-teal-50 px-2 py-0.5 rounded-md">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        -<?= $analytics['percentage_change'] ?>%
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center text-[11px] font-medium text-[#8A8F98] bg-[#F5F6F8] px-2 py-0.5 rounded-md">
                        0%
                    </span>
                <?php endif; ?>
            </div>
            <div class="text-lg sm:text-xl font-bold text-[#2B2B2B] mt-2">
                <?= formatRupiah($analytics['current_month']) ?>
            </div>
            <p class="text-[11px] text-[#8A8F98] mt-1">
                <?= $analytics['month_count'] ?> transaksi tercatat
            </p>
        </div>

        <!-- Card 2: Rata-rata Mingguan -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-[#8A8F98]">Rata-rata Mingguan</span>
                <div class="w-6 h-6 rounded-md bg-teal-50 text-[#2FA89D] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
            </div>
            <div class="text-lg sm:text-xl font-bold text-[#2B2B2B] mt-2">
                <?= formatRupiah($analytics['weekly_average']) ?>
            </div>
            <p class="text-[11px] text-[#8A8F98] mt-1">
                Estimasi per 7 hari bulan ini
            </p>
        </div>

        <!-- Card 3: Kategori Terbesar -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-[#8A8F98]">Kategori Terbesar</span>
                <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                </div>
            </div>
            <div class="text-lg sm:text-xl font-bold text-[#2B2B2B] mt-2 truncate">
                <?= e($analytics['top_category']) ?>
            </div>
            <p class="text-[11px] text-[#8A8F98] mt-1">
                <?= formatRupiah($analytics['top_category_amount']) ?> terserap
            </p>
        </div>

        <!-- Card 4: Bulan Lalu -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-[#8A8F98]">Bulan Lalu</span>
                <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
            <div class="text-lg sm:text-xl font-bold text-[#2B2B2B] mt-2">
                <?= formatRupiah($analytics['last_month']) ?>
            </div>
            <p class="text-[11px] text-[#8A8F98] mt-1">
                Total pengeluaran bulan lalu
            </p>
        </div>

    </div>

    <!-- ===================================================== -->
    <!-- 2. EXPENSE COMPARISON CHART & CATEGORY BREAKDOWN      -->
    <!-- ===================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left / Chart Card (7 cols) -->
        <div class="lg:col-span-7 bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                        Grafik Perbandingan
                    </h3>
                    <h2 class="text-base font-bold text-[#2B2B2B]">
                        Tren Pengeluaran 6 Bulan Terakhir
                    </h2>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F6F8] text-[#2B2B2B] border border-[#E8EAED]">
                    <span class="w-2 h-2 rounded-full bg-[#2FA89D]"></span>
                    Terkini
                </span>
            </div>

            <!-- SVG Smooth Trend Chart -->
            <div class="relative w-full overflow-hidden my-2">
                <!-- Floating Tooltip -->
                <div id="expense-chart-tooltip" class="absolute z-20 pointer-events-none opacity-0 transition-opacity bg-[#171717] text-white text-[11px] px-2.5 py-1 rounded-md shadow-md -translate-x-1/2 -translate-y-full mb-2">
                    <span id="expense-tooltip-text"></span>
                </div>

                <svg class="w-full h-48 sm:h-56" viewBox="0 0 <?= $chartWidth ?> <?= $chartHeight ?>" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="expenseTealGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#2FA89D" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#2FA89D" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>

                    <!-- Horizontal Grid Lines -->
                    <line x1="<?= $paddingX ?>" y1="<?= $paddingY ?>" x2="<?= $chartWidth - $paddingX ?>" y2="<?= $paddingY ?>" stroke="#F0F0F2" stroke-dasharray="3 3"/>
                    <line x1="<?= $paddingX ?>" y1="<?= $chartHeight / 2 ?>" x2="<?= $chartWidth - $paddingX ?>" y2="<?= $chartHeight / 2 ?>" stroke="#F0F0F2" stroke-dasharray="3 3"/>
                    <line x1="<?= $paddingX ?>" y1="<?= $chartHeight - $paddingY ?>" x2="<?= $chartWidth - $paddingX ?>" y2="<?= $chartHeight - $paddingY ?>" stroke="#E8EAED"/>

                    <!-- Gradient Area Fill -->
                    <?php if (!empty($areaD)): ?>
                        <path d="<?= $areaD ?>" fill="url(#expenseTealGradient)"/>
                    <?php endif; ?>

                    <!-- Line Path -->
                    <?php if (!empty($pathD)): ?>
                        <path d="<?= $pathD ?>" fill="none" stroke="#2FA89D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <?php endif; ?>

                    <!-- Interactive Data Points -->
                    <?php foreach ($points as $p): ?>
                        <circle 
                            cx="<?= $p['x'] ?>" 
                            cy="<?= $p['y'] ?>" 
                            r="4.5" 
                            fill="#FFFFFF" 
                            stroke="#2FA89D" 
                            stroke-width="2.5"
                            class="cursor-pointer transition-all hover:r-6"
                            data-month="<?= e($p['month']) ?>"
                            data-amount="<?= e($p['formatted']) ?>"
                            onmouseenter="showExpenseTooltip(event, this)"
                            onmouseleave="hideExpenseTooltip()"
                        />
                        <!-- Month Label -->
                        <text 
                            x="<?= $p['x'] ?>" 
                            y="<?= $chartHeight - 6 ?>" 
                            font-size="10" 
                            fill="#8A8F98" 
                            text-anchor="middle"
                            font-family="Inter, sans-serif"
                        >
                            <?= e($p['month']) ?>
                        </text>
                    <?php endforeach; ?>
                </svg>
            </div>

            <!-- Footer summary -->
            <div class="pt-3 border-t border-[#E8EAED] flex items-center justify-between text-xs text-[#8A8F98]">
                <span>Puncak tertinggi: <strong class="text-[#2B2B2B]"><?= formatRupiah($maxTrendAmount) ?></strong></span>
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#2FA89D]"></span>
                    Data riil transaksi
                </span>
            </div>
        </div>

        <!-- Right / Category Breakdown (5 cols) -->
        <div class="lg:col-span-5 bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                            Alokasi Dana
                        </h3>
                        <h2 class="text-base font-bold text-[#2B2B2B]">
                            Pengeluaran per Kategori
                        </h2>
                    </div>
                    <span class="text-xs text-[#8A8F98]">
                        <?= count($categoryBreakdown) ?> Kategori
                    </span>
                </div>

                <!-- Breakdown list -->
                <div class="space-y-4 max-h-56 overflow-y-auto pr-1">
                    <?php if (empty($categoryBreakdown)): ?>
                        <div class="py-8 text-center text-xs text-[#8A8F98]">
                            Belum ada data pengeluaran berdasarkan kategori.
                        </div>
                    <?php else: ?>
                        <?php foreach ($categoryBreakdown as $cat): ?>
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background-color: <?= $cat['color'] ?>15; color: <?= $cat['color'] ?>;">
                                            <?= getExpenseCategoryIconSvg($cat['icon']) ?>
                                        </div>
                                        <span class="font-medium text-[#2B2B2B]"><?= e($cat['name']) ?></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold text-[#2B2B2B]"><?= formatRupiah($cat['amount']) ?></span>
                                        <span class="text-[11px] text-[#8A8F98] ml-1.5 font-medium">(<?= $cat['percentage'] ?>%)</span>
                                    </div>
                                </div>
                                <!-- Progress bar -->
                                <div class="w-full bg-[#F5F6F8] rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full transition-all duration-500" style="width: <?= min(100, max(2, $cat['percentage'])) ?>%; background-color: <?= $cat['color'] ?>;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E8EAED] mt-4 flex items-center justify-between text-xs text-[#8A8F98]">
                <span>Total Kategori Tercatat:</span>
                <span class="font-bold text-[#2B2B2B]"><?= count($categoryBreakdown) ?> Kelompok</span>
            </div>
        </div>

    </div>

    <!-- ===================================================== -->
    <!-- 3. EXPENSES LIST / TABLE                              -->
    <!-- ===================================================== -->
    <div class="bg-white border border-[#E8EAED] rounded-xl shadow-sm overflow-hidden">

        <!-- Table Header & Search -->
        <div class="p-4 sm:p-5 border-b border-[#E8EAED] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-[#2B2B2B]">
                    Riwayat Pengeluaran
                </h3>
                <p class="text-xs text-[#8A8F98]">
                    Menampilkan <?= count($items) ?> dari <?= $totalItems ?> pengeluaran tercatat
                </p>
            </div>

            <!-- Search Form -->
            <form action="<?= base_url('expenses') ?>" method="GET" class="flex items-center gap-2">
                <div class="relative w-full sm:w-64">
                    <input 
                        type="text" 
                        name="search" 
                        value="<?= e($searchQuery) ?>" 
                        placeholder="Cari pengeluaran..." 
                        class="w-full pl-8 pr-3 py-1.5 text-xs bg-[#F5F6F8] border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] placeholder-[#8A8F98] text-[#2B2B2B]"
                    >
                    <svg class="w-3.5 h-3.5 text-[#8A8F98] absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <?php if (!empty($searchQuery)): ?>
                    <a href="<?= base_url('expenses') ?>" class="text-xs text-[#8A8F98] hover:text-[#2FA89D] px-2 py-1.5 rounded bg-gray-100 hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F5F6F8] text-[11px] font-semibold text-[#8A8F98] uppercase tracking-wider border-b border-[#E8EAED]">
                        <th class="py-3 px-5">Tanggal</th>
                        <th class="py-3 px-5">Deskripsi / Keperluan</th>
                        <th class="py-3 px-5">Kategori</th>
                        <th class="py-3 px-5">Metode / Akun</th>
                        <th class="py-3 px-5 text-right">Jumlah</th>
                        <th class="py-3 px-5 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8EAED] text-xs">
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-[#8A8F98]">
                                <div class="w-12 h-12 rounded-full bg-[#F5F6F8] flex items-center justify-center mx-auto mb-3 text-[#8A8F98]">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <line x1="12" y1="19" x2="12" y2="5"></line>
                                        <polyline points="5 12 12 5 19 12"></polyline>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-[#2B2B2B]">Belum ada data pengeluaran</p>
                                <p class="text-xs text-[#8A8F98] mt-1">
                                    <?= !empty($searchQuery) ? 'Tidak ditemukan transaksi yang cocok dengan pencarian Anda.' : 'Mulai catat transaksi pengeluaran pertama Anda sekarang.' ?>
                                </p>
                                <button 
                                    type="button" 
                                    onclick="openAddExpenseModal()" 
                                    class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#2FA89D] text-white text-xs font-medium rounded-lg hover:bg-[#27958B] transition-colors"
                                >
                                    + Catat Pengeluaran
                                </button>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $tx): ?>
                            <tr class="hover:bg-[#F5F6F8]/60 transition-colors">
                                <!-- Tanggal -->
                                <td class="py-3.5 px-5 whitespace-nowrap text-[#8A8F98]">
                                    <?= formatIndonesianDate($tx['transaction_date']) ?>
                                </td>

                                <!-- Deskripsi -->
                                <td class="py-3.5 px-5">
                                    <div class="font-medium text-[#2B2B2B] truncate max-w-xs">
                                        <?= e($tx['description']) ?>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium bg-[#F5F6F8] text-[#2B2B2B] border border-[#E8EAED]">
                                        <?= getExpenseCategoryIconSvg($tx['category_icon'] ?? 'tag') ?>
                                        <span><?= e($tx['category_name']) ?></span>
                                    </span>
                                </td>

                                <!-- Metode / Akun -->
                                <td class="py-3.5 px-5 whitespace-nowrap text-[#2B2B2B]">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-[#8A8F98]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                            <line x1="1" y1="10" x2="23" y2="10"></line>
                                        </svg>
                                        <span><?= e($tx['account_name']) ?></span>
                                    </div>
                                </td>

                                <!-- Jumlah (Minus in red/danger) -->
                                <td class="py-3.5 px-5 whitespace-nowrap text-right font-bold text-[#E56B6F]">
                                    - <?= formatRupiah((float)$tx['amount']) ?>
                                </td>

                                <!-- Aksi -->
                                <td class="py-3.5 px-5 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Detail Button -->
                                        <button 
                                            type="button" 
                                            onclick="openDetailExpenseModal(<?= (int)$tx['id'] ?>)" 
                                            title="Lihat Detail"
                                            class="p-1.5 text-[#8A8F98] hover:text-[#2FA89D] hover:bg-[#F0F9F8] rounded-md transition-colors focus:outline-none"
                                        >
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>

                                        <!-- Edit Button -->
                                        <button 
                                            type="button" 
                                            onclick="openEditExpenseModal(<?= (int)$tx['id'] ?>)" 
                                            title="Edit Pengeluaran"
                                            class="p-1.5 text-[#8A8F98] hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors focus:outline-none"
                                        >
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            type="button" 
                                            onclick="openDeleteExpenseModal(<?= (int)$tx['id'] ?>, '<?= e(addslashes($tx['description'])) ?>', '<?= formatRupiah((float)$tx['amount']) ?>')" 
                                            title="Hapus Pengeluaran"
                                            class="p-1.5 text-[#8A8F98] hover:text-[#E56B6F] hover:bg-red-50 rounded-md transition-colors focus:outline-none"
                                        >
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards View -->
        <div class="block md:hidden divide-y divide-[#E8EAED]">
            <?php if (empty($items)): ?>
                <div class="p-8 text-center text-xs text-[#8A8F98]">
                    Belum ada pengeluaran tercatat.
                </div>
            <?php else: ?>
                <?php foreach ($items as $tx): ?>
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-[#E56B6F] flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <?= getExpenseCategoryIconSvg($tx['category_icon'] ?? 'tag') ?>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-[#2B2B2B] leading-snug">
                                        <?= e($tx['description']) ?>
                                    </h4>
                                    <div class="flex items-center gap-2 text-[11px] text-[#8A8F98] mt-0.5">
                                        <span><?= e($tx['category_name']) ?></span>
                                        <span>&bull;</span>
                                        <span><?= formatIndonesianDate($tx['transaction_date']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-[#E56B6F] block">
                                    - <?= formatRupiah((float)$tx['amount']) ?>
                                </span>
                                <span class="text-[10px] text-[#8A8F98] block mt-0.5">
                                    <?= e($tx['account_name']) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                            <button 
                                type="button" 
                                onclick="openDetailExpenseModal(<?= (int)$tx['id'] ?>)" 
                                class="px-2.5 py-1 text-[11px] font-medium text-[#8A8F98] hover:text-[#2FA89D] bg-gray-50 rounded-md"
                            >
                                Detail
                            </button>
                            <button 
                                type="button" 
                                onclick="openEditExpenseModal(<?= (int)$tx['id'] ?>)" 
                                class="px-2.5 py-1 text-[11px] font-medium text-blue-600 bg-blue-50 rounded-md"
                            >
                                Edit
                            </button>
                            <button 
                                type="button" 
                                onclick="openDeleteExpenseModal(<?= (int)$tx['id'] ?>, '<?= e(addslashes($tx['description'])) ?>', '<?= formatRupiah((float)$tx['amount']) ?>')" 
                                class="px-2.5 py-1 text-[11px] font-medium text-[#E56B6F] bg-red-50 rounded-md"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Pagination Bar -->
        <?php if ($totalPages > 1): ?>
            <div class="p-4 border-t border-[#E8EAED] flex items-center justify-between">
                <span class="text-xs text-[#8A8F98]">
                    Halaman <?= $currentPage ?> dari <?= $totalPages ?>
                </span>
                <div class="flex items-center gap-1.5">
                    <!-- Prev Page -->
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= expensePaginationUrl($currentPage - 1, $searchQuery) ?>" class="px-3 py-1.5 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors">
                            Sebelumnya
                        </a>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <?php if ($p == 1 || $p == $totalPages || abs($p - $currentPage) <= 1): ?>
                            <a href="<?= expensePaginationUrl($p, $searchQuery) ?>" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors <?= ($p == $currentPage) ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED]' ?>">
                                <?= $p ?>
                            </a>
                        <?php elseif ($p == 2 || $p == $totalPages - 1): ?>
                            <span class="px-1 text-xs text-[#8A8F98]">...</span>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Next Page -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= expensePaginationUrl($currentPage + 1, $searchQuery) ?>" class="px-3 py-1.5 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors">
                            Selanjutnya
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<!-- ===================================================== -->
<!-- 1. TAMBAH PENGELUARAN MODAL                           -->
<!-- ===================================================== -->
<div id="add-expense-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Tambah Pengeluaran</h3>
                <p class="text-xs text-[#8A8F98]">Catat pengeluaran baru dan potong saldo akun otomatis</p>
            </div>
            <button type="button" onclick="closeAddExpenseModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none">&times;</button>
        </div>

        <form action="<?= base_url('expenses') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Keterangan / Deskripsi -->
            <div>
                <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                    Keterangan Pengeluaran <span class="text-[#E56B6F]">*</span>
                </label>
                <input 
                    type="text" 
                    name="description" 
                    required 
                    placeholder="Contoh: Belanja Bulanan Supermarket" 
                    class="w-full px-3.5 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                >
            </div>

            <!-- Nominal & Tanggal Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nominal -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Nominal (Rp) <span class="text-[#E56B6F]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-[#8A8F98]">Rp</span>
                        <input 
                            type="text" 
                            name="amount" 
                            required 
                            id="add-expense-amount"
                            placeholder="50.000" 
                            class="w-full pl-9 pr-3.5 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B] font-semibold"
                        >
                    </div>
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Tanggal Transaksi <span class="text-[#E56B6F]">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="transaction_date" 
                        required 
                        value="<?= date('Y-m-d') ?>"
                        class="w-full px-3.5 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                    >
                </div>
            </div>

            <!-- Akun & Kategori Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Akun Sumber -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Sumber Akun / Dompet <span class="text-[#E56B6F]">*</span>
                    </label>
                    <select 
                        name="account_id" 
                        required 
                        class="w-full px-3 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                    >
                        <option value="">Pilih Akun</option>
                        <?php foreach ($accounts as $acc): ?>
                            <option value="<?= (int)$acc['id'] ?>">
                                <?= e($acc['name']) ?> (Saldo: <?= formatRupiah((float)$acc['current_balance']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Kategori Pengeluaran -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Kategori Pengeluaran <span class="text-[#E56B6F]">*</span>
                    </label>
                    <select 
                        name="category_id" 
                        required 
                        class="w-full px-3 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                    >
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>">
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeAddExpenseModal()" 
                    class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                >
                    Simpan Pengeluaran
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================================================== -->
<!-- 2. EDIT PENGELUARAN MODAL                             -->
<!-- ===================================================== -->
<div id="edit-expense-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Edit Pengeluaran</h3>
                <p class="text-xs text-[#8A8F98]">Perbarui detail atau nominal transaksi pengeluaran</p>
            </div>
            <button type="button" onclick="closeEditExpenseModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none">&times;</button>
        </div>

        <form action="<?= base_url('expenses/update') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" id="edit-expense-id" name="id" value="">

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                    Keterangan Pengeluaran <span class="text-[#E56B6F]">*</span>
                </label>
                <input 
                    type="text" 
                    id="edit-expense-description" 
                    name="description" 
                    required 
                    class="w-full px-3.5 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                >
            </div>

            <!-- Nominal & Tanggal Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nominal -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Nominal (Rp) <span class="text-[#E56B6F]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-[#8A8F98]">Rp</span>
                        <input 
                            type="text" 
                            id="edit-expense-amount" 
                            name="amount" 
                            required 
                            class="w-full pl-9 pr-3.5 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B] font-semibold"
                        >
                    </div>
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Tanggal Transaksi <span class="text-[#E56B6F]">*</span>
                    </label>
                    <input 
                        type="date" 
                        id="edit-expense-date" 
                        name="transaction_date" 
                        required 
                        class="w-full px-3.5 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                    >
                </div>
            </div>

            <!-- Akun & Kategori Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Akun Sumber -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Sumber Akun / Dompet <span class="text-[#E56B6F]">*</span>
                    </label>
                    <select 
                        id="edit-expense-account" 
                        name="account_id" 
                        required 
                        class="w-full px-3 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                    >
                        <?php foreach ($accounts as $acc): ?>
                            <option value="<?= (int)$acc['id'] ?>">
                                <?= e($acc['name']) ?> (Saldo: <?= formatRupiah((float)$acc['current_balance']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Kategori Pengeluaran -->
                <div>
                    <label class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
                        Kategori Pengeluaran <span class="text-[#E56B6F]">*</span>
                    </label>
                    <select 
                        id="edit-expense-category" 
                        name="category_id" 
                        required 
                        class="w-full px-3 py-2 text-xs bg-white border border-[#E8EAED] rounded-lg focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] text-[#2B2B2B]"
                    >
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>">
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeEditExpenseModal()" 
                    class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                >
                    Perbarui Pengeluaran
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================================================== -->
<!-- 3. DETAIL PENGELUARAN MODAL                           -->
<!-- ===================================================== -->
<div id="detail-expense-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Detail Pengeluaran</h3>
                <p class="text-xs text-[#8A8F98]">Informasi lengkap catatan transaksi</p>
            </div>
            <button type="button" onclick="closeDetailExpenseModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] focus:outline-none">&times;</button>
        </div>

        <div class="p-6 space-y-4">
            <div class="text-center p-4 rounded-xl bg-red-50 border border-red-100">
                <span class="text-[11px] text-[#8A8F98] font-medium block">Nominal Terpotong:</span>
                <div class="text-2xl font-bold text-[#E56B6F] mt-1" id="detail-expense-amount">
                    - Rp 0
                </div>
                <span class="text-xs text-[#8A8F98] mt-1 inline-block" id="detail-expense-date">
                    -
                </span>
            </div>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-[10px] text-[#8A8F98] uppercase block">Keterangan / Deskripsi:</span>
                    <p class="text-[#2B2B2B] font-semibold mt-0.5" id="detail-expense-description">-</p>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-[#E8EAED]">
                    <div>
                        <span class="text-[10px] text-[#8A8F98] uppercase block">Kategori:</span>
                        <span class="text-[#2B2B2B] font-medium mt-0.5 inline-block" id="detail-expense-category">-</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-[#8A8F98] uppercase block">Sumber Akun:</span>
                        <span class="text-[#2B2B2B] font-medium mt-0.5 inline-block" id="detail-expense-account">-</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-[#E8EAED] flex items-center justify-between text-[11px] text-[#8A8F98]">
                    <span>Dibuat: <strong id="detail-expense-created" class="text-[#2B2B2B]">-</strong></span>
                    <span>Diubah: <strong id="detail-expense-updated" class="text-[#2B2B2B]">-</strong></span>
                </div>
            </div>
        </div>

        <div class="px-6 py-3 bg-[#F5F6F8] border-t border-[#E8EAED] text-right">
            <button 
                type="button" 
                onclick="closeDetailExpenseModal()" 
                class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-white border border-[#E8EAED] hover:bg-gray-100 rounded-lg transition-colors focus:outline-none"
            >
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- ===================================================== -->
<!-- 4. DELETE PENGELUARAN CONFIRMATION MODAL              -->
<!-- ===================================================== -->
<div id="delete-expense-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <div class="p-6 text-center space-y-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-[#E56B6F] flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </div>

            <div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Hapus Pengeluaran?</h3>
                <p class="text-xs text-[#8A8F98] mt-1.5 leading-relaxed">
                    Apakah Anda yakin ingin menghapus pengeluaran <strong id="delete-expense-name" class="text-[#2B2B2B]"></strong> sebesar <span id="delete-expense-amount" class="font-bold text-[#E56B6F]"></span>? Data transaksi akan dihapus dan saldo akun akan disesuaikan kembali.
                </p>
            </div>

            <!-- Delete Form -->
            <form action="<?= base_url('expenses/delete') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="delete-expense-id" name="id" value="">

                <div class="pt-2 flex items-center justify-center gap-2.5">
                    <button 
                        type="button" 
                        onclick="closeDeleteExpenseModal()" 
                        class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#E56B6F] hover:bg-red-600 rounded-lg shadow-sm transition-colors focus:outline-none"
                    >
                        Ya, Hapus Pengeluaran
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Modal Control Scripts -->
<script src="<?= asset('js/expense.js') ?>"></script>
