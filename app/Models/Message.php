<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class Message extends Model
{
    use HasFactory,TenantConnectionTrait;

    protected $fillable = [
        'state_id',
        'user_id',
        'message',
        'type',
    ];

    /* Relationships */

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
