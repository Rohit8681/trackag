<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\TenantConnectionTrait;

class TripLog extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'trip_id',
        'latitude',
        'longitude',
        'recorded_at',
        'battery_percentage',
        'gps_status'
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
