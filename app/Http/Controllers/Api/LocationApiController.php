<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class LocationApiController extends BaseController
{
    //
    public function index()
    {
        $states = State::with(['districts.cities.tehsils'])
            ->get();

        return $this->sendResponse($states, 'Location data fetched successfully.');
    }
}
