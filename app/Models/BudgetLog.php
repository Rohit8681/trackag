<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class BudgetLog extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'budget_id',
        'user_id',
        'admin_id',
        'financial_year',
        'month',
        'old_value',
        'new_value',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
