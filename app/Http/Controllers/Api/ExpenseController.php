<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\TaDaTourSlab;
use App\Models\TaDaVehicleSlab;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class ExpenseController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'main_id' => 'nullable|integer',
            'bill_date' => 'required|date',
            'bill_type' => 'required|string|max:255',
            'bill_title' => 'nullable|string|max:255',
            'bill_details_description' => 'nullable|string',
            'travel_mode' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $data = $validator->validated();
            $data['user_id'] = $user->id ?? $request->user_id;

            $tripExists = Trip::where('user_id', $data['user_id'])
                ->where('trip_date', $data['bill_date'])
                ->exists();

            if (!$tripExists) {
                return response()->json([
                    'status' => false,
                    'message' => 'No trip found for this user on the given bill date.'
                ], 422);
            }

            if (!empty($request->main_id)) {

                $expense = Expense::find($request->main_id);

                if (!$expense) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Expense not found'
                    ], 404);
                }

                if ($request->hasFile('image')) {
                    if ($expense->image && file_exists(storage_path('app/public/expenses/' . $expense->image))) {
                        unlink(storage_path('app/public/expenses/' . $expense->image));
                    }

                    $path = $request->file('image')->store('expenses', 'public');
                    $data['image'] = basename($path);
                }

                $expense->update($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Expense updated successfully',
                    'data' => $expense
                ]);

            } else {
                if ($request->hasFile('image')) {
                    $path = $request->file('image')->store('expenses', 'public');
                    $data['image'] = basename($path);
                }

                $expense = Expense::create($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Expense added successfully',
                    'data' => $expense
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function index(Request $request)
    {
        $userId = Auth::user()->id ?? $request->user_id;

        $expenses = Expense::with(['travelMode'])      
            ->where('user_id', $userId)
            ->orderBy('bill_date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'bill_title' => $item->bill_title,
                    'bill_date' => Carbon::parse($item->bill_date)->format('Y-m-d'),
                    'bill_type' => $item->bill_type,
                    'bill_details_description' => $item->bill_details_description,
                    // 'travel_mode_id' => $item->travel_mode_id,
                    'travel_mode_name' => $item->travel_mode ?? null,
                    'amount' => $item->amount,
                    'image_url' => $item->image ? asset('storage/expenses/' . $item->image) : null,
                    'approval_status' => $item->approval_status,
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $expenses,
        ]);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'status' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        try {
            // delete image
            if ($expense->image && file_exists(storage_path('app/public/expenses/' . $expense->image))) {
                unlink(storage_path('app/public/expenses/' . $expense->image));
            }

            $expense->delete();

            return response()->json([
                'status' => true,
                'message' => 'Expense deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting expense',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function taDaReport(Request $request)
    {
        $userId = Auth::user()->id ?? $request->user_id;

        $year  = $request->year;   // 2025
        $month = $request->month;
        
        $from = Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('Y-m-d');
        $to = Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('Y-m-d');

        $query = Trip::where('user_id',$userId)->with(['user', 'company', 'approvedByUser', 'tripLogs', 'customers', 'travelMode', 'tourType'])
            ->where('approval_status', 'approved')
            ->whereBetween('trip_date', [$from, $to]);

        $data = $query->latest()->get();

        foreach ($data as $item) {

            $slabType = $item->user->slab ?? "";

            $da_amount = null;
            $ta_amount = null;

            if ($slabType == "Slab Wise") {
                $da_amount = TaDaTourSlab::where('tour_type_id', $item->tour_type)
                    ->whereNull('user_id')
                    ->where('designation_id', $item->user->slab_designation_id)
                    ->first();

                $ta_amount = TaDaVehicleSlab::where('travel_mode_id', $item->travel_mode)
                    ->whereNull('user_id')
                    ->where('designation_id', $item->user->slab_designation_id)
                    ->first();
            }

            if ($slabType == "Individual") {
                $da_amount = TaDaTourSlab::where('tour_type_id', $item->tour_type)
                    ->where('user_id', $item->user->id)
                    ->first();

                $ta_amount = TaDaVehicleSlab::where('travel_mode_id', $item->travel_mode)
                    ->where('user_id', $item->user->id)
                    ->first();
            }

            $expense = Expense::where('user_id', $item->user_id)
                ->whereDate('bill_date', $item->trip_date)
                ->where('approval_status', 'Approved')
                ->get();

            $total_km = ($item->end_km - $item->starting_km);

            $item->ta_exp = ($ta_amount->travelling_allow_per_km ?? 0) * $total_km;
            $item->da_exp = $da_amount->da_amount ?? 0;
            $item->other_exp = $expense->sum('amount') ?? 0;
            $item->gps_travel_km =  0;

            $item->total_exp =
                $item->ta_exp +
                $item->da_exp +
                $item->other_exp;
        }
        $total_travel_km = $data->sum(fn ($i) => $i->end_km - $i->starting_km);
        $total_gps_travel_km = 0;
        $total_ta = $data->sum('ta_exp');
        $total_da = $data->sum('da_exp');
        $total_other = $data->sum('other_exp');
        $total_total = $data->sum('total_exp');

        return response()->json([
            'status' => true,
            'message' => 'TA-DA Report Summary',
            'data' => [
                'total_travel_km'   => $total_travel_km,
                'gps_travel_km'     => $total_gps_travel_km,
                'ta_allowance'      => $total_ta,
                'da_allowance'      => $total_da,
                'other_expense'     => $total_other,
                'total'             => $total_total,
            ]
        ]);
    }
}
