document.addEventListener('DOMContentLoaded', () => {
    // A single event listener on the body is more efficient than many on individual buttons
    document.body.addEventListener('click', (event) => {
        const replyButton = event.target.closest('.reply-button');
        const toggleButton = event.target.closest('.toggle-replies-btn');

        // Handle clicks on "Reply" buttons
        if (replyButton) {
            event.preventDefault();
            const commentId = replyButton.dataset.commentId;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm) {
                // Hide all other forms before showing the new one
                document.querySelectorAll('.reply-form-container').forEach(form => {
                    if (form.id !== `reply-form-${commentId}`) {
                        form.style.display = 'none';
                    }
                });
                // Toggle the target form
                const isHidden = replyForm.style.display === 'none';
                replyForm.style.display = isHidden ? 'block' : 'none';
                if (isHidden) {
                    replyForm.querySelector('input[name="comment_text"]').focus();
                }
            }
        }

        // Handle clicks on "See/Hide Replies" buttons
        if (toggleButton) {
            const commentId = toggleButton.dataset.commentId;
            const repliesContainer = document.getElementById(`replies-container-${commentId}`);
            if (repliesContainer) {
                const isHidden = repliesContainer.classList.contains('hidden');
                repliesContainer.classList.toggle('hidden');
                toggleButton.textContent = isHidden ? 'Hide Replies' : `See Replies (${toggleButton.dataset.repliesCount})`;
            }
        }
    });
});
