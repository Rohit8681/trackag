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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name', // optional, but usually needed for Spatie package
    ];

    // If you have timestamps, you can add this or remove if not needed
    public $timestamps = true;
}
