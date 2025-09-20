<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Illuminate\Support\Facades\DB;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDomains;
    use HasDatabase;
    
    /**
     * The connection name for the model.
     * This model uses the central database connection.
     */
    protected $connection = 'mysql';

    // protected $guarded = ['id'];
    protected $fillable = ['id', 'data','tenancy_db_name'];
    protected $casts = [
        'data' => 'array',
    ];
    

    public function getDatabaseName(): string
    {
        if (empty($this->tenancy_db_name)) {
            return 'default_tenant_db'; 
        }

        return $this->tenancy_db_name;
    }
}