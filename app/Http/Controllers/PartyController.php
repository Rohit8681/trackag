<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PartyVisit;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $states = State::where('status',1)->get();
        $employees = User::where('status','Active')->get();
        $customers = Customer::where('status',1)->get();
        return view('admin.party.index',compact('states','employees','customers'));

    }
    
   

    public function getPartyVisits(Request $request)
    {
        $type      = $request->get('type', 'daily'); // daily OR monthly
        $userId    = $request->get('user_id');
        $fromDate  = $request->get('from_date');
        $toDate    = $request->get('to_date');
        $agroName  = $request->get('agro_name');

        $query = PartyVisit::with(['customer', 'user']);

        // FILTER : Employee
        if ($userId) {
            $query->where('user_id', $userId);
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


    
    public function newPartyList(){
        return view('admin.new-party.index');
        // return view('coming-soon');
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
