<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'bill_date',
        'bill_type',
        'bill_details_description',
        'travel_mode_id',
        'amount',
        'image',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'bill_type' => 'array', // store multiple bill types as JSON
    ];

    /**
     * Relationships
     */

    // Each expense belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // If you have a travel_modes table
    public function travelMode()
    {
        return $this->belongsTo(TravelMode::class);
    }

    /**
     * Accessor to show image path
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/expenses/' . $this->image);
        }
        return null;
    }
}
