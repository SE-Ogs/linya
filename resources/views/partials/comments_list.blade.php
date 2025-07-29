<div class="comments-list space-y-3">
    @if (empty($comments))
        <div class="text-center my-8">
            <p class="text-base font-semibold bg-gradient-to-r from-orange-600 via-orange-500 to-blue-800 bg-clip-text text-transparent animate-pulse">
                No comments yet. Be the first to comment!
            </p>
        </div>
    @else
        @foreach ($comments as $comment)
            <div class="animate-fade-in transition-opacity duration-500">
                @include('partials.comment', ['comment' => $comment, 'article' => $article, 'level' => 0])
            </div>
        @endforeach
    @endif
    
</div>
