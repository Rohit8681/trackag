<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\State;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $state_id = $request->input('state_id');
        $user_id = $request->input('employee_id');
        $party_id = $request->input('party_id');

        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();
        
        $stateIds = [];
        $userStateAccess = \App\Models\UserStateAccess::where('user_id', $user->id)->first();
        if ($userStateAccess && !empty($userStateAccess->state_ids)) {
            $stateIds = $userStateAccess->state_ids;
        }

        $companyCount = \App\Models\Company::count();
        $company = null;
        $companyStates = [];

        if (in_array($roleName, ['master_admin', 'sub_admin'])) {
            $employees = User::where('status', 'Active')->where('id', '!=', 1)->get();
        } else {
            $employees = empty($stateIds)
                ? collect()
                : User::where('status', 'Active')->where('id', '!=', 1)
                    ->whereIn('state_id', $stateIds)
                    ->where('reporting_to', $user->id)
                    ->get();
        }

        if ($companyCount == 1) {
            $company = \App\Models\Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));
                if (in_array($roleName, ['sub_admin'])) {
                    $states = State::where('status', 1)
                    ->whereIn('id', $companyStates)
                    ->get();
                } else {
                    $states = empty($stateIds)
                        ? collect()
                        : State::where('status', 1)
                        ->whereIn('id', $stateIds)
                        ->get();
                }
            } else {
                $states = in_array($roleName, ['master_admin', 'sub_admin']) 
                        ? State::where('status', 1)->get()
                        : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
            }
        } else {
            $states = in_array($roleName, ['master_admin', 'sub_admin']) 
                    ? State::where('status', 1)->get()
                    : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
        }

        $partyQuery = Customer::where('is_active', true);
        if (!in_array($roleName, ['master_admin', 'sub_admin'])) {
            if (empty($stateIds)) {
                $partyQuery->whereRaw('1 = 0');
            } else {
                $partyQuery->whereHas('user', function ($q) use ($user, $stateIds) {
                    $q->whereIn('state_id', $stateIds)->where('reporting_to', $user->id);
                });
            }
        }
        $parties = $partyQuery->get();

        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;

        $query = Stock::with(['product', 'packing'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        if (!in_array($roleName, ['master_admin', 'sub_admin'])) {
            if (empty($stateIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('user', function ($q) use ($user, $stateIds) {
                    $q->whereIn('state_id', $stateIds)->where('reporting_to', $user->id);
                });
            }
        }

        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        if ($party_id) {
            $query->where('customer_id', $party_id);
        }
        if ($state_id) {
            $query->whereHas('customer', function($q) use ($state_id) {
                $q->where('state_id', $state_id);
            });
        }

        $stocks = $query->get();

        $reportData = [];
        $products = Product::with('packings')->get();

        foreach ($products as $product) {
            $reportData[$product->id] = [
                'name' => $product->product_name,
                'packings' => []
            ];
            foreach ($product->packings as $packing) {
                $packingStocks = $stocks->where('packing_id', $packing->id);
                
                $dailyData = [];
                $totalValues = [];
                foreach ($packingStocks as $stock) {
                    $day = Carbon::parse($stock->created_at)->format('d');
                    if (!isset($dailyData[$day])) {
                        $dailyData[$day] = 0;
                    }
                    $dailyData[$day] += $stock->quantity;
                    $totalValues[] = $stock->quantity; // track basic values for mock total
                }

                $opening = !empty($totalValues) ? $totalValues[0] : 0;
                $closing = !empty($totalValues) ? end($totalValues) : 0;
                $total = !empty($totalValues) ? array_sum($totalValues) : 0; // Or just sum of all entries

                $reportData[$product->id]['packings'][$packing->id] = [
                    'name' => trim($packing->packing_value . ' ' . $packing->packing_size),
                    'opening' => $opening,
                    'closing' => $closing,
                    'total' => $total,
                    'daily' => $dailyData
                ];
            }
        }

        return view('admin.stock.index', compact(
            'reportData', 'month', 'daysInMonth', 'startOfMonth', 
            'states', 'employees', 'parties', 
            'state_id', 'user_id', 'party_id'
        ));
    }
}
