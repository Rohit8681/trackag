<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Admin\AdminService;
use App\Models\UserSession;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Session;

class AdminController extends Controller
{
    protected $adminService;

    // ✅ Inject AdminService using Constructor
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index()
    {
        $defaultDb = Config::get('database.default');
        $defaultDbName = DB::connection()->getDatabaseName();

        $tenantDb = null;
        if (tenancy()->initialized) {
            $tenantDb = DB::connection('tenant')->getDatabaseName();
        }
        
        // dd('test web admin');
        $user = Auth::user();
        $onlineTimeout = now()->subMinutes(10);

        $isMasterAdmin = $user->hasRole('master_admin');
        if ($isMasterAdmin) {
            $totalUsers       = User::count();
            $totalRoles       = \Spatie\Permission\Models\Role::count();
            $totalPermissions = \Spatie\Permission\Models\Permission::count();
            $totalCustomers   = Customer::count();

            $onlineUsers = User::whereHas('sessions', function ($query) {
                $query->whereNull('logout_at')->whereIn('platform', ['web', 'mobile']);
            })
            ->with(['roles', 'permissions'])
            ->get();

            $sessionsQuery = UserSession::with('user')->whereDate('login_at', now());
        } else {
            $companyId        = $user->company_id;
            $totalUsers       = User::count();
            $totalRoles       = \Spatie\Permission\Models\Role::count();
            $totalPermissions = \Spatie\Permission\Models\Permission::count();
            $totalCustomers   = Customer::count();

            $onlineUsers = User::whereHas('sessions', function ($query) {
                $query->whereNull('logout_at')->whereIn('platform', ['web', 'mobile']);
            })
            ->with(['roles', 'permissions'])
            ->get();

            $sessionsQuery = UserSession::with('user')
            ->whereDate('login_at', now());
        }

        $sessionsGrouped = $sessionsQuery->get()->groupBy('user_id');
    

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRoles',
            'totalPermissions',
            'totalCustomers',
            'onlineUsers',
            'sessionsGrouped',
            'isMasterAdmin'
        ));
    }


    public function create()
    {
        $defaultDb = Config::get('database.default');
        $defaultDbName = DB::connection()->getDatabaseName();
        $company = Company::first();        
        
       
        return view('admin.login',compact('company'));
    }

    public function store(LoginRequest $request)
    {
        $credentials = $request->only('mobile', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->is_active == 0) {
                Auth::logout();
                return redirect()->back()->with('error_message', 'Your account is inactive. Please contact support.');
            }

            // if ($user->roles()->count() === 0) {
            //     Auth::logout();
            //     return redirect()->back()->with('error_message', 'You do not have any assigned role. Please contact the administrator.');
            // }

            if (!empty($request->remember)) {
                setcookie("mobile", $credentials["mobile"], time() + 3600);
                setcookie("password", $credentials["password"], time() + 3600);
            } else {
                setcookie("mobile", "", time() - 3600);
                setcookie("password", "", time() - 3600);
            }

            $request->session()->regenerate();

            // Log session logic...
            $existingSession = \App\Models\UserSession::where('user_id', $user->id)
                ->whereNull('logout_at')
                ->where('platform', 'web')
                ->latest()
                ->first();

            if ($existingSession) {
                $existingSession->update([
                    'logout_at'        => now(),
                    'session_duration' => $existingSession->login_at->diffInSeconds(now()),
                ]);
            }

            \App\Models\UserSession::create([
                'user_id'    => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'platform'   => 'web',
                'login_at'   => now(),
            ]);

            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->with('error_message', 'Invalid Email or Password.');
        }
    }


    public function edit(Admin $admin)
    {
        //
    }

    public function update(Request $request, Admin $admin)
    {
        //
    }

    public function destroy()
    {
        $user = Auth::user();

        if ($user) {
            $user->last_seen = null;
            $user->save();

            $session = UserSession::where('user_id', $user->id)
                ->whereNull('logout_at')
                ->where('platform', 'web')
                ->latest()
                ->first();

            if ($session) {
                $session->update([
                    'logout_at'        => now(),
                    'session_duration' => $session->login_at->diffInSeconds(now()),
                ]);
            }
        }

        Auth::logout();
        return redirect()->route('admin.login');
    }

    /**
     * ✅ Fetch user session logs and total logged-in time for today (for dashboard modal popup)
     */
    public function getUserSessionHistory(Request $request, $userId)
    {
        $loggedInUser = Auth::user();
        $targetUser   = User::find($userId);

        if (!$targetUser) {
            return '<p class="text-danger">User not found.</p>';
        }

        $isMasterAdmin = $loggedInUser->hasRole('master_admin');

        // ✅ Restrict company admins from viewing other companies' user sessions
        if (!$isMasterAdmin && $loggedInUser->company_id !== $targetUser->company_id) {
            return '<p class="text-danger">Unauthorized access. You can only view session logs of your own company\'s users.</p>';
        }

        // ✅ Fetch sessions for this user
        $sessions = UserSession::where('user_id', $userId)
        ->whereDate('login_at', now()->toDateString())
        ->orderByDesc('login_at')
        ->get();


        if ($sessions->isEmpty()) {
            return '<p class="text-muted">No session records found.</p>';
        }

        // ✅ Calculate today's total session duration for this user
        $todayTotalSeconds = UserSession::where('user_id', $userId)
            ->whereNotNull('session_duration')
            ->whereDate('login_at', now()->toDateString())
            ->sum('session_duration');

        $html = '<p><strong>Total Active Time Today:</strong> ' . gmdate('H:i:s', $todayTotalSeconds) . '</p>';

        // ✅ Add new Platform column in header
        $html .= '<table class="table table-bordered table-striped">';
        $html .= '<thead><tr>
                    <th>Platform</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Duration</th>
                </tr></thead><tbody>';

        foreach ($sessions as $session) {
            $platform = ucfirst($session->platform ?? 'N/A');
            $login    = $session->formatted_login_at;
            $logout   = $session->formatted_logout_at;
            $duration = $session->formatted_duration;

            // ✅ Add platform in each row
            $html .= "<tr>
                        <td>{$platform}</td>
                        <td>{$login}</td>
                        <td>{$logout}</td>
                        <td>{$duration}</td>
                    </tr>";
        }

        $html .= '</tbody></table>';

        return $html;
    }

    // public function changePassword(Request $request){
        
        
    //     $user = Auth::user();
    //     $getCompany = Company::first();

    //     $request->validate([
    //         'current_password' => ['required'],
    //         'new_password' => ['required', 'string', 'confirmed'],
    //     ], [
    //         'new_password.confirmed' => 'New password and confirm password do not match.',
    //     ]);

    //     if (!Hash::check($request->current_password, $user->password)) {
    //         throw ValidationException::withMessages([
    //             'current_password' => ['Your current password is incorrect.'],
    //         ]);
    //     }

    //     $user->update([ 
    //         'password' => Hash::make($request->new_password),
    //     ]);

    //     if($user->hasRole('sub_admin') && !empty($getCompany->tenant_id)){
             
    //         $tenantId = $getCompany->tenant_id;
    //         $centralDb = DB::connection('central');
    //         $centralDb->table('companies')
    //             ->where('tenant_id', $tenantId)
    //             ->update([
    //                 'password' => Hash::make($request->new_password),
    //                 'updated_at' => now(),
    //             ]);
    //     }

        

    //     return response()->json(['message' => 'Password updated successfully.']);
    
    // }


    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // 🧩 Step 1: Validate input
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'confirmed'],
        ], [
            'new_password.confirmed' => 'New password and confirm password do not match.',
        ]);

        // ❌ Step 2: Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Your current password is incorrect.'],
            ]);
        }

        // ✅ Step 3: Update password in tenant (current DB)
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // 🔄 Step 4: If role is sub_admin → update password in central DB too
        if ($user->hasRole('sub_admin')) {
            $company = Company::first(); 
            
            if ($company && !empty($company->tenant_id)) {
                $company->update(['password'=> $request->new_password]);
                try {
                    DB::connection('central')
                        ->table('companies')
                        ->where('tenant_id', $company->tenant_id)
                        ->update([
                            'password' => $request->new_password,
                            'updated_at' => now(),
                        ]);

                    \Log::info("Central password updated for tenant: {$company->tenant_id}");
                } catch (\Exception $e) {
                    \Log::error('Central password update failed: ' . $e->getMessage());
                }
            }
        }

        // ✅ Step 5: Return JSON response
        return response()->json(['message' => 'Password updated successfully.']);
    }

}
