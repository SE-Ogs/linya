<div class="mx-auto mb-20 w-full max-w-4xl overflow-x-hidden rounded-lg bg-white p-4 shadow-sm sm:p-6">
    <h2 class="mb-4 text-xl font-semibold text-gray-800">Comments</h2>

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
        <div class="add-comment-area-wrapper">
            <div class="comment-user-info">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                     alt="Your Avatar"
                     class="user-avatar">
            </div>
            <div class="add-comment-input-box w-full rounded-[10px] border border-black bg-white px-3 py-2">
                <form id="comment-form"
                      data-article-id="{{ $article->id }}"
                      class="w-full">

                    @csrf
                    <div class="flex items-start justify-between gap-2">
                        <textarea name="content"
                                  maxlength="500"
                                  placeholder="Add a comment"
                                  class="flex-grow resize-none overflow-hidden border-none px-3 py-2 text-sm leading-snug outline-none transition-all duration-300 focus:shadow-none focus:outline-none"
                                  rows="1"
                                  required
                                  id="comment-box"
                                  oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px';"></textarea>

                        <button type="submit"
                                name="submit_comment"
                                class="pt-1 text-2xl text-orange-500 hover:text-orange-600">
                            &#10148;
                        </button>
                    </div>

                    <div class="mt-1 flex items-center justify-between"
                         id="below-textarea">
                        <!-- JS will insert warning here -->
                        <!-- Char count already exists -->
                        <small id="char-count"
                               class="ml-auto text-gray-500">0/500</small>
                    </div>
                </form>
            </div>

        </div>

        @if (isset($error))
            <p class="error-message mt-2 text-red-500">{{ $error }}</p>
        @endif

        <div class="comment-filters scrollbar-hide my-4 flex gap-2 overflow-x-auto whitespace-nowrap">
            @php
                $filters = ['all' => 'All', 'newest' => 'Newest', 'oldest' => 'Oldest', 'most_liked' => 'Most Liked'];
            @endphp

            @foreach ($filters as $key => $label)
                <button class="comment-sort-btn {{ ($sort ?? 'all') === $key ? 'bg-blue-800 text-white border-blue-800 shadow-sm' : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-blue-800 hover:text-white hover:border-blue-800' }} rounded-md border px-4 py-1.5 text-sm font-medium transition-all duration-200 hover:scale-105"
                        data-sort="{{ $key }}"
                        type="button">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="comments-list space-y-4"
             id="comments-container">
            @include('partials.comments_list', [
                'comments' => $comments,
                'article' => $article,
                'sort' => $sort ?? 'all',
            ])
        </div>
    @endguest
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('comment-form');
        const sortButtons = document.querySelectorAll('.comment-sort-btn');


        function attachLikeDislikeListeners() {
            document.querySelectorAll('.like-dislike-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const commentId = btn.getAttribute('data-id');
                    const type = btn.getAttribute('data-type');

                    const response = await fetch(`/comments/${commentId}/${type}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        const container = btn.closest('.flex');
                        const likeEl = container.querySelector('.like-count');
                        const dislikeEl = container.querySelector('.dislike-count');

                        if (type === 'like') {
                            likeEl.textContent = data.new_count;
                            likeEl.classList.remove('hidden');
                            dislikeEl.classList.add('hidden');
                        } else {
                            dislikeEl.textContent = data.new_count;
                            dislikeEl.classList.remove('hidden');
                            likeEl.classList.add('hidden');
                        }
                    }
                });
            });
        }




        // AJAX: Submit comment
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();


                const articleId = form.getAttribute('data-article-id');
                const content = form.querySelector('textarea[name="content"]').value;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                try {
                    const response = await fetch(`/articles/${articleId}/comments/ajax`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            content
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        document.querySelector('.comments-list').insertAdjacentHTML('afterbegin',
                            data.comment_html);
                        form.reset();
                        attachLikeDislikeListeners(); // ✅ Rebind after adding new comment
                    } else {
                        alert(data.message || "Comment failed to post.");
                    }
                } catch (error) {
                    console.error("Error submitting comment:", error);
                }
            });
        }

        // AJAX: Filter comments
        sortButtons.forEach(button => {
            button.addEventListener('click', async () => {
                const sort = button.getAttribute('data-sort');
                const articleId = {{ $article->id }};

                try {
                    const response = await fetch(`/articles/${articleId}?sort=${sort}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newComments = doc.querySelector('.comments-list');

                    if (!newComments) {
                        console.warn('No .comments-list found in AJAX response.');
                        return;
                    }

                    document.querySelector('.comments-list').innerHTML = newComments
                        .innerHTML;

                    // Toggle active sort button styles
                    sortButtons.forEach(btn => {
                        btn.classList.remove('bg-blue-800', 'text-white',
                            'border-blue-800', 'shadow-sm');
                        btn.classList.add('bg-gray-100', 'text-gray-800',
                            'border-gray-300');
                    });

                    button.classList.remove('bg-gray-100', 'text-gray-800',
                        'border-gray-300');
                    button.classList.add('bg-blue-800', 'text-white', 'border-blue-800',
                        'shadow-sm');

                    attachLikeDislikeListeners(); // ✅ Rebind after filter reload
                } catch (error) {
                    console.error("AJAX error:", error);
                }
            });
        });



        attachLikeDislikeListeners(); // ✅ Initial bind
    });
</script>
