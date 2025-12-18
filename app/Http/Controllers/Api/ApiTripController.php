<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\GpsLog;
use App\Models\Purpose;
use App\Models\TourType;
use App\Models\TravelMode;
use App\Models\Trip;
use App\Models\User;
use App\Models\TripLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiTripController extends BaseController
{

    public function getTourDetails()
    {
        $user = Auth::user(); // or just Auth::user() if 'api' is default guard

        $tourPurposes = Purpose::get();
        $vehicleTypes = TravelMode::get();
        $tourTypes = TourType::get();
        $success = [];
        $success['tourPurposes'] = $tourPurposes;
        $success['vehicleTypes'] = $vehicleTypes;
        $success['tourTypes'] = $tourTypes;
        // Return the response
        return $this->sendResponse($success, 'Tour details fetch successfully');
    }
    public function fetchCustomer()
    {

        $user = Auth::user(); 

        $customers = Customer::where('is_active', 1)
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return $this->sendResponse($customers, "Customers fetched successfully");
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Trip::with([
            'user',
            'company',
            'approvedByUser',
            'tripLogs',
            'customers',
            'travelMode',
            'tourType',
            'purpose'
        ]);

        // Role-based filtering
        if ($user->hasRole('master_admin')) {
            // Master admin sees all trips
        } elseif ($user->hasRole('sub_admin')) {
            $query->where('company_id', $user->company_id);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);

                // Include pending trips from subordinates
                $subordinateIds = User::where('reporting_to', $user->id)->pluck('id');
                if ($subordinateIds->isNotEmpty()) {
                    $q->orWhere(function ($inner) use ($subordinateIds) {
                        $inner->whereIn('user_id', $subordinateIds)
                            ->where('approval_status', 'pending');
                    });
                }
            });
        }

        // Add optional filters
        if ($request->has('status')) {
            $query->where('approval_status', $request->status);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from,
                $request->date_to
            ]);
        }

        // Paginated results
        $trips = $query->latest()->paginate($request->per_page ?? 10);

        return $this->sendResponse($trips, "Trips fetched successfully");
    }
    
    public function logPoint(Request $request)
    {
        // 🪵 Log the entire incoming request
        Log::info('Received logPoint request:', [
            'payload' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Validate incoming request
        $validated = $request->validate([
            'location' => 'required|array|min:1',
            'location.*.tripId' => 'required',
            'location.*.latitude' => 'required|numeric',
            'location.*.longitude' => 'required|numeric',
            'location.*.gps_status' => 'nullable',
            'location.*.battery_percentage' => 'nullable',
            'location.*.recorded_at' => 'nullable|date',
        ]);

        $locations = $validated['location'];

        // 🪵 Log validated data
        Log::info('Validated logPoint data:', $locations);

        // Check for completed trips
        $tripIds = collect($locations)->pluck('tripId'); // fixed typo (was trip_id)
        $completedTrips = Trip::whereIn('id', $tripIds)
            ->where('status', 'completed')
            ->pluck('id')
            ->toArray();

        if (!empty($completedTrips)) {
            Log::warning('Attempt to log points for completed trips', [
                'trip_ids' => $completedTrips,
            ]);

            return response()->json([
                'success' => false,
                'message' => "Cannot log points for completed trips: " . implode(', ', $completedTrips)
            ], 403);
        }

        // Insert logs safely within a transaction
        $logs = DB::transaction(function () use ($locations) {
            return collect($locations)->map(function ($loc) {
                // if($loc['latitude'] != 0 && $loc['longitude'] != 0){
                    return TripLog::create([
                        'trip_id' => $loc['tripId'],
                        'latitude' => $loc['latitude'],
                        'longitude' => $loc['longitude'],
                        'gps_status' => $loc['gps_status'] ?? null,
                        'battery_percentage' => ($loc['battery_percentage'] === "null" || $loc['battery_percentage'] === null)
                            ? null
                            : $loc['battery_percentage'],
                        'recorded_at' => $loc['recorded_at'],
                    ]);
                // }
                
            });
        });

        // 🪵 Log successful insertions
        Log::info('Trip logs inserted successfully', [
            'count' => count($logs),
            'trip_ids' => collect($logs)->pluck('trip_id'),
        ]);

        return $this->sendResponse($logs, "Trip logs recorded successfully");
    }

    // Get all logs for a trip
    public function logs($tripId)
    {
        $trip = Trip::with('tripLogs')->findOrFail($tripId);
        $success["trip"] = $trip->only(['id', 'trip_date', 'start_time', 'end_time', 'status']);
        $success["logs"] = $trip->tripLogs->sortBy('recorded_at')->values();
        return $this->sendResponse($success, "Trip log fetch successfully");
    }

    // Mark a trip as completed
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
        return $this->sendResponse($trip, "Trip marked as completed.");
    }

    // Create a new trip via API
    public function storeTrip(Request $request)
    {
        Log::info('STORE TRIP RAW REQUEST', $request->all());
        $validated = $request->validate([
            'trip_date'      => 'nullable|date',
            'start_time'     => 'nullable',
            'start_lat'      => 'required|numeric',
            'start_lng'      => 'required|numeric',
            'travel_mode'    => 'required',
            'purpose'        => 'required',
            'tour_type'      => 'required',
            'place_to_visit' => 'nullable|string',
            'starting_km'    => 'nullable|string',
            'start_km_photo' => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',
            'customer_ids'   => 'nullable|array',
        ]);
         Log::info('VALIDATED TRIP DATA', $validated);

        $user = Auth::user();
         Log::info('AUTH USER', ['user_id' => $user->id]);
        $startKmPhoto = null;
        if ($request->hasFile('start_km_photo')) {
            try {
                Log::error('Received file:', [
                    'exists' => $request->hasFile('start_km_photo'),
                    'valid' => $request->file('start_km_photo')->isValid(),
                    'size' => $request->file('start_km_photo')->getSize(),
                ]);
                $startKmPhoto = $request->file('start_km_photo')->store('trip_photos', 'public');
            } catch (\Exception $e) {
                Log::error('File upload failed: ' . $e->getMessage());
                // Handle the error appropriately
            }
        }
        $endKmPhoto = null;
        if ($request->hasFile('end_km_photo')) {
            try {
                $endKmPhoto = $request->file('end_km_photo')->store('trip_photos', 'public');
            } catch (\Exception $e) {
                Log::error('File upload failed: ' . $e->getMessage());
                // Handle the error appropriately
            }
        }


        // If end_lat/lng provided, calculate distance
        $distance = null;
        if (!empty($validated['end_lat']) && !empty($validated['end_lng'])) {
            $distance = $this->calculateDistance(
                $validated['start_lat'],
                $validated['start_lng'],
                $validated['end_lat'],
                $validated['end_lng']
            );
            Log::info('DISTANCE CALCULATED', ['km' => $distance]);
        }

        $trip = Trip::create([
            'user_id'           => $user->id,
            'company_id' =>     isset($user->company_id) ? $user->company_id : 1,
            'trip_date'         => $validated['trip_date'] ?? now()->toDateString(),
            'start_time'        => $validated['start_time'] ?? now()->toTimeString(),
            'end_time'          => $validated['end_time'] ?? null,
            'start_lat'         => $validated['start_lat'],
            'start_lng'         => $validated['start_lng'],
            'end_lat'           => $validated['end_lat'] ?? null,
            'end_lng'           => $validated['end_lng'] ?? null,
            'total_distance_km' => $distance,
            'travel_mode'       => $validated['travel_mode'],
            'purpose'           => $validated['purpose'],
            'tour_type'         => $validated['tour_type'],
            'place_to_visit'    => $validated['place_to_visit'] ?? null,
            'starting_km'       => $validated['starting_km'] ?? null,
            'end_km'            => $validated['end_km'] ?? null,
            'start_km_photo'    => $startKmPhoto,
            'end_km_photo'      => $endKmPhoto,
            'status'            => 'pending',
            'approval_status'   => 'pending',
        ]);

        if($trip){
            TripLog::create([
                'trip_id' => $trip->id,
                'latitude' => $validated['start_lat'],
                'longitude' => $validated['start_lng'],
                'gps_status' => 1,
                'battery_percentage' => 0,
                'recorded_at' => now(),
            ]);
        }
        Log::info('TRIP CREATED SUCCESSFULLY', ['trip_id' => $trip->id,'user_id' => $user->id,]);

        // Attach customers if provided
        if (!empty($validated['customer_ids'])) {
            $trip->customers()->attach($validated['customer_ids']);
            Log::info('CUSTOMERS ATTACHED', ['customer_ids' => $validated['customer_ids']]);
        }else{
            Log::info('NO CUSTOMER IDS PROVIDED');
        }
        return $this->sendResponse($trip->load(["purpose", "tourType", "travelMode", "company", "approvedByUser", "user"]), "Day logs created successfully");
    }

    private function calculateDistanceFromLogs($tripId)
    {
        $logs = TripLog::where('trip_id', $tripId)->orderBy('recorded_at')->get();

        if ($logs->count() < 2) {
            Log::warning('Not enough trip logs to calculate distance', ['trip_id' => $tripId]);
            return 0;
        }

        $distance = 0;
        for ($i = 1; $i < $logs->count(); $i++) {
            $km = $this->calculateDistance(
                $logs[$i - 1]->latitude,
                $logs[$i - 1]->longitude,
                $logs[$i]->latitude,
                $logs[$i]->longitude
            );

            if (is_numeric($km) && !is_nan($km) && !is_infinite($km)) {
                $distance += $km;
            } else {
                Log::warning('Skipped invalid segment in distance calculation', [
                    'trip_id' => $tripId,
                    'index' => $i,
                    'value' => $km
                ]);
            }
        }

        return round($distance, 2);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        // 🧩 Clamp between -1 and 1 to prevent NaN from acos()
        if ($dist > 1) $dist = 1;
        if ($dist < -1) $dist = -1;

        $dist  = acos($dist);
        $dist  = rad2deg($dist);
        $km    = $dist * 111.13384;

        return round($km, 2);
    }
    
    public function lastActive()
    {
        $user = Auth::user();
        $trip = Trip::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->latest('trip_date')
            ->latest('start_time')
            ->first();

        if (!$trip) {
            return $this->sendResponse($trip, "No active trips found");
        }
        return $this->sendResponse($trip, "Trips fetched successfully");
    }
    public function close(Request $request)
    {
        try {
            Log::info('Trip close API called', ['request_data' => $request->all()]);

            // 🧩 Step 1: Validate input
            $validated = $request->validate([
                'end_time'       => 'required|date_format:H:i:s',
                'end_lat'        => 'required|numeric',
                'end_lng'        => 'required|numeric',
                'closenote'      => 'required|string',
                'end_km'         => 'required|string',
                'end_km_photo'   => 'required|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',
                'status'         => 'in:completed',
            ]);
            Log::info('Validation passed successfully', ['validated_data' => $validated]);

            // 🧩 Step 2: Find Trip
            $trip = Trip::findOrFail($request->id);
            Log::info('Trip found', ['trip_id' => $trip->id]);

            // 🧩 Step 3: Auth check
            $user = Auth::user();
            if ($trip->user_id !== $user->id) {
                Log::warning('Unauthorized trip access attempt', [
                    'user_id' => $user->id,
                    'trip_user_id' => $trip->user_id
                ]);
                return $this->sendError('Trip is not assigned you', [], 403);
            }

            // 🧩 Step 4: Already closed?
            if ($trip->status === 'completed') {
                Log::info('Trip already closed', ['trip_id' => $trip->id]);
                return $this->sendError('Trip is already closed.', [], 400);
            }

            // 🧩 Step 5: Handle photo upload
            $endKmPhoto = null;
            if ($request->hasFile('end_km_photo')) {
                $endKmPhoto = $request->file('end_km_photo')->store('trip_photos', 'public');
                Log::info('End KM photo uploaded', ['photo_path' => $endKmPhoto]);
            }

            // 🧩 Step 6: Calculate distance
            $total_distance_km = $this->calculateDistanceFromLogs($request->id);
            Log::info('Total distance calculated', ['distance_km' => $total_distance_km]);

            // 🧩 Step 7: Update trip
            $trip->update([
                'end_time'          => $validated['end_time'],
                'end_lat'           => $validated['end_lat'],
                'end_lng'           => $validated['end_lng'],
                'end_km'            => $validated['end_km'],
                'end_km_photo'      => $endKmPhoto,
                'total_distance_km' => $total_distance_km,
                'status'            => $validated['status'] ?? 'completed',
                'updated_at'        => Carbon::now(),
            ]);

            Log::info('Trip updated successfully', ['trip_id' => $trip->id]);

            // 🧩 Step 8: Return success
            return $this->sendResponse($trip, "Trip has been closed");

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in trip close API', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return $this->sendError('Validation failed', $e->errors(), 422);

        } catch (\Exception $e) {
            Log::error('Unexpected error in trip close API', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return $this->sendError('Something went wrong while closing trip.', [], 500);
        }
    }

    public function showTrip($id)
    {
        $user = Auth::user();
        $trip = Trip::findOrFail($id);
        return $this->sendResponse($trip->load(["purpose", "tourType", "travelMode", "company", "approvedByUser", "user"]), "Trip fetched successfully");
    }

    public function gpsStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'gps_flag' => 'required|in:0,1',
            'trip_id' => 'required|integer'
        ]);

        // Save record
        $log = GpsLog::create([
            'user_id' => $request->user_id,
            'gps_flag' => $request->gps_flag,
            'trip_id' => $request->trip_id
        ]);

        // return response()->json([
        //     "status" => true,
        //     "message" => "GPS Log Saved Successfully",
        //     "data" => $log
        // ], 200);
        return $this->sendResponse($log, "GPS Log Saved Successfully");
    }

   public function getMyTrips(Request $request)
    {
        $user = Auth::user();

        $trips = Trip::with([
                'tourType',
                'travelMode',
                'purpose',
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($data) {

                return [
                    'id' => $data->id,
                    'trip_date' => $data->trip_date,
                    'tour_type' => optional($data->tourType)->name,
                    'travel_mode' => optional($data->travelMode)->name,
                    'tour_purpose' => optional($data->purpose)->name,
                    'start_time' => $data->start_time,
                    'end_time' => $data->end_time,
                    'visit_place' => $data->place_to_visit,

                    'starting_km' => $data->starting_km,
                    'end_km' => $data->end_km,

                    'travel_km' => ($data->end_km && $data->starting_km)
                        ? ($data->end_km - $data->starting_km)
                        : 0,

                    'gps_km' => $data->total_distance_km ?? 0,

                    // km difference same as travel_km
                    'km_diff' => ($data->end_km && $data->starting_km)
                        ? ($data->end_km - $data->starting_km)
                        : 0,
                    'approval_status' => $data->approval_status,

                    'ta_exp' => "",
                    'da_exp' => "",
                    'other_exp' => "",
                    'total' => "",
                ];
            });

        return $this->sendResponse($trips, "Trips fetched successfully");
    }
}
