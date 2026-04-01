<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class OrderDispatch extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'dispatch_qty',
        'lr_number',
        'transport_name',
        'vehicle_no',
        'dispatch_date',
        'dispatch_image'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
