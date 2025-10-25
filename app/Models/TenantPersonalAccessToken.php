<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

class TenantPersonalAccessToken extends PersonalAccessToken
{
    protected $connection = 'tenant'; // default tenant connection

    public function setTenantDatabase($dbName)
    {
        config(['database.connections.tenant.database' => $dbName]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        $this->setConnection('tenant');
    }
}
