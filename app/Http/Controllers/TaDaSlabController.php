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
   public function __construct()
    {
        $this->middleware('permission:view_ta_da')->only(['form']);
        // $this->middleware('permission:create_permissions')->only(['create','store']);
        // $this->middleware('permission:edit_permissions')->only(['edit','update']);
        // $this->middleware('permission:delete_permissions')->only(['destroy']);
    }
    public function form()
    {
        $slab = TaDaSlab::whereNull('user_id')->first() ?? new TaDaSlab();
        $travelModes = TravelMode::get();
        $tourTypes = TourType::all();
        $designations = Designation::all();

        // Fetch all slab-wise records grouped by designation
        $slabWiseVehicleSlabs = TaDaVehicleSlab::where('type', 'slab_wise')->get()->groupBy('designation_id');
        $slabWiseTourSlabs = TaDaTourSlab::where('type', 'slab_wise')->get()->groupBy('designation_id');

        $individualVehicleSlabs = TaDaVehicleSlab::where('type', 'individual')->get();
        $individualTourSlabs = TaDaTourSlab::where('type', 'individual')->get();

        return view('admin.ta_da_slab.form', compact(
            'slab', 'travelModes', 'tourTypes', 'designations',
            'individualVehicleSlabs', 'individualTourSlabs',
            'slabWiseVehicleSlabs', 'slabWiseTourSlabs'
        ));
    }

    // public function save(Request $request)
    // {
    //     $data = $request->validate([
    //         'designation_id'=>'nullable',
    //         'max_monthly_travel' => 'nullable|in:yes,no',
    //         'km' => 'nullable|numeric',
    //         'approved_bills_in_da' => 'nullable|array',
    //         'approved_bills_in_da.*' => 'string',
    //         'vehicle_type_id.*' => 'required|exists:vehicle_types,id',
    //         'travelling_allow_per_km.*' => 'nullable|numeric',
    //         'tour_type_id.*' => 'required|exists:tour_types,id',
    //         'da_amount.*' => 'nullable|numeric',
    //         'slab_wise_vehicle_type_id.*' => 'required|exists:vehicle_types,id',
    //         'slab_wise_travelling_allow_per_km.*' => 'nullable|numeric',
    //         'slab_wise_tour_type_id.*' => 'required|exists:tour_types,id',
    //         'slab_wise_da_amount.*' => 'nullable|numeric',
    //     ]);

    //     // Save or update main TA-DA slab
    //     $slab = TaDaSlab::whereNull('user_id')->first() ?? new TaDaSlab();
    //     $slab->designation = $request->designation_id;
    //     $slab->max_monthly_travel = $request->max_monthly_travel;
    //     $slab->km = $request->km;
    //     $slab->approved_bills_in_da = $request->approved_bills_in_da ?? [];
    //     $slab->save();

    //     TaDaVehicleSlab::where('ta_da_slab_id', $slab->id)->whereNull('user_id')->delete();
    //     TaDaTourSlab::where('ta_da_slab_id', $slab->id)->whereNull('user_id')->delete();

    //     // --- Individual Vehicle Slabs ---
    //     foreach ($request->vehicle_type_id as $i => $vtId) {
    //         TaDaVehicleSlab::create([
    //             'ta_da_slab_id' => $slab->id,
    //             'vehicle_type_id' => $vtId,
    //             'travelling_allow_per_km' => $request->travelling_allow_per_km[$i] ?? null,
    //             'type' => 'individual',
    //         ]);
    //     }

    //     // --- Individual Tour Slabs ---
    //     foreach ($request->tour_type_id as $i => $ttId) {
    //         TaDaTourSlab::create([
    //             'ta_da_slab_id' => $slab->id,
    //             'tour_type_id' => $ttId,
    //             'da_amount' => $request->da_amount[$i] ?? null,
    //             'type' => 'individual',
    //         ]);
    //     }

    //     // --- Slab-wise Vehicle Slabs ---
    //     foreach ($request->slab_wise_vehicle_type_id as $i => $vtId) {
    //         TaDaVehicleSlab::create([
    //             'ta_da_slab_id' => $slab->id,
    //             'vehicle_type_id' => $vtId,
    //             'travelling_allow_per_km' => $request->slab_wise_travelling_allow_per_km[$i] ?? null,
    //             'type' => 'slab_wise',
    //         ]);
    //     }

    //     // --- Slab-wise Tour Slabs ---
    //     foreach ($request->slab_wise_tour_type_id as $i => $ttId) {
    //         TaDaTourSlab::create([
    //             'ta_da_slab_id' => $slab->id,
    //             'tour_type_id' => $ttId,
    //             'da_amount' => $request->slab_wise_da_amount[$i] ?? null,
    //             'type' => 'slab_wise',
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'TA-DA Slab saved successfully.');
    // }


    /**
     * Store a newly created resource in storage.
     */
    public function save(Request $request)
    {
        $data = $request->validate([
            'max_monthly_travel' => 'nullable|in:yes,no',
            'km' => 'nullable|numeric',
            'approved_bills_in_da' => 'nullable|array',
            'approved_bills_in_da_slab_wise' => 'nullable|array',
        ]);

        $slab = TaDaSlab::whereNull('user_id')->first() ?? new TaDaSlab();
        $slab->max_monthly_travel = $request->max_monthly_travel;
        $slab->km = $request->km;
        $slab->approved_bills_in_da = $request->approved_bills_in_da ?? [];
        $slab->approved_bills_in_da_slab_wise = $request->approved_bills_in_da_slab_wise ?? [];
        $slab->save();

        // Clear existing slab-wise records
        TaDaVehicleSlab::where('type', 'slab_wise')->whereNull('user_id')->delete();
        TaDaTourSlab::where('type', 'slab_wise')->whereNull('user_id')->delete();
        TaDaVehicleSlab::where('type', 'individual')->whereNull('user_id')->delete();
        TaDaTourSlab::where('type', 'individual')->whereNull('user_id')->delete();
        
        // --- Individual Slabs ---
        foreach ($request->travel_mode_id as $i => $tmId) {
            
            TaDaVehicleSlab::create([
                'ta_da_slab_id' => $slab->id,
                'travel_mode_id' => $tmId,
                'travelling_allow_per_km' => $request->travelling_allow_per_km[$i] ?? null,
                'type' => 'individual',
            ]);
        }
        
        foreach ($request->tour_type_id as $i => $ttId) {
            TaDaTourSlab::create([
                'ta_da_slab_id' => $slab->id,
                'tour_type_id' => $ttId,
                'da_amount' => $request->da_amount[$i] ?? null,
                'type' => 'individual',
            ]);
        }
        
        // --- Slab-wise (Designation-wise) ---
        if ($request->has('designation_ids')) {
            foreach ($request->designation_ids as $designationId) {
                // Travel Modes
                foreach ($request->slab_travel_mode[$designationId] ?? [] as $i => $tmId) {
                    if(!empty($request->slab_travel_amount[$designationId][$i])){
                        TaDaVehicleSlab::create([
                            'ta_da_slab_id' => $slab->id,
                            'travel_mode_id' => $tmId,
                            'travelling_allow_per_km' => $request->slab_travel_amount[$designationId][$i] ?? null,
                            'type' => 'slab_wise',
                            'designation_id' => $designationId,
                        ]);
                    }
                }

                foreach ($request->slab_tour_type[$designationId] ?? [] as $i => $ttId) {
                    if(!empty($request->slab_tour_amount[$designationId][$i])){   // <-- FIXED
                        TaDaTourSlab::create([
                            'ta_da_slab_id' => $slab->id,
                            'tour_type_id' => $ttId,
                            'da_amount' => $request->slab_tour_amount[$designationId][$i] ?? null,
                            'type' => 'slab_wise',
                            'designation_id' => $designationId,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'TA-DA Slab saved successfully.');
    }

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
