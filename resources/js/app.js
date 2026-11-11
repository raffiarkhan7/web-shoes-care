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
