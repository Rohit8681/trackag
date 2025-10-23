<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Tenant;
use App\Models\UserSession;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class ApiAuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id'   => 'required|string',
            'password'   => 'required',
            'company_id' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors(), 200);
        }

        $credentials = $request->only('login_id', 'password', 'company_id');

        if (filter_var($credentials['login_id'], FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $credentials['login_id'])->first();
        } else {
            $user = User::where('mobile', $credentials['login_id'])->first();
        }

        if (!$user) {
            return $this->sendError('Invalid Email or Password.', null, 200);
        }

        $isMasterAdmin = $user->hasRole('master_admin');

        if ($isMasterAdmin && !empty($credentials['company_id'])) {
            return $this->sendError('Master Admin login should not include Company Code.', null, 200);
        }

        if (!$isMasterAdmin) {
            if (empty($credentials['company_id'])) {
                return $this->sendError('Invalid Company Code.', null, 200);
            }
            $company = Company::where('code', $credentials['company_id'])->first();

            if (!$company) {
                return $this->sendError('Invalid Company Code.', null, 200);
            }
            if ($company->status !== 'Active') {
                return $this->sendError('Your company is inactive.', null, 200);
            }
            if ($user->company_id != $company->id) {
                return $this->sendError('User not linked to this company.', null, 200);
            }
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return $this->sendError('Invalid Email or Password.', null, 200);
        }

        if ($user->is_active == 0) {
            return $this->sendError('Account inactive. Contact support.', null, 200);
        }

        if ($user->roles()->count() === 0) {
            return $this->sendError('No role assigned. Contact admin.', null, 200);
        }

        $user->tokens()->delete();

        $token = $user->createToken('mobile-token')->plainTextToken;

        $user->last_seen = now();
        $user->save();
        
        $existingMobileSession = UserSession::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->where('platform', 'mobile')
            ->latest()
            ->first();

        if ($existingMobileSession) {
            $existingMobileSession->update([
                'logout_at'        => now(),
                'session_duration' => $existingMobileSession->login_at->diffInSeconds(now()),
            ]);
        }

        UserSession::create([
            'user_id'    => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'platform'   => 'mobile',
            'login_at'   => now(),
        ]);

        $success['token'] = $token;
        $success['user'] =  $user;
        return $this->sendResponse($success, 'User logged in successfully.');
    }

     public function login_new(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_code' => 'required|string',
            'company_mobile' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors(), 200);
        }

        $companyCode = $request->company_code;
        $mobile = $request->company_mobile;
        $password = $request->password;

        // 1️⃣ Find company in central DB
        $company = Company::where('code', $companyCode)->first();
        
        if (!$company) {
            return $this->sendError('Invalid Company Code.', null, 200);
        }

        // 2️⃣ Get tenant info
        $tenant = Tenant::find($company->tenant_id);
        
        if (!$tenant) {
            return $this->sendError('Tenant not found for this company.', null, 200);
        }

        // 3️⃣ Initialize tenant connection
        tenancy()->initialize($tenant);

        $tenantConnection = config('database.connections.tenant');
        $tenantConnection['database'] = $tenant->tenancy_db_name;
        config(['database.connections.tenant' => $tenantConnection]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        // 4️⃣ Find user in tenant DB
        $user = DB::connection('tenant')->table('users')->where('mobile', $mobile)->first();
       
        if (!$user) {
            return $this->sendError('Invalid Mobile or Password.', null, 200);
        }

        // 5️⃣ Verify password
        if (!Hash::check($password, $user->password)) {
            return $this->sendError('Invalid Mobile or Password.', null, 200);
        }

        // 6️⃣ Check if user is active
        if (!$user->is_active) {
            return $this->sendError('Account inactive. Contact support.', null, 200);
        }

        // 7️⃣ Revoke old tokens and create new one
        $userModel = new \App\Models\User();
        $userModel->setConnection('tenant');
        $user = $userModel->find($user->id);
        $user->tokens()->delete();
        $token = $user->createToken('mobile-token')->plainTextToken;

        // 8️⃣ Update last_seen
        $user->last_seen = now();
        $user->save();

        return $this->sendResponse([
            'token' => $token,
            'user' => $user,
            'company' => $company
        ], 'User logged in successfully.');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->last_seen = null;
            $user->save();

            // ✅ Update last active session record
            $session = UserSession::where('user_id', $user->id)
                ->whereNull('logout_at')
                ->where('platform', 'mobile')
                ->latest()
                ->first();

            if ($session) {
                $session->update([
                    'logout_at'        => now(),
                    'session_duration' => $session->login_at->diffInSeconds(now()),
                ]);
            }

            // ✅ Revoke all tokens
            $user->tokens()->delete();
        }
        return $this->sendResponse(null, 'Logged out successfully.');
    }

    /**
     * Get API Authenticated User Profile
     */
    // public function profile(Request $request)
    // {
    //     $user = $request->user();
    //     $success['user'] =  $user;
    //     return $this->sendResponse($success, 'User detail fetch successfully');
    // }

    public function profile(Request $request)
    {
        try {
            $this->switchToTenantDB($request);

            $userModel = new \App\Models\User();
            if ($request->header('X-Company-Code')) {
                $userModel->setConnection('tenant'); // tenant DB
            }

            $user = $userModel->find($request->user()->id);

            return $this->sendResponse(['user' => $user], 'User detail fetched successfully');
        } catch (\Exception $e) {
            return $this->sendError('Error fetching profile', $e->getMessage(), 500);
        }
    }

    protected function switchToTenantDB(Request $request)
    {
        $companyCode = $request->header('X-Company-Code'); // tenant company code from header

        if (!$companyCode) {
            return null; // central user
        }

        $company = \App\Models\Company::where('code', $companyCode)->firstOrFail();
        $tenant = \App\Models\Tenant::findOrFail($company->tenant_id);

        tenancy()->initialize($tenant);

        $tenantConnection = config('database.connections.tenant');
        $tenantConnection['database'] = $tenant->tenancy_db_name;
        config(['database.connections.tenant' => $tenantConnection]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        return $tenant;
    }
}
