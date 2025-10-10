<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaDaSlab extends Model
{
    protected $fillable = ['type','designation','max_monthly_travel','km','approved_bills_in_da','user_id'];
    protected $casts = [
        'approved_bills_in_da' => 'array',
    ];

    public function vehicleSlabs()
    {
        return $this->hasMany(TaDaVehicleSlab::class);
    }

    public function tourSlabs()
    {
        return $this->hasMany(TaDaTourSlab::class);
    }
}


