<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Tenancy;

class DomainRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $centralDomains = config('tenancy.central_domains', ['127.0.0.1', 'localhost']);

        // Check if the request is from a central domain
        if (in_array($host, $centralDomains)) {
            // Redirect to central admin login if not already on a central route
            if (!$request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
        } else {
            // Assume it's a tenant domain, resolve tenant and redirect to tenant admin login
            $resolver = app(\Stancl\Tenancy\Resolvers\DomainTenantResolver::class);
            $tenant = $resolver->resolve($host);
            if ($tenant) {
                tenancy()->initialize($tenant);
                \DB::purge('tenant');
                \DB::reconnect('tenant');
                if (!$request->is('admin/*')) {
                    return redirect()->route('tenant.admin.login');
                }
            } else {
                // Invalid tenant, redirect to central domain
                return redirect()->to('http://127.0.0.1:8000/admin/login');
            }
        }

        return $next($request);
    }
}