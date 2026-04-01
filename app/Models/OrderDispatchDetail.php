<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class OrderDispatchDetail extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'order_dispatch_id',
        'lr_number',
        'transport_name',
        'vehicle_no',
        'dispatch_image',
        'dispatch_date'
    ];

    public function orderDispatch()
    {
        return $this->belongsTo(OrderDispatch::class);
    }
}
