<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $connection = 'mysql';
    
    protected $fillable = ['domain', 'tenant_id'];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }
}