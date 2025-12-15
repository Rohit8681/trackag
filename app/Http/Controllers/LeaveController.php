<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_leave_master')->only(['index','show']);
        $this->middleware('permission:create_leave_master')->only(['create','store']);
        $this->middleware('permission:edit_leave_master')->only(['edit','update']);
        $this->middleware('permission:delete_leave_master')->only(['destroy']);
    }
    public function index()
    {
        $leaves = Leave::orderBy('created_at', 'desc')->get();
        return view('admin.leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('admin.leaves.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'leave_name' => 'required|string|max:191',
            'leave_code' => 'required|string|max:191|unique:leaves,leave_code',
            'is_paid' => ['required', Rule::in(['Yes', 'No'])],
        ]);

        Leave::create($data);

        return redirect()->route('leaves.index')->with('success', 'Leave created successfully.');
    }

    public function edit(Leave $leaf)
    {
        return view('admin.leaves.edit', compact('leaf'));
    }

    public function update(Request $request, Leave $leaf)
    {
        $data = $request->validate([
            'leave_name' => 'required|string|max:191',
            'leave_code' => ['required','string','max:191', Rule::unique('leaves')->ignore($leaf->id)],
            'is_paid' => ['required', Rule::in(['Yes', 'No'])],
            'status' => ['required', Rule::in(['0', '1', 0, 1, true, false])],
        ]);

        $leaf->update($data);

        return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
    }

    public function destroy(Leave $leaf)
    {
        $leaf->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted.');
    }

    public function toggleStatus(Request $request)
    {
        $leave = Leave::findOrFail($request->id);
        $leave->status = $request->status;
        $leave->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
