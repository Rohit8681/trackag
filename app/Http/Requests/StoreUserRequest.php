<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'password' => 'required|confirmed',
            'mobile' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'marital_status' => 'nullable|string|in:Single,Married',
            'address' => 'nullable|string',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'tehsil_id' => 'required|exists:tehsils,id',
            'pincode' => 'nullable|string',
            'postal_address' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'user_code' => 'required|string|unique:users,user_code',
            'designation_id' => 'required|exists:designations,id',
            'reporting_to' => 'nullable|string',
            'headquarter' => 'required|string',
            'user_type' => 'nullable|string',
            'joining_date' => 'required|date',
            'emergency_contact_no' => 'required|string',
            'is_self_sale' => 'required|boolean',
            'is_multi_day_start_end_allowed' => 'required|boolean',
            'is_allow_tracking' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
            'roles' => 'required|array',
            'company_id' => 'nullable|exists:companies,id',
            'company_mobile' => 'required|string|max:20',
            'village' => 'nullable|string|max:100',
            'depo_id' => 'nullable|exists:depos,id',
            'is_web_login_access' => 'required|boolean',
            'account_no' => 'nullable|string|max:30',
            'branch_name' => 'nullable|string|max:100',
            'ifsc_code' => 'nullable|string|max:20',
            'pan_card_no' => 'nullable|string|max:20',
            'aadhar_no' => 'nullable|string|max:20',
            'driving_lic_no' => 'nullable|string|max:50',
            'driving_expiry' => 'nullable|date',
            'passport_no' => 'nullable|string|max:50',
            'passport_expiry' => 'nullable|date',
            'cancel_cheque_photos'   => 'nullable|array|max:3',
            'cancel_cheque_photos.*' => 'image|max:5120', 
        ];
    }
}
