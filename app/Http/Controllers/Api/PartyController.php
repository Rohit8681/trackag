<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\PartyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartyController extends BaseController
{
    public function partyVisitsStore(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:daily,monthly',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($request->type === 'daily') {
            $validated += $request->validate([
                'visited_date' => 'required|date',
                'employee_name' => 'required|string',
                'agro_name' => 'required|string',
                'check_in_out_duration' => 'nullable|string',
                'visit_purpose' => 'nullable|string',
                'followup_date' => 'nullable|date',
                'remarks' => 'nullable|string',
                'agro_visit_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('agro_visit_image')) {
                $path = $request->file('agro_visit_image')->store('party_visits', 'public');
                $validated['agro_visit_image'] = $path;
            }
        } 
        else {
            // Monthly
            $validated += $request->validate([
                'employee_name' => 'required|string',
                'shop_name' => 'required|string',
                'visit_count' => 'nullable|integer',
                'last_visit_date' => 'nullable|string',
                'visit_purpose_count' => 'nullable|array',
            ]);
        }

        $partyVisit = PartyVisit::create($validated);

        return response()->json([
            'message' => 'Party visit saved successfully!',
            'data' => $partyVisit
        ], 201);
    }

    public function newPartyStore(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:web,mobile',
        ]);

        if ($request->type === 'mobile') {
            $validated += $request->validate([
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

            // Handle multiple image uploads
            $paths = [];
            if ($request->hasFile('party_documents')) {
                foreach ($request->file('party_documents') as $file) {
                    $paths[] = $file->store('party_documents', 'public');
                }
            }

            $validated['party_documents'] = $paths;
        } else {
            // Web form fields
            $validated += $request->validate([
                'agro_name' => 'required|string',
                'contact_person_name' => 'nullable|string',
                'party_code' => 'nullable|string',
                'state_id' => 'nullable|integer',
                'district_id' => 'nullable|integer',
                'tehsil_id' => 'nullable|integer',
                'email' => 'nullable|email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'gst_no' => 'nullable|string',
                'credit_limit' => 'nullable|string',
                'depo_id' => 'nullable|integer',
                'party_active_since' => 'nullable|date',
                'is_active' => 'nullable|boolean',
                'user_id' => 'nullable|exists:users,id',
            ]);
        }

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer saved successfully!',
            'data' => $customer
        ], 201);
    }
}

