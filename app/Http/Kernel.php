<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class, // This MUST come before StartSession
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $middlewareAliases = [
        // 'auth' => \App\Http\Middleware\Authenticate::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'domain.redirect' => \App\Http\Middleware\DomainRedirectMiddleware::class,
        'ensure.tenant.db' => \App\Http\Middleware\EnsureTenantDatabase::class,
        'prevent.central' => \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
    ];

    // protected $routeMiddleware = [
    //     // Other middleware...
    //     'domain.redirect' => \App\Http\Middleware\DomainRedirectMiddleware::class,
    // ];
}