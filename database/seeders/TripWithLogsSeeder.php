<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\TripLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TripWithLogsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::first(); // 👤 Assign first user as owner

            if (!$user) {
                $this->command->error('No users found. Please seed users first.');
                return;
            }

            $baseLat = 28.6139; // New Delhi approx
            $baseLng = 77.2090;

            for ($i = 1; $i <= 5; $i++) {
                $trip = Trip::create([
                    'user_id'           => $user->id,
                    'company_id'        => $user->company_id ?? 1,
                    'trip_date'         => now()->subDays($i)->toDateString(),
                    'start_time'        => '09:00:00',
                    'end_time'          => '11:00:00',
                    'start_lat'         => $baseLat,
                    'start_lng'         => $baseLng,
                    'end_lat'           => $baseLat + 0.2, // ~22km
                    'end_lng'           => $baseLng + 0.2,
                    'total_distance_km' => 22.0,
                    'travel_mode'       => 1,
                    'purpose'           => 1,
                    'tour_type'         => 1,
                    'status'            => 'completed',
                    'approval_status'   => 'approved',
                    'approved_by'       => $user->id,
                    'approved_at'       => now()
                ]);

                // Generate 100 logs (~every 1% of the route)
                $logsCount = 100;
                for ($j = 0; $j <= $logsCount; $j++) {
                    $fraction = $j / $logsCount;

                    TripLog::create([
                        'trip_id'     => $trip->id,
                        'latitude'    => $trip->start_lat + ($trip->end_lat - $trip->start_lat) * $fraction,
                        'longitude'   => $trip->start_lng + ($trip->end_lng - $trip->start_lng) * $fraction,
                        'recorded_at' => now()->subDays($i)->addMinutes($j),
                    ]);
                }

                $this->command->info("✅ Created Trip #$i with $logsCount GPS logs.");
            }
        });
    }
}
