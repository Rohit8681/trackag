<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class Customer extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'type',
        'visit_date',
        'sales_person_name',
        'agro_name',
        'contact_person_name',
        'party_code',
        'state_id',
        'district_id',
        'tehsil_id',
        'gst_no',
        'credit_limit',
        'depo_id',
        'party_active_since',
        'name',
        'email',
        'phone',
        'address',
        'user_id',
        'is_active',
        'working_with',
        'party_documents',
        'status',
        'remarks',
    ];

    protected $casts = [
        'party_documents' => 'array',
        'is_active' => 'boolean',
        'visit_date' => 'date',
        'party_active_since' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'customer_trip');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class, 'tehsil_id');
    }
}
