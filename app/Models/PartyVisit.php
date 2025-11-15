<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class PartyVisit extends Model
{
    use HasFactory,TenantConnectionTrait;

    protected $fillable = [
        'user_id',
        'customer_id',
        'visited_date',
        'check_in_time',
        'check_out_time',
        'visit_purpose_id',
        'followup_date',
        'agro_visit_image',
        'remarks',
    ];

    protected $casts = [
        'visited_date' => 'date',
        'followup_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function visitPurpose()
    {
        return $this->belongsTo(Purpose::class, 'visit_purpose_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

