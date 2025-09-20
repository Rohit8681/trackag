<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depo extends Model
{
    protected $fillable = [
        'depo_code',
        'depo_name',
        'state_id',
        'district_id',
        'tehsil_id',
        'manage_by',
        'city',
        'status'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }
}
