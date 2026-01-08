<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class Attendance extends Model
{
    use HasFactory,TenantConnectionTrait;
    protected $fillable = [
        'user_id',
        'attendance_date',
        'status'
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
