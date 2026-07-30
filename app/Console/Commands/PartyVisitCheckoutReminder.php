<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\FirebaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartyVisitCheckoutReminder extends Command
{
    protected $signature = 'party-visit:checkout-reminder';
    protected $description = 'Send hourly checkout reminder notifications for open party visits across tenant databases';

    public function handle(FirebaseService $firebaseService)
    {
        $now = Carbon::now('Asia/Kolkata');
        $today = $now->toDateString();
        $tenants = Tenant::all();
        $totalOpenVisits = 0;
        $totalSent = 0;
        $totalFailed = 0;

        Log::info('Party visit checkout reminder command started.', [
            'now' => $now->toDateTimeString(),
            'today' => $today,
            'tenant_count' => $tenants->count(),
        ]);

        if ($tenants->isEmpty()) {
            Log::warning('No tenants found for party visit checkout reminder.');
            return self::SUCCESS;
        }

        foreach ($tenants as $tenant) {
            if (empty($tenant->tenancy_db_name)) {
                Log::warning('Party visit checkout reminder tenant skipped because database name is empty.', [
                    'tenant_id' => $tenant->id ?? null,
                ]);
                continue;
            }

            try {
                $this->useTenantDatabase($tenant->tenancy_db_name);

                Log::info('Party visit checkout reminder tenant processing started.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'tenant_id' => $tenant->id ?? null,
                ]);

                $openVisitRows = DB::connection('tenant')
                    ->table('party_visits')
                    ->whereDate('visited_date', $today)
                    ->whereNotNull('check_in_time')
                    ->whereNull('check_out_time')
                    ->count();

                $openVisits = DB::connection('tenant')
                    ->table('party_visits')
                    ->join('users', 'users.id', '=', 'party_visits.user_id')
                    ->leftJoin('customers', 'customers.id', '=', 'party_visits.customer_id')
                    ->whereDate('party_visits.visited_date', $today)
                    ->whereNotNull('party_visits.check_in_time')
                    ->whereNull('party_visits.check_out_time')
                    ->whereNotNull('users.fcm_token')
                    ->where('users.fcm_token', '!=', '')
                    ->select(
                        'party_visits.id as party_visit_id',
                        'party_visits.user_id',
                        'party_visits.customer_id',
                        'party_visits.visited_date',
                        'party_visits.check_in_time',
                        'users.fcm_token',
                        'customers.agro_name'
                    )
                    ->orderByDesc('party_visits.id')
                    ->get()
                    ->unique('user_id');

                $totalOpenVisits += $openVisits->count();

                Log::info('Party visit checkout reminder open visits checked.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'open_visit_rows' => $openVisitRows,
                    'eligible_users_with_fcm_token' => $openVisits->count(),
                ]);

                foreach ($openVisits as $visit) {
                    $partyName = $visit->agro_name ?? 'your party visit';

                    $sent = $firebaseService->sendNotification(
                        $visit->fcm_token,
                        'Party Visit Checkout Reminder',
                        "Your visit for {$partyName} is still active. Please check out once the visit is finished.",
                        [
                            'type' => 'party_visit_checkout_reminder',
                            'party_visit_id' => (string) $visit->party_visit_id,
                            'customer_id' => (string) ($visit->customer_id ?? ''),
                            'user_id' => (string) $visit->user_id,
                            'visited_date' => (string) $visit->visited_date,
                            'check_in_time' => (string) $visit->check_in_time,
                        ],
                        $visit->user_id
                    );

                    if ($sent) {
                        $totalSent++;
                    } else {
                        $totalFailed++;
                    }

                    $logLevel = $sent ? 'info' : 'warning';

                    Log::$logLevel('Party visit checkout reminder notification processed.', [
                        'tenant_db' => $tenant->tenancy_db_name,
                        'party_visit_id' => $visit->party_visit_id,
                        'user_id' => $visit->user_id,
                        'sent' => $sent,
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Failed to send party visit checkout reminders.', [
                    'tenant_db' => $tenant->tenancy_db_name,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }

        Log::info('Party visit checkout reminder command finished.', [
            'now' => Carbon::now('Asia/Kolkata')->toDateTimeString(),
            'tenant_count' => $tenants->count(),
            'total_eligible_users' => $totalOpenVisits,
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
