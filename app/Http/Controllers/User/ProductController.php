<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function homeProducts(Request $request)
    {
        $data = $this->service->getHomeProducts($request);

        $data['featuredProducts'] = $this->service->getFeaturedProducts();

        $data['testimonials'] = Review::with([
                'user',
                'product.primaryImage'
            ])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'products' => view('user.home.sections.products_grid', array_merge($data, [
                    'emptyTitle' => __('no_products_found'),
                    'emptyMessage' => __('no_products_message'),
                    'emptyButton' => 'all',
                    'buttonText' => __('view_all_products')
                ]))->render()
            ]);
        }

        return view('index', $data);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'primaryImage', 'images']);

        $featuredProducts = $this->service
            ->getFeaturedProducts(3)
            ->where('id', '!=', $product->id)
            ->values();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage','category'])
            ->withAvg('reviews','rating')
            ->withCount('reviews')
            ->latest()
            ->take(8)
            ->get();

        $reviews = Review::with('user')
            ->where('product_id', $product->id)
            ->latest()
            ->paginate(5);

        if (Auth::check()) {
            foreach ($reviews as $review) {
                $review->is_helpful_by_user = \App\Models\ReviewHelpful::where('review_id', $review->id)
                    ->where('user_id', Auth::id())
                    ->exists();
            }
        } else {
            foreach ($reviews as $review) {
                $review->is_helpful_by_user = false;
            }
        }

        $totalReviews = Review::where('product_id', $product->id)->count();
        $averageRating = round(Review::where('product_id', $product->id)->avg('rating') ?? 0, 1);

        $ratingBreakdown = Review::where('product_id', $product->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        return view('user.pages.product.show', compact(
            'product',
            'featuredProducts',
            'relatedProducts',
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingBreakdown'
        ));
    }

    public function reviewStats(Product $product)
    {
        $total = Review::where('product_id', $product->id)->count();
        $average = round(Review::where('product_id', $product->id)->avg('rating') ?? 0, 1);

        $breakdown = Review::where('product_id', $product->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        return response()->json([
            'total' => $total,
            'average' => $average,
            'breakdown' => $breakdown
        ]);
    }
}
