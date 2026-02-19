<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PackingState;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPacking;
use App\Models\ProductPackingPrice;
use App\Models\ProductState;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'packings'])->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::where('status',1)->get();
        $companyCount = Company::count();
        $company = null;
        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));
                    $states = State::where('status', 1)
                        ->whereIn('id', $companyStates)
                        ->get();
            } 
        }else{
            $states = State::where('status', 1)->get();
        }
        return view('admin.products.create', compact('categories','states'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'technical_name' => 'required',
            // 'item_code' => 'required|unique:products,item_code',
            // 'product_category_id' => 'required',
            'shipper_gross_weight' => 'required',
            // 'master_packing' => 'required|in:Yes,No',
            'gst' => 'required',
            'product_states' => 'required|array',
            'packing_value' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            $product = Product::create([
                'product_name' => $request->product_name,
                'technical_name' => $request->technical_name,
                'item_code' => $request->item_code,
                'product_category_id' => $request->product_category_id,
                'shipper_gross_weight' => $request->shipper_gross_weight,
                'master_packing' => $request->master_packing,
                'gst' => $request->gst,
                'status' => 1
            ]);

            if($request->product_states){
                foreach ($request->product_states as $key => $value) {
                    if($value){
                        ProductState::create([
                            'product_id' => $product->id,
                            'state_id' => $value,
                        ]);

                    }

                }
            }
            foreach ($request->packing_value as $index => $value) {

                if (!$value) continue;

                $packing = $product->packings()->create([
                    'packing_value' => $value,
                    'packing_size' => $request->packing_size[$index],
                    'shipper_type' => $request->shipper_type[$index],
                    'shipper_size' => $request->shipper_size[$index],
                    'unit_in_shipper' => $request->unit_in_shipper[$index],
                    'status' => isset($request->packing_status[$index]) ? 1 : 0,
                ]);

                
                if(isset($request->packing_states[$index]) && is_array($request->packing_states[$index])) {
                    foreach ($request->packing_states[$index] as $stateId) {
                        if($stateId){
                            PackingState::create([
                                'packing_id' => $packing->id,
                                'state_id' => $stateId,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('products.index')
            ->with('success','Product created successfully');
    }

    public function edit(Product $product)
    {
        $product->load('packings.packingStates','productStates');
        $categories = ProductCategory::where('status',1)->get();
        $companyCount = Company::count();
        $company = null;
        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));
                    $states = State::where('status', 1)
                        ->whereIn('id', $companyStates)
                        ->get();
            } 
        }else{
            $states = State::where('status', 1)->get();
        }

        return view('admin.products.edit', compact('product', 'categories','states'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required',
            'technical_name' => 'required',
            // 'item_code' => 'required|unique:products,item_code,' . $product->id,
            // 'product_category_id' => 'required',
            'shipper_gross_weight' => 'required',
            // 'master_packing' => 'required|in:Yes,No',
            'gst' => 'required',
            'product_states' => 'required|array',
            'packing_value' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $product) {

            // Update product
            $product->update([
                'product_name' => $request->product_name,
                'technical_name' => $request->technical_name,
                'item_code' => $request->item_code,
                'product_category_id' => $request->product_category_id,
                'shipper_gross_weight' => $request->shipper_gross_weight,
                'master_packing' => $request->master_packing,
                'gst' => $request->gst,
                'status' => 1
            ]);

            // Update product states
            $product->productStates()->delete(); // delete old
            foreach ($request->product_states as $stateId) {
                if ($stateId) {
                    ProductState::create([
                        'product_id' => $product->id,
                        'state_id' => $stateId,
                    ]);
                }
            }

            // Update packings
            $product->packings()->delete(); // delete old packings

            foreach ($request->packing_value as $index => $value) {
                if (!$value) continue;

                $packing = $product->packings()->create([
                    'packing_value' => $value,
                    'packing_size' => $request->packing_size[$index],
                    'shipper_type' => $request->shipper_type[$index],
                    'shipper_size' => $request->shipper_size[$index],
                    'unit_in_shipper' => $request->unit_in_shipper[$index],
                    'status' => isset($request->packing_status[$index]) ? 1 : 0,
                ]);

                // Packing states
                if(isset($request->packing_states[$index]) && is_array($request->packing_states[$index])) {
                    foreach ($request->packing_states[$index] as $stateId) {
                        if($stateId){
                            PackingState::create([
                                'packing_id' => $packing->id,
                                'state_id' => $stateId,
                            ]);
                        }
                    }
                }
            }
        });

        return redirect()->route('products.index')
                        ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted');
    }

    public function priceList()
    {
        $companyCount = Company::count();
        $company = null;
        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));
                    $states = State::where('status', 1)
                        ->whereIn('id', $companyStates)
                        ->get();
            } 
        }else{
            $states = State::where('status', 1)->get();
        }

        $products = Product::with(['packings.prices'])->get();

        return view('admin.products.price_list', compact('products', 'states'));
    }

    public function priceStore(Request $request)
    {
        foreach ($request->prices as $packingId => $statePrices) {

            $packing = ProductPacking::with('packingStates')->find($packingId);
            if (!$packing) continue;

            $allowedStates = $packing->packingStates->pluck('state_id')->toArray();

            foreach ($statePrices as $stateId => $price) {

                if (!in_array($stateId, $allowedStates)) {
                    continue; // ❌ skip unauthorized state
                }

                ProductPackingPrice::updateOrCreate(
                    [
                        'packing_id' => $packingId,
                        'state_id'   => $stateId,
                    ],
                    [
                        'product_id'   => $request->product_id[$packingId],
                        'cash_price'   => $price['cash_price'] ?? null,
                        'credit_price' => $price['credit_price'] ?? null,
                    ]
                );
            }
        }

        return back()->with('success','Product prices saved successfully');
    }
}
