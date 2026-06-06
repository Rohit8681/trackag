<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureTallyAccessToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $companyCode = $request->header('X-Company-Code');

        if (blank($companyCode)) {
            return $this->unauthorized('Company code missing');
        }

        $company = Company::where('code', $companyCode)->first();

        if (! $company) {
            return $this->unauthorized('Invalid company code');
        }

        if (! $company->is_active || $company->status !== 'Active') {
            return $this->unauthorized('Company account is inactive');
        }

        if ($company->validity_upto && now()->greaterThan($company->validity_upto)) {
            return $this->unauthorized('Company subscription has expired');
        }

        $tenant = Tenant::find($company->tenant_id);

        if (! $tenant) {
            return $this->unauthorized('Tenant not found for this company');
        }

        $this->initializeTenant($tenant);

        $plainToken = $request->bearerToken();

        if (blank($plainToken)) {
            return $this->unauthorized('Bearer token missing');
        }

        [$tokenId, $tokenPlain] = array_pad(explode('|', $plainToken, 2), 2, null);

        if (! $tokenId || ! $tokenPlain) {
            return $this->unauthorized('Invalid token format');
        }

        $tokenRecord = DB::connection('tenant')
            ->table('personal_access_tokens')
            ->where('id', $tokenId)
            ->where('name', 'tally-token')
            ->first();

        if (! $tokenRecord || ! hash_equals($tokenRecord->token, hash('sha256', $tokenPlain))) {
            return $this->unauthorized('Invalid or expired token');
        }

        $user = User::on('tenant')->find($tokenRecord->tokenable_id);

        if (! $user || ! $user->is_active) {
            return $this->unauthorized('User not found or inactive');
        }

        Auth::setUser($user);

        DB::connection('tenant')
            ->table('personal_access_tokens')
            ->where('id', $tokenRecord->id)
            ->update(['last_used_at' => now()]);

        return $next($request);
    }

    private function initializeTenant(Tenant $tenant): void
    {
        tenancy()->initialize($tenant);

        config(['database.connections.tenant.database' => $tenant->tenancy_db_name]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    private function unauthorized(string $message): Response
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], Response::HTTP_UNAUTHORIZED);
    }
}
