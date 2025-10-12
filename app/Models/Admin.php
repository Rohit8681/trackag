<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Admin extends Model
{
    use TenantConnectionTrait;
    protected $connection = 'mysql';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
