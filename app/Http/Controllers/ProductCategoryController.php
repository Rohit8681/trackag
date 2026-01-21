<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::latest()->paginate(10);
        return view('admin.product_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.product_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ProductCategory::create([
            'name'        => $request->name,
            'status'      => $request->status ?? 1,
        ]);

        return redirect()->route('product-categories.index')
                         ->with('success', 'Product Category created successfully');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product_categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $productCategory->update([
            'name'        => $request->name,
            'status'      => $request->status ?? 1,
        ]);

        return redirect()->route('product-categories.index')
                         ->with('success', 'Product Category updated successfully');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return redirect()->route('product-categories.index')
                         ->with('success', 'Product Category deleted successfully');
    }
}
