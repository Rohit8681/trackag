<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\TenantConnectionTrait;

class Stock extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'user_id',
        'customer_id',
        'product_id',
        'packing_id',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function packing()
    {
        return $this->belongsTo(ProductPacking::class, 'packing_id');
    }
}
