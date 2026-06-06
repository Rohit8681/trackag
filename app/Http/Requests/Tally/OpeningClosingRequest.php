<?php

namespace App\Http\Requests\Tally;

class OpeningClosingRequest extends TallyFormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'party_name' => ['required', 'string', 'max:255'],
            'opening_balance_amt' => ['required', 'numeric'],
            'credit_amt' => ['required', 'numeric'],
            'debit_amt' => ['required', 'numeric'],
            'closing_balance_amt' => ['required', 'numeric'],
        ];
    }
}
