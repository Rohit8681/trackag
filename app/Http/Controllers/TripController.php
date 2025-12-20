<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Trip;
use App\Models\TripLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\PartyVisit;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;

class TripController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_all_trip')->only(['index','show']);
        $this->middleware('permission:create_all_trip')->only(['create','store']);
        $this->middleware('permission:edit_all_trip')->only(['edit','update']);
        $this->middleware('permission:delete_all_trip')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Trip::with(['user', 'company', 'approvedByUser', 'tripLogs', 'customers', 'travelMode', 'tourType']);

        // 🔹 Role-based access
        if (!($user->hasRole('master_admin') || $user->hasRole('sub_admin'))) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                $subordinateIds = \App\Models\User::where('reporting_to', $user->id)->pluck('id');
                if ($subordinateIds->isNotEmpty()) {
                    $q->orWhere(function ($inner) use ($subordinateIds) {
                        $inner->whereIn('user_id', $subordinateIds)
                            ->where('approval_status', 'pending');
                    });
                }
            });
        }

        // 🔹 Default date setup (if no filters applied)
        $fromDate = $request->from_date ?? date('Y-m-d');
        $toDate   = $request->to_date ?? date('Y-m-d');

        // 🔹 Apply date filters
        $query->whereDate('trip_date', '>=', $fromDate)
            ->whereDate('trip_date', '<=', $toDate);

        // 🔹 Other Filters
        if ($request->filled('state')) {
            $query->where('state_id', $request->state);
        }
        if ($request->filled('employee')) {
            $query->where('user_id', $request->employee);
        }
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $trips = $query->latest()->get();

        // Data for dropdowns
        // $states = State::all(['id', 'name']);
        $companyCount = Company::count();
        $company = null;

        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));

                $states = State::where('status', 1)
                    ->whereIn('id', $companyStates)
                    ->get();
            } else {
                $states = State::where('status', 1)->get();
            }
        } else {
            // ⬅️ Company 1 થી વધારે હોય તો બધાં states
            $states = State::where('status', 1)->get();
        }
        $employees = User::select('id', 'name')->get();
        return view('admin.trips.index_new', compact('trips', 'states', 'employees'))
            ->with([
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ]);
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $travelModes = DB::table('travel_modes')->orderBy('name')->get();
        $purposes = DB::table('purposes')->orderBy('name')->get();
        $tourTypes = DB::table('tour_types')->orderBy('name')->get();

        return view('admin.trips.create', compact('customers', 'travelModes', 'purposes', 'tourTypes'));
    }


    public function store(Request $request)
    {
        // print($request->travel_mode);exit;
        $validated = $request->validate([
            'trip_date'      => 'required|date',
            'start_time'     => 'required',
            'end_time'       => 'nullable',
            'start_lat'      => 'required|numeric',
            'start_lng'      => 'required|numeric',
            'end_lat'        => 'required|numeric',
            'end_lng'        => 'required|numeric',
            'travel_mode'    => 'required|exists:travel_modes,id',
            'purpose'        => 'required|exists:purposes,id',
            'tour_type'      => 'required|exists:tour_types,id',
            'place_to_visit' => 'nullable|string',
            'starting_km'    => 'nullable|string',
            'end_km'         => 'nullable|string',
            'start_km_photo' => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',
            'end_km_photo'   => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',

        ]);

        $startKmPhoto = $request->hasFile('start_km_photo')
            ? $request->file('start_km_photo')->store('trip_photos', 'public')
            : null;

        $endKmPhoto = $request->hasFile('end_km_photo')
            ? $request->file('end_km_photo')->store('trip_photos', 'public')
            : null;

        $distance = $this->calculateDistance(
            $request->start_lat,
            $request->start_lng,
            $request->end_lat,
            $request->end_lng
        );

        $user = Auth::user();

        $trip = Trip::create([
            'user_id'           => $user->id,
            'company_id'        => $user->hasRole('master_admin') ? 1 : $user->company_id,
            'trip_date'         => $request->trip_date,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'start_lat'         => $request->start_lat,
            'start_lng'         => $request->start_lng,
            'end_lat'           => $request->end_lat,
            'end_lng'           => $request->end_lng,
            'total_distance_km' => $distance,
            'travel_mode'       => $request->travel_mode,
            'purpose'           => $request->purpose,
            'tour_type'         => $request->tour_type,
            'place_to_visit'    => $request->place_to_visit,
            'starting_km'       => $request->starting_km,
            'end_km'            => $request->end_km,
            'start_km_photo'    => $startKmPhoto,
            'end_km_photo'      => $endKmPhoto,
            'status'            => 'pending',
            'approval_status'   => 'pending',
        ]);

        // Attach customers to trip
        if ($request->has('customer_ids')) {
            $trip->customers()->attach($request->customer_ids);
        }

        return redirect()->route('trips.index')->with('success', 'Trip added successfully.');
    }

    public function show(Trip $trip)
    {
        $tripLogs = TripLog::where('trip_id', $trip->id)
        ->where('latitude', '!=', 0)
        ->where('longitude', '!=', 0)
        ->orderBy('recorded_at')
        ->get(['latitude', 'longitude', 'recorded_at']);

        $partyVisits = PartyVisit::with('customer')
        ->whereDate('visited_date', $trip->trip_date)
        ->where('user_id', $trip->user_id)
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get()
        ->map(function ($visit) {
            return [
                'latitude' => $visit->latitude,
                'longitude' => $visit->longitude,

                // ✅ FIXED TIME (IST)
                'check_in_time' => $visit->check_in_time
                    ? Carbon::parse($visit->check_in_time)
                        ->timezone('Asia/Kolkata')
                        ->format('d-m-Y h:i A')
                    : null,

                'customer' => [
                    'agro_name' => $visit->customer->agro_name ?? 'Customer'
                ],
            ];
        });

        // dd($partyVisits);
        
        return view('admin.trips.show_new', compact('trip', 'tripLogs','partyVisits'));

    }

    public function edit(Trip $trip)
    {
        $customers = Customer::where('is_active', true)->get();
        $travelModes = DB::table('travel_modes')->orderBy('name')->get();
        $purposes = DB::table('purposes')->orderBy('name')->get();
        $tourTypes = DB::table('tour_types')->orderBy('name')->get();
        return view('admin.trips.edit', compact('trip', 'customers', 'travelModes', 'purposes', 'tourTypes'));
    }

    public function updateKm(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $request->validate([
            'starting_km' => 'required',
            'end_km' => 'required',
            
        ]);
        $trip->starting_km = $request->starting_km;
        $trip->end_km = $request->end_km;
        $trip->save();

        return redirect()->back()->with('success', 'End KM updated successfully!');
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'trip_date'       => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'nullable',
            'start_lat'       => 'required|numeric',
            'start_lng'       => 'required|numeric',
            'end_lat'         => 'required|numeric',
            'end_lng'         => 'required|numeric',
            'travel_mode'    => 'required|exists:travel_modes,id',
            'purpose'        => 'required|exists:purposes,id',
            'tour_type'      => 'required|exists:tour_types,id',
            'place_to_visit'  => 'nullable|string',
            'starting_km'     => 'nullable|string',
            'end_km'          => 'nullable|string',
            'approval_status' => 'required|in:pending,approved,denied',
            'approval_reason' => 'nullable|string|max:255',
            'start_km_photo'  => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',
            'end_km_photo'    => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',
        ]);

        $startKmPhoto = $trip->start_km_photo;
        if ($request->hasFile('start_km_photo')) {
            $startKmPhoto = $request->file('start_km_photo')->store('trip_photos', 'public');
        }

        $endKmPhoto = $trip->end_km_photo;
        if ($request->hasFile('end_km_photo')) {
            $endKmPhoto = $request->file('end_km_photo')->store('trip_photos', 'public');
        }

        $trip->update([
            'trip_date'         => $request->trip_date,
            'start_time'        => $request->start_time,
            'end_time'          => $request->end_time,
            'start_lat'         => $request->start_lat,
            'start_lng'         => $request->start_lng,
            'end_lat'           => $request->end_lat,
            'end_lng'           => $request->end_lng,
            'travel_mode'       => $request->travel_mode,
            'purpose'           => $request->purpose,
            'tour_type'         => $request->tour_type,
            'place_to_visit'    => $request->place_to_visit,
            'starting_km'       => $request->starting_km,
            'end_km'            => $request->end_km,
            'start_km_photo'    => $startKmPhoto,
            'end_km_photo'      => $endKmPhoto,
            'total_distance_km' => $this->calculateDistance(
                $request->start_lat,
                $request->start_lng,
                $request->end_lat,
                $request->end_lng
            ),
            'approval_status'   => $request->approval_status,
            'approval_reason'   => $request->approval_status === 'denied' ? $request->approval_reason : null,
            'approved_by'       => in_array($request->approval_status, ['approved', 'denied']) ? auth()->id() : null,
            'approved_at'       => in_array($request->approval_status, ['approved', 'denied']) ? now() : null,
        ]);

        if ($request->has('customer_ids')) {
            $trip->customers()->sync($request->customer_ids);
        }


        return redirect()->route('trips.index')->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('trips.index')->with('success', 'Trip deleted successfully.');
    }


    public function approve(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);
        $status = $request->input('status', 'approved');
        $reason = $request->input('reason');
        $tripType = $request->input('trip_type'); // full or half

        if ($status === 'approved') {
            if (empty($trip->starting_km) || empty($trip->end_km)) {
                return back()->with('error', 'Please fill both Starting KM and Ending KM before approving.');
            }
        }

        if ($status === 'denied') {
            $request->validate(['reason' => 'required|string|max:255']);
        }

        if ($status === 'approved') {
            $request->validate(['trip_type' => 'required|in:full,half']);
        }

        $calculatedDistance = $this->calculateDistanceFromLogs($trip->id);

        // NaN or negative check
        if (!is_finite($calculatedDistance) || $calculatedDistance < 0) {
            $calculatedDistance = 0;
        }

        $trip->update([
            'approval_status'   => $status,
            'approval_reason'   => $status === 'denied' ? $reason : null,
            'approved_by'       => auth()->id(),
            'approved_at'       => now(),
            'total_distance_km' => $calculatedDistance,
            'trip_type'         => $status === 'approved' ? $tripType : null,
        ]);

        return redirect()->back()->with('success', 'Trip approval status updated successfully.');
    }


    public function logPoint(Request $request)
    {
        $request->validate([
            'trip_id'     => 'required|exists:trips,id',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'recorded_at' => 'nullable|date',
        ]);

        $log = TripLog::create([
            'trip_id'     => $request->trip_id,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'recorded_at' => $request->recorded_at ?? now(),
        ]);

        return response()->json(['status' => 'success', 'log' => $log]);
    }

    public function logs(Trip $trip)
    {
        return response()->json(
            $trip->tripLogs()->select('latitude', 'longitude', 'recorded_at')->get()
        );
    }

    public function updateTripCoordinates($tripId)
    {
        $startLog = DB::table('trip_logs')->where('trip_id', $tripId)->orderBy('recorded_at')->first();
        $endLog   = DB::table('trip_logs')->where('trip_id', $tripId)->orderByDesc('recorded_at')->first();

        if ($startLog && $endLog) {
            DB::table('trips')->where('id', $tripId)->update([
                'start_lat' => $startLog->latitude,
                'start_lng' => $startLog->longitude,
                'end_lat'   => $endLog->latitude,
                'end_lng'   => $endLog->longitude,
            ]);
        }
    }

    public function completeTrip($tripId)
    {
        $trip = Trip::findOrFail($tripId);
        $endLog = TripLog::where('trip_id', $tripId)->orderByDesc('recorded_at')->first();

        if ($endLog) {
            $trip->end_lat = $endLog->latitude;
            $trip->end_lng = $endLog->longitude;
            $trip->end_time = now();
        }

        $trip->total_distance_km = $this->calculateDistanceFromLogs($tripId);
        $trip->status = 'completed';
        $trip->save();

        return redirect()->back()->with('success', 'Trip marked as completed.');
    }

    public function toggleStatus(Request $request, Trip $trip)
    {
        $trip->status = $request->status;
        $trip->save();

        return redirect()->back()->with('success', 'Trip status updated successfully.');
    }


    // private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    // {
    //     $theta = $lon1 - $lon2;
    //     $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
    //         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    //     $dist = acos($dist);
    //     $dist = rad2deg($dist);
    //     $km   = $dist * 111.13384;
    //     return round($km, 2);
    // }
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        // Clamp value to valid range for acos()
        $dist = max(-1, min(1, $dist));

        $dist = acos($dist);
        $dist = rad2deg($dist);
        $km   = $dist * 111.13384;

        return round($km, 2);
    }

    private function calculateDistanceFromLogs($tripId)
    {
        $logs = TripLog::where('trip_id', $tripId)->orderBy('recorded_at')->get();
        if ($logs->count() < 2) return 0;

        $distance = 0;
        for ($i = 1; $i < $logs->count(); $i++) {
            $distance += $this->calculateDistance(
                $logs[$i - 1]->latitude,
                $logs[$i - 1]->longitude,
                $logs[$i]->latitude,
                $logs[$i]->longitude
            );
        }
        return round($distance, 2);
    }

    // public function getDropdownValues($type)
    // {
    //     $tableMap = [
    //         'travel_mode' => 'travel_modes',
    //         'purpose'     => 'purposes',
    //         'tour_type'   => 'tour_types'
    //     ];

    //     if (!array_key_exists($type, $tableMap)) {
    //         return response()->json(['status' => 'error', 'message' => 'Invalid type'], 400);
    //     }

    //     $user = Auth::user();
    //     $query = DB::table($tableMap[$type])->orderBy('name');

    //     if (!$user->hasRole('master_admin')) {
    //         $query->where('company_id', $user->company_id);
    //     }

    //     // $values = $query->pluck('name');
    //     $values = $query->get(['id', 'name']);

    //     return response()->json(['status' => 'success', 'values' => $values]);
    // }
}
