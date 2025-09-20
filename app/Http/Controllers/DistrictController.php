<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $query = District::with(['country', 'state']);

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $districts = $query->orderByDesc('id')->get();

        $countries = Country::orderBy('name')->get();
        $states    = State::orderBy('name')->get();

        return view('admin.districts.index', compact('districts', 'countries', 'states'));
    }
    
    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $states    = State::orderBy('name')->get();

        return view('admin.districts.create', compact('countries', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id'   => 'required|exists:states,id',
            'name'       => 'required|string|max:150|unique:districts,name',
            'status'     => 'nullable|boolean',
        ]);

        District::create([
            'country_id' => $validated['country_id'],
            'state_id'   => $validated['state_id'],
            'name'       => $validated['name'],
            'status'     => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('districts.index')->with('success', 'District created successfully.');
    }

    public function show(District $district)
    {
        //
    }

    public function edit(District $district)
    {
        $countries = Country::orderBy('name')->get();
        $states    = State::orderBy('name')->get();
        
        return view('admin.districts.create', compact('district', 'countries', 'states'));
    }

    public function update(Request $request, District $district)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id'   => 'required|exists:states,id',
            'name'       => 'required|string|max:150|unique:districts,name,' . $district->id,
            'status'     => 'nullable|boolean',
        ]);

        $district->update([
            'country_id' => $validated['country_id'],
            'state_id'   => $validated['state_id'],
            'name'       => $validated['name'],
            'status'     => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('districts.index')->with('success', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();
        return redirect()->route('districts.index')->with('success', 'District deleted successfully.');
    }

    
    
}
