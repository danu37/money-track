<?php
/**
 * UI Element: Total Saldo & Selected Account Card (Section 9)
 */
$totalBalance = $totalBalance ?? 100000000;
$accounts = $accounts ?? [
    [
        'id' => 1,
        'name' => 'Credit Card',
        'type' => 'bank',
        'number' => '**** 2598',
        'balance' => 28500000,
        'holder' => 'Danu Anggo',
        'expires' => '08/28',
        'color' => 'from-[#1F2937] to-[#111827]',
        'badge' => 'Mastercard'
    ],
    [
        'id' => 2,
        'name' => 'BCA Account',
        'type' => 'bank',
        'number' => '**** 8841',
        'balance' => 54200000,
        'holder' => 'Danu Anggo',
        'expires' => '12/27',
        'color' => 'from-[#0F3935] to-[#171717]',
        'badge' => 'BCA'
    ],
    [
        'id' => 3,
        'name' => 'Dompet Tunai',
        'type' => 'cash',
        'number' => 'Cash Wallet',
        'balance' => 17300000,
        'holder' => 'Danu Anggo',
        'expires' => 'N/A',
        'color' => 'from-[#2FA89D] to-[#1C6962]',
        'badge' => 'Cash'
    ]
];
$accountCount = count($accounts);
?>

<!-- Total Saldo & Account Card Container -->
<div class="bg-white border border-[#E8EAED] rounded-xl p-5 sm:p-6 shadow-sm flex flex-col justify-between h-full">
    
    <!-- Header: Total Saldo & Filter Pill -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-[#8A8F98] uppercase tracking-wider">
                Total Saldo
            </span>
            <a
                href="<?= base_url('accounts') ?>"
                title="Kelola semua rekening"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F6F8] hover:bg-[#F0F9F8] text-[#2B2B2B] hover:text-[#2FA89D] border border-[#E8EAED] hover:border-[#D6F0ED] transition-colors"
            >
                <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                <span>Semua Akun (<?= $accountCount ?>)</span>
            </a>
        </div>

        <!-- Big Financial Number -->
        <div class="text-2xl sm:text-3xl font-bold text-[#2B2B2B] tracking-tight tabular-nums mb-5">
            <?= formatRupiah($totalBalance) ?>
        </div>
    </div>

    <!-- Selected Account Card Display with Interactive Carousel Controls -->
    <div class="relative mt-2">
        <?php if (empty($accounts)): ?>
            <div class="border border-dashed border-[#E8EAED] bg-[#F5F6F8] rounded-xl p-5 text-center space-y-2.5">
                <p class="text-xs font-semibold text-[#2B2B2B]">Belum Ada Rekening</p>
                <p class="text-[11px] text-[#8A8F98]">Tambahkan akun kas, bank, atau e-wallet untuk melacak saldo.</p>
                <a
                    href="<?= base_url('accounts') ?>"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-medium transition-colors"
                >
                    + Tambah Rekening
                </a>
            </div>
        <?php else: ?>
            <div class="flex items-center justify-between mb-2 text-xs font-medium text-[#8A8F98]">
                <span>Kartu Aktif</span>
                <div class="flex items-center gap-1.5">
                    <span id="account-counter" class="text-[11px] font-medium text-[#8A8F98] mr-1 tabular-nums">
                        1 / <?= $accountCount ?>
                    </span>
                    <button 
                        id="prev-account-btn" 
                        type="button" 
                        <?= $accountCount <= 1 ? 'disabled' : '' ?>
                        class="w-6 h-6 rounded-md border border-[#E8EAED] flex items-center justify-center text-[#2B2B2B] hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors focus:outline-none"
                        aria-label="Rekening sebelumnya"
                    >
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button 
                        id="next-account-btn" 
                        type="button" 
                        <?= $accountCount <= 1 ? 'disabled' : '' ?>
                        class="w-6 h-6 rounded-md border border-[#E8EAED] flex items-center justify-center text-[#2B2B2B] hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors focus:outline-none"
                        aria-label="Rekening berikutnya"
                    >
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Account Cards Stack (JavaScript-controlled slider) -->
            <div id="accounts-container" class="relative overflow-hidden rounded-xl">
                <?php foreach ($accounts as $idx => $acc): ?>
                    <div 
                        class="account-card-item transition-all duration-300 <?= ($idx === 0) ? 'block' : 'hidden' ?>"
                        data-index="<?= $idx ?>"
                    >
                        <div class="bg-gradient-to-br <?= $acc['color'] ?> text-white p-5 rounded-xl shadow-sm relative overflow-hidden">
                            
                            <!-- Background Card Pattern -->
                            <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
                            <div class="absolute right-10 -top-6 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

                            <!-- Top row: Card type & Badge -->
                            <div class="flex items-center justify-between mb-6 relative z-10">
                                <div>
                                    <span class="text-[11px] font-medium text-gray-300 block">Account Type</span>
                                    <span class="text-sm font-semibold text-white tracking-wide"><?= e($acc['name']) ?></span>
                                </div>
                                <!-- Card Chip / Badge -->
                                <div class="px-2.5 py-1 rounded bg-white/10 backdrop-blur-sm text-[11px] font-bold text-teal-300 uppercase tracking-wider border border-white/10">
                                    <?= e($acc['badge']) ?>
                                </div>
                            </div>

                            <!-- Middle row: Masked Number -->
                            <div class="mb-4 text-base sm:text-lg font-mono tracking-widest text-gray-200 relative z-10">
                                <?= e($acc['number']) ?>
                            </div>

                            <!-- Bottom row: Holder & Saldo -->
                            <div class="flex items-end justify-between text-xs relative z-10 pt-2 border-t border-white/10">
                                <div>
                                    <span class="text-[10px] text-gray-400 block uppercase">Pemegang Kartu</span>
                                    <span class="font-medium text-gray-100"><?= e($acc['holder']) ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-gray-400 block uppercase">Saldo Kartu</span>
                                    <span class="font-bold text-white tabular-nums"><?= formatRupiah($acc['balance']) ?></span>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Dots -->
            <div class="flex justify-center items-center gap-1.5 mt-3" id="account-dots">
                <?php foreach ($accounts as $idx => $acc): ?>
                    <button
                        type="button"
                        aria-label="Pilih rekening <?= e($acc['name']) ?>"
                        class="account-dot h-2 rounded-full cursor-pointer transition-all focus:outline-none <?= ($idx === 0) ? 'bg-[#2FA89D] w-5' : 'bg-gray-200 w-2 hover:bg-gray-300' ?>"
                        data-index="<?= $idx ?>"
                    ></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>
