<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class UserStateAccess extends Model
{
    use TenantConnectionTrait;
    protected $fillable = ['user_id', 'state_ids'];

    protected $casts = [
        'state_ids' => 'array', // automatically cast JSON to array
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
