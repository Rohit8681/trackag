<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CropSubCategory;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FarmerController extends Controller
{
    public function cropSowingList(Request $request)
    {
        $query = CropSubCategory::select('id', 'name');

        if ($request->filled('crop_category_id')) {
            $query->where('crop_category_id', $request->crop_category_id);
        }

        $cropSowing = $query->where('status',1)->orderBy('name')->get();

        return response()->json([
            'status' => true,
            'data' => $cropSowing
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no'       => 'required|digits:10',
            'mobile_no_2'     => 'nullable|digits:10',
            'farmer_name'     => 'required|string',
            'village'         => 'required|string',
            'state_id'        => 'required|integer',
            'district_id'     => 'required|integer',
            'taluka_id'       => 'required|integer',
            'crop_sowing_id'  => 'required|integer',
            'land_acr'        => 'nullable|string',
            'irrigation_type' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $farmer = Farmer::create([
            'user_id'         => Auth::id() ?? $request->user_id,
            'mobile_no'       => $request->mobile_no,
            'mobile_no_2'     => $request->mobile_no_2,
            'farmer_name'     => $request->farmer_name,
            'village'         => $request->village,
            'state_id'        => $request->state_id,
            'district_id'     => $request->district_id,
            'taluka_id'       => $request->taluka_id,
            'crop_sowing_id'  => $request->crop_sowing_id,
            'land_acr'        => $request->land_acr,
            'irrigation_type' => $request->irrigation_type,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Farmer added successfully',
            'data' => $farmer
        ]);
    }

    public function index()
    {
        $farmers = Farmer::with([
                'state:id,name',
                'district:id,name',
                'taluka:id,name',
                'cropSowing:id,name'
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $farmers
        ]);
    }

}

