<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class UserDepoAccess extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'user_id',
        'state_id',
        'depo_ids'
    ];

    protected $casts = [
        'depo_ids' => 'array', // automatically cast JSON to array
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }
}
