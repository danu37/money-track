<!-- MoneyTrack Brand / Logo -->
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center gap-2 mb-2">
        <div class="w-8 h-8 rounded-lg bg-[#2FA89D] flex items-center justify-center text-white shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-[#2FA89D]">
            MoneyTrack
        </h1>
    </div>
    <p class="text-xs text-[#8A8F98]">Masuk ke akun keuangan pribadi Anda</p>
</div>

<!-- Flash Message / Alerts if any -->
<?php if (!empty($errorMessage)): ?>
    <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-200 text-xs text-[#E56B6F]">
        <?= e($errorMessage) ?>
    </div>
<?php endif; ?>
<?php if (!empty($successMessage)): ?>
    <div class="mb-5 p-3 rounded-lg bg-teal-50 border border-teal-200 text-xs text-[#2FA89D]">
        <?= e($successMessage) ?>
    </div>
<?php endif; ?>

<!-- Login Form -->
<form action="<?= base_url('login') ?>" method="POST" class="space-y-4">
    <?= csrf_field() ?>

    <!-- Email Field -->
    <div>
        <label for="email" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
            Email
        </label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            required 
            placeholder="johndoe@email.com" 
            value="<?= e($old['email'] ?? 'danu@gmail.com') ?>"
            class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
        >
    </div>

    <!-- Kata Sandi Field -->
    <div>
        <label for="password" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
            Kata Sandi
        </label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            required 
            placeholder="•••••••••" 
            value="password"
            class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
        >
    </div>

    <!-- Lupa Kata Sandi Link -->
    <div class="text-right">
        <a href="<?= base_url('forgot-password') ?>" class="text-xs font-medium text-[#2FA89D] hover:underline">
            Lupa Kata Sandi?
        </a>
    </div>

    <!-- Submit Button (Masuk) -->
    <div class="pt-1">
        <button 
            type="submit" 
            class="w-full py-2.5 px-4 bg-[#2FA89D] hover:bg-[#27958B] text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
        >
            Masuk
        </button>
    </div>
</form>

<!-- Divider: ─────── atau masuk dengan ─────── -->
<div class="relative my-6 text-center">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-[#E8EAED]"></div>
    </div>
    <div class="relative flex justify-center">
        <span class="bg-[#F5F6F8] px-3 text-xs text-[#8A8F98]">
            atau masuk dengan
        </span>
    </div>
</div>

<!-- Google Button -->
<div>
    <button 
        type="button" 
        class="w-full py-2.5 px-4 bg-[#F0F1F3] hover:bg-[#E8EAED] text-[#2B2B2B] font-medium text-sm rounded-lg transition-colors flex items-center justify-center gap-2.5 focus:outline-none"
    >
        <svg class="w-4 h-4" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Lanjutkan dengan Google</span>
    </button>
</div>

<!-- Switch Auth Link -->
<div class="mt-8 text-center text-xs text-[#8A8F98] space-y-1">
    <p>Belum Memiliki Akun?</p>
    <div>
        <a href="<?= base_url('register') ?>" class="font-medium text-[#2FA89D] hover:underline">
            Daftar Di sini
        </a>
    </div>
</div>
