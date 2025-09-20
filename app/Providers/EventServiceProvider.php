<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Stancl\Tenancy\Events\TenancyInitialized;
use App\Listeners\SwitchTenantDatabase;

class EventServiceProvider extends ServiceProvider
{
    // protected $listen = [
    //     TenancyInitialized::class => [
    //         SwitchTenantDatabase::class,
    //     ],
    // ];

    public function boot(): void
    {
        //
    }
}
