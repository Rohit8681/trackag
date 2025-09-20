<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{


    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    /**
     * One Country has many States
     */
    public function states()
    {
        return $this->hasMany(State::class);
    }
}
