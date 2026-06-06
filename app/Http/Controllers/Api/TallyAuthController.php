<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tally\TallyLoginRequest;
use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class TallyAuthController extends Controller
{
    public function login(TallyLoginRequest $request): JsonResponse
    {
        try {
            $company = Company::where('code', $request->company_code)->first();

            if (! $company) {
                return $this->failedLoginResponse('Invalid company code');
            }

            if (! $company->is_active || $company->status !== 'Active') {
                return $this->failedLoginResponse('Company account is inactive');
            }

            if ($company->validity_upto && now()->greaterThan($company->validity_upto)) {
                return $this->failedLoginResponse('Company subscription has expired');
            }

            $tenant = Tenant::find($company->tenant_id);

            if (! $tenant) {
                return $this->failedLoginResponse('Tenant not found for this company');
            }

            $this->initializeTenant($tenant);

            $user = User::on('tenant')
                ->where(function ($query) use ($request) {
                    $query->where('email', $request->login_id)
                        ->orWhere('mobile', $request->login_id);
                })
                ->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return $this->failedLoginResponse('Invalid login id or password');
            }

            if (! $user->is_active) {
                return $this->failedLoginResponse('User account is inactive');
            }

            $user->tokens()->where('name', 'tally-token')->delete();

            $token = $user->createToken('tally-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'company_code' => $company->code,
            ]);
        } catch (Throwable $exception) {
            Log::error('Tally login failed.', [
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    private function initializeTenant(Tenant $tenant): void
    {
        tenancy()->initialize($tenant);

        config(['database.connections.tenant.database' => $tenant->tenancy_db_name]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    private function failedLoginResponse(string $message): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 401);
    }
}
