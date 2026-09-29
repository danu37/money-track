<?php
/**
 * Saldo / Accounts Management Page (Phase 4 of AGENTS.md)
 */
?>

<div class="space-y-6">

    <!-- Page Title & Primary Actions Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F0F9F8] text-[#2FA89D] border border-[#D6F0ED]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2FA89D]"></span>
                    Manajemen Saldo
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#2B2B2B] tracking-tight">
                Saldo & Rekening
            </h1>
            <p class="text-xs text-[#8A8F98]">
                Kelola semua rekening bank, e-wallet, dan uang tunai Anda dalam satu tempat.
            </p>
        </div>

        <!-- Add Account Trigger Button -->
        <button 
            type="button" 
            onclick="openAddModal()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#2FA89D] hover:bg-[#27958B] text-white text-xs font-semibold rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Rekening</span>
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

    <!-- Summary Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Saldo Semua Rekening -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Total Saldo</span>
                <span class="text-lg sm:text-xl font-bold text-[#2B2B2B]"><?= formatRupiah($totalBalance) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5">Semua akun aktif</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-[#2FA89D]/10 text-[#2FA89D] flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </div>
        </div>

        <!-- Bank Accounts -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Rekening Bank</span>
                <span class="text-base sm:text-lg font-bold text-[#2B2B2B]"><?= formatRupiah($statsByType['bank']['balance']) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5"><?= $statsByType['bank']['count'] ?> akun terdaftar</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="21" x2="21" y2="21"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <polyline points="5 6 12 3 19 6"></polyline>
                    <line x1="4" y1="10" x2="4" y2="21"></line>
                    <line x1="20" y1="10" x2="20" y2="21"></line>
                </svg>
            </div>
        </div>

        <!-- E-Wallet -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">E-Wallet</span>
                <span class="text-base sm:text-lg font-bold text-[#2B2B2B]"><?= formatRupiah($statsByType['ewallet']['balance']) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5"><?= $statsByType['ewallet']['count'] ?> akun terdaftar</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                </svg>
            </div>
        </div>

        <!-- Cash / Tunai -->
        <div class="bg-white border border-[#E8EAED] rounded-xl p-4 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-[#8A8F98] uppercase tracking-wider block">Uang Tunai (Cash)</span>
                <span class="text-base sm:text-lg font-bold text-[#2B2B2B]"><?= formatRupiah($statsByType['cash']['balance']) ?></span>
                <span class="text-[10px] text-[#8A8F98] block mt-0.5"><?= $statsByType['cash']['count'] ?> akun terdaftar</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                    <line x1="12" y1="6" x2="12" y2="8"></line>
                    <line x1="12" y1="16" x2="12" y2="18"></line>
                </svg>
            </div>
        </div>

    </div>

    <!-- Responsive Grid of Account Cards -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-[#2B2B2B]">Daftar Rekening</h2>
            <span class="text-xs text-[#8A8F98]">Total <?= count($accounts) ?> Rekening</span>
        </div>

        <?php if (empty($accounts)): ?>
            <!-- Empty State -->
            <div class="bg-white border border-[#E8EAED] rounded-xl p-12 text-center shadow-sm">
                <div class="w-12 h-12 rounded-full bg-[#F5F6F8] text-[#8A8F98] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B] mb-1">Belum Ada Rekening</h3>
                <p class="text-xs text-[#8A8F98] mb-4">Mulai kelola keuangan Anda dengan menambahkan rekening pertama.</p>
                <button 
                    type="button" 
                    onclick="openAddModal()" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#2FA89D] text-white text-xs font-semibold rounded-lg shadow-sm hover:bg-[#27958B] transition-colors"
                >
                    + Tambah Rekening Pertama
                </button>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php 
                // Color maps for cards based on type
                $cardStyles = [
                    'bank' => [
                        'gradient' => 'from-[#171717] via-[#242424] to-[#1E293B]', // Dark charcoal modern
                        'badge_bg' => 'bg-blue-500/20 text-blue-300 border-blue-400/20',
                        'label' => 'Bank Account',
                        'icon' => 'bank'
                    ],
                    'ewallet' => [
                        'gradient' => 'from-[#0B3B36] via-[#104A44] to-[#171717]', // Deep teal charcoal
                        'badge_bg' => 'bg-[#2FA89D]/20 text-teal-300 border-[#2FA89D]/30',
                        'label' => 'E-Wallet',
                        'icon' => 'ewallet'
                    ],
                    'cash' => [
                        'gradient' => 'from-[#2FA89D] via-[#248B81] to-[#17625A]', // Vibrant MoneyTrack Teal
                        'badge_bg' => 'bg-white/20 text-white border-white/20',
                        'label' => 'Cash / Tunai',
                        'icon' => 'cash'
                    ],
                    'other' => [
                        'gradient' => 'from-[#312E81] via-[#1E1B4B] to-[#171717]', // Indigo charcoal
                        'badge_bg' => 'bg-indigo-500/20 text-indigo-300 border-indigo-400/20',
                        'label' => 'Other / Lainnya',
                        'icon' => 'other'
                    ]
                ];
                ?>

                <?php foreach ($accounts as $acc): ?>
                    <?php 
                    $typeKey = $acc['type'] ?? 'other';
                    $style = $cardStyles[$typeKey] ?? $cardStyles['other'];
                    
                    // Generate consistent masked number
                    $last4 = str_pad((string)(($acc['id'] * 1234) % 9000 + 1000), 4, '0', STR_PAD_LEFT);
                    $maskedNumber = ($typeKey === 'cash') ? 'Uang Tunai Fisik' : "•••• •••• •••• {$last4}";
                    ?>

                    <div class="bg-white border border-[#E8EAED] rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                        
                        <!-- Top Visual Credit/Bank Card Graphic -->
                        <div class="p-5 bg-gradient-to-br <?= $style['gradient'] ?> text-white relative overflow-hidden">
                            <!-- Background Circles for depth -->
                            <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
                            <div class="absolute right-8 -top-8 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

                            <!-- Header: Type & Action Dropdown -->
                            <div class="flex items-center justify-between mb-5 relative z-10">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border <?= $style['badge_bg'] ?>">
                                    <?= e($style['label']) ?>
                                </span>
                                <!-- Chip Icon -->
                                <div class="w-8 h-6 rounded bg-amber-400/80 border border-amber-300/40 flex items-center justify-center shadow-xs">
                                    <div class="w-5 h-3 border border-amber-600/40 rounded-xs"></div>
                                </div>
                            </div>

                            <!-- Account Name & Masked Number -->
                            <div class="relative z-10 mb-4">
                                <h3 class="text-lg font-bold text-white tracking-wide truncate">
                                    <?= e($acc['name']) ?>
                                </h3>
                                <p class="text-xs font-mono tracking-widest text-gray-300 mt-1">
                                    <?= e($maskedNumber) ?>
                                </p>
                            </div>

                            <!-- Calculated Real Balance on Card -->
                            <div class="relative z-10 pt-3 border-t border-white/10 flex items-end justify-between">
                                <div>
                                    <span class="text-[10px] text-gray-400 block uppercase">Saldo Saat Ini</span>
                                    <span class="text-base sm:text-lg font-bold text-white tracking-tight">
                                        <?= formatRupiah($acc['current_balance']) ?>
                                    </span>
                                </div>
                                <span class="text-[10px] text-teal-300 font-medium">Real-time</span>
                            </div>
                        </div>

                        <!-- Card Body: Financial Breakdown & Quick Actions -->
                        <div class="p-4 bg-white flex flex-col justify-between flex-1">
                            <!-- Details List -->
                            <div class="grid grid-cols-2 gap-2 text-xs py-2 border-b border-[#E8EAED]">
                                <div>
                                    <span class="text-[10px] text-[#8A8F98] uppercase block">Saldo Awal</span>
                                    <span class="font-medium text-[#2B2B2B]"><?= formatRupiah($acc['initial_balance']) ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-[#8A8F98] uppercase block">Total Transaksi</span>
                                    <span class="font-medium text-[#2B2B2B]"><?= (int)$acc['transaction_count'] ?> aktivitas</span>
                                </div>
                            </div>

                            <!-- Action Buttons Row -->
                            <div class="flex items-center justify-between pt-3 gap-2">
                                <!-- Detail Button -->
                                <button 
                                    type="button" 
                                    onclick="openDetailModal(<?= $acc['id'] ?>)"
                                    class="flex-1 py-1.5 px-2 bg-[#F5F6F8] hover:bg-[#E8EAED] text-[#2B2B2B] rounded-lg text-xs font-medium transition-colors flex items-center justify-center gap-1 focus:outline-none"
                                >
                                    <svg class="w-3.5 h-3.5 text-[#8A8F98]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <span>Detail</span>
                                </button>

                                <!-- Edit Button -->
                                <button 
                                    type="button" 
                                    onclick='openEditModal(<?= json_encode([
                                        "id" => $acc["id"],
                                        "name" => $acc["name"],
                                        "type" => $acc["type"],
                                        "initial_balance" => $acc["initial_balance"]
                                    ]) ?>)'
                                    class="flex-1 py-1.5 px-2 bg-[#F5F6F8] hover:bg-teal-50 hover:text-[#2FA89D] text-[#2B2B2B] rounded-lg text-xs font-medium transition-colors flex items-center justify-center gap-1 focus:outline-none"
                                >
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    <span>Ubah</span>
                                </button>

                                <!-- Delete Button -->
                                <button 
                                    type="button" 
                                    onclick="openDeleteModal(<?= $acc['id'] ?>, '<?= e(addslashes($acc['name'])) ?>')"
                                    class="py-1.5 px-2.5 bg-[#F5F6F8] hover:bg-red-50 hover:text-[#E56B6F] text-[#8A8F98] rounded-lg text-xs font-medium transition-colors flex items-center justify-center focus:outline-none"
                                    title="Hapus Rekening"
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

