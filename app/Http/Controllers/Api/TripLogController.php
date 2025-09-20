<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TripLog;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripLogController extends BaseController
{
    public function logPoint(Request $request)
    {
        $request->validate([
            'trip_id'     => 'required|exists:trips,id',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        $trip = Trip::find($request->trip_id);
        if (!$trip) {
            return $this->sendError('Trip not found.', [], 200);
        }
        $user = Auth::user();
        if ($trip->user_id !== $user->id) {
            return $this->sendError('Trip is not assigned you', [], 403);
        }
        // ❌ Prevent logging if the trip is already completed
        if ($trip->status === 'completed') {
            return $this->sendError('Cannot log point. Trip is already completed.', [], 400);
        }
        $log = TripLog::create([
            'trip_id'     => $request->trip_id,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'recorded_at' => $request->recorded_at ?? now(),
        ]);
        return $this->sendResponse($log, "Trip has been logged");
    }

    public function logs($tripId)
    {
        $trip = Trip::findOrFail($tripId);
        $user = Auth::user();
        if ($trip->user_id !== $user->id) {
            return $this->sendError('Trip is not assigned you', [], 403);
        }
        $logs = $trip->tripLogs()->select('latitude', 'longitude', 'recorded_at')->get();

        return $this->sendResponse($logs, "Trip logs has been fetch");
    }

    public function calculateDistanceFromLogs($tripId)
    {
        $distance = $this->calculateDistanceFromLogsInternal($tripId);
        return response()->json(['status' => 'success', 'distance_km' => $distance]);
    }

    // Reusable internal method for use in other controllers
    public function calculateDistanceFromLogsInternal($tripId)
    {
        $logs = TripLog::where('trip_id', $tripId)->orderBy('recorded_at')->get();

        if ($logs->count() < 2) return 0;

        $distance = 0;
        for ($i = 1; $i < $logs->count(); $i++) {
            $distance += $this->calculateDistance(
                $logs[$i - 1]->latitude,
                $logs[$i - 1]->longitude,
                $logs[$i]->latitude,
                $logs[$i]->longitude
            );
        }

        return round($distance, 2);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $km = $dist * 60 * 1.1515 * 1.609344;

        return round($km, 2);
    }
}
