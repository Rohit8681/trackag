<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$userId}",
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|confirmed|min:6',
            'image' => 'nullable|image|max:5120',

            // Personal & Contact Information
            'user_code' => 'nullable|string|max:100',
            'designation_id' => 'nullable|exists:designations,id',
            'reporting_to' => 'nullable|string|max:255',
            'headquarter' => 'nullable|string|max:255',
            'user_type' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'emergency_contact_no' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married',
            'address' => 'nullable|string|max:500',
            'state_id' => 'nullable|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',
            'pincode_id' => 'nullable|string|max:10',
            'postal_address' => 'nullable|string|max:500',
            // 'latitude' => 'nullable|numeric|between:-90,90',
            // 'longitude' => 'nullable|numeric|between:-180,180',
            'depo' => 'nullable|string|max:255',

            // Boolean/Flags
            'is_self_sale' => 'nullable|boolean',
            'is_multi_day_start_end_allowed' => 'nullable|boolean',
            'is_allow_tracking' => 'nullable|boolean',

            // Roles
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
            'company_id' => 'nullable|exists:companies,id',

        ];
    }
}
