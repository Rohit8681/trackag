<?php

namespace App\Http\Controllers;

use App\Models\TravelMode;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TravelModeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_travel_modes')->only(['index','show']);
        $this->middleware('permission:create_travel_modes')->only(['create','store']);
        $this->middleware('permission:edit_travel_modes')->only(['edit','update']);
        $this->middleware('permission:delete_travel_modes')->only(['destroy']);
    }
    public function index()
    {
        $travelModes = TravelMode::with('company')->latest()->get();

        return view('admin.trips.travelmode.index', compact('travelModes'));
    }

    public function create()
    {
        return view('admin.trips.travelmode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->only('name');
        TravelMode::create($data);

        return redirect()->route('travelmode.index')->with('success', 'Travel Mode created successfully.');
    }

    public function show(TravelMode $travelmode)
    {
        return view('admin.trips.travelmode.show', compact('travelmode'));
    }

    public function edit(TravelMode $travelmode)
    {
        return view('admin.trips.travelmode.edit', compact('travelmode'));
    }

    public function update(Request $request, TravelMode $travelmode)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->only('name');
        $travelmode->update($data);

        return redirect()->route('travelmode.index')->with('success', 'Travel Mode updated successfully.');
    }

    public function destroy(TravelMode $travelmode)
    {
        $travelmode->delete();
        return redirect()->route('travelmode.index')->with('success', 'Travel Mode deleted.');
    }
}
