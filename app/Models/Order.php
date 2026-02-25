<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class Order extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'order_no',
        'user_id',
        'party_id',
        'order_type',
        'depo_id',
        'delivery_place',
        'preferred_transport',
        'remark',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'party_id', 'id');
    }
}
