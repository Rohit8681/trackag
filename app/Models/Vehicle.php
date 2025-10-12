<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Vehicle extends Model
{
    use HasFactory, TenantConnectionTrait;

    // Table name (optional if using default 'vehicles')
    protected $table = 'vehicles';

    // Mass assignable fields
    protected $fillable = [
        'vehicle_name',
        'vehicle_number',
        'vehicle_type',
        'assigned_person',
        'milage',
        'assign_date',
        'status',
    ];

    // Relationship with User (assigned person)
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_person');
    }
}
