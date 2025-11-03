<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\TaDaSlab;
use App\Models\TaDaTourSlab;
use App\Models\TaDaVehicleSlab;
use App\Models\TourType;
use App\Models\TravelMode;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class TaDaSlabController extends Controller
{
    public function form()
    {
        // Fetch or create single main slab record
        $slab = TaDaSlab::first() ?? new TaDaSlab();

        // Load related slabs by type
        $individualVehicleSlabs = TaDaVehicleSlab::where('type', 'individual')->get();
        $slabWiseVehicleSlabs   = TaDaVehicleSlab::where('type', 'slab_wise')->get();

        $individualTourSlabs = TaDaTourSlab::where('type', 'individual')->get();
        $slabWiseTourSlabs   = TaDaTourSlab::where('type', 'slab_wise')->get();

        // $vehicleTypes = VehicleType::where('is_deleted', 0)->get();
        $vehicleTypes = TravelMode::get();
        $tourTypes = TourType::all();
        $designations = Designation::all();
        return view('admin.ta_da_slab.form', compact(
            'slab', 'vehicleTypes', 'tourTypes', 'designations',
            'individualVehicleSlabs', 'slabWiseVehicleSlabs',
            'individualTourSlabs', 'slabWiseTourSlabs'
        ));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'designation_id'=>'nullable',
            'max_monthly_travel' => 'nullable|in:yes,no',
            'km' => 'nullable|numeric',
            'approved_bills_in_da' => 'nullable|array',
            'approved_bills_in_da.*' => 'string',
            'vehicle_type_id.*' => 'required|exists:vehicle_types,id',
            'travelling_allow_per_km.*' => 'nullable|numeric',
            'tour_type_id.*' => 'required|exists:tour_types,id',
            'da_amount.*' => 'nullable|numeric',
            'slab_wise_vehicle_type_id.*' => 'required|exists:vehicle_types,id',
            'slab_wise_travelling_allow_per_km.*' => 'nullable|numeric',
            'slab_wise_tour_type_id.*' => 'required|exists:tour_types,id',
            'slab_wise_da_amount.*' => 'nullable|numeric',
        ]);

        // Save or update main TA-DA slab
        $slab = TaDaSlab::whereNull('user_id')->first() ?? new TaDaSlab();
        $slab->designation = $request->designation_id;
        $slab->max_monthly_travel = $request->max_monthly_travel;
        $slab->km = $request->km;
        $slab->approved_bills_in_da = $request->approved_bills_in_da ?? [];
        $slab->save();

        TaDaVehicleSlab::where('ta_da_slab_id', $slab->id)->whereNull('user_id')->delete();
        TaDaTourSlab::where('ta_da_slab_id', $slab->id)->whereNull('user_id')->delete();

        // --- Individual Vehicle Slabs ---
        foreach ($request->vehicle_type_id as $i => $vtId) {
            TaDaVehicleSlab::create([
                'ta_da_slab_id' => $slab->id,
                'vehicle_type_id' => $vtId,
                'travelling_allow_per_km' => $request->travelling_allow_per_km[$i] ?? null,
                'type' => 'individual',
            ]);
        }

        // --- Individual Tour Slabs ---
        foreach ($request->tour_type_id as $i => $ttId) {
            TaDaTourSlab::create([
                'ta_da_slab_id' => $slab->id,
                'tour_type_id' => $ttId,
                'da_amount' => $request->da_amount[$i] ?? null,
                'type' => 'individual',
            ]);
        }

        // --- Slab-wise Vehicle Slabs ---
        foreach ($request->slab_wise_vehicle_type_id as $i => $vtId) {
            TaDaVehicleSlab::create([
                'ta_da_slab_id' => $slab->id,
                'vehicle_type_id' => $vtId,
                'travelling_allow_per_km' => $request->slab_wise_travelling_allow_per_km[$i] ?? null,
                'type' => 'slab_wise',
            ]);
        }

        // --- Slab-wise Tour Slabs ---
        foreach ($request->slab_wise_tour_type_id as $i => $ttId) {
            TaDaTourSlab::create([
                'ta_da_slab_id' => $slab->id,
                'tour_type_id' => $ttId,
                'da_amount' => $request->slab_wise_da_amount[$i] ?? null,
                'type' => 'slab_wise',
            ]);
        }

        return redirect()->back()->with('success', 'TA-DA Slab saved successfully.');
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
