<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id; 

        $rules = [
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'code' => 'required|string|unique:companies,code' . ($companyId ? ',' . $companyId : ''),
            'gst_number' => 'required|string|max:50',
            'address' => 'nullable|string',
            'contact_no' => 'required|string|max:20',
            'contact_no2' => 'nullable|string|max:20',
            'telephone_no' => 'nullable|string|max:20',
            'email' => 'required|email',
            'website' => 'required|url',
            'state' => 'required|array',
            'state.*' => 'exists:states,id',
            'product_name' => 'nullable|string|max:255',
            'subscription_type' => 'nullable|string|max:100',
            'tally_configuration' => 'nullable|boolean',
            
            'start_date' => 'required|date',
            'validity_upto' => 'required|date|after_or_equal:start_date',
            'user_assigned' => 'required|integer',
        ];

        if ($this->isMethod('post')) {
            // Create (store)
            $rules['user_password'] = 'required|confirmed';
            $rules['logo'] = 'required|image|mimes:png|max:2048';
        } else {
            // Update
            $rules['user_password'] = 'nullable|confirmed';
            $rules['logo'] = 'nullable|image|mimes:png|max:2048';
        }

        return $rules;
    }

    /**
     * Custom messages (optional).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Company name is required.',
            'code.unique' => 'This company code is already taken.',
            'user_password.required' => 'Password is required.',
            'user_password.min' => 'Password must be at least 6 characters.',
            'user_password.confirmed' => 'Password and Confirm Password do not match.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'validity_upto.required' => 'Validity date is required.',
            'validity_upto.date' => 'Validity date must be a valid date.',
            'validity_upto.after_or_equal' => 'Validity date must be after or equal to start date.',
            'user_assigned.required' => 'User assigned  is required.',
            'user_assigned.integer' => 'User assigned must be a valid user ID.',
        ];
    }
}
