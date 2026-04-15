<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MonthlyPlan;
use App\Models\User;
use App\Models\State;
use App\Models\Product;

class MonthlyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->input('month', date('n'));
        $employee_id = $request->input('employee_id');
        $state_filter = $request->input('state_id');
        $product_filter = $request->input('product_id');

        $employees = User::all();
        $states = State::all();
        $products = Product::all();

        $query = MonthlyPlan::with(['user', 'product', 'packing', 'state'])
            ->where('month', $month);

        if ($employee_id) $query->where('user_id', $employee_id);
        if ($state_filter) $query->where('state_id', $state_filter);
        if ($product_filter) $query->where('product_id', $product_filter);

        $plans = $query->get();

        $reportData = [];
        $uniqueStates = [];

        foreach($plans as $plan) {
            if(!$plan->state_id) continue;
            
            $uniqueStates[$plan->state_id] = $plan->state->name ?? 'Unknown State';
            
            if(!isset($reportData[$plan->product_id])) {
                $reportData[$plan->product_id] = [
                    'name' => $plan->product->product_name ?? 'Unknown Product',
                    'packings' => []
                ];
            }
            
            if(!isset($reportData[$plan->product_id]['packings'][$plan->packing_id])) {
                $reportData[$plan->product_id]['packings'][$plan->packing_id] = [
                     'name' => ($plan->packing->packing_value ?? '').' '.($plan->packing->packing_size ?? ''),
                     'states' => []
                ];
            }
            
            if(!isset($reportData[$plan->product_id]['packings'][$plan->packing_id]['states'][$plan->state_id])) {
                $reportData[$plan->product_id]['packings'][$plan->packing_id]['states'][$plan->state_id] = 0;
            }
            
            $reportData[$plan->product_id]['packings'][$plan->packing_id]['states'][$plan->state_id] += $plan->quantity;
        }

        return view('admin.monthly.index', compact(
            'month', 'employee_id', 'state_filter', 'product_filter',
            'employees', 'states', 'products', 'reportData', 'uniqueStates'
        ));
    }

    public function getEmployeesByState(Request $request)
    {
        $state_id = $request->state_id;
        $product_id = $request->product_id;
        $month = $request->month;

        $plans = MonthlyPlan::with(['user', 'packing'])
            ->where('state_id', $state_id)
            ->where('product_id', $product_id)
            ->where('month', $month)
            ->get();

        $data = [];
        $packings = [];

        foreach($plans as $plan) {
            $userId = $plan->user_id;
            $packId = $plan->packing_id;
            $packName = ($plan->packing->packing_value ?? '').' '.($plan->packing->packing_size ?? '');

            $packings[$packId] = $packName;

            if(!isset($data[$userId])) {
                $data[$userId] = [
                    'name' => $plan->user->name ?? 'Unknown',
                    'packings' => []
                ];
            }

            if(!isset($data[$userId]['packings'][$packId])) {
                $data[$userId]['packings'][$packId] = 0;
            }

            $data[$userId]['packings'][$packId] += $plan->quantity;
        }

        return response()->json([
            'employees' => array_values($data),
            'packings' => $packings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
