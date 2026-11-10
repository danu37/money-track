/**
 * MoneyTrack - Main Application Script
 */

document.addEventListener('DOMContentLoaded', () => {
    // Sidebar Mobile Drawer Controls
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('sidebar-open-btn');
    const closeBtn = document.getElementById('sidebar-close-btn');

    function openSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
        }
    }

    function closeSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
        }
    }

    if (openBtn) openBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Notification Bell Dropdown
    const notifBtn = document.getElementById('notification-btn');
    const notifDropdown = document.getElementById('notification-dropdown');
    const notifWrapper = document.getElementById('notification-wrapper');

    function closeNotifDropdown() {
        if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
            notifDropdown.classList.add('hidden');
            notifBtn?.setAttribute('aria-expanded', 'false');
        }
    }

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = notifDropdown.classList.contains('hidden');
            notifDropdown.classList.toggle('hidden', !isHidden);
            notifBtn.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });

        document.addEventListener('click', (e) => {
            if (notifWrapper && !notifWrapper.contains(e.target)) {
                closeNotifDropdown();
            }
        });
    }

    // Navbar Global Search Clear Button
    const searchInput = document.getElementById('global-search-input');
    const searchClear = document.getElementById('global-search-clear');

    if (searchInput && searchClear) {
        searchInput.addEventListener('input', () => {
            searchClear.classList.toggle('hidden', searchInput.value.trim() === '');
        });

        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            searchClear.classList.add('hidden');
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        });
    }

    // ESC key closes mobile sidebar & notification popover
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeSidebar();
            closeNotifDropdown();
        }
    });
});