</div>

<!-- ===================================================== -->
<!-- 1. ADD ACCOUNT MODAL                                  -->
<!-- ===================================================== -->
<div id="add-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Tambah Rekening Baru</h3>
            </div>
            <button type="button" onclick="closeAddModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <!-- Form -->
        <form action="<?= base_url('accounts') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>

            <!-- Nama Rekening -->
            <div>
                <label for="add-name" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nama Rekening <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="add-name" 
                    name="name" 
                    required 
                    placeholder="Contoh: Bank BCA, Mandiri, DANA, Dompet Tunai" 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Tipe Rekening -->
            <div>
                <label for="add-type" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tipe Rekening <span class="text-red-500">*</span></label>
                <select 
                    id="add-type" 
                    name="type" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
                    <option value="bank">Bank (BCA, Mandiri, BNI, BRI, dll)</option>
                    <option value="ewallet">E-Wallet (GoPay, OVO, DANA, ShopeePay)</option>
                    <option value="cash">Uang Tunai (Cash / Dompet)</option>
                    <option value="other">Lainnya (Investasi, Aset, dll)</option>
                </select>
            </div>

            <!-- Saldo Awal -->
            <div>
                <label for="add-balance" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Saldo Awal (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-[#8A8F98]">Rp</span>
                    <input 
                        type="number" 
                        step="any"
                        id="add-balance" 
                        name="initial_balance" 
                        required 
                        min="0"
                        value="0"
                        placeholder="0" 
                        class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                </div>
                <span class="text-[10px] text-[#8A8F98] mt-1 block">Saldo awal saat rekening pertama kali dicatat.</span>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeAddModal()" 
                    class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                >
                    Simpan Rekening
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================================================== -->
<!-- 2. EDIT ACCOUNT MODAL                                 -->
<!-- ===================================================== -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]">Ubah Rekening</h3>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <!-- Form -->
        <form action="<?= base_url('accounts/update') ?>" method="POST" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" id="edit-id" name="id" value="">

            <!-- Nama Rekening -->
            <div>
                <label for="edit-name" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Nama Rekening <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="edit-name" 
                    name="name" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
            </div>

            <!-- Tipe Rekening -->
            <div>
                <label for="edit-type" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Tipe Rekening <span class="text-red-500">*</span></label>
                <select 
                    id="edit-type" 
                    name="type" 
                    required 
                    class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                >
                    <option value="bank">Bank (BCA, Mandiri, BNI, BRI, dll)</option>
                    <option value="ewallet">E-Wallet (GoPay, OVO, DANA, ShopeePay)</option>
                    <option value="cash">Uang Tunai (Cash / Dompet)</option>
                    <option value="other">Lainnya (Investasi, Aset, dll)</option>
                </select>
            </div>

            <!-- Saldo Awal -->
            <div>
                <label for="edit-balance" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">Saldo Awal (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-xs text-[#8A8F98]">Rp</span>
                    <input 
                        type="number" 
                        step="any"
                        id="edit-balance" 
                        name="initial_balance" 
                        required 
                        min="0"
                        class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D]"
                    >
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-[#E8EAED] flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    onclick="closeEditModal()" 
                    class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-xs font-semibold text-white bg-[#2FA89D] hover:bg-[#27958B] rounded-lg shadow-sm transition-colors focus:outline-none"
                >
                    Perbarui Rekening
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ===================================================== -->
<!-- 3. DETAIL ACCOUNT MODAL                               -->
<!-- ===================================================== -->
<div id="detail-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
    <div class="bg-white rounded-xl border border-[#E8EAED] shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-[#E8EAED] flex items-center justify-between bg-[#F5F6F8]">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-[#2FA89D] text-white flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                </div>
                <h3 class="text-sm font-bold text-[#2B2B2B]" id="detail-title">Detail Rekening</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-[#8A8F98] hover:text-[#2B2B2B] text-lg font-semibold focus:outline-none">&times;</button>
        </div>

        <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto" id="detail-content">
            <!-- Loading Spinner -->
            <div class="py-8 text-center" id="detail-loading">
                <svg class="animate-spin h-6 w-6 text-[#2FA89D] mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-xs text-[#8A8F98] mt-2">Memuat rincian rekening...</p>
            </div>

            <!-- Populated Content -->
            <div id="detail-data" class="hidden space-y-4">
                <!-- Summary Card -->
                <div class="bg-[#F5F6F8] border border-[#E8EAED] rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs text-[#8A8F98] uppercase tracking-wider" id="detail-type-badge">Tipe</span>
                        <span class="text-[11px] text-[#8A8F98]" id="detail-created">Dibuat</span>
                    </div>
                    <div class="text-2xl font-bold text-[#2B2B2B] mb-3" id="detail-current-balance">Rp 0</div>

                    <!-- Formula breakdown -->
                    <div class="grid grid-cols-3 gap-2 pt-3 border-t border-[#E8EAED] text-center text-xs">
                        <div>
                            <span class="text-[10px] text-[#8A8F98] block">Saldo Awal</span>
                            <span class="font-semibold text-[#2B2B2B]" id="detail-initial-balance">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#2FA89D] block">+ Pemasukan</span>
                            <span class="font-semibold text-[#2FA89D]" id="detail-total-income">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-[#E56B6F] block">- Pengeluaran</span>
                            <span class="font-semibold text-[#E56B6F]" id="detail-total-expense">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions on this account -->
                <div>
                    <h4 class="text-xs font-bold text-[#2B2B2B] uppercase tracking-wider mb-2">Riwayat Transaksi Rekening Ini</h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1" id="detail-transactions-list">
                        <!-- Transaksi populated dynamically -->
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-3 bg-[#F5F6F8] border-t border-[#E8EAED] text-right">
            <button 
                type="button" 
                onclick="closeDetailModal()" 
                class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-white border border-[#E8EAED] hover:bg-gray-100 rounded-lg transition-colors focus:outline-none"
            >
                Tutup
            </button>
        </div>

    </div>
</div>

<!-- ===================================================== -->
<!-- 4. DELETE CONFIRMATION MODAL                          -->
<!-- ===================================================== -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity">
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
                <h3 class="text-sm font-bold text-[#2B2B2B]">Hapus Rekening?</h3>
                <p class="text-xs text-[#8A8F98] mt-1.5 leading-relaxed">
                    Apakah Anda yakin ingin menghapus rekening <strong id="delete-account-name" class="text-[#2B2B2B]"></strong>? Semua transaksi yang terkait dengan rekening ini juga akan terhapus. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <!-- Delete Form -->
            <form action="<?= base_url('accounts/delete') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="delete-id" name="id" value="">

                <div class="pt-2 flex items-center justify-center gap-2.5">
                    <button 
                        type="button" 
                        onclick="closeDeleteModal()" 
                        class="px-4 py-2 text-xs font-medium text-[#2B2B2B] bg-[#F5F6F8] hover:bg-[#E8EAED] rounded-lg transition-colors focus:outline-none"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 text-xs font-semibold text-white bg-[#E56B6F] hover:bg-red-600 rounded-lg shadow-sm transition-colors focus:outline-none"
                    >
                        Ya, Hapus Rekening
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Modal Control Scripts -->
<script src="<?= asset('js/account.js') ?>"></script>
