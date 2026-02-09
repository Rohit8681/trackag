<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Product extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'state_id',
        'product_name',
        'technical_name',
        'item_code',
        'product_category_id',
        'shipper_gross_weight',
        'master_packing',
        'gst',
        'status'
    ];

    public function packings()
    {
        return $this->hasMany(ProductPacking::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id');
    }

    public function states()
    {
        return $this->belongsToMany(State::class, 'product_state');
    }

    public function productStates()
    {
        return $this->hasMany(ProductState::class, 'product_id', 'id');
    }
}
