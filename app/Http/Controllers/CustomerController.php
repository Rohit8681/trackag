<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Depo;
use App\Models\District;
use App\Models\State;
use App\Models\Tehsil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomersImport;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();

        $query = Customer::with(['user', 'company', 'state', 'district', 'tehsil']);
        if (!($admin->hasRole('master_admin') || $admin->hasRole('sub_admin'))) {
            $query->where('user_id', $admin->id);
        }
        if ($request->filled('financial_year')) {
            // $query->where('financial_year', $request->financial_year);
        }
        if ($request->filled('party_code')) {
            $query->where('party_code', 'like', "%{$request->party_code}%");
        }
        if ($request->filled('agro_name')) {
            $query->where('agro_name', 'like', "%{$request->agro_name}%");
        }
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }
        if ($request->filled('contact_person')) {
            $query->where('contact_person_name', 'like', "%{$request->contact_person}%");
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        $customers = $query->latest()->get();

        $states = State::where('status', 1)->orderBy('name')->get();
        $financialYears = range(2020, now()->year + 1); // Example range

        return view('admin.customers.index', compact('customers', 'states', 'financialYears'));
    }


    public function create()
    {
        $admin = Auth::user();
        $executives = collect();
        $executives = User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'master_admin');
            })->get();
        $depos = Depo::where('status',1)->orderBy('depo_name')->get();
        $states = State::where('status', 1)->orderBy('name')->get();
        return view('admin.customers.create', compact( 'executives','depos','states'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'agro_name' => 'required|string|max:255',
            'party_code' => 'required|string|max:255',
            'address'    => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'gst_no' => 'required|string|max:255',
            'user_id'    => 'required|exists:users,id',
            'credit_limit' => 'required|string|max:255',
            'depo_id' => 'required|exists:depos,id',
            'party_active_since' => 'required|date',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'tehsil_id' => 'required|exists:tehsils,id',
            'email' => 'nullable|email|unique:customers,email',
            'is_active'  => 'nullable|boolean',
        ]);

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
        $admin = Auth::user();

        $executives = collect();
        $executives = User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'master_admin');
            })->get();
        $depos = Depo::where('status',1)->orderBy('depo_name')->get();
        $states = State::where('status', 1)->orderBy('name')->get();
        $districts = District::where('state_id', $customer->state_id)->where('status',1)->orderBy('name')->get();
        $tehsils  = Tehsil::where('district_id', $customer->district_id)->where('status',1)->orderBy('name')->get();
        return view('admin.customers.edit', compact('customer', 'executives', 'depos','states','districts','tehsils'));
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'agro_name'          => 'required|string|max:255',
            'party_code'         => 'required|string|max:255',
            'address'            => 'nullable|string|max:255',
            'phone'              => 'required|string|max:255',
            'gst_no'             => 'required|string|max:255',
            'user_id'            => 'required|exists:users,id',
            'credit_limit'       => 'required|string|max:255',
            'depo_id'            => 'required|exists:depos,id',
            'party_active_since' => 'required|date',
            'state_id'           => 'required|exists:states,id',
            'district_id'        => 'required|exists:districts,id',
            'tehsil_id'          => 'required|exists:tehsils,id',
            'email'              => 'nullable|email|unique:customers,email,' . $customer->id,
            'is_active'          => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (int) $request->input('is_active') : 1;

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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
        ]);

        try {
            Excel::import(new CustomersImport, $request->file('file'));
            return redirect()->route('customers.index')->with('success', 'Customers imported successfully!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
