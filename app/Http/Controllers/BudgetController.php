<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\BudgetLog;
use App\Models\User;
use App\Models\State;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
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

        $months = ['april' => 4, 'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 'september' => 9,
            'october' => 10, 'november' => 11, 'december' => 12, 'january' => 1, 'february' => 2, 'march' => 3
        ];

        $years = explode('-', $financial_year);
        $startYear = $years[0];
        $endYear = count($years) > 1 ? '20' . $years[1] : $years[0] + 1;

        foreach ($budgets as $budget) {
            $achievements = [];
            foreach ($months as $monthName => $monthNum) {
                $year = ($monthNum >= 4) ? $startYear : $endYear;
                
                $achive = OrderItem::whereHas('order', function($q) use ($budget, $monthNum, $year) {
                    $q->where('user_id', $budget->user_id)
                      ->where('status', 'dispatched') 
                      ->whereMonth('created_at', $monthNum)
                      ->whereYear('created_at', $year);
                })->sum('grand_total');

                $achievements[$monthName] = $achive;
            }
            $budget->achievements = $achievements;
        }

        $monthList = array_keys($months);

        return view('admin.budget.index', compact(
            'financial_year', 'state_id', 'employee_id',
            'employees', 'states', 'budgets', 'months', 'monthList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'state_id' => 'required',
            'financial_year' => 'required',
            'total_target' => 'nullable|numeric',
            'monthly_targets' => 'nullable|array',
        ]);

        $targets = $request->monthly_targets ?? [];
        $sumMonthly = array_sum($targets);
        $totalTarget = $request->total_target ?? $sumMonthly;

        $monthList = ['april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december', 'january', 'february', 'march'];

        if (($sumMonthly == 0) && $totalTarget > 0) {
            $monthlyValue = round($totalTarget / 12, 2);
            foreach ($monthList as $m) {
                $targets[$m] = $monthlyValue;
            }
            $targets['march'] = $totalTarget - ($monthlyValue * 11);
        } else {
            $totalTarget = $sumMonthly;
        }

        // Get existing budget to compare
        $existingBudget = Budget::where([
            'user_id' => $request->user_id,
            'state_id' => $request->state_id,
            'financial_year' => $request->financial_year,
        ])->first();

        $budget = Budget::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'state_id' => $request->state_id,
                'financial_year' => $request->financial_year,
            ],
            array_merge($targets, ['total_target' => $totalTarget])
        );

        // Record Logs
        foreach ($monthList as $month) {
            $oldValue = $existingBudget ? ($existingBudget->$month ?? 0) : 0;
            $newValue = isset($targets[$month]) ? $targets[$month] : 0;

            if (round($oldValue, 2) != round($newValue, 2)) {
                BudgetLog::create([
                    'budget_id' => $budget->id,
                    'user_id' => $request->user_id,
                    'admin_id' => auth()->id(),
                    'financial_year' => $request->financial_year,
                    'month' => $month,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Budget target set successfully.');
    }

    public function getLogs(Request $request)
    {
        $logs = BudgetLog::with(['admin'])
            ->where('user_id', $request->user_id)
            ->where('financial_year', $request->financial_year)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($log) {
                return [
                    'admin_name' => $log->admin->name ?? 'System',
                    'month' => ucfirst($log->month),
                    'old_value' => number_format($log->old_value, 2),
                    'new_value' => number_format($log->new_value, 2),
                    'date' => $log->created_at->format('d-M-Y h:i A'),
                ];
            });

        return response()->json(['logs' => $logs]);
    }
    
    public function show($id)
    {
        if ($id == 'report') {
            return redirect()->route('budget.report');
        }
        abort(404);
    }

    public function report(Request $request)
    {
        $financial_year = $request->input('financial_year', date('Y') . '-' . (date('y') + 1));
        
        $filters = $this->getRoleBasedStateAndEmployeeFilters();
        extract($filters);

        $budgets = Budget::with(['state'])
            ->where('financial_year', $financial_year);
            
        if (!empty($stateIds)) {
            $budgets->whereIn('state_id', $stateIds);
        }

        $budgets = $budgets->get();
        
        $months = [
            'april' => 4, 'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 'september' => 9,
            'october' => 10, 'november' => 11, 'december' => 12, 'january' => 1, 'february' => 2, 'march' => 3
        ];
        $monthList = array_keys($months);

        $stateReport = [];
        $years = explode('-', $financial_year);
        $startYear = $years[0];
        $endYear = count($years) > 1 ? '20' . $years[1] : $years[0] + 1;

        foreach ($budgets as $budget) {
            $stateId = $budget->state_id;
            $stateName = $budget->state->name ?? 'Unknown';

            if (!isset($stateReport[$stateId])) {
                $stateReport[$stateId] = [
                    'name' => $stateName,
                    'total_target' => 0,
                    'monthly_targets' => array_fill_keys($monthList, 0),
                    'monthly_achievements' => array_fill_keys($monthList, 0),
                ];
            }

            $stateReport[$stateId]['total_target'] += $budget->total_target;
            foreach ($monthList as $m) {
                $stateReport[$stateId]['monthly_targets'][$m] += $budget->$m ?? 0;
                
                $monthNum = $months[$m];
                $year = ($monthNum >= 4) ? $startYear : $endYear;
                
                $achive = OrderItem::whereHas('order', function($q) use ($budget, $monthNum, $year) {
                    $q->where('user_id', $budget->user_id)
                      ->where('status', 'dispatched')
                      ->whereMonth('created_at', $monthNum)
                      ->whereYear('created_at', $year);
                })->sum('grand_total');
                
                $stateReport[$stateId]['monthly_achievements'][$m] += $achive;
            }
        }

        return view('admin.budget.report', compact('stateReport', 'financial_year', 'months', 'monthList', 'states'));
    }
}
