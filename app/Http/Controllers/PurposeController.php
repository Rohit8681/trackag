<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PurposeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_trip_purposes')->only(['index','show']);
        $this->middleware('permission:create_trip_purposes')->only(['create','store']);
        $this->middleware('permission:edit_trip_purposes')->only(['edit','update']);
        $this->middleware('permission:delete_trip_purposes')->only(['destroy']);
    }
    public function index()
    {
        $purposes = purpose::with('company')->latest()->get();

        return view('admin.trips.purpose.index', compact('purposes'));
    }

    public function create()
    {
        return view('admin.trips.purpose.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->only('name');
        purpose::create($data);

        return redirect()->route('purpose.index')->with('success', 'Trip Purpose created successfully.');
    }

    public function show(purpose $purpose)
    {
        return view('admin.trips.purpose.show', compact('purpose'));
    }

    public function edit(purpose $purpose)
    {
        return view('admin.trips.purpose.edit', compact('purpose'));
    }

    public function update(Request $request, purpose $purpose)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->only('name');
        $purpose->update($data);

        return redirect()->route('purpose.index')->with('success', 'Trip Purpose updated successfully.');
    }

    public function destroy(purpose $purpose)
    {
        $purpose->delete();
        return redirect()->route('purpose.index')->with('success', 'Purpose deleted successfully.');
    }
}
