<?php
/**
 * UI Element: Target Keuangan Card (Section 10)
 */
$targetAmount = $targetAmount ?? 200000000;
$currentSaved = $currentSaved ?? 150000000;
$monthlyTarget = $monthlyTarget ?? 20000000;
$targetMonth = $targetMonth ?? 'May, 2023';

$percentage = min(100, round(($currentSaved / $targetAmount) * 100));
$circumference = 2 * M_PI * 40; // r = 40
$strokeDashoffset = $circumference - ($circumference * ($percentage / 100));
?>

<!-- Target Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between">
    
    <!-- Top Header -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                Target Keuangan
            </span>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                <?= e($targetMonth) ?>
            </span>
        </div>

        <div class="text-2xl sm:text-3xl font-bold text-[#2B2B2B] tracking-tight">
            <?= formatRupiah($targetAmount) ?>
        </div>
    </div>

    <!-- Center: Circular Progress Graphic -->
    <div class="my-5 flex flex-col items-center justify-center">
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
                    class="transition-all duration-1000 ease-out"
                />
            </svg>
            
            <!-- Centered Text in Ring -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <span class="text-2xl font-bold text-[#2B2B2B] tracking-tight">
                    <?= $percentage ?>%
                </span>
                <span class="text-[10px] text-[#8A8F98] uppercase font-medium">Tercapai</span>
            </div>
        </div>
        <p class="text-xs text-[#8A8F98] mt-2">Target Keuangan 2023</p>
    </div>

    <!-- Bottom Breakdown -->
    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-[#E8EAED] text-xs">
        <div class="bg-[#F5F6F8] p-2.5 rounded-lg border border-[#E8EAED]">
            <span class="text-[10px] text-[#8A8F98] block uppercase">Target Bulan Ini</span>
            <span class="font-semibold text-[#2B2B2B]"><?= formatRupiah($monthlyTarget) ?></span>
        </div>
        <div class="bg-[#F5F6F8] p-2.5 rounded-lg border border-[#E8EAED]">
            <span class="text-[10px] text-[#8A8F98] block uppercase">Terkumpul</span>
            <span class="font-semibold text-[#2FA89D]"><?= formatRupiah($currentSaved) ?></span>
        </div>
    </div>

</div>
