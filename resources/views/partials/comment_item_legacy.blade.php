{{-- Check if a user is logged in to determine their reaction status --}}
@php
    $currentUser = Auth::user();
    $userReaction = null;
    if ($currentUser) {
        // Eager load reactions on the comment to avoid N+1 query here if not already loaded
        // Ensure 'reactions' relationship is loaded on the comment model passed to this partial
        $userReaction = $comment->reactions->where('user_id', $currentUser->id)->first();
    }
@endphp

<div class="comment-item border-l-2 border-gray-200 pl-4 mb-4" id="comment-{{ $comment->id }}">
    <div class="flex items-start mb-2">
        <div class="flex-shrink-0 mr-3">
            {{-- User Avatar (replace with actual user avatar if available) --}}
            <img class="w-8 h-8 rounded-full" src="{{ asset('images/placeholder.jpg') }}" alt="User Avatar">
        </div>
        <div>
            <p class="font-semibold">{{ $comment->user->name ?? 'Guest' }} <span class="text-gray-500 text-sm ml-2">{{ $comment->created_at->diffForHumans() }}</span></p>
            <p class="text-gray-700">{{ $comment->content }}</p>

            <div class="flex items-center mt-2 text-sm">
                {{-- Like Button --}}
                <button class="flex items-center mr-4 text-gray-600 hover:text-blue-600 like-btn {{ $userReaction && $userReaction->is_like ? 'text-blue-600' : '' }}"
                        data-comment-id="{{ $comment->id }}" data-type="like"
                        @guest disabled @endguest> {{-- Disable if not logged in --}}
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414l-3-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="likes-count">{{ $comment->likeCount() }}</span>
                </button>

                {{-- Dislike Button --}}
                <button class="flex items-center mr-4 text-gray-600 hover:text-red-600 dislike-btn {{ $userReaction && !$userReaction->is_like ? 'text-red-600' : '' }}"
                        data-comment-id="{{ $comment->id }}" data-type="dislike"
                        @guest disabled @endguest> {{-- Disable if not logged in --}}
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm.707 10.293a1 1 0 00-1.414 0l-3-3a1 1 0 001.414-1.414L9 10.586V7a1 1 0 102 0v3.586l1.293-1.293a1 1 0 001.414 1.414l-3 3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="dislikes-count">{{ $comment->dislikeCount() }}</span>
                </button>

                {{-- Reply Button --}}
                <button class="text-gray-600 hover:text-purple-600 reply-btn"
                        onclick="showReplyForm({{ $comment->id }}, '{{ $comment->user->name ?? 'Guest' }}')"
                        @guest disabled @endguest> {{-- Disable if not logged in --}}
                    Reply
                </button>
            </div>
        </div>
    </div>

    {{-- Nested Replies --}}
    <div class="replies ml-8 mt-4">
        @foreach($comment->replies as $reply)
            {{-- Recursively include the single_comment partial for each reply --}}
            @include('partials.comment_item_legacy', ['comment' => $reply, 'articleId' => $article->id ?? $articleId ?? null])
        @endforeach
    </div>
</div>