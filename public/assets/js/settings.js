/**
 * MoneyTrack - Settings Page Interactions
 * Handles settings tab switching, live profile preview, password strength & visibility, and category modal
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Settings Tab Switching
    const tabButtons = document.querySelectorAll('.settings-tab-btn');
    const panels = document.querySelectorAll('.settings-panel');

    function activateTab(targetTab) {
        panels.forEach(panel => {
            if (panel.id === `panel-${targetTab}`) {
                panel.classList.remove('hidden');
                panel.classList.add('mt-enter-soft');
            } else {
                panel.classList.add('hidden');
                panel.classList.remove('mt-enter-soft');
            }
        });

        tabButtons.forEach(btn => {
            const isSelected = btn.getAttribute('data-settings-tab') === targetTab;
            const iconBox = btn.querySelector('div > div');

            if (isSelected) {
                btn.classList.add('bg-[#F0F9F8]', 'text-[#2FA89D]', 'border-[#D6F0ED]');
                btn.classList.remove('border-transparent', 'text-[#2B2B2B]', 'hover:bg-[#F5F6F8]');
                if (iconBox) {
                    iconBox.classList.add('bg-[#2FA89D]', 'text-white');
                    iconBox.classList.remove('bg-[#F5F6F8]', 'text-[#8A8F98]');
                }
            } else {
                btn.classList.remove('bg-[#F0F9F8]', 'text-[#2FA89D]', 'border-[#D6F0ED]');
                btn.classList.add('border-transparent', 'text-[#2B2B2B]', 'hover:bg-[#F5F6F8]');
                if (iconBox) {
                    iconBox.classList.remove('bg-[#2FA89D]', 'text-white');
                    iconBox.classList.add('bg-[#F5F6F8]', 'text-[#8A8F98]');
                }
            }
        });

        // Keep URL query ?tab= in sync without reloading
        if (window.history && window.history.replaceState) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', targetTab);
            window.history.replaceState({}, '', url.toString());
        }
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.getAttribute('data-settings-tab');
            if (tab) activateTab(tab);
        });
    });

    // 2. Live Profile Name & Email Preview
    const nameInput = document.getElementById('profile-name');
    const emailInput = document.getElementById('profile-email');
    const previewFirstname = document.getElementById('live-preview-firstname');
    const previewAvatar = document.getElementById('live-preview-avatar');
    const cardAvatar = document.getElementById('profile-card-avatar');
    const cardName = document.getElementById('profile-card-name');
    const cardEmail = document.getElementById('profile-card-email');

    if (nameInput) {
        nameInput.addEventListener('input', () => {
            const val = nameInput.value.trim();
            const first = val ? val.split(/\s+/)[0] : 'User';
            const initial = val ? val.charAt(0).toUpperCase() : 'U';

            if (previewFirstname) previewFirstname.textContent = first;
            if (previewAvatar) previewAvatar.textContent = initial;
            if (cardAvatar) cardAvatar.textContent = initial;
            if (cardName) cardName.textContent = val || 'Nama Pengguna';
        });
    }

    if (emailInput && cardEmail) {
        emailInput.addEventListener('input', () => {
            cardEmail.textContent = emailInput.value.trim() || 'email@domain.com';
        });
    }

    // 3. Password Visibility Toggles
    document.querySelectorAll('[data-toggle-password]').forEach(toggleBtn => {
        toggleBtn.addEventListener('click', () => {
            const targetId = toggleBtn.getAttribute('data-toggle-password');
            const input = document.getElementById(targetId);
            if (!input) return;

            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            toggleBtn.classList.toggle('text-[#2FA89D]', isHidden);
        });
    });

    // 4. Live Password Strength Meter
    const newPassInput = document.getElementById('new-password');
    const confirmPassInput = document.getElementById('confirm-password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthLabel = document.getElementById('password-strength-label');
    const ruleLength = document.getElementById('rule-length');
    const ruleMix = document.getElementById('rule-mix');
    const ruleMatch = document.getElementById('rule-match');

    function updatePasswordStrength() {
        if (!newPassInput || !strengthBar || !strengthLabel) return;

        const val = newPassInput.value;
        const confirmVal = confirmPassInput ? confirmPassInput.value : '';

        const hasLength = val.length >= 6;
        const hasLong = val.length >= 10;
        const hasMix = /[a-zA-Z]/.test(val) && /\d/.test(val);
        const hasSpecial = /[^a-zA-Z0-9]/.test(val);
        const matches = val.length > 0 && val === confirmVal;

        if (ruleLength) {
            ruleLength.classList.toggle('text-[#2FA89D]', hasLength);
            ruleLength.classList.toggle('font-medium', hasLength);
        }
        if (ruleMix) {
            ruleMix.classList.toggle('text-[#2FA89D]', hasMix);
            ruleMix.classList.toggle('font-medium', hasMix);
        }
        if (ruleMatch) {
            ruleMatch.classList.toggle('text-[#2FA89D]', matches);
            ruleMatch.classList.toggle('font-medium', matches);
        }

        if (val.length === 0) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'h-full w-0 bg-[#2FA89D] transition-all duration-300 rounded-full';
            strengthLabel.textContent = 'Belum diisi';
            strengthLabel.className = 'font-semibold text-[#8A8F98]';
            return;
        }

        let score = 1;
        if (hasLength) score++;
        if (hasMix) score++;
        if (hasLong || hasSpecial) score++;

        if (score <= 1) {
            strengthBar.style.width = '25%';
            strengthBar.className = 'h-full bg-[#E56B6F] transition-all duration-300 rounded-full';
            strengthLabel.textContent = 'Lemah';
            strengthLabel.className = 'font-semibold text-[#E56B6F]';
        } else if (score === 2) {
            strengthBar.style.width = '50%';
            strengthBar.className = 'h-full bg-amber-500 transition-all duration-300 rounded-full';
            strengthLabel.textContent = 'Cukup';
            strengthLabel.className = 'font-semibold text-amber-600';
        } else if (score === 3) {
            strengthBar.style.width = '75%';
            strengthBar.className = 'h-full bg-[#2FA89D] transition-all duration-300 rounded-full';
            strengthLabel.textContent = 'Kuat';
            strengthLabel.className = 'font-semibold text-[#2FA89D]';
        } else {
            strengthBar.style.width = '100%';
            strengthBar.className = 'h-full bg-[#27958B] transition-all duration-300 rounded-full';
            strengthLabel.textContent = 'Sangat Kuat';
            strengthLabel.className = 'font-semibold text-[#27958B]';
        }
    }

    if (newPassInput) newPassInput.addEventListener('input', updatePasswordStrength);
    if (confirmPassInput) confirmPassInput.addEventListener('input', updatePasswordStrength);
});

// 5. Delete Category Modal Helpers
function openDeleteCatModal(id, name) {
    const modal = document.getElementById('delete-cat-modal');
    if (modal) {
        document.getElementById('delete-cat-id').value = id;
        document.getElementById('delete-cat-name').textContent = `"${name}"`;
        modal.classList.remove('hidden');
    }
}

function closeDeleteCatModal() {
    const modal = document.getElementById('delete-cat-modal');
    if (modal) modal.classList.add('hidden');
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDeleteCatModal();
});

const deleteCatModal = document.getElementById('delete-cat-modal');
if (deleteCatModal) {
    deleteCatModal.addEventListener('click', (e) => {
        if (e.target === deleteCatModal) closeDeleteCatModal();
    });
}

