<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class Designation extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'company_id',
        'name',
        'description',
    ];

    /**
     * Relationship: A designation belongs to a company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
