<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\TenantConnectionTrait;

class UserSession extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'platform',
        'login_at',
        'logout_at',
        'session_duration'
    ];

    protected $casts = [
        'login_at'  => 'datetime',
        'logout_at' => 'datetime',
    ];

    protected $appends = ['formatted_login_at', 'formatted_logout_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Session duration formatted string
    public function getFormattedDurationAttribute()
    {
        if ($this->session_duration) {
            return gmdate('H:i:s', $this->session_duration);
        } elseif ($this->login_at) {
            return gmdate('H:i:s', $this->login_at->diffInSeconds(now())) . ' (Active)';
        } else {
            return 'N/A';
        }
    }

    // Formatted Login At
    public function getFormattedLoginAtAttribute()
    {
        return $this->login_at ? $this->login_at->format('d-m-Y H:i:s') : 'N/A';
    }

    // Formatted Logout At
    public function getFormattedLogoutAtAttribute()
    {
        return $this->logout_at ? $this->logout_at->format('d-m-Y H:i:s') : 'Active';
    }

    // Scope to get today's sessions for a user
    public function scopeTodayForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->whereDate('login_at', now()->toDateString());
    }
}
