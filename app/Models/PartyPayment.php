<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class PartyPayment extends Model
{
    use HasFactory,TenantConnectionTrait;

    protected $fillable = [
        'user_id',
        'customer_id',
        'payment_mode',
        'bank_name',
        'branch_name',
        'payment_date',
        'amount',
        'remark',
        'image',
        'status',
        'clear_return_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
