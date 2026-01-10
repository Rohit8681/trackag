<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Brochure extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        // 'date',
        'state_id',
        'pdf_path'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
