<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<section class="eco-products-section">
    <div class="container">

        <!-- Header -->
        <div class="eco-products-header">
            <div class="eco-header-content">

                <input type="hidden" id="homeCategory" value="{{ $selectedCategory }}">

                <div class="eco-header-top">
                    <span class="eco-tag">
                        <i class="fas fa-leaf"></i>
                        {{ app()->getLocale() === 'mm' ? 'သဘာဝပတ်ဝန်းကျင်သဟဇာတ စျေးကွက်' : 'Sustainable Marketplace' }}
                    </span>
                </div>

                <h2 class="eco-header-title">
                    {{ app()->getLocale() === 'mm' ? 'သဘာဝနှင့်သဟဇာတ ထုတ်ကုန်များကို ရှာဖွေပါ' : 'Discover Eco Friendly Products' }}
                </h2>

                <p class="eco-header-desc">
                    {{ app()->getLocale() === 'mm'
                        ? 'နေ့စဉ်အသုံးပြုရန် သဘာဝနှင့်သဟဇာတ ထုတ်ကုန်များကို ရွေးချယ်ပေးထားပါသည်။'
                        : 'Thoughtfully curated sustainable essentials designed to help you live cleaner, greener, and more responsibly every day.' }}
                </p>

            </div>

            <!-- Tabs -->
            <div class="eco-tabs-wrapper">
                <div class="eco-tabs">

                    <button
                        type="button"
                        class="eco-tab {{ $selectedCategory == 'all' ? 'active' : '' }}"
                        data-category="all">
                        {{ app()->getLocale() === 'mm' ? 'အားလုံး' : 'All' }}
                    </button>

                    @foreach($categories as $cat)
                        <button
                            type="button"
                            class="eco-tab {{ $selectedCategory == $cat->id ? 'active' : '' }}"
                            data-category="{{ $cat->id }}">
                            {{ $cat->display_name }}
                        </button>
                    @endforeach

                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div id="home-products-grid"></div>

    </div>
</section>

<link href="{{ asset('global/css/product-card.css') }}" rel="stylesheet">

<script src="{{ asset('global/js/ajax-global.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const wrapper = document.querySelector(".eco-tabs-wrapper");
        const tabs = document.querySelector(".eco-tabs");
        if (!wrapper || !tabs) return;

        // Clone tabs for seamless loop
        tabs.innerHTML += tabs.innerHTML;

        let speed = 0.6;
        let isPaused = false;

        function autoScroll() {
            if (!isPaused) {
                wrapper.scrollLeft += speed;

                // Seamless reset (no jump)
                if (wrapper.scrollLeft >= tabs.scrollWidth / 2) {
                    wrapper.scrollLeft = 0;
                }
            }

            requestAnimationFrame(autoScroll);
        }

        autoScroll();

        // Pause on hover/touch
        wrapper.addEventListener("mouseenter", () => isPaused = true);
        wrapper.addEventListener("mouseleave", () => isPaused = false);

        wrapper.addEventListener("touchstart", () => isPaused = true, { passive: true });
        wrapper.addEventListener("touchend", () => isPaused = false, { passive: true });

        // Mouse wheel horizontal scroll support
        wrapper.addEventListener("wheel", (e) => {
            e.preventDefault();
            isPaused = true;

            wrapper.scrollLeft += e.deltaY;

            clearTimeout(wrapper._wheelTimeout);
            wrapper._wheelTimeout = setTimeout(() => {
                isPaused = false;
            }, 700);

        }, { passive: false });

    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll(".eco-tab");
    const categoryInput = document.getElementById("homeCategory");

    document.addEventListener("click", function (e) {

        const tab = e.target.closest(".eco-tab, .eco-empty-action-btn");
        if (!tab) return;

        const category = tab.dataset.category;
        const categoryInput = document.getElementById("homeCategory");

        // Update value
        categoryInput.value = category;

        // Trigger AjaxList properly
        categoryInput.dispatchEvent(new Event("change", { bubbles: true }));

        // Active state (sync clones)
        document.querySelectorAll(".eco-tab").forEach(t => {
            t.classList.toggle("active", t.dataset.category === category);
        });
    });

    window.ajaxHomeProducts = new AjaxList({
        url: "{{ route('home.products') }}",
        params: {
            category: "#homeCategory"
        },
        targets: {
            products: "#home-products-grid"
        }
    });

    document.getElementById("homeCategory")
    .dispatchEvent(new Event("change", { bubbles: true }));
});
</script>
