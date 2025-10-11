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

        $todaysTrips = Trip::whereDate('trip_date', $today)->whereNull('end_time')->where('status', '!=', 'completed')->get();
        $count = $todaysTrips->count();

        Log::info("Trip Close Command executed at " . $currentTime . ". Checking trips for date: {$today->format('d/m/Y')}");

        Log::info("Aaj ({$today->format('d/m/Y')}) ni trip_date match karne vali records ni ginti: {$count}");

        if ($count > 0) {
            Log::info("Data found:");
            foreach ($todaysTrips as $trip) {
                // Update fields
                $trip->update([
                    'end_time' => $currentTime->format('H:i:s'), 
                    'closenote' => 'auto close',
                    'status' => 'completed',
                    'updated_at' => Carbon::now(),
                ]);

                $message = "ID: {$trip->id}, Trip Date: {$trip->trip_date}, Status: {$trip->status}, End Time: {$trip->end_time}, Close Note: {$trip->closenote}";
                $this->line($message); 
                Log::info($message);   
            }
        } else {
            $message = 'Aaj (' . $today->format('d/m/Y') . ') koi trip data nahi mila.';
            $this->warn($message); 
            Log::warning($message); 
        }

        Log::info('Trip close process completed.');
        $this->info('Trip close process completed.');
        return 0;
    }
}