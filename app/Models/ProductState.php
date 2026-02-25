<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class ProductState extends Model
{
    use TenantConnectionTrait;
    
    protected $fillable = [
        'product_id',
        'state_id',
        'is_rpl',
        'is_ncr',
        'is_advance',
        'status'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
