<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\State;
use App\Models\District;
use App\Models\Tehsil;
use App\Models\User;
use App\Models\Depo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            'agro_name' => 'required|string',
            'phone' => 'required',
            'contact_person_name' => 'required|string',

            // baaki optional
            'party_code' => 'nullable',
            'state' => 'nullable',
            'district' => 'nullable',
            'tehsil' => 'nullable',
            'address' => 'nullable',
            'gst_no' => 'nullable',
            'depo' => 'nullable',
            'credit_limit' => 'nullable|numeric',
            'party_active_since' => 'nullable',
            'status' => 'nullable',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'agro_name.required' => 'Agro Name required che',
            'phone.required' => 'Phone required che',
            'contact_person_name.required' => 'Contact Person Name required che',
        ];
    }
    public function model(array $row)
    {
        
        $state = State::where('name', $row['state'] ?? '')->first();
        $district = District::where('name', $row['district'] ?? '')->first();
        $tehsil = Tehsil::where('name', $row['tehsil'] ?? '')->first();
        $executive = User::where('name', $row['contact_person_name'] ?? '')->first();
        $depo = Depo::where('depo_name', $row['depo'] ?? '')->first();
        
        return new Customer([
            'agro_name' => $row['agro_name'],
            'party_code' => $row['party_code'],
            'state_id' => $state?->id,
            'district_id' => $district?->id,
            'tehsil_id' => $tehsil?->id,
            'address' => $row['address'] ?? null,
            'phone' => $row['phone'],
            'gst_no' => $row['gst_no'],
            'user_id' => $executive?->id,
            'depo_id' => $depo?->id,
            'credit_limit' => $row['credit_limit'] ?? 0,
            'party_active_since' => isset($row['party_active_since']) 
            ? Date::excelToDateTimeObject($row['party_active_since'])->format('Y-m-d') 
            : now(),
            'is_active' => ($row['status'] == 'active' ? 1 : 0),
        ]);
    }
}
