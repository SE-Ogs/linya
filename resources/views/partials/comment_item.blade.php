<div class="comment-item p-4 rounded shadow bg-white border-l-4 border-gray-200"
     style="background-color: {{ isset($level) && $level > 0 ? '#f9fafb' : '#fff' }}; margin-left: {{ isset($level) ? ($level * 20) : 0 }}px;"
     data-comment-id="{{ $comment->id }}">

    <div class="flex items-center gap-2">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user?->name ?? 'Unknown User') }}"
             alt="Avatar" class="w-8 h-8 rounded-full">
        <span class="font-semibold text-gray-800 text-base">
            {{ $comment->user?->name ?? 'Unknown User' }}
        </span>
        <span class="text-xs text-gray-400">
            • {{ $comment->created_at->diffForHumans() }}
        </span>
    </div>

    <p class="mt-2 text-gray-800 comment-content">{{ $comment->content }}</p>

    @auth
        {{-- Reply Form --}}
        <form method="POST"
              class="mt-3 hidden reply-form"
              data-comment-id="{{ $comment->id }}"
              data-article-id="{{ $article->id }}">
            @csrf
            <div class="relative">
                <textarea name="content"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 pr-20 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 resize-none"
                          placeholder="Write a reply..."
                          rows="2"
                          required></textarea>

                <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                <div class="flex gap-2 mt-2 justify-end">
                    <button type="button"
                            class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-400 transition reply-cancel-btn">
                       
                            Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                        Reply
                    </button>
                </div>
            </div>
        </form>

        {{-- Delete Button --}}
        @can('delete', $comment)
            <button class="delete-comment-btn text-sm text-red-500 hover:text-red-700 mt-2"
                    data-comment-id="{{ $comment->id }}">
                Delete
            </button>
        @endcan
    @endauth

    {{-- Like/Dislike and Reply Buttons --}}
    <div class="flex items-center gap-4 mt-3">
        @auth
            <div class="flex items-center gap-3 reaction-group">
                <button type="button"
                        class="like-btn flex items-center gap-1 transition-colors"
                        data-comment-id="{{ $comment->id }}">
                    @php
                        $userReaction = $comment->userReaction;
                        $isLiked = $userReaction && $userReaction->is_like;
                    @endphp

                    <svg class="w-4 h-4 {{ $isLiked ? 'text-blue-600 fill-current' : 'text-gray-400' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 9m5 1v10M4 9h1l1-1"></path>
                    </svg>
                    <span class="like-count text-xs {{ $isLiked ? 'text-blue-600' : 'text-gray-400' }}">
                        {{ $comment->likeCount() }}
                    </span>
                </button>

                <button type="button"
                        class="dislike-btn flex items-center gap-1 transition-colors"
                        data-comment-id="{{ $comment->id }}">
                    @php
                        $isDisliked = $userReaction && $userReaction->is_like === false;
                    @endphp

                    <svg class="w-4 h-4 {{ $isDisliked ? 'text-red-600 fill-current' : 'text-gray-400' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L15 15m-5-1V4M20 15h-1l-1 1"></path>
                    </svg>
                    <span class="dislike-count text-xs {{ $isDisliked ? 'text-red-600' : 'text-gray-400' }}">
                        {{ $comment->dislikeCount() }}
                    </span>
                </button>
            </div>

            <button type="button"
                    class="text-sm text-blue-500 hover:text-blue-700 reply-toggle-btn"
                    data-target-comment-id="{{ $comment->id }}">
                Reply
            </button>
            {{-- New Report Button --}}
            <button type="button"
                    class="report-comment-btn text-sm text-yellow-600 hover:text-yellow-700"
                    data-comment-id="{{ $comment->id }}">
                Report
            </button>
        @endauth
    </div>

    {{-- Replies --}}
    <div class="replies-container mt-4 space-y-2">
        @foreach($comment->replies as $reply)
            @include('partials.comment_item', [
                'comment' => $reply,
                'article' => $article,
                'level' => (isset($level) ? $level + 1 : 1)
            ])
        @endforeach
    </div>
</div>
