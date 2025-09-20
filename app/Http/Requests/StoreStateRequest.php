<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        $stateId = $this->route('state')?->id; 

        return [
            'country_id' => 'required|exists:countries,id',
            'name'       => 'required|string|max:100|unique:states,name,' . $stateId,
            'state_code' => 'nullable|string|max:10|unique:states,state_code,' . $stateId,
            'status'     => 'nullable|boolean',
        ];
    }
}
