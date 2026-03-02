<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tracking.index');
    }

    public function liveData()
    {
        dd('test rohit');
        $locations = DB::table('trips')
            ->join('trip_logs', 'trips.id', '=', 'trip_logs.trip_id')
            ->join('users', 'users.id', '=', 'trips.user_id')
            ->select(
                'users.name',
                'trip_logs.latitude',
                'trip_logs.longitude',
                'trip_logs.mobile_status'
            )
            ->whereIn('trip_logs.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('trip_logs')
                    ->groupBy('trip_id');
            })
            ->get();
        return response()->json($locations);
    }
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
