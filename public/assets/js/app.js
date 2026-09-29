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

    // ESC key closes mobile sidebar
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });
});
