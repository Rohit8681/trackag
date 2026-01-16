<?php

namespace App\Http\Controllers;

use App\Models\CropCategory;
use Illuminate\Http\Request;

class CropCategoryController extends Controller
{
    public function index()
    {
        $categories = CropCategory::latest()->get();
        return view('admin.crop_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.crop_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:crop_categories,name'
        ]);

        CropCategory::create([
            'name' => $request->name,
            'status' => 1
        ]);

        return redirect()->route('crop-categories.index')
            ->with('success','Category created successfully');
    }

    public function edit(CropCategory $cropCategory)
    {
        return view('admin.crop_categories.edit', compact('cropCategory'));
    }

    public function update(Request $request, CropCategory $cropCategory)
    {
        $request->validate([
            'name' => 'required|unique:crop_categories,name,' . $cropCategory->id
        ]);

        $cropCategory->update([
            'name' => $request->name
        ]);

        return redirect()->route('crop-categories.index')
            ->with('success','Category updated successfully');
    }

    public function destroy(CropCategory $cropCategory)
    {
        $cropCategory->delete();

        return redirect()->route('crop-categories.index')
            ->with('success','Category deleted successfully');
    }
}
