<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Leave extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'leave_name',
        'leave_code',
        'is_paid',
        'status',
    ];
}
