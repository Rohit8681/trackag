<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class TaDaSlab extends Model
{
    use TenantConnectionTrait;
    protected $fillable = ['type','max_monthly_travel','km','approved_bills_in_da','user_id','approved_bills_in_da_slab_wise'];
    protected $casts = [
        'approved_bills_in_da' => 'array',
        'approved_bills_in_da_slab_wise' => 'array',
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


