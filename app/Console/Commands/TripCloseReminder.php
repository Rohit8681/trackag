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
        $today = Carbon::today('Asia/Kolkata')->toDateString();
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            Log::warning('No tenants found for trip close reminder.');
            return self::SUCCESS;
        }

        foreach ($tenants as $tenant) {
            if (empty($tenant->tenancy_db_name)) {
                continue;
            }

            try {
                $this->useTenantDatabase($tenant->tenancy_db_name);

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

                foreach ($openTrips as $trip) {
                    $firebaseService->sendNotification(
                        $trip->fcm_token,
                        'Punch Out Reminder',
                        'Your day trip is still active. Please punch out once your day trip is finished.',
                        [
                            'type' => 'trip_close_reminder',
                            'trip_id' => (string) $trip->trip_id,
                            'user_id' => (string) $trip->user_id,
                            'trip_date' => (string) $trip->trip_date,
                        ]
                    );
                }

                Log::info('Trip close reminders sent.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'count' => $openTrips->count(),
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send trip close reminders.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'message' => $e->getMessage(),
                ]);
            }
        }

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
