<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Article;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;


class Comments extends Component
{
    public $articleId;
    public $newComment = '';

        public $currentSort = 'all'; // Track the current sort state


    protected $rules = [
        'newComment' => 'required|string|min:1',
    ];

    public function mount($articleId){
        $this->articleId = $articleId;
    }

    public function addComment(){
        $this->validate();

        Comment::create([
            'article_id' => $this->articleId,
            'user_id' => Auth::id(),
            'content' => $this->newComment,
        ]);

        $this->newComment = '';

        $this->dispatch('commentAdded');
    }

     public function setSort($sort)
    {
        $this->currentSort = $sort;
        // Dispatch to the comment-list component
        $this->dispatch('setSort', sort: $sort);
    }

    #[On('sortUpdated')]
    public function handleSortUpdate($sort)
    {
        $this->currentSort = $sort;
    }


    public function render()
    {
        return view('livewire.comments', [
                        'currentSort' => $this->currentSort,

        ]);

    }
}
