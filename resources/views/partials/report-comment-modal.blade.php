<div id="reportCommentModal"
     class="fixed inset-0 z-50 hidden h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50">
    <div class="relative top-20 mx-auto w-96 rounded-md border bg-white p-5 shadow-lg">
        <div class="mt-3">
            <h3 class="mb-4 text-center text-lg font-medium text-gray-900">Report Comment</h3>
            <form id="reportCommentForm">
                <input type="hidden"
                       id="reportCommentId"
                       name="comment_id">

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Reason for reporting:
                    </label>
                    <select name="reason"
                            id="reportReason"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400"
                            required>
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
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Additional information (optional):
                    </label>
                    <textarea name="additional_info"
                              id="reportAdditionalInfo"
                              class="w-full resize-none rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-400"
                              rows="3"
                              maxlength="500"
                              placeholder="Provide any additional details..."></textarea>
                    <div class="text-right">
                        <small class="text-gray-500">
                            <span id="reportCharCount">0</span>/500
                        </small>
                    </div>
                </div>

                <div id="reportError"
                     class="mb-4 hidden rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"></div>

                <div class="flex justify-end gap-3">
                    <button type="button"
                            id="cancelReportBtn"
                            class="rounded bg-gray-300 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                            id="confirmReportCommentBtn"
                            class="rounded bg-red-500 px-4 py-2 text-sm text-white transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-50">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Updated JavaScript for the report modal
</script>
