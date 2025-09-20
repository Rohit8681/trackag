<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Company;
use Illuminate\Support\Facades\Session;

class DesignationController extends Controller
{
    /**
     * List all designations for the current user's company.
     */
    public function index()
    {
        Session::put('page', 'designations');

        $authUser = auth()->user();

        // $designations = $authUser->user_level === 'master_admin'
        //     ? Designation::with('company')->latest()->get()
        //     : Designation::with('company')->where('company_id', $authUser->company_id)->latest()->get();
        $designations = Designation::with('company')->latest()->get();

        return view('admin.hr.index', compact('designations'));
    }

    /**
     * Show the form for creating a new designation.
     */
    public function create()
    {
        $authUser = auth()->user();

        $companies = $authUser->user_level === 'master_admin'
            ? Company::all()
            : collect();

        return view('admin.hr.create', compact('companies', 'authUser'));
    }

    /**
     * Store a newly created designation.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string'
    ]);

    $authUser = auth()->user();
    $companyId = $authUser->user_level === 'master_admin'
        ? $request->company_id
        : $authUser->company_id;

    // ✅ Duplicate designation check added here
    $existing = Designation::where('company_id', $companyId)
        ->where('name', $request->name)
        ->first();

    if ($existing) {
        // Redirect back with error if duplicate found
        return redirect()->back()
            ->withInput()
            ->withErrors(['A designation with this name already exists for the selected company.']);
    }

    $data = $request->only(['name', 'description']);
    $data['company_id'] = $companyId;

    Designation::create($data);

    return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
}


    /**
     * Show a specific designation.
     */
    public function show(Designation $designation)
    {
        $authUser = auth()->user();

        if ($authUser->user_level !== 'master_admin' && $designation->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized access to this designation.');
        }

        return view('admin.hr.show', compact('designation'));
    }

    /**
     * Show the form to edit a designation.
     */
    public function edit(Designation $designation)
    {
        $authUser = auth()->user();

        if ($authUser->user_level !== 'master_admin' && $designation->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized edit attempt.');
        }

        $companies = $authUser->user_level === 'master_admin'
            ? Company::all()
            : collect();

        return view('admin.hr.edit', compact('designation', 'companies', 'authUser'));
    }

    /**
     * Update a specific designation.
     */
    public function update(Request $request, Designation $designation)
    {
        $authUser = auth()->user();

        if ($authUser->user_level !== 'master_admin' && $designation->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized update attempt.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $designation->update([
            'name'        => $request->name,
            'description' => $request->description,
            'company_id'  => $authUser->user_level === 'master_admin'
                ? $request->company_id
                : $authUser->company_id
        ]);

        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    /**
     * Delete a specific designation.
     */
    public function destroy(Designation $designation)
    {
        $authUser = auth()->user();

        if ($authUser->user_level !== 'master_admin' && $designation->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized delete attempt.');
        }

        $designation->delete();

        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}
