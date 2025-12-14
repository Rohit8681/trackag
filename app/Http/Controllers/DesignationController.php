<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Company;
use Illuminate\Support\Facades\Session;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_designations')->only(['index','show']);
        $this->middleware('permission:create_designations')->only(['create','store']);
        $this->middleware('permission:edit_designations')->only(['edit','update']);
        $this->middleware('permission:delete_designations')->only(['destroy']);
    }
    public function index()
    {
        Session::put('page', 'designations');

        $authUser = auth()->user();
        
        $designations = Designation::with('company')->latest()->get();

        return view('admin.hr.index', compact('designations'));
    }

    public function toggleStatus(Request $request)
    {
        $designation = Designation::findOrFail($request->id);
        $designation->status = $request->status;
        $designation->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function create()
    {
        $authUser = auth()->user();

        return view('admin.hr.create', compact( 'authUser'));
    }

    /**
     * Store a newly created designation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // ✅ Duplicate designation check added here
        $existing = Designation::where('name', $request->name)->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['A designation with this name already exists ']);
        }

        $data = $request->only(['name']);

        Designation::create($data);

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }
    public function show(Designation $designation)
    {
        $authUser = auth()->user();

        if ($authUser->user_level !== 'master_admin' && $designation->company_id !== $authUser->company_id) {
            abort(403, 'Unauthorized access to this designation.');
        }

        return view('admin.hr.show', compact('designation'));
    }
    
    public function edit(Designation $designation)
    {
        return view('admin.hr.edit', compact('designation'));
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $designation->update(['name' => $request->name]);

        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    
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
