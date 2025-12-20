<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant;

trait TenantConnectionTrait
{
    /**
     * Get the database connection for the model.
     */
    public function getConnectionName()
    {
        /**
         * STEP 1:
         * If tenancy already initialized, always use tenant connection
         */
        if (tenancy()->initialized) {
            return 'tenant';
        }

        /**
         * STEP 2:
         * Detect domain
         */
        $domain = request()->getHost();

        /**
         * STEP 3:
         * Central domains (NO tenant here)
         */
        $centralDomains = [
            '127.0.0.1',
            'localhost',
            'trackag.in',
            'www.trackag.in',
        ];

        /**
         * STEP 4:
         * VERY IMPORTANT FIX 🔥
         * If user already logged in → DO NOT auto switch tenant
         */
        if (
            !in_array($domain, $centralDomains) &&
            !auth()->check() // ⭐ MAIN FIX
        ) {
            /**
             * STEP 5:
             * Find tenant by domain
             */
            $tenant = Tenant::whereHas('domains', function ($query) use ($domain) {
                $query->where('domain', $domain);
            })->first();

            if ($tenant) {
                $databaseName = $tenant->getDatabaseName();

                if ($databaseName && $databaseName !== 'default_tenant_db') {
                    /**
                     * STEP 6:
                     * Set tenant DB connection dynamically
                     */
                    Config::set('database.connections.tenant.database', $databaseName);

                    DB::purge('tenant');
                    DB::reconnect('tenant');

                    /**
                     * STEP 7:
                     * Initialize tenancy ONCE (guest only)
                     */
                    tenancy()->initialize($tenant);

                    return 'tenant';
                }
            }
        }

        /**
         * STEP 8:
         * Fallback to default connection
         */
        return $this->connection ?? 'mysql';
    }

    /**
     * Override the connection dynamically
     */
    public function getConnection()
    {
        return DB::connection($this->getConnectionName());
    }
}
