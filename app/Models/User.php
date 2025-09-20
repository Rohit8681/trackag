<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($query) {
            if (tenancy()->tenant) {
                $query->getModel()->setConnection('tenant');
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'mobile',
        'image',

        // Added fields from the extended schema
        'user_type',
        'user_code',
        'headquarter',
        'date_of_birth',
        'joining_date',
        'emergency_contact_no',
        'gender',
        'marital_status',
        'designation_id',
        'role_rights',
        'reporting_to',
        'is_self_sale',
        'is_multi_day_start_end_allowed',
        'is_allow_tracking',
        'address',
        'state_id',
        'district_id',
        'city_id',
        'tehsil_id',
        'latitude',
        'longitude',
        'pincode_id',
        'depo',
        'postal_address',
        'status',

        // ✨ Added for Multi-Tenant Support
        'company_id',
        'user_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'joining_date' => 'date',
            'is_self_sale' => 'boolean',
            'is_multi_day_start_end_allowed' => 'boolean',
            'is_allow_tracking' => 'boolean',
        ];
    }

    // Existing relationships
    public function state() { return $this->belongsTo(State::class); }
    public function district() { return $this->belongsTo(District::class); }
    public function city() { return $this->belongsTo(City::class); }
    public function tehsil() { return $this->belongsTo(Tehsil::class); }
    public function pincode() { return $this->belongsTo(Pincode::class);}

    // ✨ Company relationship (multi-tenant)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // ✨ Check if user is master admin
    public function isMasterAdmin()
    {
        return $this->user_level === 'master_admin';
    }

    public function getAllPermissionsList()
    {
        return $this->getAllPermissions()->pluck('name');
    }

    public function reportingManager()
{
    return $this->belongsTo(User::class, 'reporting_to');
}

public function designation()
{
    return $this->belongsTo(Designation::class);
}

// In App\Models\User.php

public function sessions()
{
    return $this->hasMany(UserSession::class);
}

public function activeSessions()
{
    return $this->hasMany(\App\Models\UserSession::class)->whereNull('logout_at');
}





}
