<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Pincode extends Model
{
    use TenantConnectionTrait;

    protected $fillable = ['city_id', 'pincode'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
