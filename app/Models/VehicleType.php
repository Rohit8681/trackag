<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class VehicleType extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'vehicle_type',
        'is_deleted',
    ];
}
