<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class Depo extends Model
{
    use TenantConnectionTrait;

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

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'manage_by', 'id');
    }

    public function users(){
        return $this->belongsTo(User::class,'manage_by', 'id');
    }
}
