<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\PartyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartyController extends BaseController
{
    public function index()
    {
        $user = Auth::user();

        $visits = PartyVisit::with(['customer', 'visitPurpose'])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->get();

        $visitsFormatted = $visits->map(function($visit) {
            return [
                'id' => $visit->id,
                'user_id' => $visit->user_id,
                'customer' => $visit->customer ? [
                    'id' => $visit->customer->id,
                    'name' => $visit->customer->name,
                ] : null,
                'visit_purpose' => $visit->visit_purpose,
                'visited_date' => $visit->visited_date ? $visit->visited_date : null,
                'check_in_time' => $visit->check_in_time ? $visit->check_in_time : null,
                'check_out_time' => $visit->check_out_time ? $visit->check_out_time : null,
                'followup_date' => $visit->followup_date ? $visit->followup_date : null,
                'remarks' => $visit->remarks,
                'latitude' => $visit->latitude,
                'longitude' => $visit->longitude,
                'agro_visit_image' => $visit->agro_visit_image ? asset('storage/' . $visit->agro_visit_image) : null,
                'created_at' => $visit->created_at,
                'updated_at' => $visit->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Party visits fetched successfully',
            'data' => $visitsFormatted
        ]);
    }

    public function partyVisitsStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'customer_id' => 'nullable|integer',
            'check_in_time' => 'nullable|date_format:Y-m-d H:i:s',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $validated['visited_date'] = Carbon::now();

        $partyVisit = PartyVisit::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Party visit saved successfully!',
            'data' => $partyVisit
        ]);
    }

    public function partyVisitCheckout(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'check_out_time' => 'nullable|date_format:Y-m-d H:i:s',
            'visit_purpose' => 'nullable|string',
            'followup_date' => 'nullable|date',
            'remarks' => 'nullable|string',
            'agro_visit_image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $partyVisit = PartyVisit::find($validated['id']);

        if ($request->hasFile('agro_visit_image')) {
            $path = $request->file('agro_visit_image')->store('party_visits', 'public');
            $validated['agro_visit_image'] = $path;
        }

        if (!$partyVisit) {
            return response()->json([
                'success' => false,
                'message' => 'Party visit not found'
            ], 404);
        }

        $partyVisit->check_out_time = $validated['check_out_time'] ?? now();
        $partyVisit->visit_purpose = $validated['visit_purpose'] ?? null;
        $partyVisit->followup_date = $validated['followup_date'] ?? null;
        $partyVisit->remarks = $validated['remarks'] ?? null;
        $partyVisit->agro_visit_image = $validated['agro_visit_image'] ?? null;
        $partyVisit->save();

        return response()->json([
            'success' => true,
            'message' => 'Checkout time updated successfully',
            'data' => $partyVisit
        ]);
    }
        
    public function newPartyStore(Request $request)
    {

        $validated = $request->validate([
            'visit_date' => 'required|date',
            'sales_person_name' => 'required|string',
            'agro_name' => 'required|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contact_person_name' => 'nullable|string',
            'working_with' => 'nullable|string',
            'party_documents.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:pending,approved,rejected,hold',
            'remarks' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $paths = [];
        if ($request->hasFile('party_documents')) {
            foreach ($request->file('party_documents') as $file) {
                $paths[] = $file->store('party_documents', 'public');
            }
        }

        $validated['party_documents'] = $paths;
        $validated['type'] = "mobile";

        $customer = Customer::create($validated);

        return $this->sendResponse($customer, "Customer saved successfully!");
    }
}

