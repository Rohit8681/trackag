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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function packing()
    {
        return $this->belongsTo(ProductPacking::class, 'packing_id');
    }
}
