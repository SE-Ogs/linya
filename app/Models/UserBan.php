<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    protected $fillable = ['user_id', 'reason', 'banned_by', 'banned_at', 'unbanned_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bannedBy() {
        return $this->belongsTo(User::class, 'banned_by');
    }
}

