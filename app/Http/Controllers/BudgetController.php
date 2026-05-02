<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\User;
use App\Models\State;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $financial_year = $request->input('financial_year', date('Y') . '-' . (date('y') + 1));
        $state_id = $request->input('state_id');
        $employee_id = $request->input('employee_id');

        $filters = $this->getRoleBasedStateAndEmployeeFilters();
        extract($filters);

        $budgets = Budget::with(['user', 'state']);
        if ($financial_year) $budgets->where('financial_year', $financial_year);
        if ($state_id) $budgets->where('state_id', $state_id);
        if ($employee_id) $budgets->where('user_id', $employee_id);

        $budgets = $budgets->get();

        $months = [
            'april' => 4, 'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 'september' => 9,
            'october' => 10, 'november' => 11, 'december' => 12, 'january' => 1, 'february' => 2, 'march' => 3
        ];

        // If financial year is 2026-27, years are 2026 and 2027
        $years = explode('-', $financial_year);
        $startYear = $years[0];
        $endYear = count($years) > 1 ? '20' . $years[1] : $years[0] + 1;

        foreach ($budgets as $budget) {
            $achievements = [];
            foreach ($months as $monthName => $monthNum) {
                $year = ($monthNum >= 4) ? $startYear : $endYear;
                
                $achive = OrderItem::whereHas('order', function($q) use ($budget, $monthNum, $year) {
                    $q->where('user_id', $budget->user_id)
                      ->where('status', 'dispatched') // Assuming only dispatched orders count as achievement
                      ->whereMonth('created_at', $monthNum)
                      ->whereYear('created_at', $year);
                })->sum('grand_total');

                $achievements[$monthName] = $achive;
            }
            $budget->achievements = $achievements;
        }

        return view('admin.budget.index', compact(
            'financial_year', 'state_id', 'employee_id',
            'employees', 'states', 'budgets', 'months'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'state_id' => 'required',
            'financial_year' => 'required',
            'monthly_targets' => 'required|array',
        ]);

        $targets = $request->monthly_targets;
        $totalTarget = array_sum($targets);

        Budget::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'state_id' => $request->state_id,
                'financial_year' => $request->financial_year,
            ],
            array_merge($targets, ['total_target' => $totalTarget])
        );

        return redirect()->back()->with('success', 'Budget target set successfully.');
    }
}
