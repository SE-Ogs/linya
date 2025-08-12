<div id="reportCommentModal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50">
    <div class="w-full max-w-sm rounded-lg bg-white p-6 text-center shadow-lg mx-auto">
        <h2 class="mb-4 text-lg font-semibold text-gray-800">Report Comment?</h2>
        <p class="mb-6 text-gray-600">Are you sure you want to report this comment?</p>
        <input type="hidden" id="reportCommentId" value="">
        <div class="flex justify-center space-x-4">
            <button onclick="document.getElementById('reportCommentModal').classList.add('hidden')"
                    class="rounded bg-gray-300 px-4 py-2 text-sm text-gray-800 hover:bg-gray-400">
                Cancel
            </button>

            <button type="button"
                    id="confirmReportCommentBtn"
                    class="rounded bg-yellow-600 px-4 py-2 text-sm text-white hover:bg-yellow-700">
                Report
            </button>
        </div>
    </div>
</div>