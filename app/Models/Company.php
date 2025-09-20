<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The connection name for the model.
     * This model uses the central database connection.
     */
    protected $connection = 'mysql';
    
    protected $fillable = [
        'tenant_id','name','code','owner_name','gst_number','contact_no',
        'contact_no2','telephone_no','email','logo','website','state',
        'product_name','subscription_type','tally_configuration','address',
        'subdomain','is_active','status'
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
