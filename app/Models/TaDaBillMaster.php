<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaDaBillMaster extends Model
{
    use HasFactory;

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
