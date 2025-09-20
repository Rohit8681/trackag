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
use App\Models\Tehsil;
use App\Models\Pincode;
use App\Models\Designation;


class UserController extends Controller
{
    /**
     * Display a paginated list of users.
     */
    public function index()
    {
        Session::put('page', 'dashboard');

        $query = User::with(['roles', 'permissions', 'state', 'district', 'tehsil', 'city', 'reportingManager', 'activeSessions'])->latest();

        // 🔐 Restrict users to current user's company unless master_admin
        if (auth()->user()->user_level !== 'master_admin') {
            $query->where('company_id', auth()->user()->company_id);
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form to create a new user.
     */
    public function create()
    {
        $authUser = auth()->user();

        $roles = $authUser->user_level === 'master_admin'
            ? Role::all()
            : Role::where('company_id', $authUser->company_id)->get();

        $companies = $authUser->user_level === 'master_admin'
            ? \App\Models\Company::all()
            : collect(); // empty for non-master

        // ✅ Added: get users of same company for 'Reporting To' dropdown
        $users = User::when($authUser->user_level !== 'master_admin', function ($query) use ($authUser) {
                $query->where('company_id', $authUser->company_id);
            })->get();

        $designations = $authUser->user_level === 'master_admin'
        ? Designation::all()
        : Designation::where('company_id', $authUser->company_id)->get();

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

    /**
     * Store a newly created user in the database.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if (auth()->user()->user_level !== 'master_admin') {
            $data['company_id'] = auth()->user()->company_id;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user = User::create($data);

        if ($request->filled('roles')) {
            $user->syncRoles($request->input('roles'));
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display a specific user's details.
     */
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
        'designations' => $designations // ✅ passed to edit view
    ]);
}

    /**
     * Update a specific user in the database.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (auth()->user()->user_level !== 'master_admin' && $user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized update attempt.');
        }

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user->update($data);

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

    /**
     * AJAX: Get districts for selected state.
     */
    public function getDistricts($state_id)
    {
        $districts = District::where('state_id', $state_id)->get();
        return response()->json($districts);
    }

    /**
     * AJAX: Get cities for selected district.
     */
    public function getCities($district_id)
    {
        $cities = City::where('district_id', $district_id)->get();
        return response()->json($cities);
    }

    /**
     * AJAX: Get tehsils for selected city.
     */
    public function getTehsils($city_id)
    {
        $tehsils = Tehsil::where('city_id', $city_id)->get();
        return response()->json($tehsils);
    }

    /**
     * AJAX: Get pincodes for selected city.
     */
    public function getPincodes($city_id)
    {
        $pincodes = Pincode::where('city_id', $city_id)->get();
        return response()->json($pincodes);
    }
}
