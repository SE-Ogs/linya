<div class="mb-20 w-full shadow-sm rounded-lg bg-white p-4 sm:p-6 overflow-x-hidden">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>
{{-- <div class="mx-auto mb-20 w-full max-w-4xl overflow-x-hidden rounded-lg bg-white p-4 shadow-sm sm:p-6"> --}}
{{--     <h2 class="mb-4 text-xl font-semibold text-gray-800">Comments</h2> --}}

    @guest
        <div class="w-full py-6 text-center">
            <p class="text-base font-medium text-gray-500">
                You are not logged in. Please
                <a href="{{ route('login') }}"
                   class="inline-block bg-gradient-to-r from-orange-500 via-pink-500 to-blue-500 bg-clip-text text-transparent underline transition-all duration-300 hover:scale-105 hover:opacity-90">
                    log in
                </a>
                to comment.
            </p>
        </div>
    @else
        {{-- Main Comment Form --}}
        <div class="add-comment-area-wrapper mb-6">
            <form id="main-comment-form" data-article-id="{{ $article->id }}" class="w-full">
                @csrf

                {{-- User Profile Section --}}
                <div class="flex items-center gap-3 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                         alt="Your Avatar" class="w-8 h-8 rounded-full flex-shrink-0">
                    <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>
                </div>

                {{-- Comment Input Section --}}
                <div class="border border-gray-300 rounded-lg bg-white p-3">
                    <textarea
                        name="content"
                        maxlength="1000"
                        placeholder="Add a comment..."
                        class="w-full text-sm border-none outline-none resize-none leading-relaxed"
                        rows="3"
                        required
                    ></textarea>

                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
                        <small id="main-char-count" class="text-gray-500">0/1000</small>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-1.5 rounded-md text-sm hover:bg-blue-600 transition-colors">
                            Comment
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Error Message --}}
        <div id="comment-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4"></div>

        {{-- Success Message --}}
        <div id="comment-success" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4"></div>

        {{-- Comment Filters --}}
        <div class="comment-filters flex gap-2 my-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
        {{-- <div class="add-comment-area-wrapper"> --}}
        {{--     <div class="comment-user-info"> --}}
        {{--         <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" --}}
        {{--              alt="Your Avatar" --}}
        {{--              class="user-avatar"> --}}
        {{--     </div> --}}
        {{--     <div class="add-comment-input-box w-full rounded-[10px] border border-black bg-white px-3 py-2"> --}}
        {{--         <form id="comment-form" --}}
        {{--               data-article-id="{{ $article->id }}" --}}
        {{--               class="w-full"> --}}
        {{----}}
        {{--             @csrf --}}
        {{--             <div class="flex items-start justify-between gap-2"> --}}
        {{--                 <textarea name="content" --}}
        {{--                           maxlength="500" --}}
        {{--                           placeholder="Add a comment" --}}
        {{--                           class="flex-grow resize-none overflow-hidden border-none px-3 py-2 text-sm leading-snug outline-none transition-all duration-300 focus:shadow-none focus:outline-none" --}}
        {{--                           rows="1" --}}
        {{--                           required --}}
        {{--                           id="comment-box" --}}
        {{--                           oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px';"></textarea> --}}
        {{----}}
        {{--                 <button type="submit" --}}
        {{--                         name="submit_comment" --}}
        {{--                         class="pt-1 text-2xl text-orange-500 hover:text-orange-600"> --}}
        {{--                     &#10148; --}}
        {{--                 </button> --}}
        {{--             </div> --}}
        {{----}}
        {{--             <div class="mt-1 flex items-center justify-between" --}}
        {{--                  id="below-textarea"> --}}
        {{--                 <!-- JS will insert warning here --> --}}
        {{--                 <!-- Char count already exists --> --}}
        {{--                 <small id="char-count" --}}
        {{--                        class="ml-auto text-gray-500">0/500</small> --}}
        {{--             </div> --}}
        {{--         </form> --}}
        {{--     </div> --}}
        {{----}}
        {{-- </div> --}}
        {{----}}
        {{-- @if (isset($error)) --}}
        {{--     <p class="error-message mt-2 text-red-500">{{ $error }}</p> --}}
        {{-- @endif --}}
        {{----}}
        {{-- <div class="comment-filters scrollbar-hide my-4 flex gap-2 overflow-x-auto whitespace-nowrap"> --}}
            @php
                $filters = ['all' => 'All', 'newest' => 'Newest', 'oldest' => 'Oldest', 'most_liked' => 'Most Liked'];
            @endphp

            @foreach ($filters as $key => $label)
                <button
                    class="comment-sort-btn px-4 py-1.5 rounded-md text-sm font-medium transition-all duration-200 border hover:scale-105 {{ ($sort ?? 'all') === $key ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-blue-600 hover:text-white hover:border-blue-600' }}"
                    data-sort="{{ $key }}"
                    type="button">
                {{-- <button class="comment-sort-btn {{ ($sort ?? 'all') === $key ? 'bg-blue-800 text-white border-blue-800 shadow-sm' : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-blue-800 hover:text-white hover:border-blue-800' }} rounded-md border px-4 py-1.5 text-sm font-medium transition-all duration-200 hover:scale-105" --}}
                {{--         data-sort="{{ $key }}" --}}
                {{--         type="button"> --}}
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Comments List --}}
        <div class="comments-list space-y-4" id="comments-container">
            @include('partials.comments_list', ['comments' => $comments, 'article' => $article, 'sort' => $sort ?? 'all'])
        {{-- <div class="comments-list space-y-4" --}}
        {{--      id="comments-container"> --}}
        {{--     @include('partials.comments_list', [ --}}
        {{--         'comments' => $comments, --}}
        {{--         'article' => $article, --}}
        {{--         'sort' => $sort ?? 'all', --}}
        {{--     ]) --}}
        </div>
    @endguest
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const articleId = document.getElementById('main-comment-form')?.getAttribute('data-article-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Character counter for main comment form
    const mainTextarea = document.querySelector('#main-comment-form textarea[name="content"]');
    const mainCharCount = document.getElementById('main-char-count');

    if (mainTextarea && mainCharCount) {
        mainTextarea.addEventListener('input', function() {
            const count = this.value.length;
            mainCharCount.textContent = `${count}/1000`;
            mainCharCount.className = count > 950 ? 'text-red-500' : 'text-gray-500';
    {{-- document.addEventListener("DOMContentLoaded", function() { --}}
    {{--     const form = document.getElementById('comment-form'); --}}
    {{--     const sortButtons = document.querySelectorAll('.comment-sort-btn'); --}}
    {{----}}
    {{----}}
    {{--     function attachLikeDislikeListeners() { --}}
    {{--         document.querySelectorAll('.like-dislike-btn').forEach(btn => { --}}
    {{--             btn.addEventListener('click', async () => { --}}
    {{--                 const commentId = btn.getAttribute('data-id'); --}}
    {{--                 const type = btn.getAttribute('data-type'); --}}
    {{----}}
    {{--                 const response = await fetch(`/comments/${commentId}/${type}`, { --}}
    {{--                     method: 'POST', --}}
    {{--                     headers: { --}}
    {{--                         'X-CSRF-TOKEN': document.querySelector( --}}
    {{--                             'meta[name="csrf-token"]').content, --}}
    {{--                         'Accept': 'application/json' --}}
    {{--                     } --}}
    {{--                 }); --}}
    {{----}}
    {{--                 const data = await response.json(); --}}
    {{----}}
    {{--                 if (data.success) { --}}
    {{--                     const container = btn.closest('.flex'); --}}
    {{--                     const likeEl = container.querySelector('.like-count'); --}}
    {{--                     const dislikeEl = container.querySelector('.dislike-count'); --}}
    {{----}}
    {{--                     if (type === 'like') { --}}
    {{--                         likeEl.textContent = data.new_count; --}}
    {{--                         likeEl.classList.remove('hidden'); --}}
    {{--                         dislikeEl.classList.add('hidden'); --}}
    {{--                     } else { --}}
    {{--                         dislikeEl.textContent = data.new_count; --}}
    {{--                         dislikeEl.classList.remove('hidden'); --}}
    {{--                         likeEl.classList.add('hidden'); --}}
    {{--                     } --}}
    {{--                 } --}}
    {{--             }); --}}
    {{--         }); --}}
    {{--     } --}}
    {{----}}
    {{----}}
    {{----}}
    {{----}}
    {{--     // AJAX: Submit comment --}}
    {{--     if (form) { --}}
    {{--         form.addEventListener('submit', async function(e) { --}}
    {{--             e.preventDefault(); --}}
    {{----}}
    {{----}}
    {{--             const articleId = form.getAttribute('data-article-id'); --}}
    {{--             const content = form.querySelector('textarea[name="content"]').value; --}}
    {{--             const token = document.querySelector('meta[name="csrf-token"]').getAttribute( --}}
    {{--                 'content'); --}}
    {{----}}
    {{--             try { --}}
    {{--                 const response = await fetch(`/articles/${articleId}/comments/ajax`, { --}}
    {{--                     method: 'POST', --}}
    {{--                     headers: { --}}
    {{--                         'X-CSRF-TOKEN': token, --}}
    {{--                         'Accept': 'application/json', --}}
    {{--                         'Content-Type': 'application/json' --}}
    {{--                     }, --}}
    {{--                     body: JSON.stringify({ --}}
    {{--                         content --}}
    {{--                     }) --}}
    {{--                 }); --}}
    {{----}}
    {{--                 const data = await response.json(); --}}
    {{--                 if (data.success) { --}}
    {{--                     document.querySelector('.comments-list').insertAdjacentHTML('afterbegin', --}}
    {{--                         data.comment_html); --}}
    {{--                     form.reset(); --}}
    {{--                     attachLikeDislikeListeners(); // ✅ Rebind after adding new comment --}}
    {{--                 } else { --}}
    {{--                     alert(data.message || "Comment failed to post."); --}}
    {{--                 } --}}
    {{--             } catch (error) { --}}
    {{--                 console.error("Error submitting comment:", error); --}}
    {{--             } --}}
    {{--         }); --}}
    {{--     } --}}
    {{----}}
    {{--     // AJAX: Filter comments --}}
    {{--     sortButtons.forEach(button => { --}}
    {{--         button.addEventListener('click', async () => { --}}
    {{--             const sort = button.getAttribute('data-sort'); --}}
    {{--             const articleId = {{ $article->id }}; --}}
    {{----}}
    {{--             try { --}}
    {{--                 const response = await fetch(`/articles/${articleId}?sort=${sort}`, { --}}
    {{--                     headers: { --}}
    {{--                         'X-Requested-With': 'XMLHttpRequest', --}}
    {{--                         'Accept': 'text/html' --}}
    {{--                     } --}}
    {{--                 }); --}}
    {{----}}
    {{--                 const html = await response.text(); --}}
    {{--                 const parser = new DOMParser(); --}}
    {{--                 const doc = parser.parseFromString(html, 'text/html'); --}}
    {{--                 const newComments = doc.querySelector('.comments-list'); --}}
    {{----}}
    {{--                 if (!newComments) { --}}
    {{--                     console.warn('No .comments-list found in AJAX response.'); --}}
    {{--                     return; --}}
    {{--                 } --}}
    {{----}}
    {{--                 document.querySelector('.comments-list').innerHTML = newComments --}}
    {{--                     .innerHTML; --}}
    {{----}}
    {{--                 // Toggle active sort button styles --}}
    {{--                 sortButtons.forEach(btn => { --}}
    {{--                     btn.classList.remove('bg-blue-800', 'text-white', --}}
    {{--                         'border-blue-800', 'shadow-sm'); --}}
    {{--                     btn.classList.add('bg-gray-100', 'text-gray-800', --}}
    {{--                         'border-gray-300'); --}}
    {{--                 }); --}}
    {{----}}
    {{--                 button.classList.remove('bg-gray-100', 'text-gray-800', --}}
    {{--                     'border-gray-300'); --}}
    {{--                 button.classList.add('bg-blue-800', 'text-white', 'border-blue-800', --}}
    {{--                     'shadow-sm'); --}}
    {{----}}
    {{--                 attachLikeDislikeListeners(); // ✅ Rebind after filter reload --}}
    {{--             } catch (error) { --}}
    {{--                 console.error("AJAX error:", error); --}}
    {{--             } --}}
    {{--         }); --}}
        });

<<<<<<< HEAD
    // Show message function
    function showMessage(message, type = 'success') {
        const errorEl = document.getElementById('comment-error');
        const successEl = document.getElementById('comment-success');

        // Hide both first
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

    // Reload comments function
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

    // Handle main comment form submission
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
                    if (mainCharCount) mainCharCount.textContent = '0/1000';

                    // Hide reply form if it's a reply
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

    // Handle like/dislike buttons
    document.addEventListener('click', async function (e) {
        const likeBtn = e.target.closest('.like-btn');
        const dislikeBtn = e.target.closest('.dislike-btn');

        if (likeBtn || dislikeBtn) {
            e.preventDefault();

            const isLike = !!likeBtn;
            const commentId = (likeBtn || dislikeBtn).getAttribute('data-comment-id');
            const button = likeBtn || dislikeBtn;

            // Prevent double clicks
            if (button.disabled) return;
            button.disabled = true;

            try {
                const endpoint = isLike ? 'like' : 'dislike';
                const response = await fetch(`/comments/${commentId}/${endpoint}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const commentEl = button.closest('.comment-item');

                    // Update like button
                    const likeButton = commentEl.querySelector('.like-btn');
                    const likeSvg = likeButton.querySelector('svg');
                    const likeCount = likeButton.querySelector('.like-count');

                    if (data.liked) {
                        likeSvg.classList.add('text-blue-600', 'fill-current');
                        likeSvg.classList.remove('text-gray-400');
                        likeCount.classList.add('text-blue-600');
                        likeCount.classList.remove('text-gray-400');
                    } else {
                        likeSvg.classList.remove('text-blue-600', 'fill-current');
                        likeSvg.classList.add('text-gray-400');
                        likeCount.classList.remove('text-blue-600');
                        likeCount.classList.add('text-gray-400');
                    }
                    likeCount.textContent = data.like_count;

                    // Update dislike button
                    const dislikeButton = commentEl.querySelector('.dislike-btn');
                    const dislikeSvg = dislikeButton.querySelector('svg');
                    const dislikeCount = dislikeButton.querySelector('.dislike-count');

                    if (data.disliked) {
                        dislikeSvg.classList.add('text-red-600', 'fill-current');
                        dislikeSvg.classList.remove('text-gray-400');
                        dislikeCount.classList.add('text-red-600');
                        dislikeCount.classList.remove('text-gray-400');
                    } else {
                        dislikeSvg.classList.remove('text-red-600', 'fill-current');
                        dislikeSvg.classList.add('text-gray-400');
                        dislikeCount.classList.remove('text-red-600');
                        dislikeCount.classList.add('text-gray-400');
                    }
                    dislikeCount.textContent = data.dislike_count;
                }
            } catch (error) {
                console.error('Error updating reaction:', error);
                showMessage('Failed to update reaction.', 'error');
            } finally {
                button.disabled = false;
            }
        }

        // Handle reply toggle
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

        // Handle reply cancel
        const cancelBtn = e.target.closest('.reply-cancel-btn');
        if (cancelBtn) {
            const replyForm = cancelBtn.closest('.reply-form');
            if (replyForm) {
                replyForm.classList.add('hidden');
                replyForm.reset();
            }
        }

        // Handle delete comment
        const deleteBtn = e.target.closest('.delete-comment-btn');
        if (deleteBtn) {
            if (confirm('Are you sure you want to delete this comment?')) {
                const commentId = deleteBtn.getAttribute('data-comment-id');
                try {
                    const response = await fetch(`/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
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
        }
    });

    // Handle comment sorting
    document.addEventListener('click', async function (e) {
        const sortBtn = e.target.closest('.comment-sort-btn');
        if (sortBtn) {
            const sort = sortBtn.getAttribute('data-sort');

            // Update button styles
            document.querySelectorAll('.comment-sort-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-sm');
                btn.classList.add('bg-gray-100', 'text-gray-800', 'border-gray-300');
            });

            sortBtn.classList.remove('bg-gray-100', 'text-gray-800', 'border-gray-300');
            sortBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-sm');

            await reloadComments(sort);
        }
    });
});
    {{----}}
    {{----}}
    {{--     attachLikeDislikeListeners(); // ✅ Initial bind --}}
    {{-- }); --}}
</script>
