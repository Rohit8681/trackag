<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    /**
     * The connection name for the model.
     * This model uses the central database connection.
     */
    protected $connection = 'mysql';
    
    protected $fillable = ['domain', 'tenant_id'];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }
}