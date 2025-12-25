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
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
// use Maatwebsite\Excel\Helpers\Date;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        // Lookup related data
        $state = State::where('name', $row['state'] ?? '')->first();
        $district = District::where('name', $row['district'] ?? '')->first();
        $tehsil = Tehsil::where('name', $row['tehsil'] ?? '')->first();
        $executive = User::where('name', $row['contact_person_name'])->first();
        $depo = Depo::where('depo_name', $row['depo'] ?? '')->first();

        return new Customer([
            'agro_name' => $row['agro_name'],
            'party_code' => $row['party_code'] ?? null,
            'state_id' => $state?->id,
            'district_id' => $district?->id,
            'tehsil_id' => $tehsil?->id,
            'address' => $row['address'] ?? null,
            'phone' => $row['phone'],
            'gst_no' => $row['gst_no'] ?? null,
            'contact_person_name' => $row['contact_person_name'] ?? null,
            // 'user_id' => $executive?->id,
            'depo_id' => $depo?->id,
            'credit_limit' => $row['credit_limit'] ?? 0,
            'party_active_since' => !empty($row['party_active_since'])
                ? (
                    is_numeric($row['party_active_since'])
                        ? Carbon::instance(
                            Date::excelToDateTimeObject($row['party_active_since'])
                        )->format('Y-m-d')
                        : Carbon::parse($row['party_active_since'])->format('Y-m-d')
                )
                : now()->format('Y-m-d'),
            'is_active' => (strtolower($row['status'] ?? '') === 'active' ? 1 : 0),
            'type' => 'web'
        ]);
    }

    // Required field validation
    public function rules(): array
    {
        return [
            'agro_name' => 'required',
            'phone' => 'required',
            'contact_person_name' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'agro_name.required' => 'Agro Name is required',
            'phone.required' => 'Phone is required',
            'contact_person_name.required' => 'Contact Person Name is required',
        ];
    }
}
