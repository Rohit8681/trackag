<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class ProductPackingPrice extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'product_id',
        'packing_id',
        'state_id',
        'cash_price',
        'credit_price'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}

