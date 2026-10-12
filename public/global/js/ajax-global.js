window.AjaxList = function (config) {

    const {
        url,
        params,
        delay = 400,
        targets,
        method = "GET",
        onSuccess = null
    } = config;

    let typingTimer;

    function buildUrl(customUrl = null) {

        if (customUrl) return customUrl;

        const query = new URLSearchParams();

        for (let key in params) {
            const el = document.querySelector(params[key]);
            if (el) query.append(key, el.value);
        }

        return url + "?" + query.toString();
    }

    function fetchData(customUrl = null) {

        const fetchUrl = buildUrl(customUrl);

        fetch(fetchUrl, {
            method: method,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.json())
        .then(data => {

            for (let key in targets) {
                const targetEl = document.querySelector(targets[key]);
                if (targetEl && data[key] !== undefined) {
                    targetEl.innerHTML = data[key];
                }
            }

            // Update browser URL
            window.history.pushState({}, "", fetchUrl);

            if (onSuccess) onSuccess(data);

        })
        .catch(err => console.error("AJAX Error:", err));
    }

    function init() {

        // Input events (search)
        Object.values(params).forEach(selector => {
            const el = document.querySelector(selector);
            if (!el) return;

            if (el.tagName === "INPUT") {
                ["input", "change"].forEach(evt => {
                    el.addEventListener(evt, function () {
                        clearTimeout(typingTimer);
                        typingTimer = setTimeout(() => fetchData(), delay);
                    });
                });
            } else {
                el.addEventListener("change", function () {
                    fetchData();
                });
            }
        });

        // Pagination click (global)
        document.addEventListener("click", function (e) {
            const link = e.target.closest(".ajax-pagination a");
            if (!link) return;

            e.preventDefault();

            if (link.classList.contains("disabled") || link.getAttribute("href") === "#") {
                return;
            }

            fetchData(link.href);
        });
    }

    init();
};
