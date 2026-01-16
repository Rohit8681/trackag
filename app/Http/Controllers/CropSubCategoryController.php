<?php

namespace App\Http\Controllers;

use App\Models\CropCategory;
use App\Models\CropSubCategory;
use Illuminate\Http\Request;

class CropSubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = CropSubCategory::with('category')->latest()->get();
        return view('admin.crop_sub_categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = CropCategory::orderBy('name')->get();
        return view('admin.crop_sub_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'crop_category_id' => 'required',
            'name' => 'required'
        ]);

        CropSubCategory::create([
            'crop_category_id' => $request->crop_category_id,
            'name' => $request->name,
            'status' => 1
        ]);

        return redirect()->route('crop-sub-categories.index')
            ->with('success','Sub Category created successfully');
    }

    public function edit(CropSubCategory $cropSubCategory)
    {
        $categories = CropCategory::orderBy('name')->get();
        return view('admin.crop_sub_categories.edit', compact('cropSubCategory','categories'));
    }

    public function update(Request $request, CropSubCategory $cropSubCategory)
    {
        $request->validate([
            'crop_category_id' => 'required',
            'name' => 'required'
        ]);

        $cropSubCategory->update([
            'crop_category_id' => $request->crop_category_id,
            'name' => $request->name
        ]);

        return redirect()->route('crop-sub-categories.index')
            ->with('success','Sub Category updated successfully');
    }

    public function destroy(CropSubCategory $cropSubCategory)
    {
        $cropSubCategory->delete();

        return redirect()->route('crop-sub-categories.index')
            ->with('success','Sub Category deleted successfully');
    }
}
