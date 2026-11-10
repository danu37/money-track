/**
 * MoneyTrack - Dashboard Interactive Elements (Section 22)
 * Manages Account Card Carousel, Transaction History Tab/Search Filtering,
 * Upcoming Bills Live Filter, and Quick Add Transaction Modal
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------
    // 1. Account Cards Carousel Slider (Section 9)
    // -------------------------------------------------------------------
    const accountCards = document.querySelectorAll('.account-card-item');
    const accountDots = document.querySelectorAll('.account-dot');
    const prevBtn = document.getElementById('prev-account-btn');
    const nextBtn = document.getElementById('next-account-btn');
    const accountCounter = document.getElementById('account-counter');

    let currentAccountIndex = 0;
    const totalAccounts = accountCards.length;

    function showAccount(index) {
        if (totalAccounts === 0) return;

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

        accountDots.forEach((dot, idx) => {
            if (idx === currentAccountIndex) {
                dot.className = 'account-dot h-2 w-5 rounded-full bg-[#2FA89D] cursor-pointer transition-all focus:outline-none';
            } else {
                dot.className = 'account-dot h-2 w-2 rounded-full bg-gray-200 hover:bg-gray-300 cursor-pointer transition-all focus:outline-none';
            }
        });

        if (accountCounter) {
            accountCounter.textContent = `${currentAccountIndex + 1} / ${totalAccounts}`;
        }
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
    // 2. Transaction Tabs & Navbar Live Search Filtering
    // -------------------------------------------------------------------
    const tabButtons = document.querySelectorAll('.tx-tab-btn');
    const txItems = document.querySelectorAll('.tx-item');
    const txEmptyState = document.getElementById('tx-empty-state');
    const billItems = document.querySelectorAll('.bill-item');
    const billsSearchEmpty = document.getElementById('bills-search-empty');
    const globalSearchInput = document.getElementById('global-search-input');

    let activeTxTab = 'all';
    let currentSearchTerm = '';

    function applyOverviewFilters() {
        const query = currentSearchTerm.trim().toLowerCase();

        // Filter Transactions by tab + search query
        let visibleTxCount = 0;
        txItems.forEach((item) => {
            const itemType = item.getAttribute('data-type') || '';
            const searchBlob = item.getAttribute('data-search') || item.textContent.toLowerCase();

            const matchesTab = activeTxTab === 'all' || itemType === activeTxTab;
            const matchesSearch = query === '' || searchBlob.includes(query);

            if (matchesTab && matchesSearch) {
                item.style.display = 'flex';
                visibleTxCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (txEmptyState) {
            if (txItems.length > 0 && visibleTxCount === 0) {
                txEmptyState.classList.remove('hidden');
            } else {
                txEmptyState.classList.add('hidden');
            }
        }

        // Filter Upcoming Bills by search query when on Overview
        let visibleBillsCount = 0;
        billItems.forEach((item) => {
            const searchBlob = item.getAttribute('data-search') || item.textContent.toLowerCase();
            if (query === '' || searchBlob.includes(query)) {
                item.style.display = 'flex';
                visibleBillsCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (billsSearchEmpty) {
            if (billItems.length > 0 && visibleBillsCount === 0) {
                billsSearchEmpty.classList.remove('hidden');
            } else {
                billsSearchEmpty.classList.add('hidden');
            }
        }
    }

    tabButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            activeTxTab = btn.getAttribute('data-tab') || 'all';

            tabButtons.forEach((b) => {
                b.classList.remove('active', 'bg-[#2FA89D]', 'text-white', 'shadow-sm');
                b.classList.add('text-[#8A8F98]');
            });

            btn.classList.add('active', 'bg-[#2FA89D]', 'text-white', 'shadow-sm');
            btn.classList.remove('text-[#8A8F98]');

            applyOverviewFilters();
        });
    });

    if (globalSearchInput) {
        globalSearchInput.addEventListener('input', () => {
            currentSearchTerm = globalSearchInput.value || '';
            applyOverviewFilters();
        });
    }

    // -------------------------------------------------------------------
    // 3. Quick Add Transaction Modal Controls
    // -------------------------------------------------------------------
    const quickModal = document.getElementById('quick-tx-modal');
    if (quickModal) {
        // Initialize category list to match default 'expense'
        setQuickTxType(document.getElementById('quick-tx-type')?.value || 'expense');

        quickModal.addEventListener('click', (e) => {
            if (e.target === quickModal) {
                closeQuickTxModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !quickModal.classList.contains('hidden')) {
                closeQuickTxModal();
            }
        });
    }
});

function setQuickTxType(type) {
    const normalizedType = type === 'income' ? 'income' : 'expense';
    const typeInput = document.getElementById('quick-tx-type');
    const expenseBtn = document.getElementById('quick-type-btn-expense');
    const incomeBtn = document.getElementById('quick-type-btn-income');
    const categorySelect = document.getElementById('quick-tx-category');

    if (typeInput) typeInput.value = normalizedType;

    const activeClasses = ['border-[#2FA89D]', 'bg-[#F0F9F8]', 'text-[#2FA89D]'];
    const inactiveClasses = ['border-[#E8EAED]', 'bg-white', 'text-[#8A8F98]'];

    if (expenseBtn && incomeBtn) {
        if (normalizedType === 'expense') {
            expenseBtn.classList.add(...activeClasses);
            expenseBtn.classList.remove(...inactiveClasses);
            incomeBtn.classList.remove(...activeClasses);
            incomeBtn.classList.add(...inactiveClasses);
        } else {
            incomeBtn.classList.add(...activeClasses);
            incomeBtn.classList.remove(...inactiveClasses);
            expenseBtn.classList.remove(...activeClasses);
            expenseBtn.classList.add(...inactiveClasses);
        }
    }

    if (categorySelect) {
        let firstMatchValue = '';
        let currentVisibleStillValid = false;

        Array.from(categorySelect.options).forEach((opt) => {
            const optType = opt.getAttribute('data-type');
            const matches = !optType || optType === normalizedType;
            opt.hidden = !matches;
            opt.disabled = !matches;
            if (matches) {
                if (!firstMatchValue) firstMatchValue = opt.value;
                if (opt.value === categorySelect.value) currentVisibleStillValid = true;
            }
        });

        if (!currentVisibleStillValid && firstMatchValue) {
            categorySelect.value = firstMatchValue;
        }
    }
}

function openQuickTxModal() {
    const modal = document.getElementById('quick-tx-modal');
    if (!modal) return;
    setQuickTxType('expense');
    modal.classList.remove('hidden');
    const descInput = document.getElementById('quick-tx-desc');
    if (descInput) {
        setTimeout(() => descInput.focus(), 50);
    }
}

function closeQuickTxModal() {
    const modal = document.getElementById('quick-tx-modal');
    if (modal) modal.classList.add('hidden');
}
