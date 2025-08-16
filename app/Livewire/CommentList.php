<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\On;

class CommentList extends Component
{
    public $articleId;
    public $sort = 'all';

    public function mount($articleId)
    {
        $this->articleId = $articleId;
    }

    #[On('commentAdded')]
    public function refreshComments()
    {
        // This will trigger a re-render
        $this->render();
    }

    #[On('setSort')]
    public function setSort($sort)
    {
        $this->sort = $sort;
        // Dispatch the updated sort back to parent to update button states
        $this->dispatch('sortUpdated', sort: $this->sort);
    }

    public function render()
    {
        $query = Comment::where('article_id', $this->articleId)
                        ->whereNull('parent_id');

        switch ($this->sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_liked':
                $query->withCount('likes')->orderBy('likes_count', 'desc');
                break;
            default: // 'all'
                $query->orderBy('created_at', 'desc');
        }

        $comments = $query->get();

        return view('livewire.comment-list', [
            'comments' => $comments,
            'currentSort' => $this->sort,
        ]);
    }
}
