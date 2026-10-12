@extends('user.layouts.master')

@section('content')

@php
    $isMm = app()->getLocale() === 'mm';
    $wishlistCount = $products->count();
    // Determine if the current authenticated user owns this wishlist
    $isOwner = auth()->check() && auth()->id() === $wishlistUser->id;
@endphp

<style>
    .eco-products-header {
        margin-top: 150px;
    }
</style>

<section class="eco-products-section">
    <div class="container pb-5">

        {{-- ========== HEADER – CONDITIONAL ========== --}}
        <div class="eco-products-header">
            <div class="eco-header-content">

                @if($isOwner)
                    {{-- OWNER VIEW (full actions) --}}
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

                    @if($wishlistCount > 0)
                        <div class="eco-tabs-wrapper" style="margin-top: 2rem;">
                            <div class="eco-tabs" style="justify-content: flex-start; gap: 0.75rem; flex-wrap: wrap;">
                                <button type="button"
                                        class="eco-tab"
                                        data-bs-toggle="modal"
                                        data-bs-target="#shareWishlistModal"
                                        style="cursor: pointer; background: var(--eco-green-light, #f0f7f0); color: var(--eco-green-dark, #2d6a4f); border: none;">
                                    <i class="fas fa-share-alt me-2"></i>
                                    {{ $isMm ? 'မျှဝေရန်' : 'Share Wishlist' }}
                                </button>
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

                @else
                    {{-- PUBLIC / SHARED VIEW (read‑only) --}}
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $wishlistUser->avatar_url ?? asset('user/img/avatar.jpg') }}"
                             class="rounded-circle"
                             width="60"
                             height="60"
                             style="object-fit:cover">
                        <div>
                            <h4 class="mb-0">{{ $wishlistUser->name }}</h4>
                            <small class="text-muted">
                                {{ $isMm ? 'မျှဝေထားသော လိုချင်သောပစ္စည်းများ' : 'Shared Wishlist' }}
                            </small>
                        </div>
                    </div>

                    <h2 class="eco-header-title">
                        {{ $wishlistCount }} {{ $isMm ? ' ခု သိမ်းဆည်းထားသည်' : ' items saved' }}
                    </h2>

                    <p class="eco-header-desc">
                        {{ $isMm
                            ? 'ဤအသုံးပြုသူ၏ နှစ်သက်ရာ ထုတ်ကုန်များကို ကြည့်ရှုနေပါသည်။'
                            : 'You are viewing this user’s favorite products.' }}
                    </p>
                @endif

            </div>
        </div>

        {{-- ========== PRODUCT GRID (same for both) ========== --}}
        <div style="margin-top:2.5rem">
            @include('user.pages.wishlist.partials.grid', [
                'products' => $products,

                'emptyTitle' => $isOwner
                    ? ($isMm ? 'သင်၏ လိုချင်သောပစ္စည်းများ မရှိသေးပါ။' : 'Your wishlist is empty.')
                    : ($isMm ? 'ဤအသုံးပြုသူ၏ လိုချင်သောပစ္စည်းများ မရှိသေးပါ။' : 'This user\'s wishlist is empty.'),

                'emptyMessage' => $isOwner
                    ? ($isMm ? 'သင်နှစ်သက်သော ထုတ်ကုန်များ မရှိသေးပါ။' : 'You have not added any products yet.')
                    : ($isMm ? 'ဤအသုံးပြုသူသည် လိုချင်သောပစ္စည်းစာရင်း မထည့်ရသေးပါ။' : 'This user has not added any products yet.')
            ])
        </div>

        {{-- ========== OWNER‑ONLY MODALS & SCRIPTS ========== --}}
        @if($isOwner && $wishlistCount > 0)
            @include('user.pages.wishlist.index')
        @endif

    </div>
</section>
@endsection
