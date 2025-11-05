<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class TaDaVehicleSlab extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'ta_da_slab_id',
        'travel_mode_id',
        // 'vehicle_type_id',
        'travelling_allow_per_km',
        'type',
        'user_id',
        'designation_id'
    ];

    // Relation to main slab
    public function slab()
    {
        return $this->belongsTo(TaDaSlab::class, 'ta_da_slab_id');
    }

    // Relation to vehicle type
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
