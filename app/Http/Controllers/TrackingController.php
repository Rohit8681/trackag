<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tracking.index');
    }

    public function liveData()
    {
        $trips = Trip::with([
                'user:id,name',
                'tripLogs' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->whereHas('tripLogs')
            ->get();

        $locations = $trips->map(function ($trip) {

            $latestLog = $trip->tripLogs->first();

            if (!$latestLog) return null;

            return [
                'name'          => $trip->user->name ?? 'Unknown',
                'latitude'      => $latestLog->latitude,
                'longitude'     => $latestLog->longitude,
                'mobile_status' => $latestLog->mobile_status,
            ];
        })->filter()->values();

        return response()->json($locations);
    }
    public function create()
    {
        //
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
