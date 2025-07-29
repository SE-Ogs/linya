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
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="Your Avatar" class="user-avatar">
            </div>
            <div class="add-comment-input-box border border-black rounded-[10px] bg-white px-3 py-2 w-full">
    <form id="comment-form" data-article-id="{{ $article->id }}" class="w-full">

        @csrf
        <div class="flex items-start justify-between gap-2">
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
                class="text-orange-500 hover:text-orange-600 text-2xl pt-1">
                &#10148;
            </button>
        </div>

        <div class="w-full text-right mt-1">
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
                <button
    class="comment-sort-btn px-4 py-1.5 rounded-md text-sm font-medium transition-all duration-200 border hover:scale-105 {{ ($sort ?? 'all') === $key ? 'bg-blue-800 text-white border-blue-800 shadow-sm' : 'bg-gray-100 text-gray-800 border-gray-300 hover:bg-blue-800 hover:text-white hover:border-blue-800' }}"
    data-sort="{{ $key }}"
    type="button"
>
    {{ $label }}
</button>
            @endforeach
        </div>

        <div class="comments-list space-y-4" id="comments-container">
    @include('partials.comments_list', ['comments' => $comments, 'article' => $article, 'sort' => $sort ?? 'all'])
</div>
    @endguest
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Submit comment via AJAX
    const form = document.getElementById('comment-form');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const articleId = form.getAttribute('data-article-id');
            const content = form.querySelector('textarea[name="content"]').value;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch(`/articles/${articleId}/comments/ajax`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content })
                });

                const data = await response.json();
                if (data.success) {
                    document.querySelector('.comments-list').insertAdjacentHTML('afterbegin', data.comment_html);
                    form.reset();
                } else {
                    alert(data.message || "Comment failed to post.");
                }
            } catch (error) {
                console.error("Error submitting comment:", error);
            }
        });
    }

    // Filter comments via AJAX
    const sortButtons = document.querySelectorAll('.comment-sort-btn');

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

            // Replace comment list
            document.querySelector('.comments-list').innerHTML = newComments.innerHTML;

            // 🔧 Fix styling: remove old active classes, add to current
            sortButtons.forEach(btn => {
                btn.classList.remove('bg-blue-800', 'text-white', 'border-blue-800', 'shadow-sm');
                btn.classList.add('bg-gray-100', 'text-gray-800', 'border-gray-300');
            });

            button.classList.remove('bg-gray-100', 'text-gray-800', 'border-gray-300');
            button.classList.add('bg-blue-800', 'text-white', 'border-blue-800', 'shadow-sm');

        } catch (error) {
            console.error("AJAX error:", error);
        }
    });
});
});
</script>



