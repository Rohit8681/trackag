<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\State;
use App\Models\Trip;
use App\Models\TripLog;
use App\Models\User;
use App\Models\UserStateAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TrackingController extends Controller
{
    // public function index()
    // {
    //     return view('admin.tracking.index');
    // }

    // public function liveData()
    // {
    //     $today = now()->toDateString(); // YYYY-MM-DD

    //     $latestLogs = TripLog::select('trip_logs.*')
    //         ->join('trips', 'trips.id', '=', 'trip_logs.trip_id')
    //         ->whereDate('trip_logs.created_at', $today) // 👈 Only today
    //         ->whereIn('trip_logs.id', function ($query) use ($today) {
    //             $query->selectRaw('MAX(trip_logs.id)')
    //                 ->from('trip_logs')
    //                 ->join('trips', 'trips.id', '=', 'trip_logs.trip_id')
    //                 ->whereDate('trip_logs.created_at', $today) // 👈 Only today
    //                 ->groupBy('trips.user_id');
    //         })
    //         ->with('trip.user:id,name')
    //         ->get();

    //     $locations = $latestLogs->map(function ($log) {

    //         return [
    //             'name'          => $log->trip->user->name ?? 'Unknown',
    //             'latitude'      => $log->latitude,
    //             'longitude'     => $log->longitude,
    //             'mobile_status' => $log->mobile_status,
    //         ];
    //     });

    //     return response()->json($locations);
    // }

    
    public function index()
    {
        $user = Auth::user();
        $roleName = $user->getRoleNames()->first();

        $stateIds = [];
        $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
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
        // $employees = User::select('id', 'name')->get();
        if (in_array($roleName, ['master_admin', 'sub_admin'])) {
            $users = User::where('status', 'Active')->where('id', '!=', 1)->get();
        } else {
            $users = empty($stateIds)
                ? collect()
                : User::where('status', 'Active')->where('id', '!=', 1)
                    ->whereIn('state_id', $stateIds)
                    ->where('reporting_to', $user->id)
                    ->get();
        }

        return view('admin.tracking.index',compact('states','users'));
    }


public function liveData(Request $request)
{
    $today = now()->toDateString();

    $stateId = $request->state_id;
    $userId  = $request->user_id;

    $latestLogs = TripLog::select('trip_logs.*')
        ->join('trips','trips.id','=','trip_logs.trip_id')
        ->join('users','users.id','=','trips.user_id')

        ->whereDate('trip_logs.created_at',$today)

        ->when($stateId,function($q) use ($stateId){
            $q->where('users.state_id',$stateId);
        })

        ->when($userId,function($q) use ($userId){
            $q->where('users.id',$userId);
        })

        ->whereIn('trip_logs.id', function ($query) use ($today,$stateId,$userId) {

            $query->selectRaw('MAX(trip_logs.id)')
                ->from('trip_logs')
                ->join('trips','trips.id','=','trip_logs.trip_id')
                ->join('users','users.id','=','trips.user_id')

                ->whereDate('trip_logs.created_at',$today)

                ->when($stateId,function($q) use ($stateId){
                    $q->where('users.state_id',$stateId);
                })

                ->when($userId,function($q) use ($userId){
                    $q->where('users.id',$userId);
                })

                ->groupBy('trips.user_id');
        })

        ->with('trip.user:id,name,state_id')
        ->get();


    $locations = $latestLogs->map(function ($log) {

        return [
            'name'          => $log->trip->user->name ?? 'Unknown',
            'latitude'      => $log->latitude,
            'longitude'     => $log->longitude,
            'mobile_status' => $log->mobile_status,
            'time'          => $log->recorded_at ? \Carbon\Carbon::parse($log->recorded_at)->format('d M Y H:i') : ''
        ];
    });

    return response()->json($locations);
}
    public function create(){
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
