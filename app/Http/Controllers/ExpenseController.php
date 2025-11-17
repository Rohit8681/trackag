<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['user', 'travelMode']);

        if ($request->filled('from_date')) {
            $query->whereDate('bill_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('bill_date', '<=', $request->to_date);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('bill_type')) {
            $query->whereJsonContains('bill_type', $request->bill_type);
        }
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $expenses = $query->latest()->get();
        $states = State::where('status',1)->get();
        $employees = User::where('status', 1)->get();

        return view('admin.expense.index', compact('expenses', 'states', 'employees'));
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = Expense::findOrFail($id);
        return view('admin.expense.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bill_date' => 'required|date',
            'bill_type' => 'required|array',
            'bill_title' => 'nullable|string',
            'bill_details_description' => 'nullable|string',
            'travel_mode' => 'nullable',
            'amount' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $expense = Expense::findOrFail($id);

        $expense->bill_date = $request->bill_date;
        $expense->bill_type = $request->bill_type;
        $expense->bill_title = $request->bill_title;
        $expense->bill_details_description = $request->bill_details_description;
        $expense->travel_mode = $request->travel_mode;
        $expense->amount = $request->amount;

        // IMAGE UPDATE
        if ($request->hasFile('image')) {
            if ($expense->image && file_exists(storage_path('app/public/'.$expense->image))) {
                unlink(storage_path('app/public/'.$expense->image));
            }
            $expense->image = $request->file('image')->store('expenses', 'public');
        }

        $expense->save();

        return redirect()->route('expense.index')->with('success', 'Expense Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $expense = Expense::find($id);

            if (!$expense) {
                return redirect()->back()->with('error', 'Expense not found.');
            }

            // Delete image if exists
            // if ($expense->image && file_exists(public_path('storage/expenses/' . $expense->image))) {
            //     unlink(public_path('storage/expenses/' . $expense->image));
            // }

            $expense->delete();

            return redirect()->back()->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->approval_status = 'Approved';
        $expense->save();

        return redirect()->back()->with('success', 'Expense approved successfully.');
    }

    public function reject($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->approval_status = 'Rejected';
        $expense->save();

        return redirect()->back()->with('error', 'Expense rejected.');
    }
}
