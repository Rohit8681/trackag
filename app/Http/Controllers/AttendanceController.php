<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\State;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSession;
use App\Models\UserStateAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_attendance')->only(['index','show']);
        // $this->middleware('permission:view_new_party')->only(['newPartyList']);
        // $this->middleware('permission:edit_party_visit')->only(['edit','update']);
        // $this->middleware('permission:delete_party_visit')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $loginUser = Auth::user();
        $roleName  = $loginUser->getRoleNames()->first();

        $stateIds = [];
        $userStateAccess = UserStateAccess::where('user_id', $loginUser->id)->first();
        if ($userStateAccess && !empty($userStateAccess->state_ids)) {
            $stateIds = $userStateAccess->state_ids;
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
                    : (empty($stateIds)
                        ? collect()
                        : State::where('status', 1)->whereIn('id', $stateIds)->get());
            }
        } else {
            $states = in_array($roleName, ['master_admin', 'sub_admin'])
                ? State::where('status', 1)->get()
                : (empty($stateIds)
                    ? collect()
                    : State::where('status', 1)->whereIn('id', $stateIds)->get());
        }

        $stateFilter = $request->state;
        $userFilter  = $request->user_id;

        $usersQuery = User::where('status','Active');

        if (!in_array($roleName,['master_admin','sub_admin'])) {
            $usersQuery->where('reporting_to',$loginUser->id)
                    ->whereIn('state_id',$stateIds);
        }

        if ($stateFilter) {
            $usersQuery->where('state_id',$stateFilter);
        }

        if ($userFilter) {
            $usersQuery->where('id',$userFilter);
        }

        $users = $usersQuery->orderBy('name')->get();

        $month = $request->input('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate   = Carbon::parse($month.'-01')->endOfMonth();
        $today     = now()->format('Y-m-d');

        $trips = Trip::whereBetween('trip_date',[$startDate,$endDate])
            ->get()
            ->groupBy(fn($t)=>$t->user_id.'_'.Carbon::parse($t->trip_date)->format('Y-m-d'));

        $leaves   = Leave::where('status',1)->get();
        $holidays = Holiday::pluck('holiday_date')->toArray();

        $savedAttendance = Attendance::whereBetween('attendance_date',[$startDate,$endDate])
            ->get()
            ->keyBy(fn($a)=>$a->user_id.'_'.$a->attendance_date->format('Y-m-d'));

        $attendance = [];

        foreach ($users as $user) {
            $date = $startDate->copy();

            while ($date <= $endDate) {
                $dateKey = $date->format('Y-m-d');
                $key = $user->id.'_'.$dateKey;

                if (isset($savedAttendance[$key])) {
                    $status = $savedAttendance[$key]->status;
                }
                elseif (in_array($dateKey,$holidays)) {
                    $status = 'H';
                }
                elseif ($date->isSunday()) {
                    $status = 'WO';
                }
                elseif (isset($trips[$key])) {
                    $trip = $trips[$key]->first();
                    $status = $trip->approval_status === 'approved'
                        ? ($trip->trip_type === 'full' ? 'P_FULL' : 'P_HALF')
                        : 'A';
                }
                elseif ($dateKey > $today) {
                    $status = 'NA';
                }
                else {
                    $status = 'A';
                }

                $attendance[$user->id][$dateKey] = $status;
                $date->addDay();
            }
        }

        return view('admin.hr.attendance.index_nnew',compact(
            'users','attendance','startDate','endDate','month',
            'states','leaves','stateFilter','userFilter'
        ));
    }

    // public function export(Request $request)
    // {
    //     $month = $request->input('month', now()->format('Y-m'));

    //     // SAME FILTER LOGIC as index()
    //     $usersQuery = User::where('status','Active');

    //     if ($request->state) {
    //         $usersQuery->where('state_id', $request->state);
    //     }

    //     if ($request->user_id) {
    //         $usersQuery->where('id', $request->user_id);
    //     }

    //     $users = $usersQuery->orderBy('name')->get();

    //     return Excel::download(
    //         new AttendanceExport($month, $users),
    //         'attendance_'.$month.'.xlsx'
    //     );
    // }


    public function export(Request $request)
    {
        $loginUser = Auth::user();
        $roleName  = $loginUser->getRoleNames()->first();

        $stateIds = [];
        $userStateAccess = UserStateAccess::where('user_id',$loginUser->id)->first();
        if ($userStateAccess && !empty($userStateAccess->state_ids)) {
            $stateIds = $userStateAccess->state_ids;
        }

        $usersQuery = User::where('status','Active');

        if (!in_array($roleName,['master_admin','sub_admin'])) {
            $usersQuery->where('reporting_to',$loginUser->id)
                    ->whereIn('state_id',$stateIds);
        }

        if ($request->state) {
            $usersQuery->where('state_id',$request->state);
        }

        if ($request->user_id) {
            $usersQuery->where('id',$request->user_id);
        }

        $users = $usersQuery->orderBy('name')->get();

        $month = $request->input('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate   = Carbon::parse($month.'-01')->endOfMonth();
        $today     = now()->format('Y-m-d');

        $trips = Trip::whereBetween('trip_date',[$startDate,$endDate])
            ->get()
            ->groupBy(fn($t)=>$t->user_id.'_'.Carbon::parse($t->trip_date)->format('Y-m-d'));

        $holidays = Holiday::pluck('holiday_date')->toArray();

        $savedAttendance = Attendance::whereBetween('attendance_date',[$startDate,$endDate])
            ->get()
            ->keyBy(fn($a)=>$a->user_id.'_'.$a->attendance_date->format('Y-m-d'));

        $attendance = [];

        foreach ($users as $user) {
            $date = $startDate->copy();

            while ($date <= $endDate) {
                $dateKey = $date->format('Y-m-d');
                $key = $user->id.'_'.$dateKey;

                if (isset($savedAttendance[$key])) {
                    $status = $savedAttendance[$key]->status;
                }
                elseif (in_array($dateKey,$holidays)) {
                    $status = 'H';
                }
                elseif ($date->isSunday()) {
                    $status = 'WO';
                }
                elseif (isset($trips[$key])) {
                    $trip = $trips[$key]->first();
                    $status = $trip->approval_status === 'approved'
                        ? ($trip->trip_type === 'full' ? 'P_FULL' : 'P_HALF')
                        : 'A';
                }
                elseif ($dateKey > $today) {
                    $status = 'NA';
                }
                else {
                    $status = 'A';
                }

                $attendance[$user->id][$dateKey] = $status;
                $date->addDay();
            }
        }

        return Excel::download(
            new AttendanceExport($month, $users, $attendance),
            'attendance_'.$month.'.xlsx'
        );
    }

    public function save(Request $request){
        Attendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'attendance_date' => $request->date
            ],
            [
                'status' => $request->status
            ]
        );

        return response()->json(['success'=>true]);
    }


}
