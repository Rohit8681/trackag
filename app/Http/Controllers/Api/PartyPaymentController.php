<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PartyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PartyPaymentController extends BaseController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = PartyPayment::with(['customer', 'user'])->where('user_id',$user->id)->orderBy('id', 'desc')->get();

        return $this->sendResponse($data, "Party payment list fetched successfully");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required|integer',
            'payment_mode'  => 'required|string',
            'bank_name'     => 'nullable|string',
            'branch_name'   => 'nullable|string',
            'payment_date'  => 'required|date_format:Y-m-d',
            'amount'        => 'required|numeric',
            'remark'        => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('party-payments', 'public');
        }

        $payment = PartyPayment::create([
            'user_id' => Auth::user()->id,
            'customer_id'  => $request->customer_id,
            'payment_mode' => $request->payment_mode,
            'bank_name'    => $request->bank_name,
            'branch_name'  => $request->branch_name,
            'payment_date' => $request->payment_date,
            'amount'       => $request->amount,
            'remark'       => $request->remark,
            'image'        => $imagePath,
        ]);

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Party payment stored successfully',
        //     'data' => $payment
        // ], 201);
        return $this->sendResponse($payment, "Party payment stored successfully");
    }
}
