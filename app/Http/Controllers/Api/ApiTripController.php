<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Purpose;
use App\Models\TourType;
use App\Models\TravelMode;
use App\Models\Trip;
use App\Models\User;
use App\Models\TripLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiTripController extends BaseController
{

    public function getTourDetails()
    {
        $user = Auth::user(); // or just Auth::user() if 'api' is default guard

        $tourPurposes = Purpose::where('company_id', $user->company_id)->get();
        $vehicleTypes = TravelMode::where('company_id', $user->company_id)->get();
        $tourTypes = TourType::where('company_id', $user->company_id)->get();
        $success = [];
        $success['tourPurposes'] = $tourPurposes;
        $success['vehicleTypes'] = $vehicleTypes;
        $success['tourTypes'] = $tourTypes;
        // Return the response
        return $this->sendResponse($success, 'Tour details fetch successfully');
    }
    public function fetchCustomer()
    {
        // Fetch all tour logs from the database

        $user = Auth::user(); // or just Auth::user() if 'api' is default guard

        // Only fetch day logs for the authenticated user
        $customers = Customer::where('is_active', true)
            ->where('company_id', $user->company_id)
            ->latest()
            ->get();

        // Return the view and pass the data

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

    // Store a new trip log point
    public function logPoint(Request $request)
    {
        $validated = $request->validate([
            'trip_id'     => 'required|exists:trips,id',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'battery_percentage' => 'nullable|string|numeric',
            'gps_status' => 'nullable|string|numeric',
            'recorded_at' => 'nullable|date',
        ]);

        // Check if the trip is completed
        $trip = Trip::find($validated['trip_id']);

        if ($trip->status === 'completed') {
            return $this->sendError("Cannot log points for a completed trip", [], 403);
        }

        $log = TripLog::create([
            'trip_id'     => $validated['trip_id'],
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'battery_percentage'   => $validated['battery_percentage'] ?? null,
            'gps_status'   => $validated['gps_status'] ?? null,
            'recorded_at' => $validated['recorded_at'] ?? now(),
        ]);

        return $this->sendResponse($log, "Trip log recorded successfully");
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
        $validated = $request->validate([
            'trip_date'      => 'nullable|date',
            'start_time'     => 'nullable',
            'start_lat'      => 'required|numeric',
            'start_lng'      => 'required|numeric',
            'travel_mode'    => 'required|exists:travel_modes,id',
            'purpose'        => 'required|exists:purposes,id',
            'tour_type'      => 'required|exists:tour_types,id',
            'place_to_visit' => 'nullable|string',
            'starting_km'    => 'nullable|string',
            'start_km_photo' => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',
            'customer_ids'   => 'nullable|array',
            'customer_ids.*' => 'exists:customers,id'
        ]);

        $user = Auth::user();

        // Handle photo uploads
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
        }

        $trip = Trip::create([
            'user_id'           => $user->id,
            'company_id'        => $user->company_id,
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

        // Attach customers if provided
        if (!empty($validated['customer_ids'])) {
            $trip->customers()->attach($validated['customer_ids']);
        }
        return $this->sendResponse($trip->load(["purpose", "tourType", "travelMode", "company", "approvedByUser", "user"]), "Day logs created successfully");
    }

    // Calculate total distance from trip logs
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

    // Calculate distance between two geo-points
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
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
        // 1️⃣  Authorise: only the owner (or an admin) may close the trip.
        // 2️⃣  Validate incoming data.
        $validated = $request->validate([
            'end_time' => 'required|date_format:H:i:s',   // send as 24h time, e.g. 17:45:00
            'end_lat'  => 'required|numeric',
            'end_lng'  => 'required|numeric',
            'closenote'         => 'required|string',
            'end_km'         => 'required|string',
            'end_km_photo'   => 'required|mimes:jpeg,jpg,png,bmp,gif,svg,webp,tiff,ico|max:5120',
            'status'   => 'in:completed',                 // optional override; default below
        ]);
        $trip = Trip::findOrFail($request->id);

        $user = Auth::user();
        if ($trip->user_id !== $user->id) {
            return $this->sendError('Trip is not assigned you', [], 403);
        }

        // 3️⃣ Already closed
        if ($trip->status === 'completed') {
            return $this->sendError('Trip is already closed.', [], 400);
        }
        $endKmPhoto = $request->hasFile('end_km_photo')
            ? $request->file('end_km_photo')->store('trip_photos', 'public')
            : null;

        $total_distance_km = $this->calculateDistanceFromLogs($request->id);
        // 4️⃣  Update the trip.
        $trip->update([
            'end_time'          => $validated['end_time'],
            'end_lat'           => $validated['end_lat'],
            'end_lng'           => $validated['end_lng'],
            'end_km'            => $request->end_km,
            'end_km_photo'      => $endKmPhoto,
            'total_distance_km'      => $total_distance_km,
            'status'            => $validated['status']   ?? 'completed',
            'updated_at'        => Carbon::now(),         // or leave for Eloquent timestamps
        ]);

        // 5️⃣  Return a consistent API response.
        return $this->sendResponse($trip, "Trip has been closed");
    }

    public function showTrip($id)
    {
        $user = Auth::user();
        $trip = Trip::findOrFail($id);
        return $this->sendResponse($trip->load(["purpose", "tourType", "travelMode", "company", "approvedByUser", "user"]), "Trip fetched successfully");
    }
}
