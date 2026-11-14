document.addEventListener('DOMContentLoaded', () => {
    const headerToggle = document.querySelector('.nav-toggle');
    const nav = document.getElementById('primaryNav');
    headerToggle?.addEventListener('click', () => {
        const open = nav.classList.toggle('is-open');
        headerToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    const hero = document.querySelector('.hero');
    const slides = [...document.querySelectorAll('.hero-slide')];
    const dots = [...document.querySelectorAll('[data-hero-dot]')];
    let heroIndex = 0;
    const showHero = (index) => {
        if (!slides.length) return;
        heroIndex = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            slide.classList.toggle('is-active', i === heroIndex);
            slide.hidden = i !== heroIndex;
        });
        dots.forEach((dot, i) => dot.classList.toggle('is-active', i === heroIndex));
    };
    document.querySelector('[data-hero="prev"]')?.addEventListener('click', () => showHero(heroIndex - 1));
    document.querySelector('[data-hero="next"]')?.addEventListener('click', () => showHero(heroIndex + 1));
    dots.forEach((dot) => dot.addEventListener('click', () => showHero(Number(dot.dataset.heroDot))));
    hero?.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') showHero(heroIndex - 1);
        if (event.key === 'ArrowRight') showHero(heroIndex + 1);
    });
    hero?.setAttribute('tabindex', '0');
    let heroTimer;
    const startHero = () => { if (slides.length > 1) heroTimer = window.setInterval(() => showHero(heroIndex + 1), 7000); };
    const stopHero = () => { if (heroTimer) window.clearInterval(heroTimer); };
    hero?.addEventListener('mouseenter', stopHero);
    hero?.addEventListener('mouseleave', startHero);
    let touchStartX = 0;
    hero?.addEventListener('touchstart', (event) => { touchStartX = event.changedTouches[0]?.screenX || 0; stopHero(); }, {passive: true});
    hero?.addEventListener('touchend', (event) => {
        const distance = (event.changedTouches[0]?.screenX || 0) - touchStartX;
        if (Math.abs(distance) > 40) showHero(heroIndex + (distance < 0 ? 1 : -1));
        startHero();
    }, {passive: true});
    if (slides.length > 1) {
        startHero();
    }

    const quotes = [...document.querySelectorAll('#quoteSlider blockquote')];
    const quoteDots = [...document.querySelectorAll('[data-quote]')];
    let quoteIndex = 0;
    const showQuote = (index) => {
        if (!quotes.length) return;
        quoteIndex = (index + quotes.length) % quotes.length;
        quotes.forEach((quote, i) => {
            quote.classList.toggle('is-active', i === quoteIndex);
            quote.hidden = i !== quoteIndex;
        });
        quoteDots.forEach((dot, i) => dot.classList.toggle('is-active', i === quoteIndex));
    };
    quoteDots.forEach((dot) => dot.addEventListener('click', () => showQuote(Number(dot.dataset.quote))));
    if (quotes.length > 1) setInterval(() => showQuote(quoteIndex + 1), 8000);

    const search = document.getElementById('searchModal');
    document.querySelector('[data-open-search]')?.addEventListener('click', () => {
        search.hidden = false;
        search.querySelector('input')?.focus();
    });
    document.querySelector('[data-close-search]')?.addEventListener('click', () => { search.hidden = true; });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (search) search.hidden = true;
            document.getElementById('lightbox')?.close();
        }
    });

    const dialog = document.getElementById('lightbox');
    document.querySelectorAll('.shot').forEach((shot) => {
        shot.addEventListener('click', () => {
            if (!dialog) return;
            dialog.querySelector('img').src = shot.dataset.full;
            dialog.querySelector('img').alt = shot.dataset.caption || '';
            dialog.querySelector('p').textContent = shot.dataset.caption || '';
            dialog.showModal();
        });
    });
    dialog?.querySelector('[data-close-lightbox]')?.addEventListener('click', () => dialog.close());

    const counters = document.querySelectorAll('[data-count]');
    if (counters.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = Number(el.dataset.count || 0);
                const start = performance.now();
                const tick = (now) => {
                    const progress = Math.min(1, (now - start) / 900);
                    el.textContent = Math.round(target * progress).toLocaleString();
                    if (progress < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
                observer.unobserve(el);
            });
        }, { threshold: 0.6 });
        counters.forEach((counter) => observer.observe(counter));
    }

    const departmentSelect = document.getElementById('departmentSelect');
    const programmeSelect = document.getElementById('programmeSelect');
    departmentSelect?.addEventListener('change', async () => {
        const department = departmentSelect.value;
        const base = window.SLGTI_BASE || '';
        const response = await fetch(`${base}/api/programmes?department=${encodeURIComponent(department)}`);
        if (!response.ok) return;
        const payload = await response.json();
        const selected = programmeSelect.dataset.selected || '';
        programmeSelect.innerHTML = '<option value="">Select a programme</option>';
        payload.programmes.forEach((programme) => {
            const option = document.createElement('option');
            option.value = programme.id;
            option.textContent = programme.title;
            if (String(programme.id) === selected) option.selected = true;
            programmeSelect.appendChild(option);
        });
    });
});
