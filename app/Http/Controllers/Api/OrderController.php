<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPacking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function getProductList(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:cash,debit'
        ]);

        $user = Auth::user();
        $userState = $user->state_id;
        $orderType = $request->order_type;

        $products = Product::where('status', 1)

            ->whereHas('productStates', function ($query) use ($userState, $orderType) {

                $query->where('state_id', $userState);

                if ($orderType == 'cash') {
                    $query->where('is_ncr', 1);   // cash
                }

                if ($orderType == 'debit') {
                    $query->where('is_rpl', 1);   // debit
                }
            })

            ->select('id', 'product_name')
            ->get();

        return response()->json([
            'status' => true,
            'order_type' => $orderType,
            'state_id' => $userState,
            'data' => $products
        ]);
    }

    public function getProductPackings(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_type' => 'required|in:cash,debit'
        ]);

        $user = Auth::user();
        $stateId = $user->state_id;
        $productId = $request->product_id;
        $orderType = $request->order_type;

        $packings = ProductPacking::where('product_id', $productId)
            ->where('status', 1)

            // ✅ State wise packing check
            ->whereHas('packingStates', function ($q) use ($stateId) {
                $q->where('state_id', $stateId);
            })

            // ✅ Price relation load with state filter
            ->with(['prices' => function ($q) use ($stateId) {
                $q->where('state_id', $stateId);
            }])

            ->get()
            ->map(function ($packing) use ($orderType) {

                $priceData = $packing->prices->first();

                return [
                    'packing_id' => $packing->id,
                    'packing_value' => $packing->packing_value,
                    'packing_size' => $packing->packing_size,
                    'shipper_type' => $packing->shipper_type,
                    'shipper_size' => $packing->shipper_size,
                    'unit_in_shipper' => $packing->unit_in_shipper,

                    // ✅ Cash / Debit price logic
                    'price' => $orderType == 'cash'
                                ? ($priceData->cash_price ?? 0)
                                : ($priceData->credit_price ?? 0)
                ];
            });

        return response()->json([
            'status' => true,
            'product_id' => $productId,
            'state_id' => $stateId,
            'order_type' => $orderType,
            'data' => $packings
        ]);
    }

    public function index()
    {
        $orders = Order::with([
            'customer:id,agro_name',
            'items.product:id,product_name',
            'items.packing:id,packing_value,packing_size'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        $data = $orders->map(function ($order) {

            return [
                'order_id' => $order->id,
                'order_no' => $order->order_no,
                'party_id' => $order->party_id,
                'party_name' => $order->customer->agro_name ?? null,  // 🔥 Agro Name

                'order_type' => $order->order_type,
                'depo_id' => $order->depo_id,
                'delivery_place' => $order->delivery_place,
                'preferred_transport' => $order->preferred_transport,
                'remark' => $order->remark,
                'status' => $order->status,
                'created_at' => $order->created_at->format('d-m-Y'),

                'products' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product->product_name ?? null,
                        'packing_value' => $item->packing->packing_value ?? null,
                        'packing_size' => $item->packing->packing_size ?? null,
                        'shipper_size' => $item->shipper_size,
                        'price' => $item->price,
                        'total_price' => $item->total_price,
                        'gst' => $item->gst,
                        'discount' => $item->discount,
                        'grand_total' => $item->grand_total,
                    ];
                })
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required',
            'order_type' => 'required|in:cash,debit',
            'products' => 'required|array|min:1',
        ]);
        $lastOrder = Order::latest()->first();
        $nextNumber = 1;

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_no, -4);
            $nextNumber = $lastNumber + 1;
        }

        $orderNo = 'ORD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 🔹 Create Order (Common fields)
        $order = Order::create([
            'order_no' => $orderNo,
            'user_id' => Auth::id(),
            'party_id' => $request->party_id,
            'order_type' => $request->order_type,
            'depo_id' => $request->depo_id,
            'delivery_place' => $request->delivery_place,
            'preferred_transport' => $request->preferred_transport,
            'remark' => $request->remark,
            'status' => 'pending'
        ]);

        // 🔹 Store Multiple Products
        foreach ($request->products as $item) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'packing_id' => $item['packing_id'] ?? null,
                'shipper_size' => $item['shipper_size'] ?? null,
                'price' => $item['price'],
                'total_price' => $item['total_price'],
                'gst' => $item['gst'] ?? 0,
                'discount' => $item['discount'] ?? 0,
                'grand_total' => $item['grand_total'],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order Created Successfully',
            'order_id' => $order->id
        ]);
    }
}
