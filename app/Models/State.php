<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class State extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'country_id',
        'name',
        'state_code',
        'status',
    ];
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
