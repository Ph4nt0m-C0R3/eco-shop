function updateCartBadge(count){

    // floating badge (mobile)
    let floatingBadge = $("#ecoCartBadge");

    if(count > 0){
        floatingBadge.removeClass("d-none").text(count);
    }else{
        floatingBadge.addClass("d-none").text(0);
    }

    // desktop badge
    let desktopBadge = $("#ecoDesktopCartBadge");
    if(desktopBadge.length){
        if(count > 0){
            desktopBadge
                .removeClass("d-none")
                .css("display", "flex")
                .text(count);
        }else{
            desktopBadge
                .addClass("d-none")
                .css("display", "none")
                .text(0);
        }
    }

    document.dispatchEvent(new Event("cartUpdated"));
}

$(document).on("click", ".addToCartBtn", function(e){
    e.preventDefault();

    let product_id = $(this).data("id");
    let qtyInput = $(this).data("qty-input");
    let stock = parseInt($(this).data("stock"));
    let quantity = parseInt($(qtyInput).val());

    if(isNaN(quantity) || quantity < 1){
        quantity = 1;
    }

    if(quantity > stock){
        quantity = stock;
        $(qtyInput).val(stock);
    }

    $.ajax({
        url: "/user/cart/add",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            product_id: product_id,
            quantity: quantity
        },
        success: function(res){

            if(res.success){

                // update cart badge count in navbar
                if(res.cart_count !== undefined){
                    updateCartBadge(res.cart_count);
                }

                if(typeof showToast === "function"){
                    showToast(res.message, "success");
                }else{
                    alert(res.message);
                }
            }else{
                if(typeof showToast === "function"){
                    showToast(res.message ?? "Failed to add to cart", "error");
                }else{
                    alert("Failed to add to cart");
                }
            }
        },
        error: function(xhr){

            let msg = (window.LANG && window.LANG.cart_something_wrong)
                ? window.LANG.cart_something_wrong
                : "Something went wrong. Please try again.";

            if(xhr.status === 401){
                msg = (window.LANG && window.LANG.cart_login_required)
                    ? window.LANG.cart_login_required
                    : "Please login to continue.";
            }

            if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message){
                msg = xhr.responseJSON.message;
            }

            if(typeof showToast === "function"){
                showToast(msg, "warning");
            }else{
                alert(msg);
            }
        }
    });
});


$(document).on("click", ".buyNowBtn", function(e){
    e.preventDefault();

    let product_id = $(this).data("id");
    let qtyInput = $(this).data("qty-input");
    let stock = parseInt($(this).data("stock"));
    let quantity = parseInt($(qtyInput).val());

    if(isNaN(quantity) || quantity < 1){
        quantity = 1;
    }

    if(quantity > stock){
        quantity = stock;
        $(qtyInput).val(stock);
    }

    $.ajax({
        url: "/user/cart/add",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            product_id: product_id,
            quantity: quantity
        },
        success: function(res){
            if(res.success){
                window.location.href = "/user/cart";
            }else{
                let msg = res.message ?? "Failed to add to cart";

                if(typeof showToast === "function"){
                    showToast(msg, "error");
                }else{
                    alert(msg);
                }
            }
        },
        error: function(xhr){

            let msg = (window.LANG && window.LANG.cart_something_wrong)
                ? window.LANG.cart_something_wrong
                : "Something went wrong. Please try again.";

            if(xhr.status === 401){
                msg = (window.LANG && window.LANG.cart_login_required)
                    ? window.LANG.cart_login_required
                    : "Please login to continue.";
            }

            if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message){
                msg = xhr.responseJSON.message;
            }

            if(typeof showToast === "function"){
                showToast(msg, "warning");
            }else{
                alert(msg);
            }
        }
    });
});
