const header = document.getElementById('site-header');
const navToggle = document.getElementById('nav-toggle');
const navLinks = document.getElementById('nav-links');

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

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

const iconSet = {
    water: {
        tone: 'service-card__icon--sky',
        path: '<path d="M12 2s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11Z"/>',
    },
    shield: {
        tone: 'service-card__icon--violet',
        path: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
    },
    clock: {
        tone: 'service-card__icon--amber',
        path: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    },
    leaf: {
        tone: 'service-card__icon--emerald',
        path: '<path d="M11 20A7 7 0 0 1 4 13c0-5 4-9 16-9 0 12-4 16-9 16Z"/><path d="M4 21c2-6 6-9 12-11"/>',
    },
    sparkles: {
        tone: '',
        path: '<path d="m12 3 1.9 5.8a2 2 0 0 0 1.3 1.3L21 12l-5.8 1.9a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.8a2 2 0 0 0-1.3-1.3L3 12l5.8-1.9a2 2 0 0 0 1.3-1.3L12 3Z"/>',
    },
};

const pickIcon = (category = '') => {
    const value = category.toLowerCase();
    if (value.includes('proteksi')) return iconSet.water;
    if (value.includes('restorasi')) return iconSet.shield;
    if (value.includes('express')) return iconSet.clock;
    if (value.includes('perawatan')) return iconSet.leaf;
    return iconSet.sparkles;
};

const renderServices = (services) => {
    const grid = document.getElementById('service-grid');
    if (!grid || !Array.isArray(services) || services.length === 0) return;

    grid.innerHTML = services.map((service) => {
        const icon = pickIcon(service.category);

        return `
            <article class="service-card reveal is-visible">
                <div class="service-card__top">
                    <span class="service-card__icon ${icon.tone}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${icon.path}</svg>
                    </span>
                    <span class="chip">${escapeHtml(service.category || 'Perawatan')}</span>
                </div>
                <h3>${escapeHtml(service.name)}</h3>
                <p>${escapeHtml(service.description || 'Perawatan sepatu profesional dengan hasil maksimal.')}</p>
                <div class="service-card__foot">
                    <strong>${escapeHtml(service.formatted_price)}</strong>
                    <span>± ${escapeHtml(service.estimated_days)} hari</span>
                </div>
            </article>
        `;
    }).join('');
};

const loadServices = async () => {
    const appUrl = document.body.dataset.appUrl || 'http://localhost:8000';
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 3500);

    try {
        const response = await fetch(`${appUrl}/api/services`, {
            signal: controller.signal,
            headers: { Accept: 'application/json' },
        });

        if (!response.ok) return;

        renderServices(await response.json());
    } catch {
        // Tetap tampilkan layanan statis jika backend belum berjalan.
    } finally {
        clearTimeout(timeout);
    }
};

loadServices();
