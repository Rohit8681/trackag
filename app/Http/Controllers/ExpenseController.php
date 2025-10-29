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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
