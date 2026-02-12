<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\PartyVisit;
use App\Models\State;
use App\Models\User;
use App\Models\UserStateAccess;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PartyController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view_party_visit')->only(['index','show']);
        // $this->middleware('permission:view_new_party')->only(['newPartyList']);
        // $this->middleware('permission:edit_party_visit')->only(['edit','update']);
        // $this->middleware('permission:delete_party_visit')->only(['destroy']);
    }

    public function index()
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();
        
       
        $companyCount = Company::count();
        $company = null;
        $companyStates = [];
        $stateIds = [];
        $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
        if ($userStateAccess && !empty($userStateAccess->state_ids)) {
            $stateIds = $userStateAccess->state_ids ?? [];
        
        }
        if (in_array($roleName, ['master_admin', 'sub_admin'])) {
            $employees = User::where('status','Active')->where('id', '!=', $user->id)->get();
        }else{
            if (empty($stateIds)) {
                $employees = collect();
            }else{
                $employees = User::where('status', 'Active')
                ->whereIn('state_id', $stateIds)
                ->where('reporting_to', $user->id)
                ->get();
            }
        }

        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));
                if (in_array($roleName, ['sub_admin'])) {
                    $states = State::where('status',1)
                    ->whereIn('id', $companyStates)
                    ->get();
                }else{
                    $states = State::where('status',1)
                    ->whereIn('id', $stateIds)
                    ->get();
                }
            } else {
                $states = State::where('status',1)->get();
            }

        } else {
            $states = State::where('status',1)->get();
        }
        return view('admin.party.index',compact('states','employees','company'));

    }

    public function getPartyVisits(Request $request)
    {
        $user     = auth()->user();
        $roleName = $user->getRoleNames()->first();
        $type      = $request->get('type', 'daily'); // daily OR monthly
        $userId    = $request->get('user_id');
        $fromDate  = $request->get('from_date');
        $toDate    = $request->get('to_date');
        $agroName  = $request->get('agro_name');

        $query = PartyVisit::with(['customer', 'user'])->whereNotNull('check_in_time')->whereNotNull('check_out_time');

        if (!in_array($roleName, ['master_admin', 'sub_admin'])) {

            $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
            $stateIds = $userStateAccess->state_ids ?? [];

            if (empty($stateIds)) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // ✅ Filter via user relation
            $query->whereHas('user', function ($q) use ($user, $stateIds) {
                $q->whereIn('state_id', $stateIds)
                ->where('reporting_to', $user->id);
            });
        }
        // FILTER : Employee
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }
        $today = now()->toDateString();
        if(empty($fromDate) && empty($toDate) && $type == "daily"){
            $fromDate = $today;
            $toDate   = $today;
        }

        // FILTER : Date
        if ($fromDate && $toDate) {
            $query->whereBetween('visited_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('visited_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('visited_date', '<=', $toDate);
        }

        // FILTER : Agro name
        if ($agroName) {
            $query->whereHas('customer', function ($q) use ($agroName) {
                $q->where('agro_name', 'LIKE', "%$agroName%");
            });
        }

        // -----------------------------------------------------
        // DAILY API RESPONSE
        // -----------------------------------------------------
        if ($type === 'daily') {

            $data = $query->orderByDesc('visited_date')->get()->map(function ($v) {

                // Calculate duration
                $duration = '-';
                if ($v->check_in_time && $v->check_out_time) {
                    $d = \Carbon\Carbon::parse($v->check_in_time)
                        ->diffInMinutes(\Carbon\Carbon::parse($v->check_out_time));

                    $duration = floor($d / 60) . "h " . ($d % 60) . "m";
                }

                return [
                    'id'                    => $v->id,
                    'visited_date'          => $v->visited_date ? $v->visited_date->format('d-m-Y') : null,
                    'employee_name'         => $v->user->name ?? '-',
                    'agro_name'             => $v->customer->agro_name ?? '-',
                    'check_in_out_duration' => $duration,
                    'visit_purpose'         => $v->visit_purpose ?? '-',
                    'followup_date'         => $v->followup_date ? $v->followup_date->format('d-m-Y') : '-',
                    'agro_visit_image'      => $v->agro_visit_image ? asset('storage/' . $v->agro_visit_image) : null,
                    'remarks'               => $v->remarks ?? '-',
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);
        }

        // -----------------------------------------------------
        // MONTHLY API RESPONSE
        // -----------------------------------------------------
        $data = $query->get()
        ->groupBy('customer_id')
        ->map(function ($group) {

            $lastVisit = $group->sortByDesc('visited_date')->first();

            // Purpose Wise Count FIXED
            $purposeDetails = $group->groupBy('visit_purpose')->map(function ($rows, $purposeName) {

                return [
                    'purpose_name' => $purposeName ?? '-',
                    'count' => $rows->count(),
                ];
            })->values();

            return [
                'shop_name'         => $lastVisit->customer->agro_name ?? '-',
                'employee_name'     => $lastVisit->user->name ?? '-',
                'visit_count'       => $group->count(),
                'last_visit_date'   => $lastVisit->visited_date ? $lastVisit->visited_date->format('d-m-Y') : '-',
                'visit_purpose_count' => $purposeDetails,
            ];
        })
        ->values();


        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }
    
    public function getEmployeesByState(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();
        $stateId = $request->state_id;

        // ✅ Master / Sub admin → all employees
        if (in_array($roleName, ['master_admin', 'sub_admin'])) {

            $employees = User::where('status', 'Active')
                ->when($stateId && $stateId !== 'all', function ($q) use ($stateId) {
                    $q->where('state_id', $stateId);
                })
                ->select('id', 'name')
                ->get();

            return response()->json($employees);
        }

        $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
        $stateIds = $userStateAccess->state_ids ?? [];

        if (empty($stateIds)) {
            return response()->json([]);
        }

        $employees = User::where('status', 'Active')
            ->whereIn('state_id', $stateIds)
            ->where('reporting_to', $user->id)
            ->when($stateId && $stateId !== 'all', function ($q) use ($stateId) {
                $q->where('state_id', $stateId);
            })
            ->select('id', 'name')
            ->get();

        return response()->json($employees);
    }

    
    public function newPartyList(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();
        
        $stateIds = [];
        $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
        if ($userStateAccess && !empty($userStateAccess->state_ids)) {
            $stateIds = $userStateAccess->state_ids;
        }

        if (in_array($roleName, ['master_admin', 'sub_admin'])) {
            $users = User::where('status', 'Active')->get();
        } else {
            $users = empty($stateIds)
                ? collect()
                : User::where('status', 'Active')
                    ->whereIn('state_id', $stateIds)
                    ->where('reporting_to', $user->id)
                    ->get();
        }

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
                    $states = empty($stateIds)
                        ? collect()
                        : State::where('status', 1)
                            ->whereIn('id', $stateIds)
                            ->get();
                }

            } else {
                $states = in_array($roleName, ['master_admin', 'sub_admin'])
                    ? State::where('status', 1)->get()
                    : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
            }

        } else {
            $states = in_array($roleName, ['master_admin', 'sub_admin'])
                ? State::where('status', 1)->get()
                : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
        }

        $query = Customer::with('user')
            ->where('is_active', 1)
            ->where('type', 'mobile');

        if (!in_array($roleName, ['master_admin', 'sub_admin'])) {

            if (empty($stateIds)) {
                $customer = collect();

                return view('admin.new-party.index', compact('customer', 'users', 'states', 'company'));
            }

            $query->whereHas('user', function ($q) use ($user, $stateIds) {
                $q->whereIn('state_id', $stateIds)
                ->where('reporting_to', $user->id);
            });
        }

        if ($request->financial_year) {
            $dates = explode('-', $request->financial_year);
            $query->whereYear('visit_date', '>=', $dates[0])
                ->whereYear('visit_date', '<=', $dates[1]);
        }

        if ($request->from_date) {
            $query->whereDate('visit_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('visit_date', '<=', $request->to_date);
        }

        if ($request->state_id) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->agro_name) {
            $query->where('agro_name', 'LIKE', '%' . $request->agro_name . '%');
        }

        $customer = $query->orderBy('visit_date', 'desc')->get();

        // ----------------------------
        // RETURN VIEW
        // ----------------------------
        return view('admin.new-party.index', compact('customer', 'users', 'states', 'company'));
    }

    public function newPartyPdf(Request $request)
{
    $user = auth()->user();
    $roleName = $user->getRoleNames()->first();

    $stateIds = [];
    $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
    if ($userStateAccess && !empty($userStateAccess->state_ids)) {
        $stateIds = $userStateAccess->state_ids;
    }

    $query = Customer::with('user')
        ->where('is_active', 1)
        ->where('type', 'mobile');

    /* 🔐 ROLE & STATE ACCESS */
    if (!in_array($roleName, ['master_admin', 'sub_admin'])) {
        if (!empty($stateIds)) {
            $query->whereHas('user', function ($q) use ($user, $stateIds) {
                $q->whereIn('state_id', $stateIds)
                  ->where('reporting_to', $user->id);
            });
        } else {
            $query->whereRaw('1 = 0'); // no data
        }
    }

    /* 📅 FILTERS (same as list page) */
    if ($request->financial_year) {
        $dates = explode('-', $request->financial_year);
        $query->whereYear('visit_date', '>=', $dates[0])
              ->whereYear('visit_date', '<=', $dates[1]);
    }

    if ($request->from_date && $request->to_date) {
        $query->whereBetween('visit_date', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay(),
        ]);
    } elseif ($request->from_date) {
        $query->whereDate('visit_date', '>=', $request->from_date);
    } elseif ($request->to_date) {
        $query->whereDate('visit_date', '<=', $request->to_date);
    }

    if ($request->state_id) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('state_id', $request->state_id);
        });
    }

    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->agro_name) {
        $query->where('agro_name', 'LIKE', '%' . $request->agro_name . '%');
    }

    $customer = $query->orderBy('visit_date', 'desc')->get();

    $pdf = Pdf::loadView('admin.new-party.pdf', compact('customer'))
        ->setPaper('A4', 'landscape');

    return $pdf->download('new-party-list.pdf');
}

    public function updateStatus(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'status' => 'required|string',
            'remark' => $request->status == 'approved' ? 'nullable|string' : 'required|string',
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $customer->status = $request->status;
        $customer->remarks = $request->remark;
        $customer->save();

        return back()->with('success', 'Status updated successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
