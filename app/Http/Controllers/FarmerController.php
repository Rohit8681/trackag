<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Farmer;
use App\Models\FarmVisit;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FarmerVisitTarget;
use App\Models\User;
use App\Models\UserStateAccess;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

        $farmerVisitTarget = FarmerVisitTarget::first();

        return view('admin.farmers.index', compact('farmers', 'states', 'farmerVisitTarget'));
    }

    public function saveFarmerVisitTarget(Request $request)
    {
        $request->validate([
            'target' => 'required|integer|min:0'
        ]);

        $target = FarmerVisitTarget::first() ?? new FarmerVisitTarget();
        $target->target = $request->target;
        $target->save();

        return redirect()->back()->with('success', 'Farmer Visit Target updated successfully.');
    }

    public function dailyFarmVisits(Request $request)
    {
        // 👉 Default today date
        $selectedDate = $request->date ?? now()->toDateString();
        $selectedFarmer = $request->farmer_id ?? null;

        $visits = FarmVisit::with('crop:id,name', 'farmer:id,farmer_name')
            ->when($selectedDate, function ($q) use ($selectedDate) {
                $q->whereDate('created_at', $selectedDate);
            })
            ->when($selectedFarmer, function ($q) use ($selectedFarmer) {
                $q->where('farmer_id', $selectedFarmer);
            })
            ->latest()
            ->get();

        // Farmer dropdown data
        $farmers = Farmer::select('id', 'farmer_name')->orderBy('farmer_name')->get();

        return view('admin.farmers.daily_farm_visits', compact(
            'visits',
            'farmers',
            'selectedDate',
            'selectedFarmer'
        ));
    }

    public function farmerWiseList(Farmer $farmer)
    {
        
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

    public function farmerVisitReport()
    {
        $filters = $this->getRoleBasedStateAndEmployeeFilters();
        extract($filters);

        // Generate Financial Years for Dropdown (e.g., 2024-2025, 2025-2026, 2026-2027)
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('n');
        $startYear = $currentMonth >= 4 ? $currentYear : $currentYear - 1;
        
        $financialYears = [];
        for ($i = -2; $i <= 1; $i++) {
            $y = $startYear + $i;
            $financialYears[] = $y . '-' . ($y + 1);
        }

        $currentFinancialYear = $startYear . '-' . ($startYear + 1);

        return view('admin.farmers.report', compact('states', 'employees', 'financialYears', 'currentFinancialYear'));
    }

    public function getFarmerVisitReportData(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();
        
        $stateId = $request->get('state_id');
        $employeeId = $request->get('employee_id');
        $financialYear = $request->get('financial_year');

        // Parse Financial Year
        if ($financialYear) {
            $parts = explode('-', $financialYear);
            $startYear = $parts[0];
            $endYear = $parts[1] ?? ($startYear + 1);
        } else {
            $currentMonth = (int)date('n');
            $startYear = $currentMonth >= 4 ? (int)date('Y') : (int)date('Y') - 1;
            $endYear = $startYear + 1;
        }

        $startDate = $startYear . '-04-01';
        $endDate = $endYear . '-03-31';

        $stateIds = [];
        if (!in_array($roleName, ['master_admin', 'sub_admin'])) {
            $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
            if ($userStateAccess && !empty($userStateAccess->state_ids)) {
                $stateIds = $userStateAccess->state_ids;
            } else {
                return response()->json(['data' => []]);
            }
        }

        // Fetch Employees based on role/filters
        $employeeQuery = User::where('status', 'Active')->where('id', '!=', 1);

        if (!in_array($roleName, ['master_admin', 'sub_admin'])) {
            $employeeQuery->whereIn('state_id', $stateIds)->where('reporting_to', $user->id);
        }

        if ($stateId) {
            $employeeQuery->where('state_id', $stateId);
        }

        if ($employeeId) {
            $employeeQuery->where('id', $employeeId);
        }

        $employees = $employeeQuery->orderBy('name')->get();
        if ($employees->isEmpty()) {
            return response()->json(['data' => []]);
        }
        $employeeIds = $employees->pluck('id')->toArray();

        // Fetch Visits
        $visits = FarmVisit::whereIn('user_id', $employeeIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Build columns strictly based on FY
        $months = [];
        for ($i = 4; $i <= 12; $i++) {
            $months[] = ['year' => $startYear, 'month' => $i];
        }
        for ($i = 1; $i <= 3; $i++) {
            $months[] = ['year' => $endYear, 'month' => $i];
        }

        $data = [];
        foreach ($employees as $employee) {
            $employeeVisits = $visits->where('user_id', $employee->id);
            
            // Format months keys
            $monthData = [];
            foreach ($months as $idx => $m) {
                $count = $employeeVisits->filter(function($v) use ($m) {
                    $d = Carbon::parse($v->created_at);
                    return $d->year == $m['year'] && $d->month == $m['month'];
                })->count();
                
                $monthKey = Carbon::create($m['year'], $m['month'], 1)->format('M-y'); // e.g. Apr-26
                $monthData['month_' . $idx] = [
                    'label' => $monthKey,
                    'count' => $count,
                    'year' => $m['year'],
                    'month' => $m['month']
                ];
            }

            $data[] = array_merge([
                'employee_name' => $employee->name,
                'employee_id' => $employee->id,
            ], $monthData);
        }

        // Return columns config and data row
        $columns = [
            ['data' => 'employee_name', 'name' => 'employee_name', 'title' => 'Emp Name']
        ];
        
        foreach ($months as $idx => $m) {
            $monthKey = Carbon::create($m['year'], $m['month'], 1)->format('M-y'); // e.g. Apr-26
            $columns[] = [
                'data' => 'month_' . $idx,
                'name' => 'month_' . $idx,
                'title' => $monthKey
            ];
        }

        $targetObj = FarmerVisitTarget::first();
        $target = $targetObj ? $targetObj->target : 0;

        return response()->json(['data' => $data, 'columns' => $columns, 'target' => $target]);
    }

    public function getFarmerVisitDetails(Request $request)
    {
        $employeeId = $request->get('employee_id');
        $year = $request->get('year');
        $month = $request->get('month');

        if (!$employeeId || !$year || !$month) {
            return response()->json(['data' => []]);
        }

        $visits = FarmVisit::with(['farmer.state', 'farmer.district', 'farmer.taluka'])
            ->where('user_id', $employeeId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'asc')
            ->get();

        $data = $visits->map(function ($v) {
            return [
                'date' => $v->created_at ? Carbon::parse($v->created_at)->format('d-m-Y') : '-',
                'farmer_name' => $v->farmer->farmer_name ?? '-',
                'mobile_no' => $v->farmer->mobile_no ?? '-',
                'state' => $v->farmer->state->name ?? '-',
                'district' => $v->farmer->district->name ?? '-',
                'taluka' => $v->farmer->taluka->name ?? '-',
                'city' => $v->farmer->village ?? '-', // village as city
            ];
        });

        return response()->json(['data' => $data]);
    }
}
