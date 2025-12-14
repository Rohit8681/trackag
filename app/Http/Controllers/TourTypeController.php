<?php

namespace App\Http\Controllers;

use App\Models\TourType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TourTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_trip_types')->only(['index','show']);
        $this->middleware('permission:create_trip_types')->only(['create','store']);
        $this->middleware('permission:edit_trip_types')->only(['edit','update']);
        $this->middleware('permission:delete_trip_types')->only(['destroy']);
    }
    public function index()
    {
        $tourtypes = tourtype::with('company')->latest()->get();
        return view('admin.trips.tourtype.index', compact('tourtypes'));
    }

    public function create()
    {
        return view('admin.trips.tourtype.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = $request->only('name');

        tourtype::create($data);

        return redirect()->route('tourtype.index')->with('success', 'Trip Type created successfully.');

    }

    public function show(tourtype $tourtype)
    {
        return view('admin.trips.tourtype.show', compact('tourtype'));
    }

    public function edit(tourtype $tourtype)
    {
        return view('admin.trips.tourtype.edit', compact('tourtype'));
    }

    public function update(Request $request, tourtype $tourtype)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $data = $request->only('name');
        $tourtype->update($data);
        return redirect()->route('tourtype.index')->with('success', 'Trip Type updated successfully.');
    }

    public function destroy(tourtype $tourtype)
    {
        $tourtype->delete();
        return redirect()->route('tourtype.index')->with('success', 'Trip Type deleted.');
    }
}
