<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUnban extends Model
{
    protected $fillable = [
        'user_id',
        'user_ban_id', // reference to the original ban
        'reason', // optional reason for unban
        'unbanned_by', // user who performed the unban
        'unbanned_at'
    ];

    protected $casts = [
        'unbanned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'unbanned_by');
    }

    public function ban()
    {
        return $this->belongsTo(UserBan::class, 'user_ban_id');
    }
}
