<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $companyCode = $request->header('X-Company-Code');

        if ($companyCode) {
            $tenant = Company::where('code', $companyCode)->first();

            if (!$tenant) {
                return response()->json(['message' => 'Company code is invalid or not found.'], 404);
            }

            // Initialize tenancy for the request
            tenancy()->initialize($tenant);
        }

        // Now, check for Sanctum authentication
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}