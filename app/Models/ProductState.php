<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class ProductState extends Model
{
    use TenantConnectionTrait;
    
    protected $fillable = [
        'product_id',
        'state_id',
    ];
}
