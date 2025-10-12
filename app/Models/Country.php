<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Country extends Model
{
    use TenantConnectionTrait;
    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
