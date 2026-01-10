<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class PriceList extends Model
{
    use TenantConnectionTrait;
    protected $table = 'price_lists';

    protected $fillable = [
        'state_id',
        'pdf_path',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
