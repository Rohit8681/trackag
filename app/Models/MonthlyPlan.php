<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyPlan extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'packing_id',
        'state_id',
        'month',
        'year',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function packing()
    {
        return $this->belongsTo(ProductPacking::class, 'packing_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
