<div id="publishModal" style="display:none;" class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 modal-lexend">
    <div class="bg-white p-6 rounded-lg text-center shadow-lg border">
        <h3 class="text-lg font-bold">Are you sure you want to <strong>Publish</strong> this post?</h3>
        <form id="publishForm" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="Published">
            <div class="mt-4 space-x-4">
                <button type="button" onclick="closeModal('publishModal')" class="bg-red-500 text-white px-4 py-2 rounded">No</button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Yes</button>
            </div>
        </form>
    </div>
</div>
