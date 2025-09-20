<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function index()
{
    Session::put('page', 'customers');
    $admin = Auth::user();

    if ($admin->hasRole('master_admin')) {
        // Master admin sees all customers
        $customers = Customer::with(['user', 'company'])->latest()->get();
    } elseif ($admin->hasRole('sub_admin')) {
        // Sub-admin sees all customers in their company
        $customers = Customer::with(['user', 'company'])
            ->where('company_id', $admin->company_id)
            ->latest()
            ->get();
    } else {
        // Other users see only customers assigned to them
        $customers = Customer::with(['user', 'company'])
            ->where('user_id', $admin->id)
            ->latest()
            ->get();
    }

    return view('admin.customers.index', compact('customers'));
}



    public function create()
    {
        Session::put('page', 'add-customer');
        $admin = Auth::user();

        $companies = $admin->hasRole('master_admin')
            ? Company::all()
            : Company::where('id', $admin->company_id ?? 1)->get();

        $executives = collect();

        if (!$admin->hasRole('master_admin')) {
            $executives = User::where('company_id', $admin->company_id ?? 1)
                ->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'master_admin');
                })
                ->get();
        }

        return view('admin.customers.create', compact('companies', 'executives'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|unique:customers,email',
            'phone'      => 'required|string|max:20',
            'address'    => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'user_id'    => 'nullable|exists:users,id',
            'is_active'  => 'nullable|boolean',
        ]);

        $validated['company_id'] = $admin->hasRole('master_admin')
            ? ($request->input('company_id') ?? 1)
            : ($admin->company_id ?? 1);

        $validated['user_id'] = $admin->hasRole('executive')
            ? $admin->id
            : $request->input('user_id');

        $validated['is_active'] = $request->has('is_active') ? (int) $request->input('is_active') : 1;

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function show(string $id)
    {
        $customer = Customer::with(['user', 'company'])->findOrFail($id);
        $this->authorizeCustomerAccess($customer);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        $this->authorizeCustomerAccess($customer);

        $admin = Auth::user();

        $companies = $admin->hasRole('master_admin')
            ? Company::all()
            : Company::where('id', $admin->company_id ?? 1)->get();

        $executives = User::where('company_id', $customer->company_id ?? ($admin->company_id ?? 1))
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'master_admin');
            })
            ->get();

        return view('admin.customers.edit', compact('customer', 'executives', 'companies'));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        $this->authorizeCustomerAccess($customer);

        $admin = Auth::user();

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone'      => 'required|string|max:20',
            'address'    => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'user_id'    => 'nullable|exists:users,id',
            'is_active'  => 'nullable|boolean',
        ]);

        $validated['company_id'] = $admin->hasRole('master_admin')
            ? ($request->input('company_id') ?? 1)
            : ($admin->company_id ?? 1);

        $validated['user_id'] = $admin->hasRole('executive')
            ? $admin->id
            : $request->input('user_id');

        $validated['is_active'] = $request->has('is_active') ? (int) $request->input('is_active') : 0;

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $this->authorizeCustomerAccess($customer);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * AJAX: Get executives for selected company (used in frontend)
     */
    public function getExecutives($companyId)
    {
        $executives = User::where('company_id', $companyId)
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'master_admin');
            })
            ->select('id', 'name')
            ->get();

        return response()->json(['executives' => $executives]);
    }

    /**
     * Ensure the authenticated user has access to this customer
     */
    private function authorizeCustomerAccess(Customer $customer)
    {
        $admin = Auth::user();

        if ($admin->hasRole('master_admin')) return;

        if (($admin->company_id ?? 1) !== $customer->company_id) {
            abort(403, 'Unauthorized access to this customer.');
        }
    }

    /**
     * Toggle the active/inactive status of a customer
     */
    public function toggleStatus(Customer $id)
    {
        $this->authorizeCustomerAccess($id);

        $id->is_active = !$id->is_active;
        $id->save();

        return redirect()->route('customers.index')->with('success', 'Customer status updated successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No customers selected for deletion.');
        }

        Customer::whereIn('id', $ids)->delete();

        return redirect()->route('customers.index')->with('success', 'Selected customers deleted successfully.');
    }
}
