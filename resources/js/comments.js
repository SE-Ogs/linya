document.addEventListener('DOMContentLoaded', () => {
    // AJAX REPLY
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const articleId = form.getAttribute('data-article-id');
            const parentId = form.getAttribute('data-comment-id');
            const content = form.querySelector('textarea[name="content"]').value;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/articles/${articleId}/comments/ajax`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content, parent_id: parentId })
            });

            const data = await response.json();
            if (data.success) {
                form.insertAdjacentHTML('afterend', data.comment_html);
                form.reset();
                form.classList.add('hidden');
            }
        });
    });

    // LIKE / DISLIKE
    document.querySelectorAll('.like-dislike-btn').forEach(button => {
        button.addEventListener('click', async function () {
            const commentId = button.getAttribute('data-id');
            const type = button.getAttribute('data-type');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/comments/${commentId}/${type}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            });

            const data = await response.json();
            if (data.success) {
                if (type === 'like') {
                    button.querySelector('.like-count').textContent = data.new_count;
                } else {
                    button.querySelector('.dislike-count').textContent = data.new_count;
                }
            }
        });
    });
});
