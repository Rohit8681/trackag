<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class TaDaBillMaster extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'designation_id',
        'day_limit',
        'status',
    ];

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}
