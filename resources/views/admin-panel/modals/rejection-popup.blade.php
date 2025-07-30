<div id="rejectionModal" style="display:none;" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center z-50 modal-lexend">
    <div class="bg-white p-6 rounded-2xl shadow-lg w-[400px] space-y-4">
        <form id="rejectForm" method="POST" action="{{ route('admin.articles.reject', ['id' => ':id']) }}">
            @csrf
            @method('PATCH')

            <p class="text-base mb-2 font-noto-sans">
                Enter the reason for <strong class="font-bold font-lexend">rejection</strong>:
            </p>

            <textarea
                name="rejection_reason"
                class="w-full h-32 border border-black rounded-md p-2 resize-none focus:outline-none focus:ring-2 focus:ring-orange-400 font-noto-sans"
                placeholder="Type your reason here..."
                required
            ></textarea>

            <div class="flex justify-end space-x-3">
                <button
                    type="button"
                    onclick="closeModal('rejectionModal')"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition font-lexend"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-orange-400 text-white px-6 py-2 rounded-md hover:bg-orange-500 transition font-lexend"
                >
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>
