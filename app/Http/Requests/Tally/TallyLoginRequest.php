<?php

namespace App\Http\Requests\Tally;

class TallyLoginRequest extends TallyFormRequest
{
    public function rules(): array
    {
        return [
            'company_code' => ['required', 'string', 'max:100'],
            'login_id' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }
}
