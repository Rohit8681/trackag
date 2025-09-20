<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class City extends Model
{
    use TenantConnectionTrait;
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function tehsils()
    {
        return $this->hasMany(Tehsil::class);
    }

    public function pincodes()
    {
        return $this->hasMany(Pincode::class);
    }
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
