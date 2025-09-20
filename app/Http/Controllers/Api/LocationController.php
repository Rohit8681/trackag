<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\City;
use App\Models\Tehsil;
use App\Models\Pincode;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends BaseController
{
    public function getDistricts(Request $request, $state_id)
    {
        return response()->json(District::where('state_id', $state_id)->get());
    }

    public function getCities(Request $request, $district_id)
    {
        return response()->json(City::where('district_id', $district_id)->get());
    }

    public function getTehsils(Request $request, $city_id)
    {
        return response()->json(Tehsil::where('city_id', $city_id)->get());
    }

    public function getPincodes(Request $request, $city_id)
    {
        return response()->json(Pincode::where('city_id', $city_id)->get());
    }

    public function index()
    {
        $states = State::with([
            'cities.districts.tehsils' // Correct relationship chain
        ])
            ->get();
        return $this->sendResponse($states, 'User detail fetched successfully.');
    }
}
