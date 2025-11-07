<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Holiday extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'state_ids',
        'holiday_date',
        'holiday_name',
        'holiday_type',
        'is_paid',
        'status',
    ];
    protected $casts = [
        'state_ids' => 'array',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
