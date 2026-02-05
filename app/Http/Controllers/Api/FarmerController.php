<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CropSubCategory;
use App\Models\Farmer;
use App\Models\FarmerCropSowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'crop_sowing_id'      => 'required|array',
            // 'crop_sowing_id.*'    => 'integer|exists:crop_sowings,id',  
            'land_acr'        => 'nullable|string',
            'irrigation_type' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false,'errors' => $validator->errors()], 422);
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
            // 'crop_sowing_id'  => $request->crop_sowing_id,
            'land_acr'        => $request->land_acr,
            'land_acr_size'   => $request->land_acr_size,
            'irrigation_type' => $request->irrigation_type,
        ]);

        foreach ($request->crop_sowing_id as $cropSowingId) {
            FarmerCropSowing::create([
                'farmer_id'      => $farmer->id,
                'crop_sowing_id' => $cropSowingId,
            ]);
        }

        return response()->json(['status' => true,'message' => 'Farmer added successfully','data' => $farmer]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no'       => 'required|digits:10',
            'mobile_no_2'     => 'nullable|digits:10',
            'farmer_name'     => 'required|string',
            'village'         => 'required|string',
            'state_id'        => 'required|integer',
            'district_id'     => 'required|integer',
            'taluka_id'       => 'required|integer',
            'crop_sowing_id'  => 'required|array',
            // 'crop_sowing_id.*'=> 'integer|exists:crop_sowings,id',
            'land_acr'        => 'nullable|string',
            'irrigation_type' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $farmer = Farmer::find($id);

        if (!$farmer) {
            return response()->json([
                'status' => false,
                'message' => 'Farmer not found'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // ✅ Update farmer data
            $farmer->update([
                'mobile_no'       => $request->mobile_no,
                'mobile_no_2'     => $request->mobile_no_2,
                'farmer_name'     => $request->farmer_name,
                'village'         => $request->village,
                'state_id'        => $request->state_id,
                'district_id'     => $request->district_id,
                'taluka_id'       => $request->taluka_id,
                'land_acr'        => $request->land_acr,
                'land_acr_size'   => $request->land_acr_size,
                'irrigation_type' => $request->irrigation_type,
            ]);

            // ✅ Old crop sowing delete
            FarmerCropSowing::where('farmer_id', $farmer->id)->delete();

            // ✅ Insert new crop sowing using foreach
            foreach ($request->crop_sowing_id as $cropSowingId) {
                FarmerCropSowing::create([
                    'farmer_id'      => $farmer->id,
                    'crop_sowing_id' => $cropSowingId,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Farmer updated successfully',
                'data'    => $farmer
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $farmers = Farmer::with([
                'state:id,name',
                'district:id,name',
                'taluka:id,name',
                'cropSowings.crop:id,name' // 👈 nested relation
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($farmer) {

                // 👇 get crop names from pivot table
                $farmer->crop_sowing = $farmer->cropSowings
                    ->pluck('crop.name')
                    ->implode(', ');

                unset($farmer->cropSowings);

                return $farmer;
            });

        return response()->json([
            'status' => true,
            'data'   => $farmers
        ]);
    }

}

