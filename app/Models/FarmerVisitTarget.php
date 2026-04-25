<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class FarmerVisitTarget extends Model
{
    use TenantConnectionTrait;

    protected $fillable = ['target'];
}
