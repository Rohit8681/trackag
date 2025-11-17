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

    }
    
    // public function getPartyVisits(Request $request)
    // {
    //     $type = $request->get('type', 'daily'); // default daily
    //     $userId = $request->get('user_id');
    //     $fromDate = $request->get('from_date');
    //     $toDate = $request->get('to_date');
    //     $state = $request->get('state');
    //     $agroName = $request->get('agro_name');

    //     $query = PartyVisit::with('customer','user','visitPurpose')->query();
    //     dd($query->get());
    //     if ($userId) {
    //         $query->where('user_id', $userId);
    //     }

    //     // 📅 Filter by date range
    //     if ($fromDate && $toDate) {
    //         $query->whereBetween('visited_date', [$fromDate, $toDate]);
    //     } elseif ($fromDate) {
    //         $query->whereDate('visited_date', '>=', $fromDate);
    //     } elseif ($toDate) {
    //         $query->whereDate('visited_date', '<=', $toDate);
    //     }

    //     // 🏠 Filter by state (if you store it)
    //     if ($state) {
    //         $query->where('state', $state);
    //     }

    //     // 🌾 Filter by Agro name
    //     if ($agroName) {
    //         $query->where('agro_name', $agroName);
    //     }

    //     // 🟡 Daily / Monthly split
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
        $type      = $request->get('type', 'daily');
        $userId    = $request->get('user_id');
        $fromDate  = $request->get('from_date');
        $toDate    = $request->get('to_date');
        $state     = $request->get('state');
        $agroName  = $request->get('agro_name');

        $query = PartyVisit::with(['customer', 'user', 'visitPurpose']);

        // Filter by employee
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Date filters
        if ($fromDate && $toDate) {
            $query->whereBetween('visited_date', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('visited_date', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('visited_date', '<=', $toDate);
        }

        // State filter (if in customer table)
        if ($state) {
            $query->whereHas('customer', function ($q) use ($state) {
                $q->where('state', $state);
            });
        }

        // Agro filter
        if ($agroName) {
            $query->whereHas('customer', function ($q) use ($agroName) {
                $q->where('name', $agroName);
            });
        }

        // -----------------------------
        // DAILY RESPONSE
        // -----------------------------
        if ($type === 'daily') {
            $data = $query->orderByDesc('visited_date')->get()->map(function ($v) {

                // Duration
                $duration = '-';
                if ($v->check_in_time && $v->check_out_time) {
                    $duration = \Carbon\Carbon::parse($v->check_in_time)
                        ->diffInMinutes(\Carbon\Carbon::parse($v->check_out_time));

                    $duration = floor($duration / 60) . "h " . ($duration % 60) . "m";
                }

                return [
                    'id'                     => $v->id,
                    'visited_date'           => $v->visited_date ? $v->visited_date->format('d-m-Y') : null,
                    'employee_name'          => $v->user->name ?? '-',
                    'agro_name'              => $v->customer->agro_name ?? '-',
                    'check_in_out_duration'  => $duration,
                    'visit_purpose'          => $v->visitPurpose->name ?? '-',
                    'followup_date'          => $v->followup_date ? $v->followup_date->format('d-m-Y') : '-',
                    'agro_visit_image'       => $v->agro_visit_image ? asset('storage/' . $v->agro_visit_image) : null,
                    'remarks'                => $v->remarks ?? '-',
                ];
            });
            dd($data);
            return response()->json([
                'success' => true,
                'data'    => $data
            ]);
        }

        // -----------------------------
        // MONTHLY RESPONSE
        // -----------------------------
        $data = $query->get()
            ->groupBy('customer_id')
            ->map(function ($group) {

                $lastVisit = $group->sortByDesc('visited_date')->first();

                return [
                    'shop_name'         => $lastVisit->customer->name ?? '-',
                    'employee_name'     => $lastVisit->user->name ?? '-',
                    'visit_count'       => $group->count(),
                    'last_visit_date'   => $lastVisit->visited_date ? $lastVisit->visited_date->format('d-m-Y') : '-',
                    'visit_purpose_count' => $group->groupBy('visit_purpose_id')->map->count()
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data'    => $data
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
