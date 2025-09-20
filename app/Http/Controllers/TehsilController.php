<?php
namespace App\Http\Controllers;

use App\Models\Tehsil;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;

class TehsilController extends Controller
{
   public function index(Request $request)
    {
        $query = Tehsil::with(['country', 'state', 'district']);

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tehsils = $query->orderByDesc('id')->get();

        $countries = Country::orderBy('name')->get();
        $states    = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();

        return view('admin.tehsils.index', compact('tehsils', 'countries', 'states', 'districts'));
    }


    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();

        return view('admin.tehsils.create', compact('countries','states','districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required',
            'state_id' => 'required',
            'district_id' => 'required',
            'name' => 'required|string|max:255',
        ]);

        Tehsil::create($request->all());

        return redirect()->route('tehsils.index')->with('success', 'Tehsil created successfully.');
    }

    public function edit(Tehsil $tehsil)
    {
        $tehsil->load(['country','state','district']);

        $countries = Country::orderBy('name')->get();

        $states = State::orderBy('name')->get();

        $districts = District::orderBy('name')->get();

        return view('admin.tehsils.edit', compact('tehsil','countries','states','districts'));
    }

    public function update(Request $request, Tehsil $tehsil)
    {
        $request->validate([
            'country_id' => 'required',
            'state_id' => 'required',
            'district_id' => 'required',
            'name' => 'required|string|max:255',
        ]);

        $tehsil->update($request->all());
        return redirect()->route('tehsils.index')->with('success', 'Tehsil updated successfully.');
    }

    public function destroy(Tehsil $tehsil)
    {
        $tehsil->delete();
        return redirect()->route('tehsils.index')->with('success', 'Tehsil deleted successfully.');
    }
}
