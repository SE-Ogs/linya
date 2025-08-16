<div class="comments-list space-y-4">
    @if($comments->isEmpty())
        <div class="text-center py-8">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-lg font-medium bg-gradient-to-r from-orange-600 via-orange-500 to-blue-800 bg-clip-text text-transparent">
                    No comments yet.
                </p>
                <p class="text-gray-500 text-sm">
                    Be the first to share your thoughts!
                </p>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($comments as $comment)
                <div class="comment-wrapper animate-fade-in transition-all duration-300 hover:shadow-sm">
                    @include('partials.comment-item', [
                        'comment' => $comment,
                        'article' => $article,
                        'level' => 0
                    ])
                </div>
            @endforeach
        </div>

        {{-- Comments count --}}
        <div class="mt-6 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500 text-center">
                {{ $comments->count() }} {{ Str::plural('comment', $comments->count()) }}
                @if($sort !== 'all')
                    (sorted by {{ str_replace('_', ' ', $sort) }})
                @endif
            </p>
        </div>
    @endif
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.comment-wrapper:hover {
    transform: translateY(-1px);
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
