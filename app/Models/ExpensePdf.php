<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class ExpensePdf extends Model
{
    use TenantConnectionTrait;

    protected $fillable = [
        'user_id',
        'pdf_path',
        'month',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
