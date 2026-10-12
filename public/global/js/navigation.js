document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('ecoNavToggle');
    const close = document.getElementById('ecoNavClose');
    const panel = document.getElementById('navbarCollapse');
    const overlay = document.getElementById('ecoOverlay');
    if (!toggle || !panel || !overlay || !close) return;
    let mobile = window.innerWidth < 1200;
    let previousOverflow = '';
    const setOpen = (open, returnFocus = true) => {
        panel.classList.toggle('show', open);
        overlay.classList.toggle('show', open);
        toggle.setAttribute('aria-expanded', String(open));
        panel.inert = mobile && !open;
        if (open && mobile) {
            previousOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
            close.focus();
        } else {
            document.body.style.overflow = previousOverflow;
            if (returnFocus && mobile) toggle.focus();
        }
    };
    panel.inert = mobile;
    toggle.addEventListener('click', () => setOpen(!panel.classList.contains('show')));
    close.addEventListener('click', () => setOpen(false));
    overlay.addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', event => {
        if (!mobile || !panel.classList.contains('show')) return;
        if (event.key === 'Escape') {
            event.preventDefault();
            setOpen(false);
        }
        if (event.key === 'Tab') {
            const focusable = [...panel.querySelectorAll('a[href],button,input,select,textarea')].filter(el => {
                const menu = el.closest('.lang-menu,.profile-menu,.dropdown-menu');
                return !el.disabled && getComputedStyle(el).visibility === 'visible' && el.getClientRects().length && (!menu || menu.closest('.open') || menu.classList.contains('show'));
            });
            const first = focusable[0], last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last?.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first?.focus(); }
        }
    });
    window.addEventListener('resize', () => {
        const next = window.innerWidth < 1200;
        if (next !== mobile) { mobile = next; setOpen(false, false); }
        panel.inert = mobile && !panel.classList.contains('show');
    });
});
