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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bill_date' => 'required|date',
            'bill_type' => 'required|array', 
            'bill_title' => 'nullable|string|max:255',
            'bill_details_description' => 'nullable|string',
            'travel_mode_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:0',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false,'message' => 'Validation failed','errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['user_id'] = Auth::id() ?? $request->user_id; 
            dd($data['user_id']);
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('expenses', 'public');
                $data['image'] = basename($path);
            }

            $expense = Expense::create($data);

            return response()->json(['status' => true,'message' => 'Expense saved successfully','data' => $expense,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['status' => false,'message' => 'Something went wrong','error' => $e->getMessage(),
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
                    'bill_date' => $item->bill_date, // format for app
                    'bill_type' => $item->bill_type,
                    'bill_details_description' => $item->bill_details_description,
                    'travel_mode_id' => $item->travel_mode_id,
                    'travel_mode_name' => $item->travelMode->name ?? null,
                    'amount' => $item->amount,
                    'image_url' => $item->image ? asset('storage/expenses/' . $item->image) : null,
                    'created_at' => $item->created_at,
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $expenses,
        ]);
    }
}
