<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // ===== LIST CATEGORIES =====
    public function categoryList(Request $request)
    {
        $query = Category::query();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query
            ->orderBy('created_at', 'asc')
            ->paginate(5)
            ->withQueryString();

        $totalCategories = Category::count();

        // AJAX Response
        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.category.partials.category_table', compact('categories'))->render(),
                'pagination' => view('admin.category.partials.category_pagination', compact('categories'))->render(),
            ]);
        }

        return view('admin.category.list', compact('categories', 'totalCategories'));
    }


    // ===== CREATE NEW CATEGORY =====
    public function categoryCreate(Request $request)
    {
        $perPage = 5;

        $validator = validator($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name'),
            ],
            'name_mm' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'create')
                ->withInput()
                ->with('open_create', true);
        }

        $totalBefore = Category::count();

        Category::create([
            'name' => $request->name,
            'name_mm' => $request->name_mm,
            'description' => $request->description,
        ]);

        $lastPage = ceil(($totalBefore + 1) / $perPage);

        return redirect()
            ->route('category#list', ['page' => $lastPage])
            ->with('success', 'Category created successfully');
    }



    // ===== UPDATE CATEGORY =====
    public function categoryUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = validator($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'name_mm' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator, 'edit')
                ->withInput([
                    'name' => $request->name,
                    'description' => $request->description,
                ])
                ->with('edit_id', $category->id);
        }

        $category->update([
            'name' => $request->name,
            'name_mm' => $request->name_mm,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('category#list')
            ->with('success', 'Category updated successfully');

    }



    // ===== DELETE CATEGORY =====
    public function categoryDelete(Request $request, $id)
    {
        $perPage = 5;

        // Page BEFORE delete (sent from modal)
        $currentPage = (int) $request->input('page', 1);

        Category::where('id', $id)->delete();

        // Total AFTER delete
        $total = Category::count();
        $lastPage = max(1, ceil($total / $perPage));

        // If page no longer exists → go back
        if ($currentPage > $lastPage) {
            $currentPage = $lastPage;
        }

        return redirect()
            ->route('category#list', ['page' => $currentPage])
            ->with('success', 'Category deleted successfully');
    }

}
