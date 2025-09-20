<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Depo;
use App\Models\State;
use App\Models\District;
use App\Models\Tehsil;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepoController extends Controller
{
    public function index()
    {
        
        $depos = Depo::with(['state','district','tehsil'])->orderBy('id','desc')->paginate(20);
        return view('admin.depos.index', compact('depos'));
    }

    public function create()
    {
        // active states
        $states = State::where('status', 1)->orderBy('name')->get();
        return view('admin.depos.create', compact('states'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'depo_code' => 'required|string|max:191|unique:depos,depo_code',
            'depo_name' => 'required|string|max:191',
            'state_id'  => 'nullable|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',
            'manage_by' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'status' => ['required', Rule::in(['0','1',0,1,true,false])],
        ]);

        Depo::create($data);

        return redirect()->route('depos.index')->with('success','Depo created successfully.');
    }

    public function edit(Depo $depo)
    {
        $states = State::where('status',1)->orderBy('name')->get();

        // For edit, load districts & tehsils relevant to selected values
        $districts = $depo->state_id ? District::where('state_id', $depo->state_id)->where('status',1)->orderBy('name')->get() : collect();
        $tehsils = $depo->district_id ? Tehsil::where('district_id', $depo->district_id)->where('status',1)->orderBy('name')->get() : collect();

        return view('admin.depos.edit', compact('depo','states','districts','tehsils'));
    }

    public function update(Request $request, Depo $depo)
    {
        $data = $request->validate([
            'depo_code' => ['required','string','max:191', Rule::unique('depos','depo_code')->ignore($depo->id)],
            'depo_name' => 'required|string|max:191',
            'state_id'  => 'nullable|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',
            'manage_by' => 'nullable|string|max:191',
            'city' => 'nullable|string|max:191',
            'status' => ['required', Rule::in(['0','1',0,1,true,false])],
        ]);

        $depo->update($data);

        return redirect()->route('depos.index')->with('success','Depo updated successfully.');
    }

    public function destroy(Depo $depo)
    {
        $depo->delete();
        return redirect()->route('depos.index')->with('success','Depo deleted.');
    }

    // AJAX endpoints for dependent dropdowns
    public function getDistricts(Request $request)
    {
        $stateId = $request->get('state_id');
        $districts = $stateId ? District::where('state_id', $stateId)->where('status',1)->orderBy('name')->get() : [];
        return response()->json($districts);
    }

    public function getTehsils(Request $request)
    {
        $districtId = $request->get('district_id');
        $tehsils = $districtId ? Tehsil::where('district_id', $districtId)->where('status',1)->orderBy('name')->get() : [];
        return response()->json($tehsils);
    }
}
