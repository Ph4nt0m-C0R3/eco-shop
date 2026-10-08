<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ProductService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userHome(Request $request, ProductService $service)
    {
        $data = $service->getHomeProducts($request);

        $data['featuredProducts'] = $service->getFeaturedProducts();

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
                'products' => view('user.home.sections.products_grid', $data)->render()
            ]);
        }

        return view('user.home.index', $data);
    }
}
