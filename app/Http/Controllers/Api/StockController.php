<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function getProductPackingList()
    {
        $products = Product::with(['packings' => function ($q) {
            $q->select('id', 'product_id', 'packing_value', 'packing_size')
            ->where('status', 1);
        }])
        ->where('status', 1)
        ->get();

        $data = $products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'packings' => $product->packings->map(function ($packing) {
                    return [
                        'packing_id' => $packing->id,
                        'packing_value' => $packing->packing_value,
                        'packing_size' => $packing->packing_size,
                        'label' => $packing->packing_value . ' ' . $packing->packing_size,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getStockList(Request $request)
    {
        $userId = Auth::id() ?? $request->user()->id ?? null;
        $customerId = $request->input('customer_id');

        $products = Product::with([
            'packings' => function ($q) {
                $q->select('id', 'product_id', 'packing_value', 'packing_size')
                  ->where('status', 1);
            },
            'packings.stock' => function ($query) use ($userId, $customerId) {
                $query->where('user_id', $userId);
                if ($customerId) {
                    $query->where('customer_id', $customerId);
                } else {
                    $query->whereNull('customer_id');
                }
            }
        ])
        ->where('status', 1)
        ->get();

        $data = $products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'packings' => $product->packings->map(function ($packing) {
                    return [
                        'packing_id' => $packing->id,
                        'packing' => $packing->packing_value . ' ' . $packing->packing_size,
                        'stock' => $packing->stock->quantity ?? 0
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function bulkStoreStock(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|integer',
            'products' => 'required|array',
            'products.*.product_id' => 'required',
            'products.*.packings' => 'required|array',
            'products.*.packings.*.packing_id' => 'required',
            'products.*.packings.*.quantity' => 'required|integer|min:0',
        ]);

        $userId = Auth::id() ?? $request->user()->id ?? null;
        $customerId = $request->input('customer_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.'
            ], 401);
        }

        foreach ($request->products as $product) {
            foreach ($product['packings'] as $packing) {
                Stock::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'customer_id' => $customerId,
                        'product_id' => $product['product_id'],
                        'packing_id' => $packing['packing_id'],
                    ],
                    [
                        'quantity' => $packing['quantity'],
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully.'
        ]);
    }
}
