<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\TenantConnectionTrait;

class Role extends Model
{
    use HasRoles, TenantConnectionTrait;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    protected $fillable = [
        'name',
        'guard_name', 
    ];

    public $timestamps = true;
}
