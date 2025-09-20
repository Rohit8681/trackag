<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckCompanyAccess
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures:
     * - Master admin can access all data.
     * - Regular users can only access resources linked to their own company.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // ✅ 1. Allow if user is master_admin
        if ($user && $user->user_level === 'master_admin') {
            return $next($request);
        }

        

        // ✅ 2. Detect company ID from route (e.g., /companies/{company})
        $companyFromRoute = $request->route('company');

        // ✅ 3. Extract ID if it's a bound model or scalar
        if (is_object($companyFromRoute) && method_exists($companyFromRoute, 'getKey')) {
            $companyId = $companyFromRoute->getKey(); // safe way to get model ID
        } elseif (is_numeric($companyFromRoute)) {
            $companyId = (int) $companyFromRoute;
        } else {
            $companyId = null;
        }

        // ✅ 4. Deny access if company ID mismatches
        if ($user && $companyId && (int) $user->company_id !== $companyId) {
            abort(403, 'Unauthorized: You do not have access to this company\'s data.');
        }

        // ✅ 5. Allow access
        return $next($request);
    }
}
