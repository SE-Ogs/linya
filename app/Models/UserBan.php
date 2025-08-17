<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    protected $fillable = ['user_id', 'reason', 'banned_by', 'banned_at', 'unbanned_at'];

    // Cast dates to Carbon instances
    protected $casts = [
        'banned_at' => 'datetime',
        'unbanned_at' => 'datetime',
    ];

    // RELATIONSHIPS
    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unban()
    {
        return $this->hasOne(UserUnban::class, 'user_ban_id');
    }

    // Helper method to check if ban is permanent
    public function isPermanent()
    {
        return is_null($this->unbanned_at);
    }

    // Helper method to check if ban has expired
    public function hasExpired()
    {
        if ($this->isPermanent()) {
            return false;
        }

        return $this->unbanned_at < now();
    }
}
