<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    protected $fillable = ['user_id', 'reason', 'banned_by', 'banned_at', 'unbanned_at'];

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
}
