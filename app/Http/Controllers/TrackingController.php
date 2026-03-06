<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Trip;
use App\Models\TripLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    $states = State::where('status',1)->get();
    $users = User::where('status',1)->get();

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
