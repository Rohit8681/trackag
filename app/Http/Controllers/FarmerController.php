<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Farmer;
use App\Models\FarmVisit;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class FarmerController extends Controller
{
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
            'cropSowings.crop:id,name' 
        ]);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay(),
            ]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('farmer_name')) {
            $query->where('farmer_name', 'like', '%' . $request->farmer_name . '%');
        }

        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('sales_person')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->sales_person . '%');
            });
        }

        if ($request->filled('crop_name')) {
            $query->whereHas('cropSowings.crop', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->crop_name . '%');
            });
        }

        $farmers = $query->latest()->get();

        return view('admin.farmers.index', compact('farmers', 'states'));
    }

    public function farmerWiseList(Farmer $farmer)
    {
        dd($farmer);
        $visits = FarmVisit::with('crop:id,name')
            ->where('farmer_id', $farmer->id)
            ->latest()
            ->get();

        return view('admin.farmers.farm_visits', compact('farmer', 'visits'));
    }

    public function saveAgronomistRemark(Request $request, FarmVisit $visit)
    {
        $request->validate([
            'agronomist_remark' => 'nullable|string'
        ]);

        $visit->update([
            'agronomist_remark' => $request->agronomist_remark
        ]);

        return back()->with('success', 'Agronomist remark saved');
    }

    public function downloadPdf(Request $request)
    {
        $query = Farmer::with([
            'user:id,name',
            'state:id,name',
            'district:id,name',
            'taluka:id,name',
            'cropSowings.crop:id,name'
        ]);

        /* ---- SAME FILTER LOGIC ---- */
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from_date)->startOfDay(),
                Carbon::parse($request->to_date)->endOfDay(),
            ]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('farmer_name')) {
            $query->where('farmer_name', 'like', '%' . $request->farmer_name . '%');
        }

        if ($request->filled('mobile_no')) {
            $query->where('mobile_no', 'like', '%' . $request->mobile_no . '%');
        }

        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->filled('sales_person')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->sales_person . '%');
            });
        }

        if ($request->filled('crop_name')) {
            $query->whereHas('cropSowings.crop', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->crop_name . '%');
            });
        }

        $farmers = $query->latest()->get();

        $pdf = Pdf::loadView('admin.farmers.pdf', compact('farmers'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('farmers-list.pdf');
    }

}
