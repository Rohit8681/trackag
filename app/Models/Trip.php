<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\TenantConnectionTrait;

class Trip extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'user_id',
        'company_id',
        'trip_date',
        'start_time',
        'end_time',
        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'total_distance_km',
        'travel_mode',
        'purpose',
        'tour_type',
        'place_to_visit',
        'closenote',
        'starting_km',
        'end_km',
        'status',
        'approval_status',
        'approval_reason',
        'approved_by',
        'approved_at',
        'start_km_photo',
        'end_km_photo',
    ];

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function tripLogs(): HasMany
    {
        return $this->hasMany(TripLog::class)->orderBy('recorded_at');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_trip');
    }


    public function purpose(): BelongsTo
    {
        return $this->belongsTo(Purpose::class, 'purpose');
    }
    public function tourType(): BelongsTo
    {
        return $this->belongsTo(TourType::class, 'tour_type');
    }
    public function travelMode(): BelongsTo
    {
        return $this->belongsTo(TravelMode::class, 'travel_mode');
    }
}
