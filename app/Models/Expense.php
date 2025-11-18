<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TenantConnectionTrait;

class Expense extends Model
{
    use HasFactory, SoftDeletes,TenantConnectionTrait;

    protected $fillable = [
        'user_id',
        'bill_date',
        'bill_title',
        'bill_type',
        'bill_details_description',
        'travel_mode',
        'amount',
        'image',
        'approval_status'
    ];

    protected $casts = [
        'bill_date' => 'date',
        // 'bill_type' => 'array', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // If you have a travel_modes table
    public function travelMode()
    {
        return $this->belongsTo(TravelMode::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/expenses/' . $this->image);
        }
        return null;
    }
}
