<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Spinner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* =========================
        ENHANCED ECO SPINNER
        ========================= */

        .eco-spinner-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(2px);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.4s;
        }

        .eco-spinner-overlay.hide {
            opacity: 0;
            visibility: hidden;
        }

        /* Enhanced leaf spinner */
        .eco-spinner {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(46, 125, 50, 0.05) 100%);
            border: 3px dashed #4caf50;
            border-top-color: #2e7d32;
            border-right-color: #81c784;
            border-bottom-color: #a5d6a7;
            border-left-color: #66bb6a;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: eco-spin 1.6s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
            box-shadow:
                0 4px 12px rgba(76, 175, 80, 0.15),
                inset 0 0 8px rgba(165, 214, 167, 0.2);
        }

        .eco-spinner i {
            font-size: 28px;
            background: linear-gradient(45deg, #2e7d32, #4caf50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: eco-pulse 2s ease-in-out infinite;
        }

        .eco-spinner-text {
            margin-top: 20px;
            font-weight: 700;
            color: #2e7d32;
            letter-spacing: 0.3px;
            font-size: 16px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            text-transform: uppercase;
        }

        .eco-spinner-subtext {
            margin-top: 6px;
            font-weight: 400;
            color: #666;
            font-size: 13px;
            letter-spacing: 0.2px;
            opacity: 0.8;
        }

        /* Enhanced Animations */
        @keyframes eco-spin {
            0% {
                transform: rotate(0deg);
                border-width: 3px;
            }
            50% {
                transform: rotate(180deg);
                border-width: 4px;
            }
            100% {
                transform: rotate(360deg);
                border-width: 3px;
            }
        }

        @keyframes eco-pulse {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
            25% {
                transform: scale(1.1) rotate(-5deg);
                opacity: 0.9;
            }
            75% {
                transform: scale(0.95) rotate(5deg);
                opacity: 1;
            }
        }

        /* Subtle background animation */
        .eco-spinner-overlay::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(76, 175, 80, 0.03) 0%, transparent 70%);
            animation: subtle-bg 6s ease-in-out infinite;
        }

        @keyframes subtle-bg {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Loading text animation */
        .eco-loading-text span {
            display: inline-block;
            animation: eco-text-wave 1.6s infinite ease-in-out;
        }

        .eco-loading-text span:nth-child(1) { animation-delay: 0s; }
        .eco-loading-text span:nth-child(2) { animation-delay: 0.1s; }
        .eco-loading-text span:nth-child(3) { animation-delay: 0.2s; }
        .eco-loading-text span:nth-child(4) { animation-delay: 0.3s; }
        .eco-loading-text span:nth-child(5) { animation-delay: 0.4s; }
        .eco-loading-text span:nth-child(6) { animation-delay: 0.5s; }
        .eco-loading-text span:nth-child(7) { animation-delay: 0.6s; }
        .eco-loading-text span:nth-child(8) { animation-delay: 0.7s; }
        .eco-loading-text span:nth-child(9) { animation-delay: 0.8s; }
        .eco-loading-text span:nth-child(10){ animation-delay: 0.9s; }

        @keyframes eco-text-wave {
            0%,100% { transform: translateY(0); opacity: 0.6; }
            50%     { transform: translateY(-6px); opacity: 1; }
        }

    </style>
</head>
<body>
    <!-- Enhanced Eco Spinner -->
    <div id="eco-spinner" class="eco-spinner-overlay">
        <div class="eco-spinner">
            <i class="fas fa-leaf"></i>
        </div>
        <div class="eco-spinner-text eco-loading-text">
            <span>L</span><span>o</span><span>a</span><span>d</span><span>i</span><span>n</span><span>g</span><span>.</span><span>.</span><span>.</span>
        </div>
        <div class="eco-spinner-subtext">Please Wait</div>
    </div>

    <script>
        (function () {

            const spinner = document.getElementById('eco-spinner');
            if (!spinner) return;

            function showSpinner() {
                spinner.classList.remove('hide');
            }

            function hideSpinner() {
                spinner.classList.add('hide');
            }

            /* Hide when page is ready */
            document.addEventListener('DOMContentLoaded', function () {
                hideSpinner();
            });

            /* Fix back/forward cache */
            window.addEventListener('pageshow', function () {
                hideSpinner();
            });

            /* Show spinner only for real navigation */
            document.addEventListener('click', function (e) {

                const link = e.target.closest('a');

                if (!link) return;

                // Ignore links with no-spinner class
                if (link.classList.contains('no-spinner')) return;

                // Ignore anchor links
                if (link.getAttribute('href')?.startsWith('#')) return;

                // Ignore download attribute
                if (link.hasAttribute('download')) return;

                // Only same-origin navigation
                if (link.hostname === window.location.hostname) {
                    showSpinner();
                }
            });

            /* Show spinner on form submit */
            document.addEventListener('submit', function (e) {

                const form = e.target;

                // Ignore if form is AJAX
                if (form.hasAttribute('data-ajax')) return;

                // Ignore if JS already prevented submission
                if (e.defaultPrevented) return;

                showSpinner();
            });

        })();
    </script>

</body>
</html>
