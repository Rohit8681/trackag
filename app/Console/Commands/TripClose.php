<?php

namespace App\Console\Commands;

use App\Models\Trip; // Trip model import
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class TripClose extends Command
{
    protected $signature = 'trip:close';
    protected $description = 'Close trips based on certain conditions';

    public function handle()
    {
        $today = Carbon::today('Asia/Kolkata');
        $currentTime = Carbon::now('Asia/Kolkata');

        Log::info("🚀 Trip Close Command started at {$currentTime->toDateTimeString()} for date {$today->format('Y-m-d')}");

        // Get today's trips which are still open
        $todaysTrips = Trip::whereDate('trip_date', $today)
            ->where(function ($q) {
                $q->whereNull('end_time')
                ->orWhere('status', '!=', 'completed');
            })
            ->get();

        if ($todaysTrips->isEmpty()) {
            Log::info("✅ No open trips found for {$today->format('d/m/Y')}");
            $this->info("No open trips found for today.");
            return 0;
        }

        foreach ($todaysTrips as $trip) {
            try {
                $trip->update([
                    'end_time'  => $currentTime->format('H:i:s'),
                    'closenote' => 'Auto closed by system',
                    'status'    => 'completed',
                    'updated_at'=> now('Asia/Kolkata'),
                ]);

                Log::info("🟢 Trip auto closed", [
                    'trip_id' => $trip->id,
                    'user_id' => $trip->user_id,
                    'trip_date' => $trip->trip_date,
                    'end_time' => $trip->end_time,
                ]);

            } catch (\Exception $e) {
                Log::error("❌ Failed to auto close trip ID: {$trip->id}", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        Log::info("🎯 Trip auto-close command completed successfully at {$currentTime->toTimeString()}");
        $this->info("Trip auto-close completed successfully.");

        return 0;
    }
}