<?php

namespace App\Http\Requests\Tally;

class SalesBillRequest extends TallyFormRequest
{
    public function rules(): array
    {
        return [
            'financial_year' => ['required', 'string', 'max:20'],
            'invoice_date' => ['required', 'date'],
            'party_name' => ['required', 'string', 'max:255'],
            'product_name_with_packing' => ['required', 'string', 'max:255'],
            'bill_type' => ['required', 'string', 'max:100'],
            'qty' => ['required', 'numeric'],
            'amount' => ['required', 'numeric'],
            'gst_amount' => ['required', 'numeric'],
            'grand_total' => ['required', 'numeric'],
        ];
    }
}
