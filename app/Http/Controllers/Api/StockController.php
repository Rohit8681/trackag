<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Customer;
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

        $stocks = Stock::with(['product', 'packing', 'customer'])
            ->where('user_id', $userId)
            ->orderBy('stock_date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        $data = $stocks->groupBy(function ($stock) {
            return $stock->customer_id . '_' . $stock->stock_date;
        })->map(function ($group) {
            $firstStock = $group->first();
            $customer = $firstStock->customer;

            $products = $group->groupBy('product_id')->map(function ($productGroup) {
                $firstProductStock = $productGroup->first();
                $product = $firstProductStock->product;

                return [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'packings' => $productGroup->map(function ($stock) {
                        $packing = $stock->packing;
                        return [
                            'packing_id' => $packing->id,
                            'packing' => ($packing->packing_value ?? '') . ' ' . ($packing->packing_size ?? ''),
                            'stock' => $stock->quantity ?? 0,
                            'stock_date' => $stock->stock_date
                        ];
                    })->values()
                ];
            })->values();

            return [
                'customer_id' => $customer->id ?? null,
                'contact_person_name' => $customer->contact_person_name ?? null,
                'address' => $customer->address ?? null,
                'phone' => $customer->phone ?? null,
                'stock_date' => $firstStock->stock_date,
                'products' => $products
            ];
        })->values();

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
                        'stock_date' => date('Y-m-d'),
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

    // public function bulkUpdateStock(Request $request)
    // {
    //     $request->validate([
    //         'customer_id' => 'nullable|integer',
    //         'products' => 'required|array',
    //         'products.*.product_id' => 'required',
    //         'products.*.packings' => 'required|array',
    //         'products.*.packings.*.packing_id' => 'required',
    //         'products.*.packings.*.quantity' => 'required|integer|min:0',
    //     ]);

    //     $userId = Auth::id() ?? $request->user()->id ?? null;
    //     $customerId = $request->input('customer_id');

    //     if (!$userId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unauthorized user.'
    //         ], 401);
    //     }

    //     foreach ($request->products as $product) {
    //         foreach ($product['packings'] as $packing) {
    //             Stock::updateOrCreate(
    //                 [
    //                     'user_id' => $userId,
    //                     'customer_id' => $customerId,
    //                     'product_id' => $product['product_id'],
    //                     'packing_id' => $packing['packing_id'],
    //                 ],
    //                 [
    //                     'quantity' => $packing['quantity'],
    //                 ]
    //             );
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Stock updated successfully.'
    //     ]);
    // }

    public function bulkUpdateStock(Request $request)
    {
        \Log::info('Bulk Stock API Hit', [
            'request_data' => $request->all()
        ]);

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

        \Log::info('User & Customer Info', [
            'user_id' => $userId,
            'customer_id' => $customerId
        ]);

        if (!$userId) {
            \Log::error('Unauthorized user tried to update stock');

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.'
            ], 401);
        }

        foreach ($request->products as $product) {

            \Log::info('Processing Product', [
                'product_id' => $product['product_id']
            ]);

            foreach ($product['packings'] as $packing) {

                \Log::info('Processing Packing', [
                    'product_id' => $product['product_id'],
                    'packing_id' => $packing['packing_id'],
                    'quantity' => $packing['quantity']
                ]);

                try {
                    $stock = Stock::updateOrCreate(
                        [
                            'user_id' => $userId,
                            'customer_id' => $customerId,
                            'product_id' => $product['product_id'],
                            'packing_id' => $packing['packing_id'],
                            'stock_date' => date('Y-m-d'),
                        ],
                        [
                            'quantity' => $packing['quantity'],
                        ]
                    );

                    \Log::info('Stock Updated/Created', [
                        'stock_id' => $stock->id,
                        'data' => $stock
                    ]);

                } catch (\Exception $e) {
                    \Log::error('Stock Update Failed', [
                        'error' => $e->getMessage(),
                        'product_id' => $product['product_id'],
                        'packing_id' => $packing['packing_id']
                    ]);
                }
            }
        }

        \Log::info('Bulk Stock Update Completed');

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully.'
        ]);
    }
}
