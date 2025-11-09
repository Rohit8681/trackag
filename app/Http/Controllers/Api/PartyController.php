<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\PartyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartyController extends BaseController
{
    public function index(){
        $user = Auth::user();
        $visits = PartyVisit::where('user_id', $user->id)->orderByDesc('id')
            ->get();
        return $this->sendResponse($visits, 'Party visits fetch successfully');
    }
    public function partyVisitsStore(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:daily,monthly',
            'user_id' => 'required',
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
            $validated += $request->validate([
                'employee_name' => 'required|string',
                'shop_name' => 'required|string',
                'visit_count' => 'nullable|integer',
                'last_visit_date' => 'nullable|string',
                'visit_purpose_count' => 'nullable|array',
            ]);
        }

        $partyVisit = PartyVisit::create($validated);

        return $this->sendResponse($partyVisit, "Party visit saved successfully!");

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

