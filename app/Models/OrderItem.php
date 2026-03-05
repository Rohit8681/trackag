<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class OrderItem extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'order_id',
        'product_id',
        'packing_id',
        'shipper_size',
        'price',
        'total_price',
        'gst',
        'discount',
        'grand_total',
        'qty'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function packing()
    {
        return $this->belongsTo(ProductPacking::class, 'packing_id');
    }
}
