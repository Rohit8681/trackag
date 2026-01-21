<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class ProductPacking extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'product_id',
        'packing_value',
        'packing_size',
        'shipper_type',
        'shipper_size',
        'unit_in_shipper',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function packingStates()
{
    return $this->hasMany(PackingState::class, 'packing_id', 'id');
}

}
