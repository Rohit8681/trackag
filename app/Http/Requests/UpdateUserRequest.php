<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email' => "nullable|email|max:255",
            'mobile' => 'required|string|max:20',
            'company_mobile' => 'required|string|max:20',
            // 'password' => 'nullable|confirmed',
            'password' => 'nullable|same:password_confirmation',
            'image' => 'nullable|image|max:5120',
            'cancel_cheque_photos.*' => 'nullable|image|max:5120',
            // 'user_code' => 'required|string|unique:users,user_code,' . $this->id,
            'user_code' => [
    'required',
    'string',
    Rule::unique('users', 'user_code')->ignore($this->route('user')->id),
],
            'designation_id' => 'required',
            'reporting_to' => 'required',
            'headquarter' => 'required|string|max:255',
            'user_type' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'required|date',
            'emergency_contact_no' => 'required|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married',
            'address' => 'nullable|string|max:500',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'tehsil_id' => 'required|exists:tehsils,id',
            'pincode' => 'nullable|string|max:10',
            'postal_address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'depo_id' => 'nullable|exists:depos,id',
            'is_self_sale' => 'required|boolean',
            'is_multi_day_start_end_allowed' => 'required|boolean',
            'is_allow_tracking' => 'nullable|boolean',
            'is_web_login_access' => 'required|boolean',
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name',
            'company_id' => 'nullable|exists:companies,id',
        ];
    }

}
