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
    <p class="text-xs text-[#8A8F98]">Buat kata sandi baru untuk akun Anda</p>
</div>

<!-- Reset Password Form -->
<form action="<?= base_url('reset-password') ?>" method="POST" class="space-y-4">
    <?= csrf_field() ?>

    <!-- Kata Sandi Baru -->
    <div>
        <label for="password" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
            Kata Sandi Baru
        </label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            required 
            placeholder="•••••••••" 
            class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
        >
    </div>

    <!-- Konfirmasi Kata Sandi Baru -->
    <div>
        <label for="password_confirmation" class="block text-xs font-medium text-[#2B2B2B] mb-1.5">
            Konfirmasi Kata Sandi Baru
        </label>
        <input 
            type="password" 
            id="password_confirmation" 
            name="password_confirmation" 
            required 
            placeholder="•••••••••" 
            class="w-full px-3.5 py-2.5 bg-white border border-[#E8EAED] rounded-lg text-sm text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-colors"
        >
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button 
            type="submit" 
            class="w-full py-2.5 px-4 bg-[#2FA89D] hover:bg-[#27958B] text-white font-medium text-sm rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2FA89D] focus:ring-offset-1"
        >
            Simpan Kata Sandi Baru
        </button>
    </div>
</form>

<div class="mt-8 text-center text-xs text-[#8A8F98]">
    <a href="<?= base_url('login') ?>" class="font-medium text-[#2FA89D] hover:underline">
        Batal dan Masuk
    </a>
</div>
