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
        'dispatch_type'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function detail()
    {
        return $this->hasOne(OrderDispatchDetail::class);
    }
}
