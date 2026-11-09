document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const side = document.getElementById('adminSide');
    const toggle = document.querySelector('[data-admin-nav]');
    const overlay = document.querySelector('[data-admin-overlay]');
    const desktop = () => window.matchMedia('(min-width: 1200px)').matches;

    const setNav = (open) => {
        if (!side) return;
        side.classList.toggle('is-open', open);
        overlay?.toggleAttribute('hidden', !open);
        body.classList.toggle('nav-open', open && !desktop());
        toggle?.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open && !desktop()) {
            side.querySelector('[data-admin-close]')?.focus();
        }
    };

    toggle?.addEventListener('click', () => setNav(!side?.classList.contains('is-open')));
    document.querySelector('[data-admin-close]')?.addEventListener('click', () => setNav(false));
    overlay?.addEventListener('click', () => setNav(false));
    side?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (!desktop()) setNav(false);
        });
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setNav(false);
            document.querySelector('.admin-search')?.classList.remove('is-open');
        }
    });
    window.addEventListener('resize', () => {
        if (desktop()) setNav(false);
    });

    document.querySelector('[data-search-toggle]')?.addEventListener('click', () => {
        const form = document.querySelector('.admin-search');
        form?.classList.toggle('is-open');
        if (form?.classList.contains('is-open')) form.querySelector('input')?.focus();
    });

    const modalEl = document.getElementById('confirmModal');
    const modal = modalEl && window.bootstrap ? new window.bootstrap.Modal(modalEl) : null;
    let pendingForm = null;
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === '1') return;
            event.preventDefault();
            pendingForm = form;
            if (!modal) {
                if (confirm(form.dataset.confirm || 'Continue?')) {
                    form.dataset.confirmed = '1';
                    form.submit();
                }
                return;
            }
            const text = document.getElementById('confirmText');
            if (text) text.textContent = form.dataset.confirm || 'Continue?';
            modal.show();
        });
    });
    document.getElementById('confirmYes')?.addEventListener('click', () => {
        if (!pendingForm) return;
        pendingForm.dataset.confirmed = '1';
        modal?.hide();
        pendingForm.submit();
    });

    if (window.tinymce) {
        tinymce.init({
            selector: 'textarea.richtext',
            height: 360,
            menubar: false,
            plugins: 'link lists table code autoresize',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link table | code',
            license_key: 'gpl',
            branding: false,
            promotion: false,
            content_style: 'img { max-width: 100%; height: auto; }',
        });
    }

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', () => {
            if (form.hasAttribute('data-confirm') && form.dataset.confirmed !== '1') return;
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = 'Please wait...';
            }
        });
    });
});
