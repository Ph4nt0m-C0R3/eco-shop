document.addEventListener('DOMContentLoaded', function() {
    // ---------- Product data from container ----------
    const container = document.getElementById('product-detail-container');
    let maxStock = container ? parseInt(container.dataset.stock) : 0;
    const productName = container ? container.dataset.name : '';
    const productId = container ? container.dataset.id : '';

    // ---------- Global helpers ----------
    function escapeHTML(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"]/g, function(c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c] || c;
        });
    }

    // For review items (full stars only)
    function renderStars(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            html += `<i class="${i <= rating ? 'fas' : 'far'} fa-star"></i>`;
        }
        return html;
    }

    // For average rating – supports half stars
    function renderHalfStars(rating) {
        let html = '';

        const full = Math.floor(rating);
        const half = (rating - full) >= 0.5;

        for (let i = 1; i <= 5; i++) {
            if (i <= full) {
                html += '<i class="fas fa-star"></i>';
            } else if (i === full + 1 && half) {
                html += '<i class="fas fa-star-half-alt"></i>';
            } else {
                html += '<i class="far fa-star"></i>';
            }
        }

        return html;
    }

    // ---------- Update stock UI and dataset ----------
    function updateStockUI(newStock) {
        maxStock = newStock;
        if (container) container.dataset.stock = newStock;

        const stockElement = document.querySelector('.luxury-stock-simple .luxury-stock-status');
        if (stockElement) {
            const statusText = newStock > 0 ? (newStock < 10 ? 'Low Stock' : 'In Stock') : 'Out of Stock';
            stockElement.textContent = statusText;
        }
        const countElement = document.querySelector('.luxury-stock-count');
        if (countElement) countElement.textContent = `(${newStock} left)`;

        if (quantity > newStock) quantity = newStock;
        updateQuantity();
        updateQuantityMobile();
    }

    // ---------- Quantity logic ----------
    let quantity = 1;

    function updateQuantity() {
        const quantityDisplay = document.getElementById('quantityDisplay');
        const quantityInput = document.getElementById('quantity');
        if (quantityDisplay) quantityDisplay.value = quantity;
        if (quantityInput) quantityInput.value = quantity;

        const decrementBtn = document.getElementById('decrement');
        const incrementBtn = document.getElementById('increment');
        if (decrementBtn) decrementBtn.disabled = quantity <= 1;
        if (incrementBtn) incrementBtn.disabled = quantity >= maxStock;
    }

    function updateQuantityMobile() {
        const quantityDisplayMobile = document.getElementById('quantityDisplayMobile');
        const quantityInputMobile = document.getElementById('quantityMobile');
        if (quantityDisplayMobile) quantityDisplayMobile.value = quantity;
        if (quantityInputMobile) quantityInputMobile.value = quantity;

        const decrementBtnMobile = document.getElementById('decrementMobile');
        const incrementBtnMobile = document.getElementById('incrementMobile');
        if (decrementBtnMobile) decrementBtnMobile.disabled = quantity <= 1;
        if (incrementBtnMobile) incrementBtnMobile.disabled = quantity >= maxStock;
    }

    const decrementBtn = document.getElementById('decrement');
    const incrementBtn = document.getElementById('increment');
    if (decrementBtn) decrementBtn.addEventListener('click', function() {
        if (quantity > 1) { quantity--; updateQuantity(); updateQuantityMobile(); }
    });
    if (incrementBtn) incrementBtn.addEventListener('click', function() {
        if (quantity < maxStock) { quantity++; updateQuantity(); updateQuantityMobile(); }
    });

    const decrementBtnMobile = document.getElementById('decrementMobile');
    const incrementBtnMobile = document.getElementById('incrementMobile');
    if (decrementBtnMobile) decrementBtnMobile.addEventListener('click', function() {
        if (quantity > 1) { quantity--; updateQuantity(); updateQuantityMobile(); }
    });
    if (incrementBtnMobile) incrementBtnMobile.addEventListener('click', function() {
        if (quantity < maxStock) { quantity++; updateQuantity(); updateQuantityMobile(); }
    });

    ['quantity', 'quantityMobile'].forEach(id => {

        const input = document.getElementById(id);
        if (!input) return;

        input.addEventListener('input', function () {

            let val = parseInt(this.value);

            // if empty or invalid, force back to 1
            if (isNaN(val) || val < 1) {
                val = 1;
            }

            // max stock limit
            if (val > maxStock) {
                val = maxStock;
            }

            quantity = val;

            updateQuantity();
            updateQuantityMobile();
        });

    });

    updateQuantity();
    updateQuantityMobile();

    // ---------- Wishlist (adjust URL to your route) ----------
    const wishlistBtn = document.getElementById('wishlistBtn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const isActive = this.classList.contains('active');
            const url = isActive ? '/wishlist/remove' : '/wishlist/add';
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                body: new URLSearchParams({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('active');
                    const icon = this.querySelector('i');
                    if (this.classList.contains('active')) {
                        icon.classList.remove('far'); icon.classList.add('fas');
                        showToast(window.LANG.review_wishlist_add, 'success');
                    } else {
                        icon.classList.remove('fas'); icon.classList.add('far');
                        showToast(window.LANG.review_wishlist_remove, 'info');
                    }
                }
            })
            .catch(() => showToast(window.LANG.review_wishlist_fail, 'error'));
        });
    }

    // ---------- Add to Cart – REAL AJAX, updates stock ----------
    function setupCartFunctionality(btnId) {
        const btn = document.getElementById(btnId);
        if (!btn) return;

        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled || quantity > maxStock) return;

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' +
                (btnId.includes('addToCart') ? 'Adding...' : 'Processing...');
            this.disabled = true;

            fetch('/cart/add', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                body: new URLSearchParams({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.new_stock !== undefined) {
                        updateStockUI(data.new_stock);
                    }
                    showToast(
                    window.LANG.review_added_cart.replace(':count', quantity),
                    'success'
                    );
                } else {
                    showToast(data.message || 'Failed to add to cart', 'error');
                }
            })
            .catch(() => showToast('Network error. Please try again.', 'error'))
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }

    ['addToCart', 'addToCartMobile', 'buyNow', 'buyNowMobile'].forEach(setupCartFunctionality);

    // ---------- Image gallery ----------
    function initializeImageGallery() {
        const isMobile = window.innerWidth < 992;
        const mainImageId = isMobile ? 'mainProductImageMobile' : 'mainProductImage';
        const mainImage = document.getElementById(mainImageId);
        if (!mainImage) return;

        document.querySelectorAll('.luxury-thumbnail').forEach((img, index) => {
            img.addEventListener('click', function () {
                mainImage.src = this.getAttribute('data-full');
                document.querySelectorAll('.luxury-thumbnail-item').forEach(item => {
                    item.classList.remove('active');
                });
                this.parentElement.classList.add('active');
            });
            if (index === 0) img.parentElement.classList.add('active');
        });
    }
    initializeImageGallery();
    window.addEventListener('resize', initializeImageGallery);

    // ---------- Tabs ----------
    document.querySelectorAll('.luxury-tab-item').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            document.querySelectorAll('.luxury-tab-item').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.luxury-tab-pane').forEach(pane => pane.classList.remove('active'));
            const targetPane = document.getElementById(tabId + '-tab');
            if (targetPane) targetPane.classList.add('active');
        });
    });

    // ---------- Helpful button – REVIEWS TABLE AJAX ----------
    document.addEventListener("click", function(e) {
        const btn = e.target.closest(".luxury-helpful-btn");
        if (!btn) return;

        const reviewId = btn.dataset.reviewId;

        fetch(`/review/${reviewId}/helpful`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;

            btn.dataset.active = data.active ? "1" : "0";

            if (data.active) btn.classList.add("active");
            else btn.classList.remove("active");

            btn.querySelector("span").textContent = data.helpful_count;
        });
    });

    // ---------- Share button ----------
    const shareBtn = document.querySelector('.luxury-share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: productName,
                    text: window.LANG.review_share_text,
                    url: window.location.href,
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                showToast(window.LANG.review_link_copied, 'success');
            }
        });
    }

    // ---------- Live Character Counter ----------
    const reviewTextarea = document.querySelector('.luxury-review-textarea');
    const charCount = document.getElementById('charCount');
    const maxLength = 500;

    if (reviewTextarea && charCount) {

        function updateCharCount() {
            const length = reviewTextarea.value.length;
            charCount.textContent = length;

            // Optional: color feedback
            if (length > 450) {
                charCount.style.color = '#f44336'; // red
            } else if (length > 350) {
                charCount.style.color = '#ff9800'; // orange
            } else {
                charCount.style.color = '#888'; // normal
            }
        }

        // Live typing
        reviewTextarea.addEventListener('input', updateCharCount);

        // Initialize on open
        updateCharCount();
    }

    // ---------- REVIEW MODAL SYSTEM ----------
    const reviewModal = document.getElementById('reviewModal');
    const openReviewBtn = document.querySelector('button.luxury-review-btn');
    const closeReviewBtn = document.querySelector('.luxury-review-close');
    const overlay = document.querySelector('.luxury-review-overlay');
    const cancelBtn = document.getElementById('closeModalBtn');
    const stars = document.querySelectorAll('.luxury-star');
    const ratingInput = document.getElementById('ratingValue');
    const reviewForm = document.getElementById('reviewForm');

    if (openReviewBtn && reviewModal) {
        openReviewBtn.addEventListener('click', () => {
            reviewModal.classList.add('active');
        });
    }

    [closeReviewBtn, overlay, cancelBtn].forEach(el => {
        if (!el || !reviewModal) return;

        el.addEventListener('click', () => {
            reviewModal.classList.remove('active');

            if (reviewForm) reviewForm.reset();

            if (charCount) {
                charCount.textContent = 0;
                charCount.style.color = '#888';
            }

            stars.forEach(s => {
                s.classList.remove('active');
                s.classList.replace('fas', 'far');
            });

            if (ratingInput) ratingInput.value = '';
        });
    });

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const val = this.dataset.value;
            if (ratingInput) ratingInput.value = val;

            stars.forEach(s => {
                s.classList.remove('active');
                s.classList.replace('fas', 'far');
            });

            stars.forEach(s => {
                if (s.dataset.value <= val) {
                    s.classList.add('active');
                    s.classList.replace('far', 'fas');
                }
            });
        });
    });

    // ---------- REAL TIME REVIEW SUBMISSION (REVIEWS TABLE) ----------
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const rating = document.getElementById('ratingValue')?.value;
            const message = this.querySelector('textarea[name="message"]')?.value.trim();

            if (!rating) return showToast(window.LANG.review_select_rating, 'info');
            if (!message) return showToast(window.LANG.review_write_review, 'info');

            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!token) return showToast('Security token missing', 'error');

            const submitBtn = this.querySelector('.luxury-submit-review');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            }

            fetch('/review/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    product_id: productId,
                    rating: rating,
                    message: message
                })
            })
            .then(async res => {
                const data = await res.json().catch(() => null);

                if (!res.ok) {
                    console.error("Review error response:", data);
                    throw new Error(data?.message || "Request failed");
                }

                return data;
            })
            .then(data => {
                if (!data.success) throw new Error("Review failed");

                const reviewsList = document.getElementById('reviewsList');
                if (!reviewsList) throw new Error("reviewsList not found");

                const currentUserId = document.querySelector('meta[name="current-user-id"]')?.content;
                const userAvatar =
                    document.querySelector('meta[name="current-user-avatar"]')?.content
                    || `{{ asset('user/img/avatar.jpg') }}`;

                const userName =
                    document.querySelector('meta[name="current-user-name"]')?.content
                    || 'User';

                const existingReview = currentUserId
                    ? document.querySelector(`.luxury-review-item[data-user-id="${currentUserId}"]`)
                    : null;

                if (existingReview) {
                    showToast(window.LANG.review_updated, "info");

                    existingReview.setAttribute('data-review-id', data.review.id);
                    existingReview.querySelector('.luxury-review-text').innerHTML = escapeHTML(message);
                    existingReview.querySelector('.luxury-stars').innerHTML = renderStars(parseInt(rating));
                    existingReview.querySelector('.luxury-review-date').innerText = "Updated just now";

                    const helpfulBtn = existingReview.querySelector('.luxury-helpful-btn');
                    if (helpfulBtn) helpfulBtn.dataset.reviewId = data.review.id;

                } else {
                    showToast(window.LANG.review_success, "success");

                    const newReview = document.createElement('div');
                    newReview.className = 'luxury-review-item';
                    newReview.setAttribute('data-user-id', currentUserId);
                    newReview.setAttribute('data-review-id', data.review.id);

                    newReview.innerHTML = `
                        <div class="luxury-review-header">
                            <div class="luxury-reviewer-info">
                                <div class="luxury-reviewer-avatar">
                                    <img src="${escapeHTML(userAvatar)}"
                                        alt="${escapeHTML(userName)}"
                                        class="luxury-avatar-img">
                                </div>
                                <div>
                                    <h6 class="luxury-reviewer-name">You</h6>
                                    <div class="luxury-review-date">Just now</div>
                                </div>
                            </div>

                            <div class="luxury-review-rating">
                                <div class="luxury-stars">
                                    ${renderStars(parseInt(rating))}
                                </div>
                            </div>
                        </div>

                        <div class="luxury-review-content">
                            <p class="luxury-review-text">${escapeHTML(message)}</p>
                        </div>

                        <button class="luxury-helpful-btn" data-review-id="${data.review.id}">
                            Helpful (<span>0</span>)
                        </button>
                    `;

                    const emptyMsg = document.getElementById('noReviewsMessage');
                    if (emptyMsg) emptyMsg.remove();

                    reviewsList.prepend(newReview);

                }

                // ---- Close modal ----
                reviewModal.classList.remove('active');
                reviewForm.reset();
                stars.forEach(s => {
                    s.classList.remove('active');
                    s.classList.replace('fas', 'far');
                });
                if (ratingInput) ratingInput.value = '';

                // ---- Refresh stats separately (DO NOT BREAK SUBMIT SUCCESS) ----
                fetch(`/product/${productId}/review-stats`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(stats => {

                    const badge = document.getElementById('reviewCountBadge');
                    const total = document.getElementById('reviewTotal');
                    const avg = document.getElementById('avgRating');
                    const avgStars = document.getElementById('avgStars');

                    if (badge) badge.innerText = stats.total;
                    if (total) total.innerText = stats.total + " reviews";
                    if (avg) avg.innerText = stats.average.toFixed(1);

                    if (avgStars) {
                        avgStars.innerHTML = renderHalfStars(stats.average);
                    }

                    const ratingTextEl = document.querySelector('.luxury-rating-text');
                    if (ratingTextEl) {
                        ratingTextEl.textContent = ratingTextEl.textContent.replace(
                            /\d+(\.\d+)?(?=\s*\/\s*5)/,
                            stats.average.toFixed(1)
                        );
                    }

                    const reviewCountSpan = document.querySelector('.luxury-review-count span');
                    if (reviewCountSpan) {
                        reviewCountSpan.textContent = reviewCountSpan.textContent.replace(/\d+/, stats.total);
                    }

                    const headerStars = document.querySelector('.luxury-rating-badge .luxury-stars');
                    if (headerStars) {
                        headerStars.innerHTML = renderHalfStars(stats.average);
                    }

                    updateRatingBreakdownFromBackend(stats.breakdown, stats.total);

                })
                .catch(err => {
                    console.warn('Stats refresh failed — ignored:', err);
                });
            })

            .catch(error => {
                console.error('Review submission error:', error);
                showToast(window.LANG.review_failed, "error");
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.dataset.originalText || '<i class="fas fa-paper-plane me-2"></i>Submit Review';
                }
            });
        });

        const submitBtn = reviewForm.querySelector('.luxury-submit-review');
        if (submitBtn) submitBtn.dataset.originalText = submitBtn.innerHTML;
    }

    // ---------- Helper: Update rating breakdown bars ----------
    function updateRatingBreakdownFromBackend(breakdown, total) {
        const bars = document.querySelectorAll('.luxury-rating-bar');

        bars.forEach(bar => {
            const label = bar.querySelector('.luxury-rating-label');
            const countSpan = bar.querySelector('.luxury-rating-count');
            const fillDiv = bar.querySelector('.luxury-rating-fill');

            if (!label || !countSpan || !fillDiv) return;

            const starValue = parseInt(label.textContent.trim());

            const count = breakdown[starValue] || 0;
            countSpan.textContent = count;

            const percent = total > 0 ? (count / total) * 100 : 0;
            fillDiv.style.width = percent + "%";
        });
    }
});
