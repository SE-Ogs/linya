<div class="border p-4 rounded bg-white">
    <div class="flex items-center gap-2">
        <span class="font-semibold text-gray-800 text-base sm:text-base">
            {{ $comment->user?->name ?? 'Unknown User' }}
        </span>
        <span class="text-xs text-gray-400 text-xs sm:text-xs">
            {{-- Display the time since the comment was created --}}
            {{-- Example: "2 hours ago" --}}
            • {{ $comment->created_at->diffForHumans() }}
        </span>
    </div>
    <p class="mt-2 text-gray-800">{{ $comment->content }}</p>

    @auth
        

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

    @php
    $reaction = $comment->userReaction;
@endphp

    <div class="flex items-center gap-3 mt-2">
    <button type="button"
    class="like-dislike-btn"
    data-id="{{ $comment->id }}"
    data-type="like">
    <img src="{{ asset('images/Like.png') }}"
         class="w-4 h-4 inline {{ $reaction && $reaction->is_like ? 'text-blue-600' : 'text-gray-400' }}"
>
    <span class="like-count text-gray-500 {{ $reaction && $reaction->is_like ? '' : 'hidden' }}">
        {{ $comment->likeCount() }}
    </span>
</button>

{{-- DISLIKE button --}}
<button type="button"
    class="like-dislike-btn"
    data-id="{{ $comment->id }}"
    data-type="dislike">
    <img src="{{ asset('images/Dislike.png') }}"
         class="w-4 h-4 inline {{ $reaction && $reaction->is_like === false ? 'text-red-600' : 'text-gray-400' }}"
>
    <span class="dislike-count text-gray-500 {{ $reaction && $reaction->is_like === false ? '' : 'hidden' }}">
        {{ $comment->dislikeCount() }}
    </span>
</button>

<button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')" class="text-sm text-blue-500 mt-2">Reply</button>
</div>



</div>

<script>
function initLikeDislikeButtons() {
    document.querySelectorAll('.like-dislike-btn').forEach(button => {
        button.removeEventListener('click', handleReaction); // Prevent duplicate binding
        button.addEventListener('click', handleReaction);
    });
}

async function handleReaction(e) {
    const btn = e.currentTarget;
    const commentId = btn.getAttribute('data-id');
    const type = btn.getAttribute('data-type');
    const token = document.querySelector('meta[name="csrf-token"]').content;

    const likeBtn = btn.closest('.flex').querySelector('[data-type="like"]');
    const dislikeBtn = btn.closest('.flex').querySelector('[data-type="dislike"]');
    const likeCount = likeBtn.querySelector('.like-count');
    const dislikeCount = dislikeBtn.querySelector('.dislike-count');

    try {
        const response = await fetch(`/comments/${commentId}/${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        if (data.success) {
            if (type === 'like') {
                likeCount.textContent = data.new_count;
                likeCount.classList.remove('hidden');
                dislikeCount.classList.add('hidden');
            } else {
                dislikeCount.textContent = data.new_count;
                dislikeCount.classList.remove('hidden');
                likeCount.classList.add('hidden');
            }
        }
    } catch (error) {
        console.error('Reaction error:', error);
    }
}

document.querySelector('.comments-list').innerHTML = newComments.innerHTML;
initLikeDislikeButtons(); // re-attach event listeners

</script>
