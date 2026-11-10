<?php
/**
 * Target Keuangan (Financial Goals) Management Page
 */

$filterStatus = $filterStatus ?? 'all';
$searchQuery = $search ?? '';
$overallPercent = (int)($summary['overall_percentage'] ?? 0);
$circumference = 2 * M_PI * 40;
$strokeDashoffset = $circumference - ($circumference * ($overallPercent / 100));

$goalIconMap = [
    'target' => 'Target',
    'shield' => 'Darurat',
    'home' => 'Properti',
    'laptop' => 'Teknologi',
    'plane' => 'Travel',
    'trending-up' => 'Investasi',
    'graduation-cap' => 'Edukasi',
    'car' => 'Kendaraan',
];

function getGoalIconSvg(string $icon): string
{
    switch ($icon) {
        case 'shield':
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>';
        case 'home':
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>';
        case 'laptop':
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="2" y1="20" x2="22" y2="20"></line></svg>';
        case 'plane':
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path></svg>';
        case 'trending-up':
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>';
        case 'graduation-cap':
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>';
        case 'car':
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><path d="M9 17h6"></path><circle cx="17" cy="17" r="2"></circle></svg>';
        default:
            return '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>';
    }
}
?>

<div class="space-y-6">

    <!-- Page Title & "+ Tambah Target" Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                    Perencanaan Finansial
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#2B2B2B] tracking-tight">
                Target Keuangan
            </h1>
            <p class="text-xs text-[#8A8F98]">
                Tetapkan tujuan finansial, pantau progres tabungan, dan capai target bulanan Anda lebih terarah.
            </p>
        </div>

        <button
            type="button"
            onclick="openAddGoalModal()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Target</span>
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

    <!-- Hero Summary Row: Circular Progress + 4 Metric Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Overall Progress Ring Card (Matches Section 10 Target Card) -->
        <div class="lg:col-span-4 bg-white border border-[#E8EAED] rounded-xl p-5 shadow-sm flex items-center justify-between gap-4">
            <div class="min-w-0">
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block mb-1">
                    Progres Keseluruhan
                </span>
                <p class="text-lg sm:text-xl font-bold text-[#2B2B2B] truncate">
                    <?= formatRupiah($summary['total_saved']) ?>
                </p>
                <p class="text-xs text-[#8A8F98] mt-0.5">
                    dari <?= formatRupiah($summary['total_target']) ?>
                </p>
                <div class="mt-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                    Kurang <?= formatShortRupiah($summary['total_remaining']) ?>
                </div>
            </div>

            <div class="relative w-24 h-24 flex-shrink-0 flex items-center justify-center">
                <svg class="w-full h-full -rotate-90 transform" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke="#E8EAED" stroke-width="8" fill="transparent" />
                    <circle
                        cx="50"
                        cy="50"
                        r="40"
                        stroke="#2FA89D"
                        stroke-width="8"
                        stroke-linecap="round"
                        fill="transparent"
                        stroke-dasharray="<?= $circumference ?>"
                        stroke-dashoffset="<?= $strokeDashoffset ?>"
                        style="--ring-circumference: <?= $circumference ?>; --ring-offset: <?= $strokeDashoffset ?>;"
                        class="mt-ring-progress"
                    />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-lg font-bold text-[#2B2B2B]"><?= $overallPercent ?>%</span>
                </div>
            </div>
        </div>

        <!-- 2x2 Summary Metrics Cards -->
        <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Target Dana</span>
                    <span class="text-lg sm:text-xl font-bold text-[#2B2B2B]"><?= formatRupiah($summary['total_target']) ?></span>
                    <span class="text-[10px] text-[#8A8F98] block mt-0.5"><?= (int)$summary['total_count'] ?> target terdaftar</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <circle cx="12" cy="12" r="6"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                    </svg>
                </div>
            </div>

            <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Target Nabung / Bulan</span>
                    <span class="text-lg sm:text-xl font-bold text-[#2FA89D]"><?= formatRupiah($summary['total_monthly']) ?></span>
                    <span class="text-[10px] text-[#8A8F98] block mt-0.5">Komitmen bulanan aktif</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-[#2FA89D] flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
            </div>

            <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Target Berjalan</span>
                    <span class="text-lg sm:text-xl font-bold text-[#2B2B2B]"><?= (int)$summary['active_count'] ?> Target</span>
                    <span class="text-[10px] text-[#8A8F98] block mt-0.5">Sedang dalam proses menabung</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
            </div>

            <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Target Tercapai</span>
                    <span class="text-lg sm:text-xl font-bold text-[#2FA89D]"><?= (int)$summary['completed_count'] ?> Target</span>
                    <span class="text-[10px] text-[#8A8F98] block mt-0.5">Sudah memenuhi 100% dana</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#F0F9F8] text-[#2FA89D] flex items-center justify-center">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center bg-[#F5F6F8] p-1 rounded-lg border border-[#E8EAED] text-xs">
            <a
                href="<?= base_url('goals' . (!empty($searchQuery) ? '?search=' . urlencode($searchQuery) : '')) ?>"
                class="px-4 py-1.5 rounded-md font-medium transition-all <?= (!$filterStatus || $filterStatus === 'all') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-[#8A8F98] hover:text-[#2B2B2B]' ?>"
            >
                Semua (<?= (int)$summary['total_count'] ?>)
            </a>
            <a
                href="<?= base_url('goals?status=active' . (!empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '')) ?>"
                class="px-4 py-1.5 rounded-md font-medium transition-all <?= ($filterStatus === 'active') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-[#8A8F98] hover:text-[#2B2B2B]' ?>"
            >
                Berjalan (<?= (int)$summary['active_count'] ?>)
            </a>
            <a
                href="<?= base_url('goals?status=completed' . (!empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '')) ?>"
                class="px-4 py-1.5 rounded-md font-medium transition-all <?= ($filterStatus === 'completed') ? 'bg-[#2FA89D] text-white shadow-sm' : 'text-[#8A8F98] hover:text-[#2B2B2B]' ?>"
            >
                Tercapai (<?= (int)$summary['completed_count'] ?>)
            </a>
        </div>

        <form action="<?= base_url('goals') ?>" method="GET" class="flex items-center gap-2">
            <?php if (!empty($filterStatus) && $filterStatus !== 'all'): ?>
                <input type="hidden" name="status" value="<?= e($filterStatus) ?>">
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
                    placeholder="Cari target atau kategori..."
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
                    href="<?= base_url('goals' . ($filterStatus && $filterStatus !== 'all' ? '?status=' . e($filterStatus) : '')) ?>"
                    class="px-2 py-1.5 text-xs text-[#E56B6F] hover:underline"
                >
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Goals Grid Cards -->
    <?php if (empty($goals)): ?>
        <div class="bg-white border border-[#E8EAED] rounded-xl p-12 text-center shadow-sm">
            <div class="w-12 h-12 rounded-full bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <circle cx="12" cy="12" r="6"></circle>
                    <circle cx="12" cy="12" r="2"></circle>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#2B2B2B] mb-1">Belum Ada Target Keuangan</h3>
            <p class="text-xs text-[#8A8F98] mb-4">
                <?= !empty($searchQuery) ? 'Tidak ditemukan target yang cocok dengan kata kunci pencarian Anda.' : 'Mulai rencanakan impian finansial Anda dengan menambahkan target pertama.' ?>
            </p>
            <button
                type="button"
                onclick="openAddGoalModal()"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#2FA89D] text-white text-xs font-semibold rounded-lg shadow-sm hover:bg-[#27958B] transition-colors"
            >
                + Tambah Target Baru
            </button>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach ($goals as $g): ?>
                <?php
                    $targetAmt = (float)$g['target_amount'];
                    $currentAmt = (float)$g['current_amount'];
                    $monthlyAmt = (float)$g['monthly_target'];
                    $pct = $targetAmt > 0 ? min(100, (int)round(($currentAmt / $targetAmt) * 100)) : 0;
                    $remaining = max(0, $targetAmt - $currentAmt);
                    $isCompleted = ($g['status'] === 'completed') || ($pct >= 100);
                    $deadlineLabel = formatIndonesianDate($g['deadline_date']);
                ?>
                <div class="bg-white border border-[#E8EAED] hover:border-[#2FA89D]/50 rounded-xl p-5 shadow-sm flex flex-col justify-between transition-all">

                    <div>
                        <!-- Top Header: Icon + Title + Status -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 <?= $isCompleted ? 'bg-[#2FA89D] text-white' : 'bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]' ?>">
                                    <?= getGoalIconSvg($g['icon'] ?: 'target') ?>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-semibold uppercase tracking-wider text-[#2FA89D] block">
                                        <?= e($g['category_tag']) ?>
                                    </span>
                                    <h3 class="text-sm font-bold text-[#2B2B2B] truncate" title="<?= e($g['title']) ?>">
                                        <?= e($g['title']) ?>
                                    </h3>
                                </div>
                            </div>

                            <?php if ($isCompleted): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-[#2FA89D] border border-emerald-200 flex-shrink-0">
                                    Tercapai
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-[#F5F6F8] text-[#8A8F98] border border-[#E8EAED] flex-shrink-0">
                                    <?= e($deadlineLabel) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Description -->
                        <p class="text-xs text-[#8A8F98] mb-4 line-clamp-2 min-h-[32px]">
                            <?= e($g['description'] ?: 'Target tabungan pribadi MoneyTrack.') ?>
                        </p>

                        <!-- Amount & Progress Bar -->
                        <div class="p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E8EAED] mb-4">
                            <div class="flex items-baseline justify-between gap-2 mb-2">
                                <div>
                                    <span class="text-[10px] text-[#8A8F98] uppercase block">Terkumpul</span>
                                    <span class="text-base font-bold text-[#2B2B2B]"><?= formatRupiah($currentAmt) ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-[#8A8F98] uppercase block">Target</span>
                                    <span class="text-xs font-semibold text-[#2B2B2B]"><?= formatRupiah($targetAmt) ?></span>
                                </div>
                            </div>

                            <div class="w-full h-2 bg-[#E8EAED] rounded-full overflow-hidden mb-2">
                                <div
                                    class="h-full rounded-full transition-all duration-700 <?= $isCompleted ? 'bg-[#27958B]' : 'bg-[#2FA89D]' ?>"
                                    style="width: <?= $pct ?>%;"
                                ></div>
                            </div>

                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-[#2FA89D]"><?= $pct ?>% Tercapai</span>
                                <span class="text-[#8A8F98]">
                                    <?= $isCompleted ? 'Target selesai' : 'Kurang ' . formatShortRupiah($remaining) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Monthly Plan Row -->
                        <div class="flex items-center justify-between text-xs text-[#8A8F98] mb-4 px-1">
                            <span>Rencana Nabung / Bulan</span>
                            <span class="font-semibold text-[#2B2B2B]"><?= formatRupiah($monthlyAmt) ?></span>
                        </div>
                    </div>

                    <!-- Card Actions Footer -->
                    <div class="pt-3 border-t border-[#E8EAED] flex items-center justify-between gap-2">
                        <?php if (!$isCompleted): ?>
                            <button
                                type="button"
                                onclick="openDepositGoalModal(<?= (int)$g['id'] ?>, <?= e(json_encode((string)$g['title'])) ?>, <?= $pct ?>, <?= e(json_encode(formatRupiah($remaining))) ?>)"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-colors focus:outline-none"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Nabung</span>
                            </button>
                        <?php else: ?>
                            <button
                                type="button"
                                onclick="openDetailGoalModal(<?= (int)$g['id'] ?>)"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-[#F0F9F8] hover:bg-[#D6F0ED] text-[#2FA89D] border border-[#D6F0ED] text-xs font-semibold rounded-lg transition-colors focus:outline-none"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Lihat Ringkasan</span>
                            </button>
                        <?php endif; ?>

                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                onclick="openDetailGoalModal(<?= (int)$g['id'] ?>)"
                                class="p-2 rounded-lg border border-[#E8EAED] text-[#8A8F98] hover:text-[#2FA89D] hover:border-[#2FA89D]/40 transition-colors focus:outline-none"
                                title="Detail target"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            <button
                                type="button"
                                onclick="openEditGoalModal(<?= (int)$g['id'] ?>)"
                                class="p-2 rounded-lg border border-[#E8EAED] text-[#8A8F98] hover:text-[#2B2B2B] hover:bg-[#F5F6F8] transition-colors focus:outline-none"
                                title="Edit target"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            <button
                                type="button"
                                onclick="openDeleteGoalModal(<?= (int)$g['id'] ?>, <?= e(json_encode((string)$g['title'])) ?>)"
                                class="p-2 rounded-lg border border-[#E8EAED] text-[#8A8F98] hover:text-[#E56B6F] hover:bg-red-50 hover:border-red-200 transition-colors focus:outline-none"
                                title="Hapus target"
                            >
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- ===================================================== -->
<!-- 1. ADD GOAL MODAL                                     -->
<!-- ===================================================== -->
<div id="add-goal-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-lg overflow-hidden animate-in">
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Tambah Target Keuangan</h3>
            </div>
            <button type="button" onclick="closeAddGoalModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <form action="<?= base_url('goals') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="add-goal-title" class="block text-xs font-medium text-[#2B2B2B] mb-1">Nama Target</label>
                    <input type="text" id="add-goal-title" name="title" required maxlength="120" placeholder="Contoh: Dana Darurat" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
                <div>
                    <label for="add-goal-tag" class="block text-xs font-medium text-[#2B2B2B] mb-1">Label Kategori</label>
                    <input type="text" id="add-goal-tag" name="category_tag" maxlength="50" value="Tabungan" placeholder="Properti / Travel / Darurat" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="add-goal-target" class="block text-xs font-medium text-[#2B2B2B] mb-1">Target Dana (Rp)</label>
                    <input type="number" id="add-goal-target" name="target_amount" min="1" step="1000" required placeholder="50000000" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
                <div>
                    <label for="add-goal-current" class="block text-xs font-medium text-[#2B2B2B] mb-1">Dana Terkumpul Awal (Rp)</label>
                    <input type="number" id="add-goal-current" name="current_amount" min="0" step="1000" value="0" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="add-goal-monthly" class="block text-xs font-medium text-[#2B2B2B] mb-1">Target Nabung / Bulan (Rp)</label>
                    <input type="number" id="add-goal-monthly" name="monthly_target" min="0" step="1000" value="1000000" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
                <div>
                    <label for="add-goal-deadline" class="block text-xs font-medium text-[#2B2B2B] mb-1">Tanggal Target Tercapai</label>
                    <input type="date" id="add-goal-deadline" name="deadline_date" required value="<?= date('Y-m-d', strtotime('+12 months')) ?>" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
            </div>

            <div>
                <span class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Pilih Ikon Target</span>
                <div class="grid grid-cols-4 sm:grid-cols-8 gap-2">
                    <?php $first = true; foreach ($goalIconMap as $ik => $il): ?>
                        <label class="cursor-pointer" title="<?= e($il) ?>">
                            <input type="radio" name="icon" value="<?= e($ik) ?>" <?= $first ? 'checked' : '' ?> class="peer sr-only">
                            <div class="p-2 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] text-[#8A8F98] flex flex-col items-center justify-center gap-1 peer-checked:bg-[#F0F9F8] peer-checked:text-[#2FA89D] peer-checked:border-[#2FA89D] transition-all">
                                <?= getGoalIconSvg($ik) ?>
                                <span class="text-[9px] truncate max-w-full"><?= e($il) ?></span>
                            </div>
                        </label>
                    <?php $first = false; endforeach; ?>
                </div>
            </div>

            <div>
                <label for="add-goal-desc" class="block text-xs font-medium text-[#2B2B2B] mb-1">Catatan (Opsional)</label>
                <textarea id="add-goal-desc" name="description" rows="2" maxlength="255" placeholder="Deskripsi singkat rencana tabungan..." class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeAddGoalModal()" class="px-4 py-2 text-xs font-medium text-[#8A8F98] hover:text-[#2B2B2B] bg-[#F5F6F8] rounded-lg border border-[#E8EAED]">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm">Simpan Target</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================== -->
<!-- 2. DEPOSIT / TAMBAH NABUNG MODAL                      -->
<!-- ===================================================== -->
<div id="deposit-goal-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in">
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div>
                <span class="text-[10px] font-semibold uppercase tracking-wider text-[#2FA89D] block">Tambah Tabungan</span>
                <h3 id="deposit-goal-title" class="text-sm font-bold text-[#2B2B2B]">Target</h3>
            </div>
            <button type="button" onclick="closeDepositGoalModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <form action="<?= base_url('goals/deposit') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" id="deposit-goal-id" name="id" value="">

            <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex items-center justify-between text-xs">
                <span class="text-[#8A8F98]">Progres saat ini: <strong id="deposit-goal-pct" class="text-[#2FA89D]">0%</strong></span>
                <span class="text-[#8A8F98]">Sisa: <strong id="deposit-goal-remaining" class="text-[#2B2B2B]">Rp 0</strong></span>
            </div>

            <div>
                <label for="deposit-goal-amount" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nominal Setoran (Rp)</label>
                <input type="number" id="deposit-goal-amount" name="amount" min="1" step="1000" required placeholder="500000" class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm font-semibold text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
            </div>

            <div>
                <span class="block text-[11px] text-[#8A8F98] mb-1.5">Pilih Cepat Nominal:</span>
                <div class="grid grid-cols-4 gap-2">
                    <button type="button" onclick="setDepositAmount(100000)" class="py-1.5 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] hover:border-[#2FA89D] hover:text-[#2FA89D] text-xs font-medium transition-colors">+100rb</button>
                    <button type="button" onclick="setDepositAmount(250000)" class="py-1.5 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] hover:border-[#2FA89D] hover:text-[#2FA89D] text-xs font-medium transition-colors">+250rb</button>
                    <button type="button" onclick="setDepositAmount(500000)" class="py-1.5 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] hover:border-[#2FA89D] hover:text-[#2FA89D] text-xs font-medium transition-colors">+500rb</button>
                    <button type="button" onclick="setDepositAmount(1000000)" class="py-1.5 rounded-lg border border-[#E8EAED] bg-[#F5F6F8] hover:border-[#2FA89D] hover:text-[#2FA89D] text-xs font-medium transition-colors">+1jt</button>
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeDepositGoalModal()" class="px-4 py-2 text-xs font-medium text-[#8A8F98] hover:text-[#2B2B2B] bg-[#F5F6F8] rounded-lg border border-[#E8EAED]">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm">Simpan Setoran</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================== -->
<!-- 3. EDIT GOAL MODAL                                    -->
<!-- ===================================================== -->
<div id="edit-goal-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-lg overflow-hidden animate-in">
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <h3 class="text-sm font-bold text-[#2B2B2B]">Edit Target Keuangan</h3>
            <button type="button" onclick="closeEditGoalModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <form action="<?= base_url('goals/update') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" id="edit-goal-id" name="id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-goal-title" class="block text-xs font-medium text-[#2B2B2B] mb-1">Nama Target</label>
                    <input type="text" id="edit-goal-title" name="title" required maxlength="120" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
                <div>
                    <label for="edit-goal-tag" class="block text-xs font-medium text-[#2B2B2B] mb-1">Label Kategori</label>
                    <input type="text" id="edit-goal-tag" name="category_tag" maxlength="50" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-goal-target" class="block text-xs font-medium text-[#2B2B2B] mb-1">Target Dana (Rp)</label>
                    <input type="number" id="edit-goal-target" name="target_amount" min="1" step="1000" required class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
                <div>
                    <label for="edit-goal-current" class="block text-xs font-medium text-[#2B2B2B] mb-1">Dana Terkumpul (Rp)</label>
                    <input type="number" id="edit-goal-current" name="current_amount" min="0" step="1000" required class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-goal-monthly" class="block text-xs font-medium text-[#2B2B2B] mb-1">Target Nabung / Bulan (Rp)</label>
                    <input type="number" id="edit-goal-monthly" name="monthly_target" min="0" step="1000" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
                <div>
                    <label for="edit-goal-deadline" class="block text-xs font-medium text-[#2B2B2B] mb-1">Tanggal Target</label>
                    <input type="date" id="edit-goal-deadline" name="deadline_date" required class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                </div>
            </div>

            <div>
                <label for="edit-goal-icon" class="block text-xs font-medium text-[#2B2B2B] mb-1">Ikon</label>
                <select id="edit-goal-icon" name="icon" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]">
                    <?php foreach ($goalIconMap as $ik => $il): ?>
                        <option value="<?= e($ik) ?>"><?= e($il) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="edit-goal-desc" class="block text-xs font-medium text-[#2B2B2B] mb-1">Catatan</label>
                <textarea id="edit-goal-desc" name="description" rows="2" maxlength="255" class="w-full px-3.5 py-2 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeEditGoalModal()" class="px-4 py-2 text-xs font-medium text-[#8A8F98] hover:text-[#2B2B2B] bg-[#F5F6F8] rounded-lg border border-[#E8EAED]">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================== -->
<!-- 4. DETAIL GOAL MODAL                                  -->
<!-- ===================================================== -->
<div id="detail-goal-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in">
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div>
                <span id="detail-goal-tag" class="text-[10px] font-semibold uppercase tracking-wider text-[#2FA89D] block">Kategori</span>
                <h3 id="detail-goal-title" class="text-sm font-bold text-[#2B2B2B]">Detail Target</h3>
            </div>
            <button type="button" onclick="closeDetailGoalModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <div id="detail-goal-loading" class="p-8 text-center text-xs text-[#8A8F98]">
            Memuat rincian target...
        </div>

        <div id="detail-goal-body" class="hidden p-6 space-y-4">
            <p id="detail-goal-desc" class="text-xs text-[#8A8F98]"></p>

            <div class="p-4 rounded-xl bg-[#F5F6F8] border border-[#E8EAED] space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-[#8A8F98]">Progres Pencapaian</span>
                    <span id="detail-goal-pct" class="font-bold text-[#2FA89D]">0%</span>
                </div>
                <div class="w-full h-2.5 bg-[#E8EAED] rounded-full overflow-hidden">
                    <div id="detail-goal-bar" class="h-full bg-[#2FA89D] rounded-full transition-all duration-500" style="width: 0%;"></div>
                </div>
                <div class="flex items-center justify-between text-xs pt-1">
                    <span id="detail-goal-saved" class="font-bold text-[#2B2B2B]">Rp 0</span>
                    <span id="detail-goal-target" class="text-[#8A8F98]">Rp 0</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                    <span class="text-[10px] text-[#8A8F98] uppercase block">Sisa Kekurangan</span>
                    <span id="detail-goal-remaining" class="font-bold text-[#2B2B2B]">Rp 0</span>
                </div>
                <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                    <span class="text-[10px] text-[#8A8F98] uppercase block">Target Bulanan</span>
                    <span id="detail-goal-monthly" class="font-bold text-[#2FA89D]">Rp 0</span>
                </div>
                <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                    <span class="text-[10px] text-[#8A8F98] uppercase block">Batas Waktu</span>
                    <span id="detail-goal-deadline" class="font-semibold text-[#2B2B2B]">-</span>
                </div>
                <div class="p-3 rounded-lg bg-[#F5F6F8] border border-[#E8EAED]">
                    <span class="text-[10px] text-[#8A8F98] uppercase block">Estimasi Tercapai</span>
                    <span id="detail-goal-estimate" class="font-semibold text-[#2B2B2B]">-</span>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" onclick="closeDetailGoalModal()" class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg border border-[#E8EAED]">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ===================================================== -->
<!-- 5. DELETE GOAL MODAL                                  -->
<!-- ===================================================== -->
<div id="delete-goal-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-sm overflow-hidden p-6 text-center animate-in">
        <div class="w-12 h-12 rounded-full bg-red-50 text-[#E56B6F] flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            </svg>
        </div>
        <h3 class="text-base font-bold text-[#2B2B2B] mb-1">Hapus Target Keuangan?</h3>
        <p class="text-xs text-[#8A8F98] mb-5">
            Target <strong id="delete-goal-name" class="text-[#2B2B2B]"></strong> akan dihapus permanen.
        </p>

        <form action="<?= base_url('goals/delete') ?>" method="POST" class="flex items-center justify-center gap-2">
            <?= csrf_field() ?>
            <input type="hidden" id="delete-goal-id" name="id" value="">

            <button type="button" onclick="closeDeleteGoalModal()" class="flex-1 px-4 py-2 text-xs font-medium text-[#8A8F98] hover:text-[#2B2B2B] bg-[#F5F6F8] rounded-lg border border-[#E8EAED]">Batal</button>
            <button type="submit" class="flex-1 px-4 py-2 bg-[#E56B6F] hover:bg-red-600 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">Ya, Hapus</button>
        </form>
    </div>
</div>

<script src="<?= asset('js/goal.js') ?>"></script>

