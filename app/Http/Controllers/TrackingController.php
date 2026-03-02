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
        $today = now()->toDateString(); // YYYY-MM-DD

        $latestLogs = TripLog::select('trip_logs.*')
            ->join('trips', 'trips.id', '=', 'trip_logs.trip_id')
            ->whereDate('trip_logs.created_at', $today) // 👈 Only today
            ->whereIn('trip_logs.id', function ($query) use ($today) {
                $query->selectRaw('MAX(trip_logs.id)')
                    ->from('trip_logs')
                    ->join('trips', 'trips.id', '=', 'trip_logs.trip_id')
                    ->whereDate('trip_logs.created_at', $today) // 👈 Only today
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
