<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::with('state')->orderBy('holiday_date', 'desc')->get();
        return view('admin.holidays.index', compact('holidays'));
    }

    public function create()
    {
        $states = State::where('status', 1)->orderBy('name')->get();
        return view('admin.holidays.create', compact('states'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'state_id' => 'nullable|exists:states,id',
            'holiday_date' => 'required|date',
            'holiday_name' => 'required|string|max:191',
            'holiday_type' => ['required', Rule::in(['Public', 'National', 'State', 'Festival'])],
            'is_paid' => ['required', Rule::in(['Yes', 'No'])],
        ]);

        Holiday::create($data);

        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully.');
    }

    public function edit(Holiday $holiday)
    {
        $states = State::where('status', 1)->orderBy('name')->get();
        return view('admin.holidays.edit', compact('holiday', 'states'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $data = $request->validate([
            'state_id' => 'nullable|exists:states,id',
            'holiday_date' => 'required|date',
            'holiday_name' => 'required|string|max:191',
            'holiday_type' => ['required', Rule::in(['Public', 'National', 'State', 'Festival'])],
            'is_paid' => ['required', Rule::in(['Yes', 'No'])],
            'status' => ['required', Rule::in(['0', '1', 0, 1, true, false])],
        ]);

        $holiday->update($data);

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted.');
    }

    public function toggleStatus(Request $request)
    {
        $holiday = Holiday::findOrFail($request->id);
        $holiday->status = $request->status;
        $holiday->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
