<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Product::withAvg('reviews','rating')
            ->withAvg('reviews','rating')
            ->withCount('reviews')
            ->withExists(['wishlistedBy as isInWishlist' => function($q){
                $q->where('user_id', Auth::id());
            }])
            ->whereIn('id', Auth::user()
                ->wishlist()
                ->pluck('product_id'))
            ->get();

        return view('user.pages.wishlist.index', compact('products'));
    }

    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status'=>'guest'], 401);
        }

        $userId = Auth::id();
        $productId = $request->input('product_id');

        $exists = Wishlist::where('user_id',$userId)
            ->where('product_id',$productId)
            ->exists();

        if ($exists) {

            Wishlist::where('user_id',$userId)
                ->where('product_id',$productId)
                ->delete();

            $count = Wishlist::where('user_id',$userId)->count();

            return response()->json([
                'status'=>'removed',
                'count'=>$count
            ]);
        }

        Wishlist::create([
            'user_id'=>$userId,
            'product_id'=>$productId
        ]);

        $count = Wishlist::where('user_id',$userId)->count();

        return response()->json([
            'status'=>'added',
            'count'=>$count
        ]);
    }

    public function grid()
    {
        $products = Product::withAvg('reviews','rating')
            ->withExists(['wishlistedBy as isInWishlist' => function($q){
                $q->where('user_id', Auth::id());
            }])
            ->whereIn('id', Auth::user()
                ->wishlist()
                ->pluck('product_id'))
            ->get();


        return view('user.pages.wishlist.partials.grid', [
            'products' => $products,
            'emptyTitle'   => __('no_wishlist'),
            'emptyMessage' => __('no_wishlist_message'),
            'emptyButton'  => 'all',
            'buttonText'   => __('view_all_products')
        ]);
    }

    public function clear()
    {
        Auth::user()->wishlist()->delete();

        return response()->json([
            'status' => 'cleared'
        ]);
    }

    public function shareLink()
    {
        $user = Auth::user();

        $link = URL::temporarySignedRoute(
            'wishlist.public',
            now()->addDays(7), // expires in 7 days
            ['user' => $user->id]
        );

        return response()->json([
            'link' => $link
        ]);
    }

    public function public(User $user)
    {
        $products = Product::with('primaryImage')
            ->withAvg('reviews','rating')
            ->whereIn('id',
                Wishlist::where('user_id', $user->id)->pluck('product_id')
            )
            ->get();

        return view('user.pages.wishlist.public', [
            'products' => $products,
            'wishlistUser' => $user
        ]);
    }

}
