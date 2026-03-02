<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripLog;
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
        $latestLogs = TripLog::select('trip_logs.*')
            ->join('trips', 'trips.id', '=', 'trip_logs.trip_id')
            ->whereIn('trip_logs.id', function ($query) {
                $query->selectRaw('MAX(trip_logs.id)')
                    ->from('trip_logs')
                    ->join('trips', 'trips.id', '=', 'trip_logs.trip_id')
                    ->groupBy('trips.user_id');
            })
            ->with('trip.user:id,name')
            ->get();

        $locations = $latestLogs->map(function ($log) {

            return [
                'name'          => $log->trip->user->name ?? 'Unknown',
                'latitude'      => $log->latitude,
                'longitude'     => $log->longitude,
                'mobile_status' => $log->mobile_status,
            ];
        });

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
