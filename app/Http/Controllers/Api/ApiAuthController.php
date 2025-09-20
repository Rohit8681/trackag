<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\UserSession;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends BaseController
{
    /**
     * API Login — revoke old token, create new, update last_seen and log session
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'login_id'   => 'required|string',
            'password'   => 'required',
            'company_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation errors', $validator->errors(), 200);
        }

        $credentials = $request->only('login_id', 'password', 'company_id');

        // Detect if login_id is email or mobile
        if (filter_var($credentials['login_id'], FILTER_VALIDATE_EMAIL)) {
            // Email login
            $user = User::where('email', $credentials['login_id'])->first();
        } else {
            // Mobile login
            $user = User::where('mobile', $credentials['login_id'])->first();
        }

        if (!$user) {
            return $this->sendError('Invalid Email or Password.', null, 200);
        }

        $isMasterAdmin = $user->hasRole('master_admin');

        // Master admin cannot pass company_id
        if ($isMasterAdmin && !empty($credentials['company_id'])) {
            return $this->sendError('Master Admin login should not include Company Code.', null, 200);
        }

        // Validate company if not master_admin
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

        // Validate password
        if (!Hash::check($credentials['password'], $user->password)) {
            return $this->sendError('Invalid Email or Password.', null, 200);
        }

        // Check active
        if ($user->is_active == 0) {
            return $this->sendError('Account inactive. Contact support.', null, 200);
        }

        // Check role presence
        if ($user->roles()->count() === 0) {
            return $this->sendError('No role assigned. Contact admin.', null, 200);
        }

        // ✅ Revoke old tokens
        $user->tokens()->delete();

        // ✅ Create new token
        $token = $user->createToken('mobile-token')->plainTextToken;

        // ✅ Update last_seen
        $user->last_seen = now();
        $user->save();

        // ✅ Log API user login session
        // ✅ Check and close existing open session
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


        // ✅ Log API user login session
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

    /**
     * API Logout — revoke tokens, clear last_seen, and update session log
     */
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
    public function profile(Request $request)
    {
        $user = $request->user();
        $success['user'] =  $user;
        return $this->sendResponse($success, 'User detail fetch successfully');
    }
}
