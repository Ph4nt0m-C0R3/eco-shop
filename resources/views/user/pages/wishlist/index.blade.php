@extends('user.layouts.master')

@section('content')

@php
    $locale = app()->getLocale();
    $isMm = $locale === 'mm';
    $wishlistCount = $products->isNotEmpty() ? $products->count() : 0;

    // GLOBAL HEADER VARIABLES
    $title = $isMm ? 'လိုချင်သောပစ္စည်းများ' : 'Wishlist';

    $breadcrumbs = [
        [
            'label' => $isMm ? 'လိုချင်သောပစ္စည်းများ' : 'Wishlist',
            'icon' => 'fas fa-heart'
        ]
    ];
@endphp


{{-- ===== GLOBAL PAGE HEADER (REUSED — NO CHANGE) ===== --}}
@include('user.pages.partials.page-header')

<div id="wishlist-config"
     data-page="wishlist"
     data-confirm-clear="..."
     data-cleared-text="{{ __('no_wishlist') }}">
</div>

<!-- ===== REDESIGNED WISHLIST – LAYOUT MATCHES ECO PRODUCTS SECTION ===== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<section class="eco-products-section">
    <div class="container pb-5">

        <!-- === WISHLIST HEADER (identical structure to the original layout) === -->
        <div class="eco-products-header">
            <div class="eco-header-content">

                <div class="eco-header-top">
                    <span class="eco-tag">
                        <i class="fas fa-heart text-danger"></i>
                        {{ $isMm ? 'သင်နှစ်သက်သော ထုတ်ကုန်များ' : 'Your Wishlist' }}
                    </span>
                </div>

                <h2 class="eco-header-title">
                    @if($wishlistCount > 0)
                        <span id="wishlist-count">{{ $wishlistCount }}</span>
                        {{ $isMm ? ' ခု သိမ်းဆည်းထားသည်' : ' items saved' }}
                    @else
                        {{ __('no_wishlist') }}
                    @endif
                </h2>

                <p class="eco-header-desc">
                    {{ $isMm
                        ? 'သင်နှစ်သက်သော ထုတ်ကုန်များကို ဤနေရာတွင် သိမ်းဆည်းထားသည်။ စိတ်ကြိုက်ဝယ်ယူရန် အဆင်သင့်ဖြစ်ပါပြီ။'
                        : 'Products you’ve saved for later. Ready to make a sustainable choice?' }}
                </p>

                <!-- === ACTION BUTTONS – styled like the original tabs but only visible when items exist === -->
                @if($wishlistCount > 0)
                <div class="eco-tabs-wrapper" style="margin-top: 2rem;">
                    <div class="eco-tabs" style="justify-content: flex-start; gap: 0.75rem; flex-wrap: wrap;">

                        <!-- Share button (triggers modal) -->
                        <button type="button"
                                class="eco-tab"
                                data-bs-toggle="modal"
                                data-bs-target="#shareWishlistModal"
                                style="cursor: pointer; background: var(--eco-green-light, #f0f7f0); color: var(--eco-green-dark, #2d6a4f); border: none;">
                            <i class="fas fa-share-alt me-2"></i>
                            {{ $isMm ? 'မျှဝေရန်' : 'Share Wishlist' }}
                        </button>

                        <!-- Clear all form (styled as eco-tab) -->
                        <button type="button"
                            id="eco-clear-wishlist"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmClearWishlistModal"
                                class="eco-tab"
                                style="cursor:pointer;background:#fef2f2;color:#b91c1c;border:none;">
                            <i class="fas fa-trash-alt me-2"></i>
                            {{ $isMm ? 'အားလုံးရှင်းရန်' : 'Clear all' }}
                        </button>

                    </div>
                </div>
                @endif

            </div>
        </div>
        <!-- === END HEADER === -->

        <!-- === ORIGINAL PRODUCT GRID – 100% UNTOUCHED === -->
        <div id="wishlist-products-grid" style="margin-top: 2.5rem;">
            @include('user.pages.wishlist.partials.grid', [
                'products'     => $products,
                'emptyButton'  => 'all',
                'buttonText'   => __('view_all_products'),
                'emptyTitle'   => __('no_wishlist'),
                'emptyMessage' => __('no_wishlist_message'),
            ])
        </div>

    </div>
</section>

<!-- === SHARE WISHLIST MODAL (exactly as in the original layout style) === -->
@if($wishlistCount > 0)
<div class="modal fade" id="shareWishlistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-share-alt text-danger me-2"></i>
                    {{ $isMm ? 'Wishlist ကိုမျှဝေရန်' : 'Share Your Wishlist' }}
                </h5>
                <button type="button" class="eco-modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">
                    {{ $isMm ? 'လင့်ခ်ကို ကူးယူပြီး မိတ်ဆွေများနှင့် မျှဝေလိုက်ပါ။' : 'Copy the link below and share it with your friends.' }}
                </p>
                <div class="input-group">
                    <input type="text" class="form-control bg-light" id="wishlistShareLink"
                           value="" readonly>
                    <button class="btn btn-danger" type="button" id="copyShareBtn" onclick="copyWishlistLink()">
                        <i class="fas fa-copy me-2"></i>{{ $isMm ? 'ကူးယူရန်' : 'Copy' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- === CLEAR WISHLIST CONFIRM MODAL === -->
<div class="modal fade" id="confirmClearWishlistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-trash-alt me-2"></i>
                    {{ $isMm ? 'Wishlist ရှင်းမလား?' : 'Clear Wishlist?' }}
                </h5>
                <button type="button" class="eco-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <p class="mb-0 text-muted">
                    {{ $isMm
                        ? 'Wishlist ထဲရှိ ထုတ်ကုန်အားလုံးကို ဖျက်မည် ဖြစ်ပါသည်။ ဆက်လုပ်မလား?'
                        : 'This will remove all items from your wishlist. Continue?' }}
                </p>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    {{ $isMm ? 'မလုပ်တော့ပါ' : 'Cancel' }}
                </button>

                <button type="button" id="confirm-clear-wishlist-btn" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-2"></i>
                    {{ $isMm ? 'ရှင်းမည်' : 'Yes, Clear All' }}
                </button>
            </div>

        </div>
    </div>
</div>

<!-- === MINIMAL SCRIPT FOR SHARE FUNCTIONALITY === -->
<script src="{{ asset('user/js/wishlist.js') }}"></script>
<script>
window.copyWishlistLink = function() {
    const input = document.getElementById('wishlistShareLink');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = document.getElementById('copyShareBtn');
        btn.innerHTML = '<i class="fas fa-check me-2"></i>{{ $isMm ? "ကူးယူပြီးပါပြီ" : "Copied!" }}';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy me-2"></i>{{ $isMm ? "ကူးယူရန်" : "Copy" }}';
        }, 2000);
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const shareModal = document.getElementById('shareWishlistModal');

    if (shareModal) {
        shareModal.addEventListener('show.bs.modal', function () {

            fetch("{{ route('wishlist.share') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('wishlistShareLink').value = data.link;
            })
            .catch(err => console.error(err));

        });
    }

});
</script>
<!-- The layout already includes Font Awesome via CDN – no extra CSS needed if eco-* classes are global -->
@endsection
