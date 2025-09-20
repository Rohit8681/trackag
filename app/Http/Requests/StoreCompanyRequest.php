<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add condition if only admins can create company
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Company Fields
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:companies,code',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'owner_name' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'contact_no' => 'nullable|string|max:20',
            'contact_no2' => 'nullable|string|max:20',
            'telephone_no' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'state' => 'nullable|string|max:100',
            'product_name' => 'nullable|string|max:255',
            'subscription_type' => 'nullable|string|max:100',
            'tally_configuration' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:png|max:2048',
            'subdomain' => 'nullable|string|alpha_dash|unique:companies,subdomain',

            // Admin User Fields
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|min:6|confirmed',
            'user_mobile' => 'nullable|string|max:20',
            'user_dob' => 'nullable|date',
            'user_gender' => 'nullable|string',
            'user_marital_status' => 'nullable|string',
            'user_address' => 'nullable|string',
            'state_id' => 'nullable|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',
            'pincode_id' => 'nullable|exists:pincodes,id',
            'postal_address' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'user_type' => 'nullable|string',
            'user_code' => 'nullable|string|unique:users,user_code',
            'designation_id' => 'nullable|exists:designations,id',
            'reporting_to' => 'nullable|exists:users,id',
            'headquarter' => 'nullable|string',
            'is_self_sale' => 'nullable|boolean',
            'is_multi_day_start_end_allowed' => 'nullable|boolean',
            'is_allow_tracking' => 'nullable|boolean',
        ];
    }

    /**
     * Custom messages (optional).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'user_name.required' => 'Admin user name is required.',
            'user_email.unique' => 'This email is already taken for another user.',
            'subdomain.unique' => 'This subdomain is already taken.',
        ];
    }
}
