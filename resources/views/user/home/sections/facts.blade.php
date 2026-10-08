<!-- Eco Stats – colors #81c408 & #ff9800, fully bilingual (EN / MM) -->
<div class="eco-stats-scope">
    <style>
        .eco-stats-scope {
            --primary: #81c408;
            --primary-light: #e2f0d0;
            --secondary: #ff9800;
            --secondary-dark: #cc7b00;
            --dark-green: #1b3c1b;
            --medium-green: #2f5f2f;
            --light-green: #f6fbe9;
            background: var(--light-green);
            position: relative;
            overflow: hidden;
            font-family: "Noto Sans Myanmar", system-ui, sans-serif; /* Burmese support */
        }

        .eco-stats-scope .stat-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(129, 196, 8, 0.15);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 2.2rem 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary);
        }

        .eco-stats-scope .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
            border-top-color: var(--secondary);
        }

        .eco-stats-scope .stat-card:hover .stat-icon {
            transform: scale(1.05);
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .eco-stats-scope .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            transition: transform 0.3s ease, background 0.3s ease;
            border: 2px solid transparent;
        }

        .eco-stats-scope .stat-icon i {
            font-size: 30px;
            color: var(--primary);
        }

        .eco-stats-scope .stat-number {
            font-size: 3.2rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 4px;
            margin-bottom: 0.6rem;
            line-height: 1.2;
        }

        .eco-stats-scope .counter-suffix {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--secondary);
        }

        .eco-stats-scope .stat-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--medium-green);
            line-height: 1.4;
            margin-bottom: 0.4rem;
            text-align: center;
            min-height: 2.8em;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: capitalize;
        }

        .eco-stats-scope .stat-desc {
            font-size: 0.85rem;
            color: var(--secondary-dark);
            text-align: center;
            font-weight: 500;
        }

        @media (max-width: 576px) {
            .eco-stats-scope .stat-number {
                font-size: 2.4rem;
            }

            .eco-stats-scope .counter-suffix {
                font-size: 1.4rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .eco-stats-scope * {
                transition: none !important;
                animation: none !important;
            }
        }

        html[lang="mm"] .eco-stats-scope .stat-title {
            line-height: 1.9;
            text-transform: none;
        }

        html[lang="mm"] .eco-stats-scope .stat-desc {
            line-height: 1.8;
        }

        html[lang="mm"] .eco-stats-scope .stat-number {
            line-height: 1.4;
        }
    </style>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <!-- satisfied customers / စိတ်ကျေနပ်မှုရှိသော ဖောက်သည် -->
                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-smile"></i></div>
                        <h4 class="stat-title">
                            {{ app()->getLocale() === 'mm' ? 'စိတ်ကျေနပ်မှုရှိသော ဖောက်သည်' : 'Happy Customers' }}
                        </h4>
                        <div class="stat-number">
                            <span class="counter-num" data-target="1963" data-suffix="+">0</span>
                            <span class="counter-suffix"></span>
                        </div>
                        <div class="stat-desc">
                            {{ app()->getLocale() === 'mm' ? '၂၀၂၅ ခုနှစ်ကတည်းက ယုံကြည်စိတ်ချရ' : 'Trust Since 2025' }}
                        </div>
                    </div>
                </div>
                <!-- service quality / ဝန်ဆောင်မှုအရည်အသွေး -->
                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-star"></i></div>
                        <h4 class="stat-title">
                            {{ app()->getLocale() === 'mm' ? 'ဝန်ဆောင်မှုအရည်အသွေး' : 'Service Quality' }}
                        </h4>
                        <div class="stat-number">
                            <span class="counter-num" data-target="99" data-suffix="%">0</span>
                            <span class="counter-suffix"></span>
                        </div>
                        <div class="stat-desc">
                            {{ app()->getLocale() === 'mm' ? 'အပြုသဘောဆောင်သော တုံ့ပြန်ချက်' : 'Positive Feedback' }}
                        </div>
                    </div>
                </div>
                <!-- certificates / အစိမ်းရောင် လက်မှတ်များ -->
                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-certificate"></i></div>
                        <h4 class="stat-title">
                            {{ app()->getLocale() === 'mm' ? 'အစိမ်းရောင် လက်မှတ်များ' : 'Green Certificates' }}
                        </h4>
                        <div class="stat-number">
                            <span class="counter-num" data-target="33">0</span>
                            <span class="counter-suffix"></span>
                        </div>
                        <div class="stat-desc">
                            {{ app()->getLocale() === 'mm' ? 'ဂေဟစနစ် တံဆိပ်များ' : 'Eco Labels' }}
                        </div>
                    </div>
                </div>
                <!-- products / ဈေးကွက်ပစ္စည်းများ -->
                <div class="col-sm-6 col-lg-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-box-open"></i></div>
                        <h4 class="stat-title">
                            {{ app()->getLocale() === 'mm' ? 'ဈေးကွက်ပစ္စည်းများ' : 'Market Items' }}
                        </h4>
                        <div class="stat-number">
                            <span class="counter-num" data-target="789">0</span>
                            <span class="counter-suffix"></span>
                        </div>
                        <div class="stat-desc">
                            {{ app()->getLocale() === 'mm' ? 'လတ်ဆတ်သော ထုတ်ကုန်များ' : 'Fresh Supply' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict';
            const scope = document.currentScript.closest('.eco-stats-scope');
            if (!scope) return;

            const easeOutQuad = (t) => t * (2 - t);

            const counters = scope.querySelectorAll('.counter-num');
            if (!counters.length) return;

            const elements = Array.from(counters).map(el => {
                const target = parseInt(el.getAttribute('data-target'), 10);
                const suffix = el.getAttribute('data-suffix') || '';
                return {
                    element: el,
                    target: isNaN(target) ? 0 : target,
                    suffix: suffix,
                    animated: false,
                    suffixEl: el.nextElementSibling?.classList.contains('counter-suffix') ? el.nextElementSibling : null
                };
            }).filter(item => item.target > 0);

            function setValue(item, value) {
                if (item.element) {
                    const formatted = value >= 1000 ? value.toLocaleString() : value.toString();
                    item.element.innerText = formatted;
                }
                if (item.suffixEl) {
                    item.suffixEl.innerText = item.suffix;
                }
            }

            function startCounter(item) {
                if (item.animated) return;
                item.animated = true;

                const startTime = performance.now();
                const endValue = item.target;
                const duration = 1500;

                function animate(currentTime) {
                    const elapsed = currentTime - startTime;
                    let progress = Math.min(elapsed / duration, 1);
                    progress = easeOutQuad(progress);

                    const current = Math.round(progress * endValue);
                    setValue(item, current);

                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        setValue(item, endValue);
                    }
                }

                requestAnimationFrame(animate);
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const item = elements.find(i => i.element === el);
                        if (item && !item.animated) startCounter(item);
                    }
                });
            }, { threshold: 0.3, rootMargin: '20px' });

            elements.forEach(item => observer.observe(item.element));

            elements.forEach(item => {
                const rect = item.element.getBoundingClientRect();
                if (rect.top < window.innerHeight - 50 && rect.bottom > 0) {
                    if (!item.animated) startCounter(item);
                }
            });
        })();
    </script>
</div>
