<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class District extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'country_id',
        'state_id',
        'name',
        'status',
    ];


    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function tehsils()
    {
        return $this->hasMany(Tehsil::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

   
}
