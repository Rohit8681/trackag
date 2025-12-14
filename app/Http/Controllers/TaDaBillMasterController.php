<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\TaDaBillMaster;
use Illuminate\Http\Request;

class TaDaBillMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_ta_da_bill_master')->only(['index']);
        // $this->middleware('permission:create_permissions')->only(['create','store']);
        // $this->middleware('permission:edit_permissions')->only(['edit','update']);
        // $this->middleware('permission:delete_permissions')->only(['destroy']);
    }
    public function index()
    {
        $designations = Designation::with('taDaBillMaster')->orderBy('name')->get();
        return view('admin.ta-da-bill-master.index', compact('designations'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'designations' => 'required|array',
            'designations.*.day_limit' => 'nullable|integer|min:0',
            'designations.*.status' => 'nullable|boolean',
        ]);

        foreach ($data['designations'] as $designation_id => $designationData) {
            TaDaBillMaster::updateOrCreate(
                ['designation_id' => $designation_id],
                [
                    'day_limit' => $designationData['day_limit'] ?? null,
                ]
            );
        }

        return redirect()->route('ta-da-bill-master.index')->with('success', 'TA-DA Bill Master updated successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $TaDaBill = TaDaBillMaster::findOrFail($request->id);
        $TaDaBill->status = $request->status;
        $TaDaBill->save();
       

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
