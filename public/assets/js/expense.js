/**
 * MoneyTrack - Pengeluaran (Expenses) JavaScript Interactions
 */

// Tooltip for SVG comparison chart
function showExpenseTooltip(event, el) {
    const tooltip = document.getElementById('expense-chart-tooltip');
    const tooltipText = document.getElementById('expense-tooltip-text');
    if (!tooltip || !tooltipText) return;

    const month = el.getAttribute('data-month');
    const amount = el.getAttribute('data-amount');
    tooltipText.textContent = `${month}: ${amount}`;

    const svgRect = el.closest('svg').getBoundingClientRect();
    const parentRect = el.closest('.relative').getBoundingClientRect();
    const cx = parseFloat(el.getAttribute('cx'));
    const cy = parseFloat(el.getAttribute('cy'));

    // Compute relative coordinates
    const scaleX = svgRect.width / 560;
    const scaleY = svgRect.height / 180;

    tooltip.style.left = `${(cx * scaleX)}px`;
    tooltip.style.top = `${(cy * scaleY) - 8}px`;
    tooltip.classList.remove('opacity-0');
}

function hideExpenseTooltip() {
    const tooltip = document.getElementById('expense-chart-tooltip');
    if (tooltip) {
        tooltip.classList.add('opacity-0');
    }
}

// Add Expense Modal
function openAddExpenseModal() {
    const modal = document.getElementById('add-expense-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.querySelector('#add-expense-modal input[name="description"]')?.focus();
    }
}

function closeAddExpenseModal() {
    const modal = document.getElementById('add-expense-modal');
    if (modal) modal.classList.add('hidden');
}

// Edit Expense Modal
async function openEditExpenseModal(id) {
    const modal = document.getElementById('edit-expense-modal');
    if (!modal) return;

    try {
        const baseUrl = window.APP_BASE_URL ? window.APP_BASE_URL.replace(/\/+$/, '') : '';
        const res = await fetch(`${baseUrl}/expenses/detail?id=${id}`);
        const data = await res.json();

        if (data.success && data.expense) {
            const exp = data.expense;
            document.getElementById('edit-expense-id').value = exp.id;
            document.getElementById('edit-expense-description').value = exp.description;
            document.getElementById('edit-expense-amount').value = formatRupiahInput(exp.amount);
            document.getElementById('edit-expense-date').value = exp.transaction_date;
            document.getElementById('edit-expense-account').value = exp.account_id;
            document.getElementById('edit-expense-category').value = exp.category_id;

            modal.classList.remove('hidden');
            document.getElementById('edit-expense-description')?.focus();
        } else {
            alert(data.message || 'Gagal memuat data pengeluaran.');
        }
    } catch (err) {
        console.error('Error loading expense for edit:', err);
        alert('Terjadi kesalahan saat memuat data pengeluaran.');
    }
}

function closeEditExpenseModal() {
    const modal = document.getElementById('edit-expense-modal');
    if (modal) modal.classList.add('hidden');
}

// Detail Expense Modal
async function openDetailExpenseModal(id) {
    const modal = document.getElementById('detail-expense-modal');
    if (!modal) return;

    try {
        const baseUrl = window.APP_BASE_URL ? window.APP_BASE_URL.replace(/\/+$/, '') : '';
        const res = await fetch(`${baseUrl}/expenses/detail?id=${id}`);
        const data = await res.json();

        if (data.success && data.expense) {
            const exp = data.expense;
            document.getElementById('detail-expense-amount').textContent = `- ${exp.amount_formatted}`;
            document.getElementById('detail-expense-date').textContent = exp.date_formatted;
            document.getElementById('detail-expense-description').textContent = exp.description;
            document.getElementById('detail-expense-category').textContent = exp.category_name;
            document.getElementById('detail-expense-account').textContent = exp.account_name;
            document.getElementById('detail-expense-created').textContent = exp.created_at;
            document.getElementById('detail-expense-updated').textContent = exp.updated_at;

            modal.classList.remove('hidden');
        } else {
            alert(data.message || 'Gagal memuat data pengeluaran.');
        }
    } catch (err) {
        console.error('Error loading expense detail:', err);
        alert('Terjadi kesalahan saat memuat data pengeluaran.');
    }
}

function closeDetailExpenseModal() {
    const modal = document.getElementById('detail-expense-modal');
    if (modal) modal.classList.add('hidden');
}

// Delete Confirmation Modal
function openDeleteExpenseModal(id, name, amount) {
    const modal = document.getElementById('delete-expense-modal');
    if (modal) {
        document.getElementById('delete-expense-id').value = id;
        document.getElementById('delete-expense-name').textContent = `"${name}"`;
        document.getElementById('delete-expense-amount').textContent = amount;
        modal.classList.remove('hidden');
    }
}

function closeDeleteExpenseModal() {
    const modal = document.getElementById('delete-expense-modal');
    if (modal) modal.classList.add('hidden');
}

// Helper to format number with thousand separators
function formatRupiahInput(value) {
    if (!value && value !== 0) return '';
    const numberString = Math.round(Number(value)).toString();
    return numberString.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Input mask formatting for nominal fields
function setupAmountMasking(selector) {
    const el = document.querySelector(selector);
    if (!el) return;

    el.addEventListener('input', (e) => {
        let val = e.target.value.replace(/[^0-9]/g, '');
        if (val) {
            e.target.value = parseInt(val, 10).toLocaleString('id-ID');
        } else {
            e.target.value = '';
        }
    });
}

// Global dismiss listeners
document.addEventListener('DOMContentLoaded', () => {
    setupAmountMasking('#add-expense-amount');
    setupAmountMasking('#edit-expense-amount');

    // Dismiss on backdrop click
    const modals = [
        'add-expense-modal',
        'edit-expense-modal',
        'detail-expense-modal',
        'delete-expense-modal'
    ];

    modals.forEach((id) => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', (e) => {
                if (e.target === el) {
                    el.classList.add('hidden');
                }
            });
        }
    });
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAddExpenseModal();
        closeEditExpenseModal();
        closeDetailExpenseModal();
        closeDeleteExpenseModal();
    }
});
