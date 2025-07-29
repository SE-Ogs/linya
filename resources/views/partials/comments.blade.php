<div class="mb-20 w-full max-w-4xl mx-auto shadow-sm rounded-lg bg-white p-4 sm:p-6 overflow-x-hidden">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>

    @guest
        <div class="w-full text-center py-6">
            <p class="text-base font-medium text-gray-500">
                You are not logged in. Please
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-orange-500 via-pink-500 to-blue-500 bg-clip-text text-transparent underline hover:opacity-90 transition-all duration-300 hover:scale-105 inline-block">
                    log in
                </a>
                to comment.
            </p>
        </div>
    @else
        <div class="add-comment-area-wrapper">
            <div class="comment-user-info">
                <img src="placeholder-avatar.png" alt="Your Avatar" class="user-avatar">
            </div>
            <div class="add-comment-input-box border border-black rounded-[10px] bg-white flex items-center px-3 py-2 w-full">
                <form action="{{ route('comments.store', ['article' => $article->id]) }}" method="POST">
                    @csrf
                    <textarea
                        name="content"
                        maxlength="500"
                        placeholder="Add a comment"
                        class="flex-grow px-3 py-2 text-sm border-none outline-none resize-none overflow-hidden leading-snug transition-all duration-300 focus:outline-none focus:shadow-none"
                        rows="1"
                        required
                        oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px';"
                    ></textarea>

                    <button
                        type="submit"
                        name="submit_comment"
                        class="text-orange-500 hover:text-orange-600 text-xl ml-2">
                        &#10148;
                    </button>

                    <div class="w-full text-right">
                        <small id="char-count" class="text-gray-500">0/500</small>
                    </div>
                </form>
            </div>
        </div>

        @if (isset($error))
            <p class="error-message text-red-500 mt-2">{{ $error }}</p>
        @endif

        <div class="comment-filters flex gap-2 my-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
            @php
                $filters = ['all' => 'All', 'newest' => 'Newest', 'oldest' => 'Oldest', 'most_liked' => 'Most Liked'];
            @endphp

            @foreach ($filters as $key => $label)
                <a href="?sort={{ $key }}"
                   class="px-4 py-1.5 rounded-md text-sm font-medium transition-all duration-200 border hover:scale-105 transition-transform ease-in-out
                   {{ ($sort ?? 'all') === $key
                       ? 'bg-blue-800 text-white border-blue-800 shadow-sm'
                       : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-blue-800 hover:text-white hover:border-blue-800' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="comments-list">
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
    @endguest
</div>

<script src="{{ asset('js/comments.js') }}"></script>
