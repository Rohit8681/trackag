<?php

namespace App\Http\Controllers;

use App\Models\PartyVisit;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.party.index');
        // return view('coming-soon');

    }

    // public function getPartyVisits(Request $request)
    // {
    //     $type = $request->get('type', 'daily'); // default daily
    //     $userId = $request->get('user_id');

    //     $query = PartyVisit::query();

    //     if ($userId) {
    //         $query->where('user_id', $userId);
    //     }

    //     if ($type === 'daily') {
    //         $data = $query->where('type', 'daily')
    //             ->orderByDesc('visited_date')
    //             ->get([
    //                 'id',
    //                 'visited_date',
    //                 'employee_name',
    //                 'agro_name',
    //                 'check_in_out_duration',
    //                 'visit_purpose',
    //                 'followup_date',
    //                 'agro_visit_image',
    //                 'remarks',
    //             ]);
    //     } else {
    //         $data = $query->where('type', 'monthly')
    //             ->orderByDesc('last_visit_date')
    //             ->get([
    //                 'id',
    //                 'shop_name',
    //                 'employee_name',
    //                 'visit_count',
    //                 'last_visit_date',
    //                 'visit_purpose_count',
    //             ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => $data
    //     ]);
    // }

    
    public function getPartyVisits(Request $request)
    {
        $type = $request->get('type', 'daily'); // default daily
        $userId = $request->get('user_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $state = $request->get('state');
        $agroName = $request->get('agro_name');

        $query = PartyVisit::query();

        // 👤 Filter by employee (user)
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // 📅 Filter by date range
        if ($fromDate && $toDate) {
            $query->whereBetween('visited_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('visited_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('visited_date', '<=', $toDate);
        }

        // 🏠 Filter by state (if you store it)
        if ($state) {
            $query->where('state', $state);
        }

        // 🌾 Filter by Agro name
        if ($agroName) {
            $query->where('agro_name', $agroName);
        }

        // 🟡 Daily / Monthly split
        if ($type === 'daily') {
            $data = $query->where('type', 'daily')
                ->orderByDesc('visited_date')
                ->get([
                    'id',
                    'visited_date',
                    'employee_name',
                    'agro_name',
                    'check_in_out_duration',
                    'visit_purpose',
                    'followup_date',
                    'agro_visit_image',
                    'remarks',
                ]);
        } else {
            $data = $query->where('type', 'monthly')
                ->orderByDesc('last_visit_date')
                ->get([
                    'id',
                    'shop_name',
                    'employee_name',
                    'visit_count',
                    'last_visit_date',
                    'visit_purpose_count',
                ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function newPartyList(){
        // return view('admin.new-party.index');
        return view('coming-soon');
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
