<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'article_id', 'content', 'parent_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function replies() {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent() {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
{
    return $this->hasMany(Comment::class, 'parent_id')->with('user', 'children');
}
    public function likes()
{
    return $this->hasMany(CommentLike::class);
}

public function likedBy(User $user)
{
    return $this->likes()->where('user_id', $user->id)->where('is_like', true)->exists();
}

public function dislikedBy(User $user)
{
    return $this->likes()->where('user_id', $user->id)->where('is_like', false)->exists();
}

public function likeCount()
{
    return $this->likes()->where('is_like', true)->count();
}

public function dislikeCount()
{
    return $this->likes()->where('is_like', false)->count();
}
}


