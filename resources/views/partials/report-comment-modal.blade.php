<div id="reportCommentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 text-center mb-4">Report Comment</h3>
            <form id="reportCommentForm">
                <input type="hidden" id="reportCommentId" name="comment_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for reporting:
                    </label>
                    <select name="reason" id="reportReason" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400" required>
                        <option value="">Select a reason...</option>
                        <option value="spam">Spam or unwanted content</option>
                        <option value="harassment">Harassment or bullying</option>
                        <option value="hate_speech">Hate speech or discrimination</option>
                        <option value="inappropriate">Inappropriate content</option>
                        <option value="misinformation">False or misleading information</option>
                        <option value="violence">Violent or threatening content</option>
                        <option value="copyright">Copyright infringement</option>
                        <option value="other">Other (please specify)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Additional information (optional):
                    </label>
                    <textarea name="additional_info" id="reportAdditionalInfo"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 resize-none"
                              rows="3"
                              maxlength="500"
                              placeholder="Provide any additional details..."></textarea>
                    <div class="text-right">
                        <small class="text-gray-500">
                            <span id="reportCharCount">0</span>/500
                        </small>
                    </div>
                </div>

                <div id="reportError" class="mb-4 hidden rounded border border-red-200 bg-red-50 px-3 py-2 text-red-700 text-sm"></div>

                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancelReportBtn"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button type="submit" id="confirmReportCommentBtn"
                            class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Updated JavaScript for the report modal
document.addEventListener('DOMContentLoaded', function() {
    const reportModal = document.getElementById('reportCommentModal');
    const reportForm = document.getElementById('reportCommentForm');
    const reportError = document.getElementById('reportError');
    const reportAdditionalInfo = document.getElementById('reportAdditionalInfo');
    const reportCharCount = document.getElementById('reportCharCount');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Character count for additional info
    reportAdditionalInfo?.addEventListener('input', function() {
        const length = this.value.length;
        reportCharCount.textContent = length;
    });

    // Close modal handlers
    document.getElementById('cancelReportBtn')?.addEventListener('click', function() {
        closeReportModal();
    });

    // Close modal when clicking outside
    reportModal?.addEventListener('click', function(e) {
        if (e.target === reportModal) {
            closeReportModal();
        }
    });

    function closeReportModal() {
        reportModal.classList.add('hidden');
        reportForm.reset();
        reportError.classList.add('hidden');
        reportCharCount.textContent = '0';
    }

    function showReportError(message) {
        reportError.textContent = message;
        reportError.classList.remove('hidden');
    }

    // Handle report form submission
    reportForm?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const commentId = document.getElementById('reportCommentId').value;
        const reason = document.getElementById('reportReason').value;
        const additionalInfo = document.getElementById('reportAdditionalInfo').value;
        const submitBtn = document.getElementById('confirmReportCommentBtn');

        if (!reason) {
            showReportError('Please select a reason for reporting.');
            return;
        }

        // Disable submit button and show loading state
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        try {
            const response = await fetch(`/comments/${commentId}/report`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    comment_id: commentId,
                    reason: reason,
                    additional_info: additionalInfo || null
                })
            });

            const data = await response.json();

            if (data.success) {
                closeReportModal();
                // Show success message using the existing function
                if (typeof showMessage === 'function') {
                    showMessage(data.message);
                } else {
                    alert(data.message);
                }
            } else {
                showReportError(data.message || 'Failed to submit report.');
            }
        } catch (error) {
            console.error('Error submitting report:', error);
            showReportError('An error occurred while submitting your report. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
});
</script>
