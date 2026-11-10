/**
 * MoneyTrack - Target Keuangan (Goals) Interactions
 */

function openAddGoalModal() {
    const modal = document.getElementById('add-goal-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.getElementById('add-goal-title')?.focus();
    }
}

function closeAddGoalModal() {
    document.getElementById('add-goal-modal')?.classList.add('hidden');
}

function openDepositGoalModal(id, title, pct, remainingFormatted) {
    const modal = document.getElementById('deposit-goal-modal');
    if (!modal) return;

    document.getElementById('deposit-goal-id').value = id;
    document.getElementById('deposit-goal-title').textContent = title;
    document.getElementById('deposit-goal-pct').textContent = `${pct}%`;
    document.getElementById('deposit-goal-remaining').textContent = remainingFormatted;
    document.getElementById('deposit-goal-amount').value = '';

    modal.classList.remove('hidden');
    document.getElementById('deposit-goal-amount')?.focus();
}

function closeDepositGoalModal() {
    document.getElementById('deposit-goal-modal')?.classList.add('hidden');
}

function setDepositAmount(amount) {
    const input = document.getElementById('deposit-goal-amount');
    if (input) {
        input.value = amount;
        input.focus();
    }
}

async function openEditGoalModal(id) {
    const modal = document.getElementById('edit-goal-modal');
    if (!modal) return;

    try {
        const baseUrl = window.APP_BASE_URL ? window.APP_BASE_URL.replace(/\/+$/, '') : '';
        const res = await fetch(`${baseUrl}/goals/detail?id=${id}`);
        const data = await res.json();

        if (data.success && data.goal) {
            const g = data.goal;
            document.getElementById('edit-goal-id').value = g.id;
            document.getElementById('edit-goal-title').value = g.title;
            document.getElementById('edit-goal-tag').value = g.category_tag;
            document.getElementById('edit-goal-target').value = g.target_amount;
            document.getElementById('edit-goal-current').value = g.current_amount;
            document.getElementById('edit-goal-monthly').value = g.monthly_target;
            document.getElementById('edit-goal-deadline').value = g.deadline_date;
            document.getElementById('edit-goal-icon').value = g.icon;
            document.getElementById('edit-goal-desc').value = g.raw_description || '';

            modal.classList.remove('hidden');
            document.getElementById('edit-goal-title')?.focus();
        } else {
            alert(data.message || 'Target keuangan tidak ditemukan.');
        }
    } catch (err) {
        console.error('Failed to load goal for edit:', err);
        alert('Gagal memuat data target keuangan.');
    }
}

function closeEditGoalModal() {
    document.getElementById('edit-goal-modal')?.classList.add('hidden');
}

async function openDetailGoalModal(id) {
    const modal = document.getElementById('detail-goal-modal');
    const loading = document.getElementById('detail-goal-loading');
    const body = document.getElementById('detail-goal-body');
    if (!modal) return;

    modal.classList.remove('hidden');
    loading?.classList.remove('hidden');
    body?.classList.add('hidden');

    try {
        const baseUrl = window.APP_BASE_URL ? window.APP_BASE_URL.replace(/\/+$/, '') : '';
        const res = await fetch(`${baseUrl}/goals/detail?id=${id}`);
        const data = await res.json();

        if (data.success && data.goal) {
            const g = data.goal;
            document.getElementById('detail-goal-tag').textContent = g.category_tag;
            document.getElementById('detail-goal-title').textContent = g.title;
            document.getElementById('detail-goal-desc').textContent = g.description;
            document.getElementById('detail-goal-pct').textContent = `${g.percentage}%`;
            document.getElementById('detail-goal-bar').style.width = `${g.percentage}%`;
            document.getElementById('detail-goal-saved').textContent = g.current_amount_formatted;
            document.getElementById('detail-goal-target').textContent = `dari ${g.target_amount_formatted}`;
            document.getElementById('detail-goal-remaining').textContent = g.remaining_formatted;
            document.getElementById('detail-goal-monthly').textContent = g.monthly_target_formatted;
            document.getElementById('detail-goal-deadline').textContent = g.deadline_date_formatted;

            let estText = 'Sudah tercapai';
            if (g.percentage < 100) {
                estText = g.estimated_months > 0 ? `± ${g.estimated_months} bulan lagi` : 'Belum diatur';
            }
            document.getElementById('detail-goal-estimate').textContent = estText;

            loading?.classList.add('hidden');
            body?.classList.remove('hidden');
        } else {
            alert(data.message || 'Gagal memuat detail target.');
            closeDetailGoalModal();
        }
    } catch (err) {
        console.error('Failed to fetch goal detail:', err);
        alert('Terjadi kesalahan saat memuat detail target.');
        closeDetailGoalModal();
    }
}

function closeDetailGoalModal() {
    document.getElementById('detail-goal-modal')?.classList.add('hidden');
}

function openDeleteGoalModal(id, title) {
    const modal = document.getElementById('delete-goal-modal');
    if (modal) {
        document.getElementById('delete-goal-id').value = id;
        document.getElementById('delete-goal-name').textContent = `"${title}"`;
        modal.classList.remove('hidden');
    }
}

function closeDeleteGoalModal() {
    document.getElementById('delete-goal-modal')?.classList.add('hidden');
}

// Global Escape & backdrop dismiss listeners
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAddGoalModal();
        closeDepositGoalModal();
        closeEditGoalModal();
        closeDetailGoalModal();
        closeDeleteGoalModal();
    }
});

['add-goal-modal', 'deposit-goal-modal', 'edit-goal-modal', 'detail-goal-modal', 'delete-goal-modal'].forEach(modalId => {
    const el = document.getElementById(modalId);
    if (el) {
        el.addEventListener('click', (e) => {
            if (e.target === el) {
                el.classList.add('hidden');
            }
        });
    }
});

