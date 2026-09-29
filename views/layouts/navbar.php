<?php
$userName = $userName ?? 'Danu';
$currentDate = $currentDate ?? date('d M Y');
?>

<!-- Dashboard Header / Navbar (Section 8) -->
<header class="h-16 bg-white border-b border-[#E8EAED] px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
    
    <!-- Left: Hamburger toggle (mobile) + Greeting & Date -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Mobile Sidebar Toggle -->
        <button id="sidebar-open-btn" type="button" class="lg:hidden p-1.5 rounded-md text-[#2B2B2B] hover:bg-gray-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Greeting & Subtext -->
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-base sm:text-lg font-semibold text-[#2B2B2B]">
                    Hello <?= e(explode(' ', $userName)[0]) ?>
                </h1>
                <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-[#8A8F98]"></span>
                <span class="hidden sm:inline-block text-xs text-[#8A8F98]">
                    <?= e($currentDate) ?>
                </span>
            </div>
            <p class="sm:hidden text-[11px] text-[#8A8F98]">
                <?= e($currentDate) ?>
            </p>
        </div>
    </div>

    <!-- Right: Notification Bell + Search Field -->
    <div class="flex items-center gap-3 sm:gap-4">
        
        <!-- Search Field -->
        <div class="relative hidden sm:block">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#8A8F98]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <input 
                type="text" 
                placeholder="Search here" 
                class="w-44 md:w-60 pl-9 pr-3.5 py-1.5 bg-[#F5F6F8] border border-[#E8EAED] rounded-lg text-xs text-[#2B2B2B] placeholder-[#8A8F98] focus:outline-none focus:ring-1 focus:ring-[#2FA89D] focus:border-[#2FA89D] transition-all"
            >
        </div>

        <!-- Notification Bell Icon with Indicator -->
        <div class="relative">
            <button type="button" class="w-9 h-9 rounded-lg border border-[#E8EAED] flex items-center justify-center text-[#2B2B2B] hover:bg-gray-50 focus:outline-none transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <!-- Notification Dot Badge -->
                <span class="absolute top-2 right-2 w-2 h-2 bg-[#2FA89D] rounded-full ring-2 ring-white"></span>
            </button>
        </div>

    </div>

</header>
