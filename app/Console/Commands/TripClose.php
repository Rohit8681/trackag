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

        // Fetch all tenants from main DB
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

                // Reconnect to tenant DB
                DB::purge('tenant');
                DB::reconnect('tenant');

                // Get open trips from tenant DB
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

                foreach ($trips as $trip) {
                    DB::connection('tenant')->table('trips')
                        ->where('id', $trip->id)
                        ->update([
                            'end_time' => $currentTime->format('H:i:s'),
                            'closenote' => 'Auto closed by system',
                            'status' => 'completed',
                            'updated_at' => now('Asia/Kolkata'),
                        ]);

                    Log::info("🟢 Trip auto closed", [
                        'tenant_db' => $tenantDb,
                        'trip_id' => $trip->id,
                        'user_id' => $trip->user_id ?? null,
                    ]);
                }

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
}
