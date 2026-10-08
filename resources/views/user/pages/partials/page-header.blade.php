<style>
    .eco-page-header {
        background:
            radial-gradient(circle at top left, rgba(129,196,8,0.15), transparent 40%),
            radial-gradient(circle at bottom right, rgba(168,230,46,0.18), transparent 45%),
            linear-gradient(135deg, #f6ffe9 0%, #ffffff 60%, #f2ffe0 100%);
        padding: 20px 0 40px;
        margin-top: var(--navbar-height);
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(129, 196, 8, 0.15);
    }

    /* Simple background accent */
    .eco-page-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #81c408, #a8e62e);
    }

    /* Minimal geometric accent */
    .eco-accent {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }

    .accent-circle {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(129,196,8,0.15), rgba(129,196,8,0.03));
        filter: blur(2px);
    }

    .accent-circle-1 {
        width: 160px;
        height: 160px;
        top: -40px;
        right: -40px;
    }

    .accent-circle-2 {
        width: 120px;
        height: 120px;
        bottom: -30px;
        left: -30px;
    }

    /* Container */
    .header-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 2;
    }

    /* Title */
    .eco-page-header h1 {
        font-size: 2.6rem;
        font-weight: 800;
        margin-bottom: 16px;
        color: #1a3a00;
        line-height: 1.15;
        letter-spacing: -0.5px;
    }

    /* Breadcrumb */
    .eco-breadcrumb-modern {
        display: inline-flex;
        align-items: center;
        line-height: 1.4;
        gap: 6px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(6px);
        padding: 14px 28px;
        border-radius: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(129, 196, 8, 0.15);
        flex-wrap: wrap;
        position: relative;
    }

    .eco-breadcrumb-modern a {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #5a6c5a;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 6px 14px;
        border-radius: 15px;
        transition: all 0.2s ease;
    }

    .eco-breadcrumb-modern a i {
        color: #81c408;
        font-size: 0.9rem;
    }

    .eco-breadcrumb-modern a:hover {
        background: rgba(129, 196, 8, 0.1);
        color: #2e7d32;
        transform: translateY(-1px);
    }

    .eco-breadcrumb-modern span.separator {
        color: rgba(129, 196, 8, 0.4);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
    }

    .eco-breadcrumb-modern .active {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #81c408;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 6px 14px;
        background: rgba(129, 196, 8, 0.08);
        border-radius: 15px;
    }

    .eco-breadcrumb-modern .active i {
        color: #81c408;
        font-size: 0.9rem;
    }

    .eco-breadcrumb-modern a,
    .eco-breadcrumb-modern .active {
        font-size: 0.9rem;
        line-height: 1.3;
    }

    /* Responsive */
    /* Tablet */
    @media (max-width: 768px) {

        .eco-page-header {
            padding: 18px 0 32px;
        }

        .eco-page-header h1 {
            font-size: 2.1rem;
            line-height: 1.2;
        }

        .eco-breadcrumb-modern {
            padding: 10px 18px;
        }
    }

    /* Mobile */
    @media (max-width: 480px) {

        .eco-page-header {
            padding: 16px 0 26px;
        }

        .eco-page-header h1 {
            font-size: 1.65rem;
            line-height: 1.25;
            margin-bottom: 14px;
        }

        .eco-breadcrumb-modern {
            padding: 8px 14px;
            gap: 4px;
            font-size: 0.85rem;
            justify-content: center;
        }

        .eco-breadcrumb-modern a,
        .eco-breadcrumb-modern .active {
            font-size: 0.82rem;
            padding: 5px 10px;
        }

        .accent-circle {
            display: none;
        }
    }

    /* =========================
    Myanmar Language Fix
    ========================= */

    html[lang="mm"] .eco-page-header h1 {
        font-size: 2.7rem;          /* Slightly larger (MM looks smaller visually) */
        line-height: 1.45;          /* Much more breathing room */
        letter-spacing: 0;          /* Remove negative spacing */
        font-weight: 700;           /* Slightly softer weight */
    }

    html[lang="mm"] .eco-breadcrumb-modern {
        line-height: 1.6;
    }

    html[lang="mm"] .eco-breadcrumb-modern a,
    html[lang="mm"] .eco-breadcrumb-modern .active {
        font-size: 1rem;            /* Slightly larger */
        line-height: 1.6;
        font-weight: 600;
    }

    @media (max-width: 480px) {

        html[lang="mm"] .eco-page-header h1 {
            font-size: 1.9rem;
            line-height: 1.5;
        }

        html[lang="mm"] .eco-breadcrumb-modern a,
        html[lang="mm"] .eco-breadcrumb-modern .active {
            font-size: 0.95rem;
            line-height: 1.6;
        }

    }
</style>

<div class="eco-page-header">
    <!-- Minimal accent -->
    <div class="eco-accent">
        <div class="accent-circle accent-circle-1"></div>
        <div class="accent-circle accent-circle-2"></div>
    </div>

    <div class="header-container text-center">
        <!-- Page Title -->
        <div>
            <h1>{{ strip_tags($title ?? 'Page Title') }}</h1>
        </div>

        <!-- Modern Breadcrumb -->
        <div class="eco-breadcrumb-modern">
            {{-- Always Home --}}
            <a href="{{ url('/') }}">
                <i class="fas fa-home"></i> {{ __('home') }}
            </a>

            @if(!empty($breadcrumbs))
                @foreach($breadcrumbs as $crumb)
                    <span class="separator">/</span>

                    @if(isset($crumb['url']))
                        <a href="{{ $crumb['url'] }}">
                            <i class="{{ $crumb['icon'] ?? 'fas fa-circle' }}"></i>
                            {{ $crumb['label'] }}
                        </a>
                    @else
                        <span class="active">
                            <i class="{{ $crumb['icon'] ?? 'fas fa-bookmark' }}"></i>
                            {!! $crumb['label'] !!}
                        </span>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple title fade-in
    const title = document.querySelector('.eco-page-header h1');
    if (title) {
        title.style.opacity = '0';
        title.style.transform = 'translateY(10px)';

        setTimeout(() => {
            title.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            title.style.opacity = '1';
            title.style.transform = 'translateY(0)';
        }, 100);
    }

    // Simple breadcrumb items animation
    const breadcrumbItems = document.querySelectorAll('.eco-breadcrumb-modern a, .eco-breadcrumb-modern .active');
    breadcrumbItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(8px)';

        setTimeout(() => {
            item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 200 + (index * 50));
    });
});
</script>
