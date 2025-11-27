<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\State;
use App\Models\Trip;
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
            $query->where('bill_type', $request->bill_type);
        }
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $expenses = $query->latest()->get();
        $states = State::where('status',1)->get();
        $employees = User::where('status','Active')->get();
        

        return view('admin.expense.index', compact('expenses', 'states', 'employees'));
    }
    
    public function edit(string $id)
    {
        $expense = Expense::findOrFail($id);
        return view('admin.expense.edit', compact('expense'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bill_date' => 'required|date',
            'bill_type' => 'required|string',
            'bill_title' => 'nullable|string',
            'bill_details_description' => 'nullable|string',
            'travel_mode' => 'nullable',
            'amount' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
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
            $path = $request->file('image')->store('expenses', 'public');
            // $expense->image = $request->file('image')->store('expenses', 'public');
            $expense->image = basename($path);
        }

        $expense->save();

        return redirect()->route('expense.index')->with('success', 'Expense Updated Successfully!');
    }

    public function destroy(string $id)
    {
        try {
            $expense = Expense::find($id);

            if (!$expense) {
                return redirect()->back()->with('error', 'Expense not found.');
            }

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

    // public function expenseReport(Request $request)
    // {
    //     $query = Trip::with(['user', 'company', 'approvedByUser', 'tripLogs', 'customers', 'travelMode', 'tourType'])
    //         ->where('approval_status', 'approved');

    //     if ($request->filled('from_date')) {
    //         $query->whereDate('trip_date', '>=', $request->from_date);
    //     }

    //     if ($request->filled('to_date')) {
    //         $query->whereDate('trip_date', '<=', $request->to_date);
    //     }

    //     if ($request->filled('state_id')) {
    //         $query->whereHas('user', function ($q) use ($request) {
    //             $q->where('state_id', $request->state_id);
    //         });
    //     }

    //     if ($request->filled('user_id')) {
    //         $query->where('user_id', $request->user_id);
    //     }

    //     $data = $query->latest()->get();

    //     // Get dropdown data
    //     $states = State::where('status',1)->get();
    //     $employees = User::where('is_active',1)->get();

    //     return view('admin.expense.report', compact('data', 'states', 'employees'));
    // }

    public function expenseReport(Request $request)
    {
        $from = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');

        $query = Trip::with(['user', 'company', 'approvedByUser', 'tripLogs', 'customers', 'travelMode', 'tourType'])
            ->where('approval_status', 'approved')
            ->whereDate('trip_date', '>=', $from)
            ->whereDate('trip_date', '<=', $to);

        if ($request->filled('state_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $data = $query->latest()->get();

        if(!empty($data)){
            foreach ($data as $item) {
                dd($item);

                // $expense = Expense::where('trip_id', $item->id)->first();

                // $item->ta_exp = $expense->ta_amount ?? 0;
                // $item->da_exp = $expense->da_amount ?? 0;
                // $item->other_exp = $expense->other_amount ?? 0;

                // $item->total_exp = 
                //     ($expense->ta_amount ?? 0) +
                //     ($expense->da_amount ?? 0) +
                //     ($expense->other_amount ?? 0);
            }
        }
        
        
        $states = State::where('status', 1)->get();
        $employees = User::where('is_active', 1)->get();

        return view('admin.expense.report', compact('data', 'states', 'employees'))
            ->with(['from_date' => $from, 'to_date' => $to]);
    }
}
