<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    /**
     * ADMIN PRODUCT LIST
     */
    public function getAdminProducts(Request $request)
    {
        $query = Product::with(['category', 'images', 'primaryImage']);

        /**
         * SEARCH
         */
        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name_en) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(name_mm) LIKE ?', ["%{$search}%"]);
            });
        }

        /**
         * CATEGORY FILTER (by ID — safer)
         */
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        /**
         * SORTING
         */
        $sort = $request->get('sort');

        match ($sort) {
            'price_asc'  => $query->orderBy('price_usd', 'asc'),
            'price_desc' => $query->orderBy('price_usd', 'desc'),
            'name_asc'   => $query->orderByRaw('LOWER(name_en) asc'),
            'name_desc'  => $query->orderByRaw('LOWER(name_en) desc'),
            default      => $query->latest(),
        };

        return $query->paginate(12)->withQueryString();
    }

    /**
     * USER HOME PRODUCTS
     */
    public function getHomeProducts(Request $request)
    {
        $categories = Category::orderBy('sort_order')->get();
        $selectedCategory = $request->get('category', 'all');

        $showViewAll = false;

        if ($selectedCategory === 'all') {

            $products = collect();

            foreach ($categories as $category) {

                if ($products->count() >= 8) {
                    break;
                }

                $product = Product::with(['category', 'primaryImage'])
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->where('category_id', $category->id)
                    ->latest()
                    ->first();

                if ($product) {
                    $products->push($product);
                }
            }

            if ($products->count() < 8) {

                $remaining = 8 - $products->count();

                $extraProducts = Product::with(['category', 'primaryImage'])
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->whereNotIn('id', $products->pluck('id'))
                    ->latest()
                    ->take($remaining)
                    ->get();

                $products = $products->merge($extraProducts);
            }

        } else {

            $totalCount = Product::where('category_id', $selectedCategory)->count();

            $products = Product::with(['category', 'primaryImage'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->where('category_id', $selectedCategory)
                ->latest()
                ->take(8)
                ->get();

            $showViewAll = $totalCount > 8;
        }

        return compact('categories', 'products', 'selectedCategory', 'showViewAll');
    }

    /**
     * FEATURED PRODUCTS (Production Version)
     */
    public function getFeaturedProducts(int $limit = 10)
    {
        return Cache::remember(
            "featured_products_{$limit}",
            now()->addMinutes(10), // cache 10 mins
            function () use ($limit) {

                // Try highest rated products
                $products = Product::featured()
                    ->with(['category','primaryImage'])
                    ->withAvg('reviews','rating')
                    ->withCount('reviews')
                    ->take($limit)
                    ->get();

                // Fallback → newest products
                if ($products->isEmpty()) {
                    $products = Product::active()
                        ->inStock()
                        ->with(['category', 'primaryImage'])
                        ->withReviewStats()
                        ->latest()
                        ->take($limit)
                        ->get();
                }

                return $products;
            }
        );
    }
}
