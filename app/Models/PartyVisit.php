<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class PartyVisit extends Model
{
    use HasFactory,TenantConnectionTrait;

    protected $fillable = [
        'type',
        'user_id',
        'employee_name',
        'visited_date',
        'agro_name',
        'check_in_out_duration',
        'visit_purpose',
        'followup_date',
        'agro_visit_image',
        'remarks',
        'shop_name',
        'visit_count',
        'last_visit_date',
        'visit_purpose_count',
    ];

    protected $casts = [
        'visit_purpose_count' => 'array',
        'visited_date' => 'date',
        'followup_date' => 'date',
    ];
}

