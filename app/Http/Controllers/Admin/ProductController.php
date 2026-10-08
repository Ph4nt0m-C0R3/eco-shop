<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request, ProductService $service)
    {
        $products = $service->getAdminProducts($request);
        $categories = Category::orderBy('sort_order')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.product.partials.product_grid', compact('products'))->render(),
                'pagination' => view('admin.product.partials.pagination', compact('products'))->render(),
                'modals' => view('admin.product.partials.product_modals', compact('products'))->render(),
            ]);
        }

        return view('admin.product.list', compact('products','categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name_en' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('products')->using(function ($query) use ($request) {
                        $query->whereRaw('LOWER(name_en) = LOWER(?)', [$request->name_en]);
                    }),
                ],
                'name_mm'        => 'required|string|max:255',
                'description_en' => 'nullable|string',
                'description_mm' => 'nullable|string',

                'currency' => 'required|in:USD,MMK',
                'price' => 'required|numeric|min:0.01',

                'stock'          => 'required|integer|min:0',
                'eco_badge'      => 'nullable|string|max:100',
                'eco_badge_mm' => 'nullable|string|max:100',

                'category_id'    => 'required|exists:categories,id',

                'images'         => 'required|array|min:1|max:5',
                'images.*'       => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                'primary_image' => 'nullable|string',
            ],
            [
                'name_en.required'     => 'Product name (English) is required.',
                'name_mm.required'     => 'Product name (Myanmar) is required.',

                'name_en.unique'       => 'A product with this name already exists.',

                'price.required' => 'Price is required.',
                'price.numeric' => 'Price must be a number.',
                'currency.required' => 'Currency selection is required.',

                'stock.required'       => 'Stock quantity is required.',
                'stock.integer'        => 'Stock must be a valid number.',

                'category_id.required' => 'Please select a category.',
                'category_id.exists'   => 'Selected category is invalid.',

                'images.max'           => 'You can upload up to 5 images only.',
                'images.*.image'       => 'Each file must be an image.',
                'images.*.max'         => 'Each image must be less than 2MB.',
                'images.*.mimes' => 'Images must be JPG, PNG, or WEBP format.',
            ]
        );

        $rate = Currency::where('code', $validated['currency'])
            ->where('is_active', true)
            ->value('rate') ?? 1;

        // Convert to USD if admin entered MMK
        if ($validated['currency'] === 'MMK') {
            $priceUsd = $validated['price'] / $rate;
        } else {
            $priceUsd = $validated['price'];
        }

        $product = Product::create([
            'name_en' => $validated['name_en'],
            'name_mm' => $validated['name_mm'],
            'description_en' => $validated['description_en'] ?? null,
            'description_mm' => $validated['description_mm'] ?? null,
            'price_usd' => round($priceUsd, 2),
            'stock' => $validated['stock'],
            'eco_badge' => $validated['eco_badge'] ?? null,
            'eco_badge_mm' => $validated['eco_badge_mm'] ?? null,
            'category_id' => $validated['category_id'],
            'created_by' => Auth::id(),
        ]);

        if ($request->hasFile('images')) {
            $primary = $request->primary_image; // example: new_0, new_1

            foreach ($request->file('images') as $index => $image) {

                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image'      => $path,
                    'is_primary' => ($primary === "new_$index"), // selected one
                ]);
            }
        }

        return redirect()
            ->route('product#list')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($id);

        $validated = $request->validate([
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')
                    ->ignore($product->id)
                    ->using(function ($query) use ($request) {
                        $query->whereRaw('LOWER(name_en) = LOWER(?)', [$request->name_en]);
                    }),
            ],
            'name_mm'     => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_mm' => 'nullable|string',
            'currency' => 'required|in:USD,MMK',
            'price' => 'required|numeric|min:0.01',
            'stock'       => 'required|integer|min:0',
            'eco_badge'    => 'nullable|string|max:100',
            'eco_badge_mm' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',

            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'primary_image' => 'nullable|string',
        ],
        [
            'name_en.unique'       => 'A product with this name already exists.',
            ]
        );

        $rate = Currency::where('code', $validated['currency'])
            ->where('is_active', true)
            ->value('rate') ?? 1;

        if ($validated['currency'] === 'MMK') {
            $priceUsd = $validated['price'] / $rate;
        } else {
            $priceUsd = $validated['price'];
        }

        $product->update([
            'name_en' => $validated['name_en'],
            'name_mm' => $validated['name_mm'],
            'description_en' => $validated['description_en'] ?? null,
            'description_mm' => $validated['description_mm'] ?? null,
            'price_usd' => round($priceUsd, 2),
            'stock' => $validated['stock'],
            'eco_badge' => $validated['eco_badge'] ?? null,
            'eco_badge_mm' => $validated['eco_badge_mm'] ?? null,
            'category_id' => $validated['category_id'],
            'updated_by' => Auth::id(),
        ]);

        /**
         * Update primary image
         */
        $primary = $request->primary_image; // can be "5" or "new_0"

        if ($request->hasFile('images')) {

            $existingCount = $product->images()->count();
            $newCount = count($request->file('images'));

            if (($existingCount + $newCount) > 5) {
                return back()
                    ->withErrors(['images' => 'You can upload a maximum of 5 images.'])
                    ->withInput();
            }

            foreach ($request->file('images') as $index => $image) {

                $path = $image->store('products', 'public');

                $img = $product->images()->create([
                    'image'      => $path,
                    'is_primary' => false,
                ]);

                // If selected primary is this new uploaded image
                if ($primary === "new_$index") {
                    $product->images()->update(['is_primary' => false]);
                    $img->update(['is_primary' => true]);
                }
            }
        }

        // If selected primary is an existing image id
        if ($primary && is_numeric($primary)) {

            $product->images()->update(['is_primary' => false]);

            $product->images()
                ->where('id', $primary)
                ->update(['is_primary' => true]);
        }

        return redirect()
            ->route('product#list')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Delete images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $product->delete();

        return redirect()
            ->route('product#list')
            ->with('success', 'Product deleted successfully.');
    }

    public function deleteImage($id)
    {
        $image = \App\Models\ProductImage::with('product')->findOrFail($id);
        $product = $image->product;

        if ($product->images()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'At least one image is required.'
            ], 422);
        }

        Storage::disk('public')->delete($image->image);

        $wasPrimary = $image->is_primary;
        $image->delete();

        // Re-assign primary if needed
        if ($wasPrimary) {
            $next = $product->images()
                ->orderBy('id')
                ->first();

            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

}
