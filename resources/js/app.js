const openSidebar = () => {
    document.getElementById('sidebar')?.classList.remove('-translate-x-full');
    document.getElementById('sidebar-backdrop')?.classList.remove('hidden');
};

const closeSidebar = () => {
    document.getElementById('sidebar')?.classList.add('-translate-x-full');
    document.getElementById('sidebar-backdrop')?.classList.add('hidden');
};

document.getElementById('sidebar-toggle')?.addEventListener('click', openSidebar);
document.getElementById('sidebar-close')?.addEventListener('click', closeSidebar);
document.getElementById('sidebar-backdrop')?.addEventListener('click', closeSidebar);

document.querySelectorAll('.demo-account').forEach((button) => {
    button.addEventListener('click', () => {
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        if (email && password) {
            email.value = button.dataset.demoEmail ?? '';
            password.value = button.dataset.demoPassword ?? '';
            email.dispatchEvent(new Event('input', { bubbles: true }));
            password.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
});

const roleInputs = document.querySelectorAll('input[name="role"]');
const shopField = document.getElementById('shop-name-field');

const syncShopField = () => {
    if (!shopField) return;

    const selected = document.querySelector('input[name="role"]:checked')?.value;
    shopField.classList.toggle('hidden', selected !== 'penjual');
};

roleInputs.forEach((input) => input.addEventListener('change', syncShopField));
syncShopField();

document.querySelectorAll('[data-confirm]').forEach((element) => {
    element.addEventListener('click', (event) => {
        if (!window.confirm(element.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
