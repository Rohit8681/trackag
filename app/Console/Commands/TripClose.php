<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant;

class TripClose extends Command
{
    protected $signature = 'trip:close';
    protected $description = 'Auto close open trips for all tenant databases';

    public function handle()
    {
        $today = Carbon::today('Asia/Kolkata');
        $currentTime = Carbon::now('Asia/Kolkata');

        Log::info("🚀 Trip Close started at {$currentTime->toDateTimeString()} for all tenants");

        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            Log::warning("⚠️ No tenants found in tenants table.");
            return 0;
        }

        foreach ($tenants as $tenant) {
            $tenantDb = $tenant->tenancy_db_name;

            if (!$tenantDb) {
                Log::warning("⛔ Tenant {$tenant->id} has no database name.");
                continue;
            }

            Log::info("🔹 Switching to tenant DB: {$tenantDb}");

            try {
                // Configure tenant DB connection dynamically
                Config::set('database.connections.tenant', [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '3306'),
                    'database' => $tenantDb,
                    'username' => env('DB_USERNAME', 'root'),
                    'password' => env('DB_PASSWORD', ''),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'strict' => false,
                ]);

                DB::purge('tenant');
                DB::reconnect('tenant');

                // 🔍 Get today's open trips
                $trips = DB::connection('tenant')
                    ->table('trips')
                    ->whereDate('trip_date', $today)
                    ->where(function ($q) {
                        $q->whereNull('end_time')
                            ->orWhere('status', '!=', 'completed');
                    })
                    ->get();

                if ($trips->isEmpty()) {
                    Log::info("✅ No open trips in {$tenantDb}");
                    continue;
                }

                $closedCount = 0;

                foreach ($trips as $trip) {
                    $last_log = DB::connection('tenant')->table('trip_logs')
                        ->where('trip_id', $trip->id)
                        ->orderByDesc('recorded_at')
                        ->first();

                    if (!$last_log) {
                        Log::warning("⚠️ No trip logs found for trip {$trip->id} in {$tenantDb}");
                        continue;
                    }

                    // ✅ Calculate total distance from tenant DB logs
                    $total_distance_km = $this->calculateDistanceFromLogs($trip->id);

                    DB::connection('tenant')->table('trips')
                        ->where('id', $trip->id)
                        ->update([
                            'end_lat' => $last_log->latitude ?? null,
                            'end_lng' => $last_log->longitude ?? null,
                            'end_time' => $currentTime->format('H:i:s'),
                            'total_distance_km' => $total_distance_km,
                            'closenote' => 'Auto closed by system',
                            'status' => 'completed',
                            'updated_at' => now('Asia/Kolkata'),
                        ]);

                    DB::connection('tenant')->table('trip_logs')->insert([
                        'trip_id' => $trip->id,
                        'latitude' => $last_log->latitude ?? null,
                        'longitude' => $last_log->longitude ?? null,
                        'gps_status' => null,
                        'recorded_at' => now('Asia/Kolkata'),
                        'created_at' => now('Asia/Kolkata'),
                        'updated_at' => now('Asia/Kolkata'),
                    ]);

                    

                    Log::info("🟢 Trip auto closed", [
                        'tenant_db' => $tenantDb,
                        'trip_id' => $trip->id,
                        'total_distance_km' => $total_distance_km,
                    ]);

                    $closedCount++;
                }

                Log::info("📦 Total {$closedCount} trips closed in tenant DB: {$tenantDb}");

            } catch (\Exception $e) {
                Log::error("❌ Error processing tenant DB {$tenantDb}", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        Log::info("🎯 Trip Close completed successfully for all tenants at {$currentTime->toTimeString()}");
        $this->info("Trip auto-close completed for all tenants.");

        return 0;
    }

    /**
     * ✅ Calculate distance safely using tenant DB connection
     */
    private function calculateDistanceFromLogs($tripId): float|int
    {
        $logs = DB::connection('tenant')->table('trip_logs')
            ->where('trip_id', $tripId)
            ->orderBy('recorded_at')
            ->get();

        if ($logs->count() < 2) {
            Log::warning('Not enough trip logs to calculate distance', ['trip_id' => $tripId]);
            return 0;
        }

        $distance = 0;
        for ($i = 1; $i < $logs->count(); $i++) {
            $km = $this->calculateDistance(
                $logs[$i - 1]->latitude,
                $logs[$i - 1]->longitude,
                $logs[$i]->latitude,
                $logs[$i]->longitude
            );

            if (is_numeric($km) && !is_nan($km) && !is_infinite($km)) {
                $distance += $km;
            } else {
                Log::warning('Skipped invalid segment in distance calculation', [
                    'trip_id' => $tripId,
                    'index' => $i,
                    'value' => $km
                ]);
            }
        }

        return round($distance, 2);
    }

    /**
     * ✅ Clamp-safe trigonometric distance formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        // 🧩 Clamp between -1 and 1 to prevent NaN from acos()
        if ($dist > 1) $dist = 1;
        if ($dist < -1) $dist = -1;

        $dist  = acos($dist);
        $dist  = rad2deg($dist);
        $km    = $dist * 111.13384;

        return round($km, 2);
    }
}
