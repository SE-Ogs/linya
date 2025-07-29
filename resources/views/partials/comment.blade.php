<div class="border p-4 rounded">
    <div class="text-sm text-gray-700">
       <strong>{{ $comment->user?->name ?? 'Unknown User' }}</strong> · <span>{{ $comment->created_at->diffForHumans() }}</span>
    </div>
    <p class="mt-2 text-gray-800">{{ $comment->content }}</p>

    @auth
        <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')" class="text-sm text-blue-500 mt-2">Reply</button>

       <form method="POST"
      class="mt-2 hidden reply-form"
      id="reply-{{ $comment->id }}"
      data-comment-id="{{ $comment->id }}"
      data-article-id="{{ $article->id }}">
    @csrf
    <textarea name="content" class="w-full border p-2 mb-2" placeholder="Reply..." required></textarea>
    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
    <button class="bg-gray-300 px-3 py-1 rounded">Reply</button>
</form>


        @can('delete', $comment)
            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline-block ml-4">
                @csrf
                @method('DELETE')
                <button class="text-sm text-red-500">Delete</button>
            </form>
        @endcan
    @endauth

    {{-- Replies --}}
    @if($comment->replies->count())
        <div class="ml-6 mt-4 space-y-2">
            @foreach($comment->replies as $reply)
                @include('partials.comment', ['comment' => $reply, 'article' => $article])
            @endforeach
        </div>
    @endif

    <div class="flex items-center gap-3 mt-2">
    <button type="button"
        class="like-dislike-btn text-sm text-green-600 flex items-center gap-1"
        data-id="{{ $comment->id }}"
        data-type="like">
        <img src="{{ asset('images/Like.png') }}" alt="Like" class="w-5 h-5 transition-transform duration-150 hover:scale-110 hover:brightness-110"> 
        <span class="like-count hidden text-gray-500">{{ $comment->likeCount() }}</span>
    </button>

    <button type="button"
        class="like-dislike-btn text-sm text-red-600 flex items-center gap-1"
        data-id="{{ $comment->id }}"
        data-type="dislike">
        <img src="{{ asset('images/Dislike.png') }}" alt="Dislike" class="w-5 h-5 transition-transform duration-150 hover:scale-110 hover:brightness-110"> 
        <span class="dislike-count hidden text-gray-500">{{ $comment->dislikeCount() }}</span>
    </button>
</div>

</div>

<script>
document.querySelectorAll('.like-dislike-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const commentId = btn.getAttribute('data-id');
        const type = btn.getAttribute('data-type');
        const oppositeType = type === 'like' ? 'dislike' : 'like';

        const response = await fetch(`/comments/${commentId}/${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        if (data.success) {
            // Update current type count
            const countSpan = btn.querySelector(`.${type}-count`);
            countSpan.textContent = data.new_count;
            countSpan.classList.remove('hidden');

            // Reset opposite type count visually (optional: set to 0 or hide)
            const parent = btn.parentElement;
            const oppositeBtn = parent.querySelector(`[data-type="${oppositeType}"]`);
            const oppositeSpan = oppositeBtn.querySelector(`.${oppositeType}-count`);

            oppositeSpan.textContent = '0';
            oppositeSpan.classList.add('hidden');
        }
    });
});


</script>
