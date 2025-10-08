<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'holiday_date',
        'holiday_name',
        'holiday_type',
        'is_paid',
        'status',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
