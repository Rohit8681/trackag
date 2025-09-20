<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Config::set('tenancy.tenant_model', \App\Models\Tenant::class);
        if (file_exists(base_path('routes/tenant.php'))) {
        Route::middleware('web')
            ->group(base_path('routes/tenant.php'));
    }
    }
}
