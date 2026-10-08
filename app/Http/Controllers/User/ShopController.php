<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        $query = Product::with(['category', 'primaryImage'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // SEARCH (English + Burmese + Category)
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name_en', 'like', "%{$search}%")
                ->orWhere('name_mm', 'like', "%{$search}%")
                ->orWhereHas('category', function ($cat) use ($search) {
                    $cat->where('name', 'like', "%{$search}%")
                        ->orWhere('name_mm', 'like', "%{$search}%");
                });

            });
        }

        // CATEGORY FILTER
        if ($request->filled('category') && $request->category !== "all") {
            $query->where('category_id', $request->category);
        }

        // PRICE FILTER
        if ($request->filled('currency')) {

            if ($request->currency === 'usd') {

                if ($request->filled('price_from')) {
                    $query->where('price_usd', '>=', (float) $request->price_from);
                }

                if ($request->filled('price_to')) {
                    $query->where('price_usd', '<=', (float) $request->price_to);
                }

            } elseif ($request->currency === 'mmk') {

                $rate = \App\Models\Currency::where('code', 'MMK')
                    ->where('is_active', true)
                    ->value('rate') ?? 0;

                if ($rate > 0) {

                    if ($request->filled('price_from')) {
                        $query->where('price_usd', '>=', ((float)$request->price_from) / $rate);
                    }

                    if ($request->filled('price_to')) {
                        $query->where('price_usd', '<=', ((float)$request->price_to) / $rate);
                    }

                }
            }
        }

        // SORTING
        $sort = $request->get('sort', 'default');

        if ($sort === 'price_low') {

            if ($request->currency === 'mmk') {
                // MMK sorting = still sort by USD (because mmk is computed)
                $query->orderBy('price_usd', 'asc');
            } else {
                $query->orderBy('price_usd', 'asc');
            }

        } elseif ($sort === 'price_high') {

            if ($request->currency === 'mmk') {
                $query->orderBy('price_usd', 'desc');
            } else {
                $query->orderBy('price_usd', 'desc');
            }

        } elseif ($sort === 'newest') {

            $query->latest(); // created_at desc

        } elseif ($sort === 'oldest') {

            $query->oldest(); // created_at asc

        } elseif ($sort === 'name_asc') {

            $query->orderBy('name_en', 'asc');

        } elseif ($sort === 'name_desc') {

            $query->orderBy('name_en', 'desc');

        } elseif ($sort === 'rating_high') {

            $query->orderByDesc('reviews_avg_rating');

        } elseif ($sort === 'rating_low') {

            $query->orderBy('reviews_avg_rating', 'asc');

        } else {

            $query->latest();
        }

        $products = $query->paginate(9)->withQueryString();

        // AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.components.products.grid', [
                    'products' => $products,
                    'emptyTitle' => __('no_products_found'),
                    'emptyMessage' => __('no_products_message'),
                    'emptyButton' => 'all',
                    'buttonText' => __('view_all_products')
                ])->render(),
                'pagination' => view('user.pages.shop.partials.pagination', compact('products'))->render(),
            ]);
        }

        return view('user.pages.shop.index', compact('products', 'categories'));
    }

}
