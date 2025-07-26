<div class="comment-section-container">
    <div class="add-comment-area-wrapper">
        <div class="comment-user-info">
            <img src="placeholder-avatar.png" alt="Your Avatar" class="user-avatar">
        </div>
        <div class="add-comment-input-box">
            <form action="" method="post" style="flex-grow: 1; display: flex;">
                <input type="text" name="comment_text" placeholder="Add a comment" required>
                <button type="submit" name="submit_comment" style="display: none;"></button>
            </form>
        </div>
    </div>
    @if (isset($error))
        <p class="error-message">{{ $error }}</p>
    @endif

    <div class="comment-filters">
        <a href="?sort=all" class="{{ ($sort ?? '') === 'all' ? 'active' : '' }}">All</a>
        <a href="?sort=newest" class="{{ ($sort ?? '') === 'newest' ? 'active' : '' }}">Newest</a>
        <a href="?sort=oldest" class="{{ ($sort ?? '') === 'oldest' ? 'active' : '' }}">Oldest</a>
        <a href="?sort=most_liked" class="{{ ($sort ?? '') === 'most_liked' ? 'active' : '' }}">Most Liked</a>
    </div>

    <div class="comments-list">
        @if (empty($comments))
            <p>No comments yet. Be the first to comment!</p>
        @else
            @foreach ($comments as $comment)
                @include('partials.comment', ['comment' => $comment, 'level' => 0])
            @endforeach
        @endif
    </div>
</div>

<script src="{{ asset('js/comments.js') }}"></script>
