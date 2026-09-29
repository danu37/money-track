/**
 * MoneyTrack - Transactions Page JavaScript Interactions
 */

// Modal open/close controls
function openAddTxModal() {
    const modal = document.getElementById('add-tx-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.getElementById('add-tx-desc')?.focus();
        toggleCategoryType('expense', 'add');
    }
}

function closeAddTxModal() {
    const modal = document.getElementById('add-tx-modal');
    if (modal) modal.classList.add('hidden');
}

function openEditTxModal(data) {
    const modal = document.getElementById('edit-tx-modal');
    if (modal && data) {
        document.getElementById('edit-tx-id').value = data.id;
        document.getElementById('edit-tx-desc').value = data.description;
        document.getElementById('edit-tx-amount').value = data.amount;
        document.getElementById('edit-tx-account').value = data.account_id;
        document.getElementById('edit-tx-date').value = data.transaction_date;

        if (data.type === 'income') {
            document.getElementById('edit-type-income').checked = true;
            toggleCategoryType('income', 'edit');
        } else {
            document.getElementById('edit-type-expense').checked = true;
            toggleCategoryType('expense', 'edit');
        }

        document.getElementById('edit-tx-category').value = data.category_id;
        modal.classList.remove('hidden');
        document.getElementById('edit-tx-desc')?.focus();
    }
}

function closeEditTxModal() {
    const modal = document.getElementById('edit-tx-modal');
    if (modal) modal.classList.add('hidden');
}

function openDeleteTxModal(id, name, amount) {
    const modal = document.getElementById('delete-tx-modal');
    if (modal) {
        document.getElementById('delete-tx-id').value = id;
        document.getElementById('delete-tx-name').textContent = `"${name}"`;
        document.getElementById('delete-tx-amount').textContent = amount;
        modal.classList.remove('hidden');
    }
}

function closeDeleteTxModal() {
    const modal = document.getElementById('delete-tx-modal');
    if (modal) modal.classList.add('hidden');
}

// Filter category options based on type (income vs expense)
function toggleCategoryType(type, formPrefix) {
    const select = document.getElementById(`${formPrefix}-tx-category`);
    if (!select) return;

    let hasSelected = false;
    Array.from(select.options).forEach(opt => {
        const catType = opt.getAttribute('data-type');
        if (!catType || catType === type) {
            opt.style.display = 'block';
            if (!hasSelected) {
                select.value = opt.value;
                hasSelected = true;
            }
        } else {
            opt.style.display = 'none';
        }
    });
}

// Global modal dismiss listeners
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAddTxModal();
        closeEditTxModal();
        closeDeleteTxModal();
    }
});

['add-tx-modal', 'edit-tx-modal', 'delete-tx-modal'].forEach(modalId => {
    const el = document.getElementById(modalId);
    if (el) {
        el.addEventListener('click', (e) => {
            if (e.target === el) {
                el.classList.add('hidden');
            }
        });
    }
});
