<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class Customer extends Model
{
    use TenantConnectionTrait;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'user_id',      // ✅ updated from executive_id
        'company_id',
        'is_active',
    ];

    public function user() // ✅ renamed from executive() to match user_id
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
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

}
