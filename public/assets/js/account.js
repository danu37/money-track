/**
 * MoneyTrack - Account (Saldo) Interactions
 */

// Global Modal helpers
function openAddModal() {
    const modal = document.getElementById('add-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.getElementById('add-name')?.focus();
    }
}

function closeAddModal() {
    const modal = document.getElementById('add-modal');
    if (modal) modal.classList.add('hidden');
}

function openEditModal(data) {
    const modal = document.getElementById('edit-modal');
    if (modal && data) {
        document.getElementById('edit-id').value = data.id;
        document.getElementById('edit-name').value = data.name;
        document.getElementById('edit-type').value = data.type;
        document.getElementById('edit-balance').value = data.initial_balance;
        modal.classList.remove('hidden');
        document.getElementById('edit-name')?.focus();
    }
}

function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    if (modal) modal.classList.add('hidden');
}

function openDeleteModal(id, name) {
    const modal = document.getElementById('delete-modal');
    if (modal) {
        document.getElementById('delete-id').value = id;
        document.getElementById('delete-account-name').textContent = `"${name}"`;
        modal.classList.remove('hidden');
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    if (modal) modal.classList.add('hidden');
}

async function openDetailModal(id) {
    const modal = document.getElementById('detail-modal');
    const loading = document.getElementById('detail-loading');
    const dataContainer = document.getElementById('detail-data');
    if (!modal) return;

    modal.classList.remove('hidden');
    loading?.classList.remove('hidden');
    dataContainer?.classList.add('hidden');

    try {
        // Fetch account detail via JSON endpoint
        const baseUrl = window.APP_BASE_URL ? window.APP_BASE_URL.replace(/\/+$/, '') : '';
        const res = await fetch(`${baseUrl}/accounts/detail?id=${id}`);
        const data = await res.json();

        if (data.success && data.account) {
            const acc = data.account;
            document.getElementById('detail-title').textContent = `Detail: ${acc.name}`;
            document.getElementById('detail-type-badge').textContent = `Tipe: ${acc.type.toUpperCase()}`;
            document.getElementById('detail-created').textContent = `Dibuat: ${acc.created_at}`;
            document.getElementById('detail-current-balance').textContent = acc.current_balance_formatted;
            document.getElementById('detail-initial-balance').textContent = acc.initial_balance_formatted;
            document.getElementById('detail-total-income').textContent = `+ ${acc.total_income_formatted}`;
            document.getElementById('detail-total-expense').textContent = `- ${acc.total_expense_formatted}`;

            const txList = document.getElementById('detail-transactions-list');
            if (txList) {
                txList.innerHTML = '';
                if (!data.transactions || data.transactions.length === 0) {
                    txList.innerHTML = '<p class="text-xs text-[#8A8F98] italic py-2">Belum ada transaksi pada rekening ini.</p>';
                } else {
                    data.transactions.forEach(tx => {
                        const isIncome = tx.type === 'income';
                        const itemHtml = `
                            <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-[#E8EAED] text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full ${isIncome ? 'bg-teal-50 text-[#2FA89D]' : 'bg-red-50 text-[#E56B6F]'} flex items-center justify-center text-[10px] font-bold">
                                        ${isIncome ? '↓' : '↑'}
                                    </div>
                                    <div>
                                        <p class="font-medium text-[#2B2B2B]">${tx.description}</p>
                                        <p class="text-[10px] text-[#8A8F98]">${tx.category} • ${tx.date}</p>
                                    </div>
                                </div>
                                <span class="font-bold ${isIncome ? 'text-[#2FA89D]' : 'text-[#2B2B2B]'}">
                                    ${isIncome ? '+ ' : '- '}${tx.amount_formatted}
                                </span>
                            </div>
                        `;
                        txList.insertAdjacentHTML('beforeend', itemHtml);
                    });
                }
            }

            loading?.classList.add('hidden');
            dataContainer?.classList.remove('hidden');
        } else {
            alert(data.message || 'Gagal memuat rincian.');
            closeDetailModal();
        }
    } catch (err) {
        console.error('Error fetching account detail:', err);
        alert('Terjadi kesalahan saat memuat data rekening.');
        closeDetailModal();
    }
}

function closeDetailModal() {
    const modal = document.getElementById('detail-modal');
    if (modal) modal.classList.add('hidden');
}

// Close modals when clicking backdrop or pressing Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
        closeDetailModal();
        closeDeleteModal();
    }
});

// Click outside modal container to close
['add-modal', 'edit-modal', 'detail-modal', 'delete-modal'].forEach(modalId => {
    const el = document.getElementById(modalId);
    if (el) {
        el.addEventListener('click', (e) => {
            if (e.target === el) {
                el.classList.add('hidden');
            }
        });
    }
});
