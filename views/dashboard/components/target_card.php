<?php
/**
 * UI Element: Target Keuangan Card (Section 10)
 */
$targetAmount = $targetAmount ?? 200000000;
$currentSaved = $currentSaved ?? 150000000;
$monthlyTarget = $monthlyTarget ?? 20000000;
$targetMonth = $targetMonth ?? 'May, 2023';
$targetCaption = $targetCaption ?? 'Target Keuangan ' . date('Y');

$percentage = $targetAmount > 0 ? min(100, round(($currentSaved / $targetAmount) * 100)) : 0;
$remainingTarget = max(0, $targetAmount - $currentSaved);
$circumference = 2 * M_PI * 40; // r = 40
$strokeDashoffset = $circumference - ($circumference * ($percentage / 100));
?>

<!-- Target Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between h-full">
    
    <!-- Top Header -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <a href="<?= base_url('goals') ?>" class="text-xs font-medium text-[#8A8F98] hover:text-[#2FA89D] uppercase tracking-wider transition-colors">
                Target Keuangan
            </a>
            <a href="<?= base_url('goals') ?>" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-[#F0F9F8] hover:bg-[#E2F4F2] text-[#2FA89D] border border-[#D6F0ED] transition-colors">
                <span><?= e($targetMonth) ?></span>
            </a>
        </div>

        <div class="text-2xl sm:text-3xl font-bold text-[#2B2B2B] tracking-tight tabular-nums">
            <?= formatRupiah($targetAmount) ?>
        </div>
    </div>

    <!-- Center: Circular Progress Graphic -->
    <div class="my-4 flex flex-col items-center justify-center">
        <div class="relative w-32 h-32 flex items-center justify-center">
            <svg class="w-full h-full -rotate-90 transform" viewBox="0 0 100 100">
                <!-- Background Ring -->
                <circle 
                    cx="50" 
                    cy="50" 
                    r="40" 
                    stroke="#E8EAED" 
                    stroke-width="8" 
                    fill="transparent"
                />
                <!-- Progress Ring -->
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
                    class="transition-all duration-1000 ease-out mt-ring-progress"
                />
            </svg>
            
            <!-- Centered Text in Ring -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <span class="text-2xl font-bold text-[#2B2B2B] tracking-tight tabular-nums">
                    <?= $percentage ?>%
                </span>
                <span class="text-[10px] text-[#8A8F98] uppercase font-medium">Tercapai</span>
            </div>
        </div>
        <p class="text-xs font-medium text-[#2B2B2B] mt-2"><?= e($targetCaption) ?></p>
        <?php if ($targetAmount > 0): ?>
            <p class="text-[11px] text-[#8A8F98] mt-0.5 tabular-nums">
                <?= $remainingTarget <= 0 ? 'Semua target telah tercapai!' : 'Kurang ' . formatRupiah($remainingTarget) ?>
            </p>
        <?php else: ?>
            <a href="<?= base_url('goals') ?>" class="text-[11px] font-semibold text-[#2FA89D] hover:underline mt-1">
                + Buat Target Pertama &rarr;
            </a>
        <?php endif; ?>
    </div>

    <!-- Bottom Breakdown -->
    <div class="space-y-2.5 pt-3 border-t border-[#E8EAED]">
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="bg-[#F5F6F8] p-2.5 rounded-lg border border-[#E8EAED]">
                <span class="text-[10px] text-[#8A8F98] block uppercase">Target / Bln</span>
                <span class="font-semibold text-[#2B2B2B] tabular-nums"><?= formatRupiah($monthlyTarget) ?></span>
            </div>
            <div class="bg-[#F5F6F8] p-2.5 rounded-lg border border-[#E8EAED]">
                <span class="text-[10px] text-[#8A8F98] block uppercase">Terkumpul</span>
                <span class="font-semibold text-[#2FA89D] tabular-nums"><?= formatRupiah($currentSaved) ?></span>
            </div>
        </div>
        <div class="text-center">
            <a href="<?= base_url('goals') ?>" class="text-[11px] font-medium text-[#2FA89D] hover:underline">
                Kelola Target Keuangan &rarr;
            </a>
        </div>
    </div>

</div>
