<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\CommentLike;

class CommentItem extends Component
{
   public $likes = 0;
public $dislikes = 0;
public $comment;

public $userVote = null; // 'like', 'dislike', or null

public function mount(Comment $comment)
{
    $this->comment = $comment;
    $this->refreshCounts();

    if (Auth::check()) {
        $existing = $this->comment->likes()->where('user_id', Auth::id())->first();
        if ($existing) {
            $this->userVote = $existing->is_like ? 'like' : 'dislike';
        }
    }
}
public function like()
{
    $userId = auth()->id();
    $existing = $this->comment->likes()->where('user_id', $userId)->first();

    if ($existing) {
        if ($existing->is_like) {
            $existing->delete();
            $this->userVote = null;
        } else {
            $existing->is_like = true;
            $existing->save();
            $this->userVote = 'like';
        }
    } else {
        $this->comment->likes()->create([
            'user_id' => $userId,
            'is_like' => true,
        ]);
        $this->userVote = 'like';
    }

    $this->refreshCounts();
}

public function dislike()
{
    $userId = auth()->id();
    $existing = $this->comment->likes()->where('user_id', $userId)->first();

    if ($existing) {
        if (!$existing->is_like) {
            $existing->delete();
            $this->userVote = null;
        } else {
            $existing->is_like = false;
            $existing->save();
            $this->userVote = 'dislike';
        }
    } else {
        $this->comment->likes()->create([
            'user_id' => $userId,
            'is_like' => false,
        ]);
        $this->userVote = 'dislike';
    }

    $this->refreshCounts();
}


protected function refreshCounts()
{
    $this->comment = $this->comment->fresh();
    $this->likes = $this->comment->likeCount();
    $this->dislikes = $this->comment->dislikeCount();
}



    public function render()
    {
        return view('livewire.comment-item');
    }
}
