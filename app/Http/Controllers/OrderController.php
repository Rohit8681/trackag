<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user:id,name','customer:id,agro_name','user.state:id,name'])
            ->latest();

        if ($request->from_date) {
            $query->whereDate('created_at','>=',$request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at','<=',$request->to_date);
        }

        if ($request->state_id) {
            $query->whereHas('user.state', function($q) use ($request){
                $q->where('id',$request->state_id);
            });
        }

        if ($request->user_id) {
            $query->where('user_id',$request->user_id);
        }

        if ($request->party_id) {
            $query->where('party_id',$request->party_id);
        }

        if ($request->order_no) {
            $query->where('order_no',$request->order_no);
        }

        if ($request->status) {
            $query->where('status',$request->status);
        }

        $orders = $query->paginate(20);

        return view('admin.order.index',compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'status' => 'required'
        ]);

        $order = Order::findOrFail($request->order_id);

        // HOLD & REJECT → Remark required
        if (in_array($request->status,['HOLD','REJECT'])) {
            if (!$request->remark) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Remark required'
                ]);
            }
            $order->remark = $request->remark;
        }

        // DISPATCH DETAILS
        if (in_array($request->status,['PART DISPATCHED','DISPATCHED'])) {
            $order->lr_number = $request->lr_number;
            $order->transport_name = $request->transport_name;
            $order->destination = $request->destination;
            $order->dispatch_date = now();
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'status'=>true,
            'message'=>'Status Updated Successfully'
        ]);
    }

    
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
