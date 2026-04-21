<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\MonthlyPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonthlyPlanApiController extends Controller
{
    public function getProductPackingList(Request $request)
    {
        $userId = Auth::id() ?? $request->user()->id ?? null;
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.'
            ], 401);
        }

        $search = $request->input('search');
        
        // Determine the target month and year (default to current or next month context)
        // If today is April, we might be planning for May. 
        // Based on image context (Mar, Apr, May), if today is April, May is the target.
        // We'll allow passing month/year, else default to "next month" context.
        $targetMonth = $request->input('month', date('n') == 12 ? 1 : date('n') + 1);
        $targetYear = $request->input('year', date('n') == 12 ? date('Y') + 1 : date('Y'));

        $targetDate = Carbon::createFromDate($targetYear, $targetMonth, 1);
        $dateRange = [
            $targetDate->copy()->subMonths(2),
            $targetDate->copy()->subMonths(1),
            $targetDate,
        ];

        $productQuery = Product::with(['packings' => function ($q) {
            $q->select('id', 'product_id', 'packing_value', 'packing_size')
                ->where('status', 1);
        }])->where('status', 1);

        if ($search) {
            $productQuery->where('product_name', 'like', "%{$search}%");
        } else {
            $productQuery->orderBy('id')->limit(2);
        }

        $products = $productQuery->get();

        // Collect all packing IDs for bulk fetching plans
        $packingIds = $products->flatMap(function ($product) {
            return $product->packings->pluck('id');
        })->unique();

        // Fetch monthly plans for the user and the 3-month range
        $plans = MonthlyPlan::where('user_id', $userId)
            ->whereIn('packing_id', $packingIds)
            ->where(function ($query) use ($dateRange) {
                foreach ($dateRange as $date) {
                    $query->orWhere(function ($q) use ($date) {
                        $q->where('month', $date->month)->where('year', $date->year);
                    });
                }
            })
            ->get()
            ->groupBy(function ($plan) {
                return $plan->packing_id . '-' . $plan->month . '-' . $plan->year;
            });

        $data = $products->map(function ($product) use ($dateRange, $plans) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'packings' => $product->packings->map(function ($packing) use ($dateRange, $plans) {
                    $monthsData = collect($dateRange)->map(function ($date) use ($packing, $plans) {
                        $key = $packing->id . '-' . $date->month . '-' . $date->year;
                        $planRec = $plans->get($key)?->first();
                        
                        return [
                            'month' => $date->month,
                            'year' => $date->year,
                            'month_name' => $date->format('M'),
                            'quantity' => $planRec ? $planRec->quantity : 0,
                        ];
                    });

                    return [
                        'packing_id' => $packing->id,
                        'packing_value' => $packing->packing_value,
                        'packing_size' => $packing->packing_size,
                        'label' => $packing->packing_value . ' ' . $packing->packing_size,
                        'months' => $monthsData,
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
