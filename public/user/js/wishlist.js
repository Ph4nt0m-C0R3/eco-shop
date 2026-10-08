document.addEventListener("DOMContentLoaded", function () {

    let isClearingWishlist = false;

    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    const isLoggedIn = document
        .querySelector('meta[name="auth-user"]')
        ?.getAttribute("content") === "1";

    const config = document.getElementById("wishlist-config");

    const confirmText = config?.dataset.confirmClear;
    const clearedText = config?.dataset.clearedText;

    const emptyDesc = config?.dataset.emptyDesc;

    function reloadWishlistGrid(){

        const pageType = config?.dataset.page;
        if(pageType !== "wishlist") return;

        fetch("/user/wishlist/grid")
        .then(res => res.text())
        .then(html => {

            document.getElementById("wishlist-products-grid").innerHTML = html;

            const cards = document.querySelectorAll(".eco-product-card");

            if(cards.length === 0){

                const header = document.querySelector(".eco-header-title");
                if(header){
                    header.innerHTML = clearedText;
                }

                const tabs = document.querySelector(".eco-tabs-wrapper");
                if(tabs){
                    tabs.remove();
                }
            }

        });
    }

    function updateWishlistCount(change = 0){

        const countEl = document.getElementById("wishlist-count");
        if(!countEl) return;

        let count = parseInt(countEl.textContent || "0");

        count += change;
        if(count < 0) count = 0;

        countEl.textContent = count;

        // when empty change header text
        if(count === 0){

            const header = document.querySelector(".eco-header-title");

            if(header){
                header.innerHTML = clearedText;
            }

        }

    }

    /* ======================
       TOGGLE WISHLIST
    ====================== */
    document.body.addEventListener("click", function(e){

        const btn = e.target.closest(".eco-wishlist-btn");
        if(!btn) return;

        if(!isLoggedIn){
            let msg = (window.LANG && window.LANG.wishlist_login_required)
                ? window.LANG.wishlist_login_required
                : "Please login to use wishlist.";

            showToast(msg,"warning");
            return;
        }

        if(btn.dataset.loading === "1") return;
        btn.dataset.loading = "1";

        e.preventDefault();
        e.stopPropagation();

        fetch("/wishlist/toggle", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                product_id: btn.dataset.product
            })
        })
        .then(res => res.json())
        .then(data => {

            btn.dataset.loading = "0";

            const icon = btn.querySelector("i");

            if(data.status === "added"){
                icon.classList.replace("far","fas");
                updateWishlistCount(+1);
                let msg = (window.LANG && window.LANG.wishlist_added)
                    ? window.LANG.wishlist_added
                    : "Added to wishlist";

                showToast(msg,"success");
            }

            if(data.status === "removed"){

                icon.classList.replace("fas","far");
                updateWishlistCount(-1);

                // prevent multiple toasts when clearing all
                if(!isClearingWishlist){
                    let msg = (window.LANG && window.LANG.wishlist_removed)
                        ? window.LANG.wishlist_removed
                        : "Removed from wishlist";

                    showToast(msg,"info");
                }

                // REMOVE CARD ONLY IF CURRENT PAGE IS WISHLIST
                const pageType = config?.dataset.page;

                if(pageType === "wishlist"){

                    const card = btn.closest(".eco-product-card");

                    if(card){
                        card.remove();
                    }

                    // CHECK IF LAST CARD REMOVED
                    const remainingCards = document.querySelectorAll(".eco-product-card");

                    if(remainingCards.length === 0){

                        // reload from backend to render EMPTY STATE + VIEW ALL BUTTON
                        reloadWishlistGrid();

                    }
                }

            }

        })
        .catch(()=>{
            let msg = (window.LANG && window.LANG.wishlist_failed)
                ? window.LANG.wishlist_failed
                : "Wishlist action failed";

            showToast(msg,"error");

            btn.dataset.loading="0";
        });

    });

    /* ======================
       SHARE LINK AJAX
    ====================== */
    const shareModal = document.getElementById("shareWishlistModal");

    if(shareModal){

        shareModal.addEventListener("show.bs.modal", () => {

            fetch("/user/wishlist/share-link")
            .then(res => res.json())
            .then(data => {
                document.getElementById("wishlistShareLink").value = data.link;
            });

        });

    }

    /* ======================
    CLEAR ALL AJAX (FIXED — single toast only)
    ====================== */
    const confirmClearBtn = document.getElementById("confirm-clear-wishlist-btn");

    if(confirmClearBtn){

        confirmClearBtn.addEventListener("click", function handler(){

            // prevent multiple execution
            if(confirmClearBtn.dataset.processing === "1") return;
            confirmClearBtn.dataset.processing = "1";

            fetch("/user/wishlist/clear", {
                method:"DELETE",
                headers:{
                    "X-CSRF-TOKEN": token,
                    "Accept":"application/json"
                }
            })
            .then(res => res.json())
            .then(data => {

                if(data.status === "cleared"){

                    // reset count
                    const countEl = document.getElementById("wishlist-count");
                    if(countEl){
                        countEl.textContent = "0";
                    }

                    reloadWishlistGrid();

                    // ONLY ONE TOAST
                    let msg = (window.LANG && window.LANG.wishlist_cleared)
                        ? window.LANG.wishlist_cleared
                        : "Wishlist cleared successfully";

                    showToast(msg,"success");

                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("confirmClearWishlistModal")
                    );

                    if(modal){
                        modal.hide();
                    }
                }
            })
            .finally(()=>{
                confirmClearBtn.dataset.processing = "0";
            });

        }, { once: true });
    }

    /* ======================
    EMPTY STATE BUTTON (WISHLIST ONLY)
    ====================== */
    document.body.addEventListener("click", function(e){

        const pageType = config?.dataset.page;
        if(pageType !== "wishlist") return;

        const btn = e.target.closest(".eco-empty-action-btn");
        if(!btn) return;

        let msg = (window.LANG && window.LANG.wishlist_redirect_shop)
            ? window.LANG.wishlist_redirect_shop
            : "Redirecting to shop...";

        showToast(msg,"info");

        const category = btn.dataset.category;

        if(category === "all"){
            window.location.href = "/shop";
        }

    });

});
