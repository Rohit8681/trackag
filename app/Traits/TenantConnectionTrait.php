<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

trait TenantConnectionTrait
{
    /**
     * Get the database connection for the model.
     */
    public function getConnectionName()
    {
        // If we're in a tenant context, use tenant connection
        if (tenancy()->tenant) {
            $tenant = tenancy()->tenant;
            $databaseName = $tenant->getDatabaseName();
            
            if ($databaseName && $databaseName !== 'default_tenant_db') {
                // Ensure the tenant database is set
                Config::set("database.connections.tenant.database", $databaseName);
                
                // Purge and reconnect to ensure the new database is used
                DB::purge('tenant');
                DB::reconnect('tenant');
                
                return 'tenant';
            }
        }
        
        // Check if we're on a tenant domain but tenancy is not initialized
        $domain = request()->getHost();
        $centralDomains = ['127.0.0.1', 'localhost'];
        
        if (!in_array($domain, $centralDomains)) {
            // We're on a tenant domain, try to find and initialize tenant
            $tenant = \App\Models\Tenant::whereHas('domains', function($query) use ($domain) {
                $query->where('domain', $domain);
            })->first();
            
            if ($tenant) {
                $databaseName = $tenant->getDatabaseName();
                if ($databaseName && $databaseName !== 'default_tenant_db') {
                    // Set the database name in the tenant connection
                    Config::set("database.connections.tenant.database", $databaseName);
                    
                    // Purge and reconnect to ensure the new database is used
                    DB::purge('tenant');
                    DB::reconnect('tenant');
                    
                    // Initialize tenancy context
                    tenancy()->initialize($tenant);
                    
                    return 'tenant';
                }
            }
        }
        
        // Fallback to default connection
        return $this->connection ?? 'mysql';
    }
    
    /**
     * Override the connection property dynamically
     */
    public function getConnection()
    {
        return DB::connection($this->getConnectionName());
    }
}
