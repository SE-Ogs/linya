<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        $comment->article()->increment('comments_count');
    }

    public function deleted(Comment $comment): void
    {
        $comment->article()->decrement('comments_count');
    }
}
