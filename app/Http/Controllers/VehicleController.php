<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Display list of vehicles
    public function index()
    {
        $vehicles = Vehicle::with('assignedUser')->get();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    // Show create form
    public function create()
    {
        $users = User::all(); // For assigned_person dropdown
        return view('admin.vehicles.create', compact('users'));
    }

    // Store new vehicle
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:100|unique:vehicles',
            'vehicle_type' => 'required|in:Petrol,Diesel,CNG,EV,LPG',
            'assigned_person' => 'nullable|exists:users,id',
            'milage' => 'nullable|numeric',
            'assign_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicle.index')->with('success', 'Vehicle added successfully.');
    }

    // Show single vehicle
    public function show(Vehicle $vehicle)
    {
        return view('vehicle.show', compact('vehicle'));
    }

    // Show edit form
    public function edit(Vehicle $vehicle)
    {
        $users = User::all();
        return view('admin.vehicles.edit', compact('vehicle', 'users'));
    }

    // Update vehicle
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:100|unique:vehicles,vehicle_number,' . $vehicle->id,
            'vehicle_type' => 'required|in:Petrol,Diesel,CNG,EV,LPG',
            'assigned_person' => 'nullable|exists:users,id',
            'milage' => 'nullable|numeric',
            'assign_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicle.index')->with('success', 'Vehicle updated successfully.');
    }

    // Delete vehicle
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicle.index')->with('success', 'Vehicle deleted successfully.');
    }

     public function toggleStatus(Request $request)
    {
        $Vehicle = Vehicle::findOrFail($request->id);
        $Vehicle->status = $request->status;
        $Vehicle->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
