const header = document.getElementById('site-header');
const navToggle = document.getElementById('nav-toggle');
const navLinks = document.getElementById('nav-links');
const whatsappNumber = document.body.dataset.whatsappNumber?.replace(/\D/g, '') ?? '';

document.querySelectorAll('[data-whatsapp-message]').forEach((link) => {
    if (!whatsappNumber) {
        link.setAttribute('aria-disabled', 'true');
        link.setAttribute('title', 'Nomor WhatsApp admin belum dikonfigurasi');
        link.addEventListener('click', (event) => event.preventDefault());

        return;
    }

    const message = link.dataset.whatsappMessage ?? '';
    link.href = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
});

const onScroll = () => {
    header?.classList.toggle('is-scrolled', window.scrollY > 12);
};

onScroll();
window.addEventListener('scroll', onScroll, { passive: true });

navToggle?.addEventListener('click', () => {
    const open = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', String(!open));
    navLinks?.classList.toggle('is-open', !open);
});

navLinks?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
        navToggle?.setAttribute('aria-expanded', 'false');
        navLinks?.classList.remove('is-open');
    });
});

const year = document.getElementById('year');
if (year) year.textContent = new Date().getFullYear();

document.querySelectorAll('[data-accordion]').forEach((accordion) => {
    const items = [...accordion.querySelectorAll('.accordion__item')];

    items.forEach((item) => {
        const trigger = item.querySelector('.accordion__trigger');
        if (!trigger) return;

        trigger.addEventListener('click', () => {
            const wasOpen = item.classList.contains('is-open');

            items.forEach((other) => {
                other.classList.remove('is-open');
                other.querySelector('.accordion__trigger')?.setAttribute('aria-expanded', 'false');
            });

            if (!wasOpen) {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });
});

const reveals = document.querySelectorAll('.reveal');

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    reveals.forEach((element) => observer.observe(element));
} else {
    reveals.forEach((element) => element.classList.add('is-visible'));
}
