/**
 * MoneyTrack - Dashboard Interactive Elements (Section 22)
 * Manages Account Card Carousel and Transaction History Tab Filtering
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // -------------------------------------------------------------------
    // 1. Account Cards Carousel Slider (Section 9)
    // -------------------------------------------------------------------
    const accountCards = document.querySelectorAll('.account-card-item');
    const accountDots = document.querySelectorAll('.account-dot');
    const prevBtn = document.getElementById('prev-account-btn');
    const nextBtn = document.getElementById('next-account-btn');
    
    let currentAccountIndex = 0;
    const totalAccounts = accountCards.length;

    function showAccount(index) {
        if (totalAccounts === 0) return;

        // Wrap around
        if (index < 0) index = totalAccounts - 1;
        if (index >= totalAccounts) index = 0;
        currentAccountIndex = index;

        accountCards.forEach((card, idx) => {
            if (idx === currentAccountIndex) {
                card.classList.remove('hidden');
                card.classList.add('block');
            } else {
                card.classList.add('hidden');
                card.classList.remove('block');
            }
        });

        // Update Dots
        accountDots.forEach((dot, idx) => {
            if (idx === currentAccountIndex) {
                dot.className = 'account-dot w-5 h-2 rounded-full bg-[#2FA89D] transition-all';
            } else {
                dot.className = 'account-dot w-2 h-2 rounded-full bg-gray-200 transition-all';
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            showAccount(currentAccountIndex - 1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            showAccount(currentAccountIndex + 1);
        });
    }

    accountDots.forEach((dot) => {
        dot.addEventListener('click', () => {
            const idx = parseInt(dot.getAttribute('data-index') || '0', 10);
            showAccount(idx);
        });
    });

    // -------------------------------------------------------------------
    // 2. Transaction History Filter Tabs (Section 12: All, Revenue, Expenses)
    // -------------------------------------------------------------------
    const tabButtons = document.querySelectorAll('.tx-tab-btn');
    const txItems = document.querySelectorAll('.tx-item');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const selectedTab = btn.getAttribute('data-tab');

            // Toggle active button style
            tabButtons.forEach(b => {
                b.classList.remove('active', 'bg-[#2FA89D]', 'text-white', 'shadow-sm');
                b.classList.add('text-[#8A8F98]');
            });

            btn.classList.add('active', 'bg-[#2FA89D]', 'text-white', 'shadow-sm');
            btn.classList.remove('text-[#8A8F98]');

            // Filter item rows
            txItems.forEach(item => {
                const itemType = item.getAttribute('data-type');
                if (selectedTab === 'all' || itemType === selectedTab) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

});
