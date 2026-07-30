<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\FirebaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TripCloseReminder extends Command
{
    protected $signature = 'trip:close-reminder';
    protected $description = 'Send 9 PM punch out reminder notifications for open trips across tenant databases';

    public function handle(FirebaseService $firebaseService)
    {
        $now = Carbon::now('Asia/Kolkata');
        $today = $now->toDateString();
        $tenants = Tenant::all();
        $totalOpenTrips = 0;
        $totalSent = 0;
        $totalFailed = 0;

        Log::info('Trip close reminder command started.', [
            'now' => $now->toDateTimeString(),
            'today' => $today,
            'tenant_count' => $tenants->count(),
        ]);

        if ($tenants->isEmpty()) {
            Log::warning('No tenants found for trip close reminder.');
            return self::SUCCESS;
        }

        foreach ($tenants as $tenant) {
            if (empty($tenant->tenancy_db_name)) {
                Log::warning('Trip close reminder tenant skipped because database name is empty.', [
                    'tenant_id' => $tenant->id ?? null,
                ]);
                continue;
            }

            try {
                $this->useTenantDatabase($tenant->tenancy_db_name);

                Log::info('Trip close reminder tenant processing started.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'tenant_id' => $tenant->id ?? null,
                ]);

                $openTripRows = DB::connection('tenant')
                    ->table('trips')
                    ->whereDate('trip_date', $today)
                    ->where(function ($query) {
                        $query->whereNull('end_time')
                            ->orWhere('status', '!=', 'completed');
                    })
                    ->count();

                $openTrips = DB::connection('tenant')
                    ->table('trips')
                    ->join('users', 'users.id', '=', 'trips.user_id')
                    ->whereDate('trips.trip_date', $today)
                    ->where(function ($query) {
                        $query->whereNull('trips.end_time')
                            ->orWhere('trips.status', '!=', 'completed');
                    })
                    ->whereNotNull('users.fcm_token')
                    ->where('users.fcm_token', '!=', '')
                    ->select(
                        'trips.id as trip_id',
                        'trips.user_id',
                        'trips.trip_date',
                        'users.fcm_token',
                        'users.name as user_name'
                    )
                    ->orderByDesc('trips.id')
                    ->get()
                    ->unique('user_id');

                $totalOpenTrips += $openTrips->count();

                Log::info('Trip close reminder open trips checked.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'open_trip_rows' => $openTripRows,
                    'eligible_users_with_fcm_token' => $openTrips->count(),
                ]);

                foreach ($openTrips as $trip) {
                    $sent = $firebaseService->sendNotification(
                        $trip->fcm_token,
                        'Punch Out Reminder',
                        'Your day trip is still active. Please punch out once your day trip is finished.',
                        [
                            'type' => 'trip_close_reminder',
                            'trip_id' => (string) $trip->trip_id,
                            'user_id' => (string) $trip->user_id,
                            'trip_date' => (string) $trip->trip_date,
                        ],
                        $trip->user_id
                    );

                    if ($sent) {
                        $totalSent++;
                    } else {
                        $totalFailed++;
                    }

                    $logLevel = $sent ? 'info' : 'warning';

                    Log::$logLevel('Trip close reminder notification processed.', [
                        'tenant_db' => $tenant->tenancy_db_name,
                        'trip_id' => $trip->trip_id,
                        'user_id' => $trip->user_id,
                        'sent' => $sent,
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Failed to send trip close reminders.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }

        Log::info('Trip close reminder command finished.', [
            'now' => Carbon::now('Asia/Kolkata')->toDateTimeString(),
            'tenant_count' => $tenants->count(),
            'total_eligible_users' => $totalOpenTrips,
            'total_sent' => $totalSent,
            'total_failed' => $totalFailed,
        ]);

        return self::SUCCESS;
    }

    private function useTenantDatabase(string $database): void
    {
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $database,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }
}
