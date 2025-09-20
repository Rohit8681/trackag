<?php

namespace App\Http\Requests\Auth;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Everyone is allowed to attempt login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for login request.
     */
    public function rules(): array
    {
        $rules = [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'company_id' => 'required|string',
        ];

        return $rules;
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required!',
            'email.email' => 'Please provide a valid email address!',
            'email.exists' => 'No user found with this email address!',
            'password.required' => 'Password is required!',
            'password.min' => 'Password must be at least 6 characters!',
            'company_id.required' => 'Company code is required!',
            'company_id.exists' => 'Invalid company code!',
        ];
    }
}
