<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function indexUsers(Request $request)
    {
        $query = User::with(['roles', 'permissions', 'state', 'district', 'city', 'tehsil'])->latest();

        if (!$request->user()->hasRole('master_admin')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->image = $user->image ? asset('storage/' . $user->image) : null;
        }

        return response()->json(['status' => true, 'data' => $users]);
    }

    public function storeUser(Request $request)
    {
        if (!$request->user()->hasPermissionTo('create_users')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized to create users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'roles' => 'array|nullable',
            'image' => 'file|image|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);
        $data['company_id'] = $request->user()->company_id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user = User::create($data);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        $user->image = $user->image ? asset('storage/' . $user->image) : null;

        return response()->json(['status' => true, 'message' => 'User created successfully.', 'data' => $user], 201);
    }

    public function showUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found']);
        }

        if (!$request->user()->hasRole('master_admin') && $user->company_id !== $request->user()->company_id) {
            return $this->sendError('Unauthorized', ['error' => 'User not found']);
        }

        $user->image = $user->image ? asset('storage/' . $user->image) : null;
        return $this->sendResponse([
            'user' => $user
        ], 'User fetch successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        if (!$request->user()->hasRole('master_admin') && $user->company_id !== $request->user()->company_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|min:6',
            'roles' => 'sometimes|array',
            'permissions' => 'sometimes|array',
            'image' => 'sometimes|file|image|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user->update($data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        if (isset($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        $user->image = $user->image ? asset('storage/' . $user->image) : null;

        return response()->json(['status' => true, 'data' => $user]);
    }

    public function deleteUser(Request $request, User $user)
    {
        if (!$request->user()->hasPermissionTo('delete_users')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized to delete users.'], 403);
        }

        if (!$request->user()->hasRole('master_admin') && $user->company_id !== $request->user()->company_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized to delete this user.'], 403);
        }

        $user->delete();

        return response()->json(['status' => true, 'message' => 'User deleted successfully.']);
    }

    public function toggleUser(Request $request, User $user)
    {
        if (!$request->user()->hasRole('master_admin') && $user->company_id !== $request->user()->company_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $user->image = $user->image ? asset('storage/' . $user->image) : null;

        return response()->json(['status' => true, 'data' => $user]);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load(['roles', 'permissions', 'state', 'district', 'city', 'tehsil', 'pincode']);

        $user->image = $user->image ? asset('storage/' . $user->image) : null;

        return response()->json(['status' => true, 'data' => $user]);
    }
}
