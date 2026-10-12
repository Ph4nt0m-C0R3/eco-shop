document.addEventListener('DOMContentLoaded', () => {
    const config = window.ECO_SHOP;
    const search = document.getElementById('liveSearch');
    const sort = document.getElementById('liveSort');
    const currency = document.getElementById('priceCurrency');
    const from = document.getElementById('priceFrom');
    const to = document.getElementById('priceTo');
    const grid = document.getElementById('productGrid');
    const pagination = document.getElementById('paginationArea');
    const status = document.getElementById('shopStatus');
    const retry = document.getElementById('shopRetry');
    if (!config || !search || !grid) return;
    const controls = {search, sort, currency, price_from: from, price_to: to};
    let category = 'all', controller, timer, lastUrl, sequence = 0;
    const details = document.getElementById('shopFilters');
    const media = window.matchMedia('(min-width:992px)');
    const adjustFilters = () => { details.open = media.matches; };
    adjustFilters(); media.addEventListener('change', adjustFilters);

    function restore(url) {
        const params = new URL(url).searchParams;
        for (const [key, control] of Object.entries(controls)) control.value = params.get(key) || (key === 'sort' ? 'default' : '');
        category = params.get('category') || 'all';
        document.querySelectorAll('.eco-category-link').forEach(el => el.classList.toggle('active', el.dataset.category === category));
    }
    function buildUrl(page = null) {
        const url = new URL(config.url, location.href);
        for (const [key, control] of Object.entries(controls)) {
            if (control.value.trim() && control.value !== 'default') url.searchParams.set(key, control.value.trim());
        }
        if (category !== 'all') url.searchParams.set('category', category);
        if (page) url.searchParams.set('page', page);
        return url;
    }
    async function load(url = buildUrl(), historyMode = 'push') {
        controller?.abort(); controller = new AbortController();
        const current = ++sequence;
        lastUrl = url;
        grid.setAttribute('aria-busy', 'true');
        status.textContent = config.loading; retry.hidden = true;
        try {
            const response = await fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}, signal: controller.signal});
            if (!response.ok) throw new Error('Products request failed');
            const data = await response.json();
            if (current !== sequence) return;
            grid.innerHTML = data.html; pagination.innerHTML = data.pagination;
            if (historyMode === 'push' && url.href !== location.href) history.pushState({}, '', url);
            status.textContent = '';
        } catch (error) {
            if (current !== sequence || error.name === 'AbortError') return;
            status.textContent = config.error; retry.hidden = false;
        } finally {
            if (current === sequence) grid.setAttribute('aria-busy', 'false');
        }
    }
    function reset() {
        restore(new URL(config.url, location.href)); load();
    }
    search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => load(), 400); });
    search.addEventListener('keydown', event => { if (event.key === 'Enter') { event.preventDefault(); clearTimeout(timer); load(); } });
    document.getElementById('shopSearchButton').addEventListener('click', () => { clearTimeout(timer); load(); });
    sort.addEventListener('change', () => { clearTimeout(timer); load(); });
    [currency, from, to].forEach(control => control.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => load(), 300); }));
    document.addEventListener('click', event => {
        const cat = event.target.closest('.eco-category-link');
        const link = event.target.closest('#paginationArea a');
        const empty = event.target.closest('.eco-empty-action-btn');
        if (cat) {
            event.preventDefault(); clearTimeout(timer); category = cat.dataset.category;
            document.querySelectorAll('.eco-category-link').forEach(el => el.classList.toggle('active', el === cat)); load();
        } else if (link && link.getAttribute('href') !== '#' && !link.closest('.disabled')) {
            event.preventDefault(); clearTimeout(timer); load(buildUrl(new URL(link.href).searchParams.get('page')));
        } else if (event.target.closest('#resetFilters') || empty) {
            event.preventDefault(); clearTimeout(timer); reset();
        }
    });
    retry.addEventListener('click', () => load(lastUrl));
    window.addEventListener('popstate', () => { clearTimeout(timer); restore(location.href); load(new URL(location.href), 'none'); });
    restore(location.href);
});
