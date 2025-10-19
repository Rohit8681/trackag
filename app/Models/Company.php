<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class Company extends Model
{
    use TenantConnectionTrait;

    
    protected $fillable = [
        'tenant_id','name','code','owner_name','gst_number','contact_no',
        'contact_no2','telephone_no','email','logo','website','state',
        'product_name','subscription_type','tally_configuration','address',
        'subdomain','is_active','status','start_date','validity_upto','user_assigned','password',
        'created_at','updated_at'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(\Spatie\Permission\Models\Role::class);
    }

    /**
     * Get the designations for the company.
     */
    public function designations()
    {
        return $this->hasMany(Designation::class);
    }
}
