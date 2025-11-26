<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class GpsLog extends Model
{
    use HasFactory,TenantConnectionTrait;

    protected $table = 'gps_logs';

    // Fillable fields
    protected $fillable = [
        'user_id',
        'gps_flag',
    ];

    // Relation With User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
