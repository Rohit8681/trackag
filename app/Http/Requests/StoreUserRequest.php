<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        // You can customize authorization logic here; true means everyone is allowed
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'mobile' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'marital_status' => 'nullable|string|in:Single,Married',
            'address' => 'nullable|string',
            'state_id' => 'nullable|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',

            'pincode_id' => 'nullable|string',
            'postal_address' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'user_code' => 'nullable|string',
            'designation_id' => 'nullable|exists:designations,id',
            'reporting_to' => 'nullable|string',
            'headquarter' => 'nullable|string',
            'user_type' => 'nullable|string',
            'joining_date' => 'nullable|date',
            'emergency_contact_no' => 'nullable|string',
            'is_self_sale' => 'nullable|boolean',
            'is_multi_day_start_end_allowed' => 'nullable|boolean',
            'is_allow_tracking' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
            'roles' => 'nullable|array',
            'company_id' => 'nullable|exists:companies,id',
        ];
    }
}
