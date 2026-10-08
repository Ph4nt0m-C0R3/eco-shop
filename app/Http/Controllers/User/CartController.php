<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $items = Cart::where('user_id',$userId)->get();
        $cart = [];

        foreach($items as $item){

            $product = Product::with('primaryImage')->find($item->product_id);

            if(!$product){
                continue;
            }

            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->display_name,
                'eco_badge' => $product->display_eco_badge,
                'price_mmk' => $product->price_mmk,
                'price_usd' => $product->price_usd,
                'quantity' => $item->qty,
                'stock' => $product->available_stock,
                'image' => $product->primaryImage
                    ? asset('storage/'.$product->primaryImage->image)
                    : asset('default/no-image.png'),
            ];
        }

        $subtotal_mmk = collect($cart)->sum(fn($i)=>$i['price_mmk']*$i['quantity']);
        $subtotal_usd = collect($cart)->sum(fn($i)=>$i['price_usd']*$i['quantity']);

        return view('user.pages.cart.index',compact('cart','subtotal_mmk','subtotal_usd'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('cart.login_required')
            ], 401);
        }

        $request->validate([
            'product_id'=>'required|exists:products,id',
            'quantity'=>'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $qty = $request->quantity ?? 1;

        $product = Product::findOrFail($request->product_id);

        $cart = Cart::firstOrNew([
            'user_id'=>$userId,
            'product_id'=>$product->id
        ]);

        $newQty = ($cart->exists ? $cart->qty : 0) + $qty;

        // stock protection
        if ($newQty > $product->available_stock) {
            return response()->json([
                'success' => false,
                'message' => __('cart.stock_only', ['stock' => $product->available_stock])
            ], 422);
        }

        $cart->qty = $newQty;
        $cart->save();

        $cartCount = Cart::where('user_id',$userId)->sum('qty');

        return response()->json([
            'success'=>true,
            'message'=> __('cart.added'),
            'cart_count'=>$cartCount
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1'
        ]);

        $userId = Auth::id();

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->available_stock) {
            return response()->json([
                'success'=>false,
                'message'=> __('cart.stock_only', ['stock' => $product->available_stock])
            ], 422);
        }

        Cart::where('user_id',$userId)
            ->where('product_id',$request->product_id)
            ->update(['qty'=>$request->quantity]);

        $cartCount = Cart::where('user_id',$userId)->sum('qty');

        return response()->json([
            'success'=>true,
            'message'=> __('cart.updated'),
            'cart_count'=>$cartCount
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id'=>'required'
        ]);

        $userId = Auth::id();

        Cart::where('user_id',$userId)
            ->where('product_id',$request->product_id)
            ->delete();

        $cartCount = Cart::where('user_id',$userId)->sum('qty');

        return response()->json([
            'success'=>true,
            'message'=> __('cart.removed'),
            'cart_count'=>$cartCount
        ]);
    }

    public function clear()
    {
        $userId = Auth::id();

        Cart::where('user_id',$userId)->delete();

        return response()->json([
            'success'=>true,
            'message'=> __('cart.cleared'),
            'cart_count'=>0
        ]);
    }
}
