<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class PackingState extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'packing_id',
        'state_id',
    ];
}
