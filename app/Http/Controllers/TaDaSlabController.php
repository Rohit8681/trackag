<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\TaDaSlab;
use App\Models\TourType;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class TaDaSlabController extends Controller
{
    public function form()
    {
        $slab = TaDaSlab::with(['vehicleSlabs', 'tourSlabs'])->first();
        $vehicleTypes = VehicleType::where('is_deleted', 0)->get();
        $tourTypes = TourType::all();
        $designations = Designation::all();
        return view('admin.ta_da_slab.form', compact('slab','vehicleTypes','tourTypes','designations'));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'designation'=>'nullable|string',
            'max_monthly_travel' => 'nullable|in:yes,no',
            'km' => 'nullable|numeric',
            'approved_bills_in_da' => 'nullable|array',
            'approved_bills_in_da.*' => 'string',
            'vehicle_type_id.*' => 'required|exists:vehicle_types,id',
            'travelling_allow_per_km.*' => 'nullable|numeric',
            'tour_type_id.*' => 'required|exists:tour_types,id',
            'da_amount.*' => 'nullable|numeric',
        ]);
        dd($request->all());
        // Determine type automatically
        $type = $request->designation ? 'slab_wise' : 'individual';

        // Create or fetch first slab with same type + designation
        $slab = TaDaSlab::firstOrNew(['type'=>$type,'designation'=>$request->designation ?? null]);
        $slab->max_monthly_travel = $request->max_monthly_travel;
        $slab->km = $request->km;
        $slab->approved_bills_in_da = $request->approved_bills_in_da ?? [];
        $slab->save();

        // Delete old children
        $slab->vehicleSlabs()->delete();
        $slab->tourSlabs()->delete();

        // Save vehicle slabs
        foreach ($request->vehicle_type_id as $i => $vtId) {
            $slab->vehicleSlabs()->create([
                'vehicle_type_id' => $vtId,
                'travelling_allow_per_km' => $request->travelling_allow_per_km[$i] ?? null,
            ]);
        }

        // Save tour slabs
        foreach ($request->tour_type_id as $i => $ttId) {
            $slab->tourSlabs()->create([
                'tour_type_id' => $ttId,
                'da_amount' => $request->da_amount[$i] ?? null,
            ]);
        }

        return redirect()->back()->with('success','TA-DA Slab saved successfully.');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
