<style>
.rainbow-text {
  background: linear-gradient(270deg, #ff6ec4, #7873f5, #1fd1f9, #76ff7a, #f9ff6e, #ff6ec4);
  background-size: 1200% 1200%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
  animation: rainbow-move 4s linear infinite;
}
@keyframes rainbow-move {
  0% { background-position: 0% 50%; }
  100% { background-position: 100% 50%; }
}
</style>
<div class="mb-20 w-full max-w-4xl mx-auto shadow-sm rounded-lg bg-white p-4 sm:p-6 overflow-x-hidden">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>

    @guest
        <div class="w-full text-center py-6">
            <p class="rainbow-text text-base font-medium underline hover:opacity-90 transition-all duration-300 hover:scale-105 inline-block">
                You are not logged in. Please
                <a href="{{ route('login') }}">
                    log in
                </a>
                to comment.
            </p>
        </div>
    @else
        {{-- Main Comment Form --}}
        <div class="add-comment-area-wrapper">
            <div class="comment-user-info">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                     alt="Your Avatar" class="user-avatar">
            </div>
            <div class="add-comment-input-box border border-black rounded-[10px] bg-white flex items-center px-3 py-2 w-full">
                <form id="main-comment-form" data-article-id="{{ $article->id }}" class="flex flex-grow flex-wrap gap-2">
                    @csrf
                    <textarea
                        name="content"
                        id="comment-box"
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

                    <div id="below-textarea" class="w-full text-right flex justify-between items-center">
                        <span class="text-xs text-red-500 spooky-warning ml-2 block mt-1 hidden"></span>
                        <small id="char-count" class="text-gray-500">0/500</small>
                    </div>
                </form>
            </div>
        </div>

        {{-- Error Message --}}
        <div id="comment-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4"></div>

        {{-- Success Message --}}
        <div id="comment-success" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4"></div>

        @if (isset($error))
            <p class="error-message">{{ $error }}</p>
        @endif

        {{-- Comment Filters --}}
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

        {{-- Comments List --}}
        <div class="comments-list" id="comments-container">
            @include('partials.comments_list', ['comments' => $comments, 'article' => $article, 'sort' => $sort ?? 'all'])
        </div>
    @endguest
</div>

<script>
// Comment-specific functionality only
document.addEventListener('DOMContentLoaded', function () {
    const articleId = document.getElementById('main-comment-form')?.getAttribute('data-article-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Message display function
    function showMessage(message, type = 'success') {
        const errorEl = document.getElementById('comment-error');
        const successEl = document.getElementById('comment-success');

        errorEl.classList.add('hidden');
        successEl.classList.add('hidden');

        if (type === 'error') {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
            setTimeout(() => errorEl.classList.add('hidden'), 5000);
        } else {
            successEl.textContent = message;
            successEl.classList.remove('hidden');
            setTimeout(() => successEl.classList.add('hidden'), 3000);
        }
    }

    // Comment reload function
    async function reloadComments(sort = 'all') {
        try {
            const response = await fetch(`/articles/${articleId}/comments?sort=${sort}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (data.success) {
                document.getElementById('comments-container').innerHTML = data.html;
            }
        } catch (error) {
            console.error('Error reloading comments:', error);
        }
    }

    // Form submission handler
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        if (form.matches('#main-comment-form, .reply-form')) {
            e.preventDefault();

            const formData = new FormData(form);
            const content = formData.get('content').trim();

            if (!content) {
                showMessage('Please enter a comment.', 'error');
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Posting...';

            try {
                const payload = {
                    content: content,
                    parent_id: formData.get('parent_id') || null
                };

                const response = await fetch(`/articles/${articleId}/comments/ajax`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    form.reset();
                    const charCount = document.getElementById('char-count');
                    if (charCount) charCount.textContent = '0/500';

                    if (form.classList.contains('reply-form')) {
                        form.classList.add('hidden');
                    }

                    await reloadComments();
                    showMessage('Comment posted successfully!');
                } else {
                    showMessage(data.message || 'Failed to post comment.', 'error');
                }
            } catch (error) {
                console.error('Error submitting comment:', error);
                showMessage('An error occurred while posting your comment.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }
    });

    // Event delegation for comment interactions
    document.getElementById('comments-container')?.addEventListener('click', async function(e) {
        // Like/dislike handling
        const likeBtn = e.target.closest('.like-btn');
        const dislikeBtn = e.target.closest('.dislike-btn');

        if (likeBtn || dislikeBtn) {
            e.preventDefault();
            const isLike = !!likeBtn;
            const commentId = (likeBtn || dislikeBtn).getAttribute('data-comment-id');
            const button = likeBtn || dislikeBtn;

            if (button.disabled) return;
            button.disabled = true;

            try {
                const endpoint = isLike ? 'like' : 'dislike';
                const response = await fetch(`/comments/${commentId}/${endpoint}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const commentEl = button.closest('.comment-item');

                    // Update like button
                    const likeButton = commentEl.querySelector('.like-btn');
                    const likeSvg = likeButton.querySelector('svg');
                    const likeCount = likeButton.querySelector('.like-count');

                    likeSvg.classList.toggle('text-blue-600', data.liked);
                    likeSvg.classList.toggle('text-gray-400', !data.liked);
                    likeCount.classList.toggle('text-blue-600', data.liked);
                    likeCount.textContent = data.like_count;

                    // Update dislike button
                    const dislikeButton = commentEl.querySelector('.dislike-btn');
                    const dislikeSvg = dislikeButton.querySelector('svg');
                    const dislikeCount = dislikeButton.querySelector('.dislike-count');

                    dislikeSvg.classList.toggle('text-red-600', data.disliked);
                    dislikeSvg.classList.toggle('text-gray-400', !data.disliked);
                    dislikeCount.classList.toggle('text-red-600', data.disliked);
                    dislikeCount.textContent = data.dislike_count;
                }
            } catch (error) {
                console.error('Error updating reaction:', error);
                showMessage('Failed to update reaction.', 'error');
            } finally {
                button.disabled = false;
            }
        }

        // Reply toggle
        const replyBtn = e.target.closest('.reply-toggle-btn');
        if (replyBtn) {
            const commentId = replyBtn.getAttribute('data-target-comment-id');
            const replyForm = document.querySelector(`.reply-form[data-comment-id='${commentId}']`);
            if (replyForm) {
                replyForm.classList.toggle('hidden');
                if (!replyForm.classList.contains('hidden')) {
                    replyForm.querySelector('textarea').focus();
                }
            }
        }

        // Delete comment
        const deleteBtn = e.target.closest('.delete-comment-btn');
        if (deleteBtn && confirm('Are you sure you want to delete this comment?')) {
            const commentId = deleteBtn.getAttribute('data-comment-id');
            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.ok) {
                    await reloadComments();
                    showMessage('Comment deleted successfully!');
                } else {
                    showMessage('Failed to delete comment.', 'error');
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
                showMessage('An error occurred while deleting the comment.', 'error');
            }
        }
    });

    // Sorting handler
    document.querySelector('.comment-filters')?.addEventListener('click', async function(e) {
        const sortBtn = e.target.closest('a');
        if (sortBtn) {
            e.preventDefault();
            const sort = new URL(sortBtn.href).searchParams.get('sort');
            await reloadComments(sort);
        }
    });
});
</script>
