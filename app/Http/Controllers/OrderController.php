<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Depo;
use App\Models\Order;
use App\Models\OrderDispatch;
use App\Models\OrderDispatchDetail;
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
            'items.packing',
            'items.dispatches',
            'dispatches.detail'
        ])->latest();

        // NEW DATE FILTER
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

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
                $q->where('product_name', 'like', "%{$request->product}%");
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
        $depos = Depo::where('status', 1)->get();

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
        if (in_array($request->status, ['hold', 'rejected'])) {
            if (!$request->remark) {
                return response()->json([
                    'status' => false,
                    'message' => 'Remark required'
                ]);
            }
            $order->remark2 = $request->remark;
        }
        // DISPATCH DETAILS
        if (in_array($request->status, ['part_dispatched', 'dispatched'])) {

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

        // Send Push Notification
        // try {
        //     $order->loadMissing('user');
        //     if ($order->user && !empty($order->user->fcm_token)) {
        //         $firebaseService = app(\App\Services\FirebaseService::class);
        //         $title = "Order #{$order->order_no} Status Updated";
                
        //         $statusMessage = "Your order status has been updated to {$order->status}.";
        //         if ($order->status === 'part_dispatched') {
        //             $statusMessage = "Your order is partially dispatched.";
        //         } elseif ($order->status === 'dispatched') {
        //             $statusMessage = "Your order is now fully dispatched.";
        //         } elseif ($order->status === 'hold') {
        //             $statusMessage = "Your order has been placed on hold.";
        //         } elseif ($order->status === 'rejected') {
        //             $statusMessage = "Your order has been rejected.";
        //         } elseif ($order->status === 'approved') {
        //             $statusMessage = "Your order has been approved.";
        //         } elseif ($order->status === 'delivered') {
        //             $statusMessage = "Your order has been delivered.";
        //         }

        //         $firebaseService->sendNotification($order->user->fcm_token, $title, $statusMessage, [
        //             'order_id' => (string) $order->id,
        //             'status' => $order->status
        //         ]);
        //     }
        // } catch (\Exception $e) {
        //     \Illuminate\Support\Facades\Log::error('Failed to send push notification: ' . $e->getMessage());
        // }

        return response()->json([
            'status'  => true,
            'message' => 'Status Updated Successfully'
        ]);
    }

    public function updateItem(Request $request)
    {
        $request->validate([
            'item_id'  => 'required',
            'shipper_size' => 'required|integer|min:0',
        ]);

        $item = \App\Models\OrderItem::with(['order', 'product'])->findOrFail($request->item_id);

        if ($item->order->status === 'approved' || !in_array($item->order->status, ['pending', 'hold'])) {
            return response()->json([
                'success' => false,
                'message' => 'Approved orders cannot be edited'
            ]);
        }

        $productGst = $item->product ? $item->product->gst : 0;

        $item->shipper_size = $request->shipper_size;

        $amount = $item->price * $item->shipper_size;
        $amountAfterDiscount = $amount - $item->discount;
        if ($amountAfterDiscount < 0) $amountAfterDiscount = 0;
        $gstAmount = ($amountAfterDiscount * $productGst) / 100;

        $item->gst = $gstAmount;
        $item->total_price = $amount;
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

    public function updateOrderItemsQty(Request $request)
    {
        $request->validate([
            'order_id'       => 'required',
            'items'          => 'required|array',
            'items.*.id'     => 'required',
            'items.*.shipper_size' => 'required|integer|min:0',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Items can only be edited when the order is pending.'
            ]);
        }

        foreach ($request->items as $itemData) {
            $item = \App\Models\OrderItem::with('product')->where('order_id', $order->id)
                ->where('id', $itemData['id'])
                ->first();

            if ($item) {
                $productGst = $item->product ? $item->product->gst : 0;

                $item->shipper_size = $itemData['shipper_size'];
                $amount = $item->price * $item->shipper_size;
                $amountAfterDiscount = $amount - $item->discount;
                if ($amountAfterDiscount < 0) $amountAfterDiscount = 0;
                $gstAmount = ($amountAfterDiscount * $productGst) / 100;
                
                $item->gst = $gstAmount;
                $item->total_price = $amount;
                $item->grand_total = round($amountAfterDiscount + $gstAmount, 2);
                $item->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Order items updated successfully'
        ]);
    }

    public function getDispatchData(Order $order)
    {
        $order->load(['items.product', 'items.packing', 'customer', 'dispatches.detail', 'items.dispatches']);
        
        $itemsData = $order->items->map(function ($item) {
            $totalDispatched = $item->dispatches->sum('dispatch_qty');
            $pendingQty = $item->shipper_size - $totalDispatched;
            
            return [
                'id' => $item->id,
                'product' => optional($item->product)->product_name,
                'packing_value' => optional($item->packing)->packing_value,
                'packing' => optional($item->packing)->packing_size,
                'price' => $item->price,
                'gst_percent' => optional($item->product)->gst ?? 0,
                'gst' => $item->gst,
                'discount' => $item->discount,
                'order_qty' => $item->qty,
                'dispatched_qty' => $totalDispatched,
                'pending_qty' => $pendingQty,
                'shipper_size' => $item->shipper_size,
                'grand_total' => $item->grand_total
            ];
        });

        return response()->json([
            'status' => true,
            'order' => [
                'id' => $order->id,
                'order_no' => $order->order_no,
                'status' => $order->status
            ],
            'items' => $itemsData,
            'dispatches' => $order->dispatches
        ]);
    }

    public function storeDispatch(Request $request)
    {
        $request->validate([
            'order_id'       => 'required',
            'dispatch_items' => 'required|array',
            'dispatch_items.*.item_id' => 'required',
            'dispatch_items.*.dispatch_qty' => 'required|integer|min:0',
            'lr_number'      => 'nullable|string',
            'transport_name' => 'nullable|string',
            'vehicle_no'     => 'nullable|string',
            'dispatch_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $order = Order::with('items')->findOrFail($request->order_id);
        
        $imagePath = null;
        if ($request->hasFile('dispatch_image')) {
            $imagePath = $request->file('dispatch_image')->store('dispatch_images', 'public');
        }

        $totalItemsPendingAfterDispatch = 0;
        $anyDispatched = false;

        foreach ($request->dispatch_items as $dispatchItem) {
            if ($dispatchItem['dispatch_qty'] > 0) {
                // Determine remaining pending qty locally for the type
                $item = $order->items->where('id', $dispatchItem['item_id'])->first();
                $totalDispatchedBefore = OrderDispatch::where('order_item_id', $dispatchItem['item_id'])->sum('dispatch_qty');
                $pendingBefore = $item ? ($item->shipper_size - $totalDispatchedBefore) : 0;
                $willBeZero = ($pendingBefore - $dispatchItem['dispatch_qty']) <= 0;

                $disp = OrderDispatch::create([
                    'order_id' => $order->id,
                    'order_item_id' => $dispatchItem['item_id'],
                    'dispatch_qty' => $dispatchItem['dispatch_qty'],
                    'dispatch_type' => $willBeZero ? 'final' : 'partial',
                ]);
                OrderDispatchDetail::create([
                    'order_dispatch_id' => $disp->id,
                    'lr_number' => $request->lr_number,
                    'transport_name' => $request->transport_name,
                    'vehicle_no' => $request->vehicle_no,
                    'dispatch_date' => now(),
                    'dispatch_image' => $imagePath
                ]);
                $anyDispatched = true;
            }
            // Check remaining qty for this item
            $item = $order->items->where('id', $dispatchItem['item_id'])->first();
            if ($item) {
                // Total previously dispatched + this dispatch
                $totalDispatched = OrderDispatch::where('order_item_id', $item->id)->sum('dispatch_qty');
                $pending = $item->shipper_size - $totalDispatched;
                if ($pending > 0) {
                    $totalItemsPendingAfterDispatch += $pending;
                }
            }
        }

        if ($anyDispatched) {
            if ($totalItemsPendingAfterDispatch == 0) {
                $order->status = 'dispatched';
            } else {
                $order->status = 'part_dispatched';
            }
            $order->save();

            // Send Push Notification
            // try {
            //     $order->loadMissing('user');
            //     if ($order->user && !empty($order->user->fcm_token)) {
            //         $firebaseService = app(\App\Services\FirebaseService::class);
            //         $title = "Order #{$order->order_no} Status Updated";
                    
            //         $statusMessage = $order->status === 'dispatched' 
            //             ? "Your order is now fully dispatched." 
            //             : "Your order is partially dispatched.";

            //         $firebaseService->sendNotification($order->user->fcm_token, $title, $statusMessage, [
            //             'order_id' => (string) $order->id,
            //             'status' => $order->status
            //         ]);
            //     }
            // } catch (\Exception $e) {
            //     \Illuminate\Support\Facades\Log::error('Failed to send push notification on dispatch: ' . $e->getMessage());
            // }
        }

        return response()->json([
            'status' => true,
            'message' => 'Dispatch saved successfully'
        ]);
    }

}
