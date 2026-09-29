/**
 * MoneyTrack - Tagihan (Bills) JavaScript Interactions
 */

function openAddBillModal() {
    const modal = document.getElementById('add-bill-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.getElementById('add-bill-service')?.focus();
    }
}

function closeAddBillModal() {
    const modal = document.getElementById('add-bill-modal');
    if (modal) modal.classList.add('hidden');
}

function openEditBillModal(data) {
    const modal = document.getElementById('edit-bill-modal');
    if (modal && data) {
        document.getElementById('edit-bill-id').value = data.id;
        document.getElementById('edit-bill-service').value = data.service_name;
        document.getElementById('edit-bill-amount').value = data.amount;
        document.getElementById('edit-bill-cycle').value = data.billing_cycle || 'Bulanan';
        document.getElementById('edit-bill-date').value = data.billing_date;
        document.getElementById('edit-bill-logo').value = data.logo || 'default';
        document.getElementById('edit-bill-desc').value = data.description || '';
        modal.classList.remove('hidden');
        document.getElementById('edit-bill-service')?.focus();
    }
}

function closeEditBillModal() {
    const modal = document.getElementById('edit-bill-modal');
    if (modal) modal.classList.add('hidden');
}

async function openDetailBillModal(id) {
    const modal = document.getElementById('detail-bill-modal');
    const loading = document.getElementById('detail-bill-loading');
    const body = document.getElementById('detail-bill-body');
    if (!modal) return;

    modal.classList.remove('hidden');
    loading?.classList.remove('hidden');
    body?.classList.add('hidden');

    try {
        const baseUrl = window.APP_BASE_URL ? window.APP_BASE_URL.replace(/\/+$/, '') : '';
        const res = await fetch(`${baseUrl}/bills/detail?id=${id}`);
        const data = await res.json();

        if (data.success && data.bill) {
            const b = data.bill;
            document.getElementById('detail-bill-name').textContent = b.service_name;
            document.getElementById('detail-bill-cycle-badge').textContent = `Tagihan ${b.billing_cycle}`;
            document.getElementById('detail-bill-amount').textContent = b.amount_formatted;
            document.getElementById('detail-bill-date').textContent = `Jatuh Tempo: ${b.billing_date_formatted}`;
            document.getElementById('detail-bill-desc').textContent = b.description || 'Tidak ada catatan tambahan.';
            document.getElementById('detail-bill-created').textContent = b.created_at;
            document.getElementById('detail-bill-updated').textContent = b.updated_at;

            loading?.classList.add('hidden');
            body?.classList.remove('hidden');
        } else {
            alert(data.message || 'Gagal memuat data tagihan.');
            closeDetailBillModal();
        }
    } catch (err) {
        console.error('Error fetching bill details:', err);
        alert('Terjadi kesalahan saat memuat data tagihan.');
        closeDetailBillModal();
    }
}

function closeDetailBillModal() {
    const modal = document.getElementById('detail-bill-modal');
    if (modal) modal.classList.add('hidden');
}

function openDeleteBillModal(id, name, amount) {
    const modal = document.getElementById('delete-bill-modal');
    if (modal) {
        document.getElementById('delete-bill-id').value = id;
        document.getElementById('delete-bill-name').textContent = `"${name}"`;
        document.getElementById('delete-bill-amount').textContent = amount;
        modal.classList.remove('hidden');
    }
}

function closeDeleteBillModal() {
    const modal = document.getElementById('delete-bill-modal');
    if (modal) modal.classList.add('hidden');
}

// Global modal dismiss listeners
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAddBillModal();
        closeEditBillModal();
        closeDetailBillModal();
        closeDeleteBillModal();
    }
});

['add-bill-modal', 'edit-bill-modal', 'detail-bill-modal', 'delete-bill-modal'].forEach(modalId => {
    const el = document.getElementById(modalId);
    if (el) {
        el.addEventListener('click', (e) => {
            if (e.target === el) {
                el.classList.add('hidden');
            }
        });
    }
});
