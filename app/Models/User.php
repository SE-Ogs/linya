<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status',
        'avatar',
        'role',
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
        ];
    }

    public function recentSearches()
    {
        return $this->hasMany(RecentSearch::class);
    }

    /**
     * Convenience method to check if the user is an admin (via role only).
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Convenience method to check if the user is a writer.
     */
    public function isWriter(): bool
    {
        return $this->role === 'writer';
    }

    /**
     * Accessor to allow `$user->isWriter` in views.
     */
    public function getIsWriterAttribute(): bool
    {
        return $this->role === 'writer';
    }

    /**
     * Accessor to allow `$user->isAdmin` in views.
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    public function bans()
    {
        return $this->hasMany(UserBan::class);
    }

    public function currentBan()
    {
        return $this->hasOne(UserBan::class)->latest('banned_at');
    }

    public function latestBan()
    {
        return $this->hasOne(UserBan::class)->latestOfMany();
    }

    /**
     * Check if user is currently banned
     */
    public function isBanned()
    {
        if ($this->status !== 'Banned') {
            return false;
        }

        $currentBan = $this->currentBan;

        if (!$currentBan) {
            return false;
        }

        // If ban has an expiry date and it's passed, user shouldn't be banned
        if ($currentBan->unbanned_at && $currentBan->unbanned_at < now()) {
            return false;
        }

        return true;
    }

    public function commentReports()
    {
        return $this->hasMany(CommentReport::class, 'user_id');
    }

    public function userBans()
    {
        return $this->hasMany(UserBan::class, 'user_id');
    }
}
