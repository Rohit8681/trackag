<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\MonthlyPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MonthlyPlanApiController extends Controller
{
    public function getProductPackingList()
    {
        $products = Product::with(['packings' => function ($q) {
            $q->select('id', 'product_id', 'packing_value', 'packing_size')
            ->where('status', 1);
        }])
        ->where('status', 1)
        ->get();

        $data = $products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'packings' => $product->packings->map(function ($packing) {
                    return [
                        'packing_id' => $packing->id,
                        'packing_value' => $packing->packing_value,
                        'packing_size' => $packing->packing_size,
                        'label' => $packing->packing_value . ' ' . $packing->packing_size,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getPlanList(Request $request)
    {
        $userId = Auth::id() ?? $request->user()->id ?? null;
        $year = $request->input('year', date('Y'));

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.'
            ], 401);
        }

        $plans = MonthlyPlan::with(['product', 'packing'])
            ->where('user_id', $userId)
            ->where('year', $year)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    public function bulkStorePlan(Request $request)
    {
        $request->validate([
            'plans' => 'required|array',
            'plans.*.product_id' => 'required',
            'plans.*.packing_id' => 'required',
            'plans.*.month' => 'required|integer|min:1|max:12',
            'plans.*.year' => 'required|integer',
            'plans.*.quantity' => 'required|integer|min:0',
        ]);

        $userId = Auth::id() ?? $request->user()->id ?? null;
        $userStateId = $request->user()->state_id ?? null;

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.'
            ], 401);
        }

        foreach ($request->plans as $plan) {
            MonthlyPlan::updateOrCreate(
                [
                    'user_id' => $userId,
                    'product_id' => $plan['product_id'],
                    'packing_id' => $plan['packing_id'],
                    'month' => $plan['month'],
                    'year' => $plan['year'],
                ],
                [
                    'quantity' => $plan['quantity'],
                    'state_id' => $userStateId,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Monthly plan updated successfully.'
        ]);
    }
}
