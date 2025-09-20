<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\TripLog;
use Carbon\Carbon;

class TripLogSeeder extends Seeder
{
    public function run()
    {
        // Delete old logs first
        TripLog::truncate();

        // Number of logs per trip
        $numLogs = 100;

        // Get latest 5 trips (or all if you prefer)
        $trips = Trip::orderBy('id')->take(5)->get();

        foreach ($trips as $index => $trip) {
            // Skip if coordinates are missing
            if (!$trip->start_lat || !$trip->start_lng || !$trip->end_lat || !$trip->end_lng) {
                continue;
            }

            $startLat = $trip->start_lat;
            $startLng = $trip->start_lng;
            $endLat = $trip->end_lat;
            $endLng = $trip->end_lng;

            $baseTime = Carbon::parse($trip->trip_date)->setTime(9, 0)->addHours($index); // just spacing logs per trip

            for ($i = 0; $i < $numLogs; $i++) {
                $fraction = $i / ($numLogs - 1); // linear progression

                $lat = $startLat + ($endLat - $startLat) * $fraction;
                $lng = $startLng + ($endLng - $startLng) * $fraction;

                TripLog::create([
                    'trip_id'     => $trip->id,
                    'latitude'    => round($lat, 7),
                    'longitude'   => round($lng, 7),
                    'recorded_at' => $baseTime->copy()->addSeconds($i * 30),
                    'battery_percentage' => rand(40, 100),
                    'gps_status' => true,
                ]);
            }
        }
    }
}
