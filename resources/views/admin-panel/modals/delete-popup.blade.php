<div id="deleteModal" style="display:none;" class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 modal-lexend">
    <div class="bg-white p-6 rounded-lg text-center shadow-lg border">
        <h3 class="text-lg font-bold">Are you sure you want to <strong class="text-red-600">Delete</strong> this post?</h3>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="mt-4 space-x-4">
                <button
                    type="button"
                    onclick="closeModal('deleteModal')"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 transition font-lexend"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition font-lexend"
                >
                    Confirm Delete
                </button>
            </div>
        </form>
    </div>
</div>
