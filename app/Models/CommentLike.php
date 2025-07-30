<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment_id',
        'is_like', // true for like, false for dislike
    ];

    protected $casts = [
        'is_like' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    // Scopes
    public function scopeLikes($query)
    {
        return $query->where('is_like', true);
    }

    public function scopeDislikes($query)
    {
        return $query->where('is_like', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForComment($query, $commentId)
    {
        return $query->where('comment_id', $commentId);
    }
}
