<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class TenantAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        // 1️⃣ Get company code
        $companyCode = $request->header('X-Company-Code');
        if (!$companyCode) {
            return response()->json(['message' => 'Company code missing'], 400);
        }

        // 2️⃣ Get company & tenant
        $company = Company::where('code', $companyCode)->first();
        if (!$company) return response()->json(['message'=>'Invalid company code'], 404);

        $tenant = Tenant::find($company->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'], 404);

        // 3️⃣ Switch tenant DB
        config(['database.connections.tenant.database' => $tenant->tenancy_db_name]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        tenancy()->initialize($tenant); 

        // 4️⃣ Get Bearer token
        $plainToken = $request->bearerToken();
        if (!$plainToken) return response()->json(['message' => 'Bearer token missing'], 401);

        // 5️⃣ Token format: id|plain_token
        $parts = explode('|', $plainToken);
        $tokenId = $parts[0] ?? null;
        $tokenPlain = $parts[1] ?? null;
        if (!$tokenId || !$tokenPlain) return response()->json(['message'=>'Invalid token format'], 401);

        // 6️⃣ Manually check token in tenant DB
        $tokenRecord = DB::connection('tenant')->table('personal_access_tokens')->where('id', $tokenId)->first();
        if (!$tokenRecord || !hash_equals($tokenRecord->token, hash('sha256', $tokenPlain))) {
            return response()->json(['message'=>'Invalid or expired token'], 401);
        }

        // 7️⃣ Get user from tenant DB
        $user = User::on('tenant')->find($tokenRecord->tokenable_id);
        if (!$user) return response()->json(['message'=>'User not found'], 401);

        // 8️⃣ Authenticate manually
        Auth::setUser($user);

        return $next($request);
    }
}
