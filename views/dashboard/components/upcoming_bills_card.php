<?php
/**
 * UI Element: Upcoming Bills / Tagihan Mendatang (Section 11)
 */
$upcomingBills = $upcomingBills ?? [
    [
        'day' => '15',
        'month' => 'Mar',
        'name' => 'Figma',
        'type' => 'Bulanan',
        'amount' => 900000,
        'icon_bg' => 'bg-[#F24E1E]/10 text-[#F24E1E]',
        'icon_type' => 'figma'
    ],
    [
        'day' => '16',
        'month' => 'Jun',
        'name' => 'Adobe',
        'type' => 'Tahunan',
        'amount' => 500000,
        'icon_bg' => 'bg-[#FF0000]/10 text-[#FF0000]',
        'icon_type' => 'adobe'
    ],
    [
        'day' => '22',
        'month' => 'Jun',
        'name' => 'Spotify Family',
        'type' => 'Bulanan',
        'amount' => 86000,
        'icon_bg' => 'bg-[#1DB954]/10 text-[#1DB954]',
        'icon_type' => 'spotify'
    ],
    [
        'day' => '28',
        'month' => 'Jun',
        'name' => 'Internet & WiFi',
        'type' => 'Bulanan',
        'amount' => 375000,
        'icon_bg' => 'bg-[#2FA89D]/10 text-[#2FA89D]',
        'icon_type' => 'wifi'
    ]
];

$totalBillsCount = $totalBillsCount ?? count($upcomingBills);
$totalBillsAmount = $totalBillsAmount ?? array_sum(array_map(fn($b) => (float)($b['amount'] ?? 0), $upcomingBills));
$displayBills = array_slice($upcomingBills, 0, 4);
?>

<!-- Upcoming Bills Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between h-full">
    
    <!-- Top Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                Tagihan Mendatang
            </h3>
            <span class="text-sm font-bold text-[#2B2B2B]">Jadwal Pembayaran</span>
        </div>
        <a href="<?= base_url('bills') ?>" class="text-xs text-[#2FA89D] hover:underline font-medium inline-flex items-center gap-1">
            <span>Lihat Semua</span>
            <span aria-hidden="true">&rarr;</span>
        </a>
    </div>

    <!-- Bills List (max 4 items for balanced card height) -->
    <?php if (empty($displayBills)): ?>
        <div class="flex-1 flex flex-col items-center justify-center py-8 text-center">
            <div class="w-10 h-10 rounded-full bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center mb-2.5">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <p class="text-xs font-semibold text-[#2B2B2B]">Tidak Ada Tagihan</p>
            <p class="text-[11px] text-[#8A8F98] mt-0.5 mb-3">Catat langganan atau tagihan rutin Anda.</p>
            <a href="<?= base_url('bills') ?>" class="px-3 py-1.5 rounded-lg bg-[#F0F9F8] hover:bg-[#E2F4F2] text-[#2FA89D] border border-[#D6F0ED] text-xs font-medium transition-colors">
                + Tambah Tagihan
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-2.5 flex-1" id="bills-list">
            <?php foreach ($displayBills as $idx => $bill): ?>
                <?php $iconType = strtolower(trim((string)($bill['icon_type'] ?? ''))); ?>
                <div
                    class="bill-item flex items-center justify-between p-2.5 rounded-lg hover:bg-[#F5F6F8] transition-colors border border-transparent hover:border-[#E8EAED] mt-enter-soft"
                    style="--mt-delay: <?= 200 + ($idx * 35) ?>ms;"
                    data-search="<?= e(strtolower(($bill['name'] ?? '') . ' ' . ($bill['type'] ?? ''))) ?>"
                >
                    <!-- Left: Date Box & Info -->
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Date Box -->
                        <div class="w-10 h-10 rounded-lg bg-[#F5F6F8] border border-[#E8EAED] flex flex-col items-center justify-center flex-shrink-0 text-center">
                            <span class="text-xs font-bold text-[#2B2B2B] leading-none tabular-nums"><?= e($bill['day']) ?></span>
                            <span class="text-[9px] font-medium text-[#8A8F98] uppercase leading-tight"><?= e($bill['month']) ?></span>
                        </div>

                        <!-- Small Brand Avatar & Details -->
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-full <?= $bill['icon_bg'] ?> flex items-center justify-center font-bold text-xs flex-shrink-0">
                                <?php if (str_contains($iconType, 'figma')): ?>
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12a4 4 0 1 1 8 0 4 4 0 0 1-8 0zM4 12a4 4 0 0 1 8 0v4a4 4 0 1 1-8 0v-4zm0-8a4 4 0 0 1 8 0v4H8a4 4 0 0 1-4-4zm8 0a4 4 0 1 1 8 0 4 4 0 0 1-8 0zm-4 16a4 4 0 0 1 4-4v4a4 4 0 1 1-4 0z"/>
                                    </svg>
                                <?php elseif (str_contains($iconType, 'adobe')): ?>
                                    <span class="font-extrabold text-xs">Ai</span>
                                <?php elseif (str_contains($iconType, 'spotify')): ?>
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.586 14.424a.623.623 0 0 1-.858.207c-2.348-1.434-5.304-1.758-8.784-.963a.625.625 0 0 1-.277-1.22c3.805-.87 7.076-.503 9.712 1.118a.624.624 0 0 1 .207.858zm1.225-2.723a.78.78 0 0 1-1.074.257c-2.688-1.652-6.786-2.13-9.965-1.165a.781.781 0 0 1-.454-1.493c3.632-1.103 8.163-.574 11.236 1.327a.78.78 0 0 1 .257 1.074zm.105-2.835C14.693 8.94 9.38 8.766 6.305 9.7a.936.936 0 1 1-.546-1.791c3.528-1.07 9.395-.87 13.064 1.309a.936.936 0 0 1-1.047 1.554z"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-[#2B2B2B] leading-snug truncate">
                                    <?= e($bill['name']) ?>
                                </p>
                                <p class="text-[11px] text-[#8A8F98] truncate">
                                    <?= e($bill['type']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Compact Amount -->
                    <div class="text-right flex-shrink-0 pl-2">
                        <span class="text-xs font-bold text-[#2B2B2B] block tabular-nums">
                            <?= formatShortRupiah($bill['amount']) ?>
                        </span>
                        <span class="text-[10px] text-[#8A8F98] tabular-nums">
                            <?= formatRupiah($bill['amount']) ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>

            <div id="bills-search-empty" class="hidden py-6 text-center text-xs text-[#8A8F98]">
                Tidak ada tagihan yang cocok dengan pencarian.
            </div>
        </div>
    <?php endif; ?>

    <!-- Bottom summary -->
    <div class="mt-4 pt-3 border-t border-[#E8EAED] flex items-center justify-between text-xs text-[#8A8F98]">
        <span>Total <?= (int)$totalBillsCount ?> Tagihan<?= $totalBillsCount > 4 ? ' (4 terdekat)' : '' ?></span>
        <span class="font-semibold text-[#2B2B2B] tabular-nums"><?= formatRupiah($totalBillsAmount) ?></span>
    </div>

</div>
