<?php

namespace App\Http\Controllers;

use App\Models\Depo;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use App\Models\Company;
use App\Models\Tehsil;
use App\Models\Pincode;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    /**
     * Display a paginated list of users.
     */
    public function index()
    {
        Session::put('page', 'dashboard');

        $query = User::with(['roles', 'permissions', 'state', 'district', 'tehsil', 'city', 'reportingManager', 'activeSessions','depos'])->latest();

        // 🔐 Restrict users to current user's company unless master_admin
        $maxUsers = 0;
        if (auth()->user()->user_level !== 'master_admin') {
            $getcompany = Company::find(1);
            if(isset($getcompany->id)) {
                $maxUsers = $getcompany->user_assigned;
                
            }
        }

        $users = $query->get();
        $currentUsers = $query->count();
        

        return view('admin.users.index', compact('users','maxUsers','currentUsers'));
    }

    /**
     * Show the form to create a new user.
     */
    public function create()
    {
        $authUser = auth()->user();
        $roles = Role::all();
        $companies = Company::all();
        $users = User::when($authUser->user_level !== 'master_admin', function ($query) use ($authUser) {
                $query->where('company_id', $authUser->company_id);
            })->get();

        $designations = Designation::all();
        $depos = Depo::where('status',1)->get();
        
        return view('admin.users.create', [
            'roles' => $roles,
            'permissions' => Permission::all(),
            'states' => State::all(),
            'companies' => $companies,
            'authUser' => $authUser,
            'users' => $users, // ✅ added here
            'designations' => $designations, // ✅ passed to view
            'depos' => $depos
        ]);
    }
   
    

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        if ($request->hasFile('cancel_cheque_photos')) {
            $photos = [];
            foreach ($request->file('cancel_cheque_photos') as $index => $photo) {
                if ($index >= 3) break; 
                $photos[] = $photo->store('cancel_cheques', 'public');
            }
            $data['cancel_cheque_photos'] = json_encode($photos);
        }

        $user = User::create($data);

        if ($request->filled('roles')) {
            $user->syncRoles($request->input('roles'));
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    public function show(User $user)
    {
        if (auth()->user()->user_level !== 'master_admin' && $user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to user.');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
 * Show the form to edit an existing user.
 */
public function edit(User $user)
{
    if (auth()->user()->user_level !== 'master_admin' && $user->company_id !== auth()->user()->company_id) {
        abort(403, 'Unauthorized access to user.');
    }

    $authUser = auth()->user();

    $roles = $authUser->user_level === 'master_admin'
        ? Role::all()
        : Role::where('company_id', $authUser->company_id)->get();

    $companies = $authUser->user_level === 'master_admin'
        ? \App\Models\Company::all()
        : collect();

    // ✅ Added: get users of same company for 'Reporting To' dropdown
    $users = User::when($authUser->user_level !== 'master_admin', function ($query) use ($authUser) {
            $query->where('company_id', $authUser->company_id);
        })
        ->where('id', '!=', $user->id)  // 👈 exclude the currently edited user
        ->get();

        $designations = $authUser->user_level === 'master_admin'
    ? Designation::all()
    : Designation::where('company_id', $authUser->company_id)->get();

    $depos = Depo::where('status',1)->get();

    return view('admin.users.edit', [
        'user' => $user,
        'roles' => $roles,
        'permissions' => Permission::all(),
        'states' => State::all(),
        'districts' => District::where('state_id', $user->state_id)->get(),
        'cities' => City::where('district_id', $user->district_id)->get(),
        'tehsils' => Tehsil::where('city_id', $user->city_id)->get(),
        'pincodes' => Pincode::where('city_id', $user->city_id)->get(),
        'companies' => $companies,
        'authUser' => $authUser,
        'users' => $users, // ✅ passed this for Reporting To dropdown
        'designations' => $designations, // ✅ passed to edit view,
        'depos' => $depos

    ]);
}

    

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        // Handle password
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle Profile Image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        // Handle Cancel Cheque Photos
        if ($request->hasFile('cancel_cheque_photos')) {
            $cheque_photos = [];
            foreach ($request->file('cancel_cheque_photos') as $photo) {
                $cheque_photos[] = $photo->store('cheque_photos', 'public');
            }
            $data['cancel_cheque_photos'] = json_encode($cheque_photos);
        }

        $user->update($data);

        // Sync Roles & Permissions
        $user->syncRoles($request->input('roles', []));
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    /**
     * Delete a specific user.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->user_level !== 'master_admin' && $user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized delete attempt.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggle(User $user)
    {
        if (auth()->user()->user_level !== 'master_admin' && $user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized toggle attempt.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User status updated.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|min:6',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully']);
    }

    public function getDistricts($state_id)
    {
        $districts = District::where('state_id', $state_id)->get();
        return response()->json($districts);
    }

    public function getCities($district_id)
    {
        $cities = City::where('district_id', $district_id)->get();
        return response()->json($cities);
    }

    public function getTehsils($city_id)
    {
        $tehsils = Tehsil::where('city_id', $city_id)->get();
        return response()->json($tehsils);
    }

    public function getPincodes($city_id)
    {
        $pincodes = Pincode::where('city_id', $city_id)->get();
        return response()->json($pincodes);
    }
}
