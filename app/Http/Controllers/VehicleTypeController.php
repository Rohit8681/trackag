<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicleTypes = VehicleType::where('is_deleted', false)->latest()->paginate(10);
        return view('admin.vehicle_types.index', compact('vehicleTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vehicle_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type' => 'required|string|max:255|unique:vehicle_types,vehicle_type',
        ]);

        VehicleType::create([
            'vehicle_type' => $request->vehicle_type,
        ]);

        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleType $vehicleType)
    {
        return view('admin.vehicle_types.show', compact('vehicleType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleType $vehicleType)
    {
        return view('admin.vehicle_types.edit', compact('vehicleType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleType $vehicleType)
    {
        $request->validate([
            'vehicle_type' => 'required|string|max:255|unique:vehicle_types,vehicle_type,' . $vehicleType->id,
        ]);

        $vehicleType->update([
            'vehicle_type' => $request->vehicle_type,
        ]);

        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(VehicleType $vehicleType)
    {
        $vehicleType->update(['is_deleted' => true]);
        return redirect()->route('vehicle-types.index')->with('success', 'Vehicle Type deleted successfully.');
    }
}
