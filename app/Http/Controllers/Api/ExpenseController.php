<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class ExpenseController extends Controller
{
    // public function store(Request $request)
    // {
    //     $user = Auth::user(); 
    //     $validator = Validator::make($request->all(), [
    //         'bill_date' => 'required|date',
    //         'bill_type' => 'required|string|max:255', 
    //         'bill_title' => 'nullable|string|max:255',
    //         'bill_details_description' => 'nullable|string',
    //         'travel_mode' => 'nullable|string',
    //         'amount' => 'required|numeric|min:0',
    //         'image' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['status' => false,'message' => 'Validation failed','errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     try {
    //         $data = $validator->validated();
    //         $data['user_id'] = $user->id ?? $request->user_id; 
            
    //         if ($request->hasFile('image')) {
    //             $path = $request->file('image')->store('expenses', 'public');
    //             $data['image'] = basename($path);
    //         }

    //         $expense = Expense::create($data);

    //         return response()->json(['status' => true,'message' => 'Expense saved successfully','data' => $expense,
    //         ], 200);

    //     } catch (\Exception $e) {
    //         return response()->json(['status' => false,'message' => 'Something went wrong','error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    
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
}
