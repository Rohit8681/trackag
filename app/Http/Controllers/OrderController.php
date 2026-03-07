<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Depo;
use App\Models\Order;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->getRoleNames()->first();
        $query = Order::with([
            'user.state',
            'customer',
            'depo',
            'items.product',
            'items.packing'
        ])->latest();

        if ($request->filled('state_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }

        if ($request->filled('product')) {
            $query->whereHas('items.product', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->product}%");
            });
        }

        if ($request->filled('packing')) {
            $query->whereHas('items.packing', function ($q) use ($request) {
                $q->where('packing_size', 'like', "%{$request->packing}%");
            });
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('order_no')) {
            $query->where('order_no', 'like', "%{$request->order_no}%");
        }

        if ($request->filled('depo_id')) {
            $query->where('depo_id', $request->depo_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        $companyCount = Company::count();
        $company = null;

        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));

                if ($roleName === 'sub_admin') {
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
                    : (empty($stateIds)
                        ? collect()
                        : State::where('status', 1)->whereIn('id', $stateIds)->get());
            }
        } else {
            $states = in_array($roleName, ['master_admin', 'sub_admin'])
                ? State::where('status', 1)->get()
                : (empty($stateIds)
                    ? collect()
                    : State::where('status', 1)->whereIn('id', $stateIds)->get());
        }
        // $employees = User::select('id', 'name')->get();
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

        $customer = Customer::where('is_active', true)->get();
        $depos = Depo::where('status',1)->get();


        return view('admin.order.index', [
            'orders' => $orders,
            'states' => $states,
            'users' => $employees,
            'customers' => $customer,
            'depos' => $depos,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id'       => 'required',
            'status'         => 'required',
            'dispatch_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $order = Order::findOrFail($request->order_id);
        // hold / rejected → remark required
        if (in_array($request->status, ['hold','rejected'])) {
            if (!$request->remark) {
                return response()->json([
                    'status' => false,
                    'message' => 'Remark required'
                ]);
            }
            $order->remark = $request->remark;
        }
        // DISPATCH DETAILS
        if (in_array($request->status, ['part_dispatched','dispatched'])) {

            $order->lr_number       = $request->lr_number;
            $order->transport_name  = $request->transport_name;
            $order->destination     = $request->destination;
            $order->dispatch_date   = now();

            if ($request->hasFile('dispatch_image')) {
                $path = $request->file('dispatch_image')->store('dispatch_images', 'public');
                $order->dispatch_image = $path;
            } else if (!$order->dispatch_image) {
                return response()->json([
                    'status' => false,
                    'message' => 'Dispatch Image is required'
                ]);
            }
        }
        // UPDATE STATUS
        $order->status = $request->status;

        $order->save();


        return response()->json([
            'status'  => true,
            'message' => 'Status Updated Successfully'
        ]);

    }

    public function updateItem(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:order_items,id',
            'price'    => 'required|numeric|min:0',
            'gst'      => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'qty'      => 'required|integer|min:1',
        ]);

        $item = \App\Models\OrderItem::findOrFail($request->item_id);
        
        $item->price = $request->price;
        $item->gst = $request->gst;
        $item->discount = $request->discount;
        $item->qty = $request->qty;
        
        $amount = $item->price * $item->qty;
        $amountAfterDiscount = $amount - $item->discount;
        $gstAmount = ($amountAfterDiscount * $item->gst) / 100;
        
        $item->grand_total = round($amountAfterDiscount + $gstAmount, 2);
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully',
            'data'    => [
                'grand_total' => number_format($item->grand_total, 2, '.', '')
            ]
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
