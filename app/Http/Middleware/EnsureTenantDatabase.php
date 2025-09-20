<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant;

class EnsureTenantDatabase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get current domain
        $domain = $request->getHost();

        // Central domains (skip tenancy)
        $centralDomains = ['127.0.0.1', 'localhost'];
        if (in_array($domain, $centralDomains)) {
            return $next($request);
        }
        // Find tenant by domain
        $tenant = Tenant::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();
        
        if ($tenant) {
            $databaseName = (string) $tenant->tenancy_db_name;

            if ($databaseName) {
                // Set tenant DB connection dynamically
                Config::set("database.connections.tenant.database", $databaseName);

                // Purge and reconnect to tenant
                DB::purge('tenant');
                DB::reconnect('tenant');

                // ✅ Make tenant the default connection for this request
                Config::set('database.default', 'tenant');

                try {
                    $currentDb = DB::connection()->getDatabaseName(); // now points to tenant
                    \Log::info("✅ Tenant DB connected: {$currentDb} for domain: {$domain}");
                } catch (\Exception $e) {
                    \Log::error("❌ Tenant DB connection failed for {$databaseName}: " . $e->getMessage());

                    return response()->json([
                        'error' => 'Database connection failed',
                        'message' => "Cannot connect to tenant database: {$databaseName}",
                        'domain' => $domain,
                        'tenant_id' => $tenant->id,
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'error' => 'Tenant not found',
                'message' => "No tenant found for domain: {$domain}",
                'domain' => $domain,
            ], 404);
        }

        return $next($request);
    }
}
