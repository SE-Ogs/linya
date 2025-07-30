<div class=" p-4 rounded bg-white shadow">
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
    class="mt-3 hidden reply-form"
    id="reply-{{ $comment->id }}"
    data-comment-id="{{ $comment->id }}"
    data-article-id="{{ $article->id }}">
    @csrf
    <div class="relative">
        <textarea name="content"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 pr-20 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 resize-none"
                  placeholder="Write a reply..." required></textarea>

        <input type="hidden" name="parent_id" value="{{ $comment->id }}">

        <button type="submit"
                class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
            Reply
        </button>
    </div>
    </form>

        @can('delete', $comment)
            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline-block ml-4">
                @csrf
                @method('DELETE')
                <button class="text-sm text-red-500">Delete</button>
            </form>
        @endcan
    @endauth

    @php
    $reaction = $comment->userReaction;
@endphp

    <div class="flex items-center gap-4 mt-2">
    <div class="flex items-center gap-3 reaction-group">
        <button type="button"
            class="like-dislike-btn transition-transform duration-150 ease-out hover:scale-110 active:scale-95"
            data-id="{{ $comment->id }}"
            data-type="like">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline transition-colors duration-150 ease-out {{ $reaction && $reaction->is_like ? 'liked-gradient' : 'text-gray-400' }}"
            fill="{{ $reaction && $reaction->is_like ? 'url(#likeGradient)' : 'currentColor' }}" viewBox="0 0 24 24">
                <defs>
                    <linearGradient id="likeGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#FF6600"/> <!-- Bright orange -->
                        <stop offset="100%" stop-color="#0066FF"/> <!-- Bright blue -->
                    </linearGradient>
                </defs>
                <path d="M2 21h4V9H2v12zM22 9h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L15.17 2 8.59 8.59C8.22 8.95 8 9.45 8 10v9c0 1.1.9 2 2 2h8c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2c0-1.1-.9-2-2-2z"/>
            </svg>
            <span class="like-count text-gray-500 {{ $reaction && $reaction->is_like ? '' : 'hidden' }}">
                {{ $comment->likeCount() }}
            </span>
        </button>

        <button type="button"
            class="like-dislike-btn transition-transform duration-150 ease-out hover:scale-110 active:scale-95"
            data-id="{{ $comment->id }}"
            data-type="dislike">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline transition-colors duration-150 ease-out {{ $reaction && $reaction->is_like === false ? 'disliked-gradient' : 'text-gray-400' }}"
            fill="{{ $reaction && $reaction->is_like === false ? 'url(#dislikeGradient)' : 'currentColor' }}" viewBox="0 0 24 24">
                <defs>
                    <linearGradient id="dislikeGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#FF5F7E"/> <!-- Vibrant pinkish coral -->
                        <stop offset="100%" stop-color="#FF0054"/> <!-- Strong pink/red -->
                    </linearGradient>
                </defs>
                <path d="M22 3h-4v12h4V3zM2 15h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L8.83 22l6.58-6.59c.37-.36.59-.86.59-1.41V5c0-1.1-.9-2-2-2H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2z"/>
            </svg>
            <span class="dislike-count text-gray-500 {{ $reaction && $reaction->is_like === false ? '' : 'hidden' }}">
                {{ $comment->dislikeCount() }}
            </span>
        </button>
    </div>

    <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')"
        class="text-sm text-blue-500">Reply</button>
</div>

{{-- Replies --}}
@if($comment->replies->count())
    <div class="ml-6 mt-4 space-y-2">
        @foreach($comment->replies as $reply)
            @include('partials.comment', ['comment' => $reply, 'article' => $article])
        @endforeach
    </div>
@endif



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

    const group = btn.closest('.reaction-group');
const likeBtn = group.querySelector('[data-type="like"]');
const dislikeBtn = group.querySelector('[data-type="dislike"]');

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
            // Reset both buttons to gray
            likeBtn.querySelector('svg').classList.remove('text-blue-600');
            likeBtn.querySelector('svg').classList.add('text-gray-400');
            likeBtn.querySelector('svg').setAttribute('fill', 'currentColor');
            likeBtn.querySelector('svg').style.filter = '';
            dislikeBtn.querySelector('svg').classList.remove('text-red-600');
            dislikeBtn.querySelector('svg').classList.add('text-gray-400');
            dislikeBtn.querySelector('svg').setAttribute('fill', 'currentColor');

            if (type === 'like') {
                const likeSvg = likeBtn.querySelector('svg');
                // Activate like button gradient
                likeSvg.setAttribute('fill', 'url(#likeGradient)');
                likeSvg.classList.remove('text-gray-400');
                likeSvg.style.filter = '';

                likeCount.textContent = data.new_count;
                likeCount.classList.remove('hidden');
                dislikeCount.classList.add('hidden');
            } else {
                // Activate dislike button gradient
                const dislikeSvg = dislikeBtn.querySelector('svg');
                dislikeSvg.setAttribute('fill', 'url(#dislikeGradient)');
                dislikeSvg.classList.remove('text-gray-400');

                dislikeCount.textContent = data.new_count;
                dislikeCount.classList.remove('hidden');
                likeCount.classList.add('hidden');
            }
        }
    } catch (error) {
        console.error('Reaction error:', error);
    }
}

// Initialize on page load for all like/dislike buttons (including replies)
document.addEventListener('DOMContentLoaded', () => {
    initLikeDislikeButtons();
});

// Function to refresh comments UI and re-bind events (can be used after AJAX loads new comments)
function refreshCommentsUI(newComments) {
    const commentsList = document.querySelector('.comments-list');
    if (commentsList && newComments) {
        commentsList.innerHTML = newComments.innerHTML;
    }
    initLikeDislikeButtons();
}
</script>
