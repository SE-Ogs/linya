<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'article_id',
        'parent_id',
        'content',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies')->orderBy('created_at', 'asc');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function reactions()
    {
        return $this->hasMany(CommentLike::class);
    }

    // Helper methods for like/dislike counts
    public function likeCount()
    {
        return $this->likes()->where('is_like', true)->count();
    }

    public function dislikeCount()
    {
        return $this->likes()->where('is_like', false)->count();
    }

    // Get user's reaction to this comment
    public function getUserReactionAttribute()
    {
        if (!auth()->check()) {
            return null;
        }

        return $this->likes()->where('user_id', auth()->id())->first();
    }

    // Alternative method name for compatibility
    public function userReaction()
    {
        return $this->getUserReactionAttribute();
    }

    // Scope for getting root comments (no parent)
    public function scopeRootComments($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope for getting replies to a specific comment
    public function scopeRepliesTo($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    // Check if comment has replies
    public function hasReplies()
    {
        return $this->replies()->count() > 0;
    }

    // Get total reply count (including nested replies)
    public function getTotalReplyCount()
    {
        $count = $this->replies()->count();

        foreach ($this->replies as $reply) {
            $count += $reply->getTotalReplyCount();
        }

        return $count;
    }

    // Boot method to handle cascading deletes
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($comment) {
            // Delete all replies when deleting a comment
            $comment->replies()->delete();

            // Delete all likes for this comment
            $comment->likes()->delete();
        });
    }
}
