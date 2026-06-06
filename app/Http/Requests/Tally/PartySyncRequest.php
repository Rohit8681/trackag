<?php

namespace App\Http\Requests\Tally;

class PartySyncRequest extends TallyFormRequest
{
    public function rules(): array
    {
        return [
            'group_name' => ['required', 'string', 'max:255'],
            'party_name' => ['required', 'string', 'max:255'],
            'phone_1' => ['nullable', 'string', 'max:30'],
            'phone_2' => ['nullable', 'string', 'max:30'],
            'contact_person_name' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'gst_no' => ['nullable', 'string', 'max:50'],
            'party_create_date' => ['nullable', 'date'],
        ];
    }
}
