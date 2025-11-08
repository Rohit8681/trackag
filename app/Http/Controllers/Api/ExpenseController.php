<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Store expense via API
     */
    public function store(Request $request)
    {
        // ✅ Validation rules
        $validator = Validator::make($request->all(), [
            'bill_date' => 'required|date',
            'bill_type' => 'required|array', 
            'bill_details_description' => 'nullable|string',
            'bill_title' => 'nullable|string|max:255',
            'travel_mode_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['user_id'] = Auth::id() ?? $request->user_id; // for mobile app user_id

            // ✅ Handle file upload (if exists)
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('expenses', 'public');
                $data['image'] = basename($path);
            }

            // ✅ Store expense
            $expense = Expense::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Expense saved successfully',
                'data' => $expense,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get list of expenses (optional for mobile view)
     */
    public function index(Request $request)
    {
        $userId = Auth::id() ?? $request->user_id;

        $expenses = Expense::where('user_id', $userId)
            ->orderBy('bill_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $expenses,
        ]);
    }
}
