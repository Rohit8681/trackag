<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Farmer;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = Auth::user();
    //     $roleName = $user->getRoleNames()->first();
    //     $companyCount = Company::count();
    //     $company = null;

    //     if ($companyCount == 1) {
    //         $company = Company::first();

    //         if ($company && !empty($company->state)) {
    //             $companyStates = array_map('intval', explode(',', $company->state));

    //             if ($roleName === 'sub_admin') {
    //                 $states = State::where('status', 1)
    //                     ->whereIn('id', $companyStates)
    //                     ->get();
    //             } else {
    //                 $states = empty($stateIds)
    //                     ? collect()
    //                     : State::where('status', 1)
    //                         ->whereIn('id', $stateIds)
    //                         ->get();
    //             }
    //         } else {
    //             $states = in_array($roleName, ['master_admin', 'sub_admin'])
    //                 ? State::where('status', 1)->get()
    //                 : (empty($stateIds)
    //                     ? collect()
    //                     : State::where('status', 1)->whereIn('id', $stateIds)->get());
    //         }
    //     } else {
    //         $states = in_array($roleName, ['master_admin', 'sub_admin'])
    //             ? State::where('status', 1)->get()
    //             : (empty($stateIds)
    //                 ? collect()
    //                 : State::where('status', 1)->whereIn('id', $stateIds)->get());
    //     }

    //     $query = Farmer::with([
    //         'user:id,name',
    //         'state:id,name',
    //         'district:id,name',
    //         'taluka:id,name',
    //         'cropSowing:id,name'
    //     ]);

    //     // 🔍 Filters
    //     if ($request->filled('farmer_name')) {
    //         $query->where('farmer_name', 'like', '%' . $request->farmer_name . '%');
    //     }

    //     if ($request->filled('mobile_no')) {
    //         $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
    //     }

    //     if ($request->filled('state_id')) {
    //         $query->where('state_id', $request->state_id);
    //     }

    //     $farmers = $query->latest()->get();

    //     return view('admin.farmers.index', compact(
    //         'farmers',
    //         'states'
    //     ));
    // }


    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->getRoleNames()->first();

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
                    $states = State::where('status', 1)->get();
                }
            } else {
                $states = State::where('status', 1)->get();
            }
        } else {
            $states = State::where('status', 1)->get();
        }

        $query = Farmer::with([
            'user:id,name',
            'state:id,name',
            'district:id,name',
            'taluka:id,name',
            // 'cropSowing:id,name'
            'cropSowings.crop:id,name' // 👈 IMPORTANT
        ]);

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // 🔍 FARMER NAME
        if ($request->filled('farmer_name')) {
            $query->where('farmer_name', 'like', '%' . $request->farmer_name . '%');
        }

        // 🔍 MOBILE NUMBER
        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

        // 🔍 STATE
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        // 🔍 SALES PERSON (USER NAME)
        if ($request->filled('sales_person')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->sales_person . '%');
            });
        }

        // 🔍 CROP NAME (MANUAL TYPE)
        // if ($request->filled('crop_name')) {
        //     $query->whereHas('cropSowing', function ($q) use ($request) {
        //         $q->where('name', 'like', '%' . $request->crop_name . '%');
        //     });
        // }
        if ($request->filled('crop_name')) {
            $query->whereHas('cropSowings.crop', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->crop_name . '%');
            });
        }

        $farmers = $query->latest()->get();

        return view('admin.farmers.index', compact('farmers', 'states'));
    }

}
