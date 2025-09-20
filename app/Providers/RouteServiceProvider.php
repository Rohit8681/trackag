<?php

namespace App\Providers;

use App\Http\Middleware\EnsureTenantDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapCentralRoutes();

            if (!in_array(request()->getHost(), config('tenancy.central_domains'))) {
                $this->mapTenantRoutes();
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Rate limiting configuration if needed
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapCentralRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/central.php'));
    }

    /**
     * Define the tenant routes for the application.
     *
     * These routes are for tenant-specific domains.
     */
    protected function mapTenantRoutes(): void
    {
        Route::middleware([
            'web',
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
            \App\Http\Middleware\EnsureTenantDatabase::class,
        ])->group(base_path('routes/tenant.php'));

    }
}
