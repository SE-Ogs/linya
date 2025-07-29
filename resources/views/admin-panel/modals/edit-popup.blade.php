<div id="editModal" style="display:none;" class="fixed inset-0 bg-transparent backdrop-blur-sm flex items-center justify-center z-50 modal-lexend">
    <div class="bg-white p-6 rounded-lg text-center shadow-lg border">
        <h3 class="text-lg font-bold">Are you sure you want to <strong>Edit</strong> this post?</h3>
        <div class="mt-4 space-x-4">
            <button type="button" onclick="closeModal('editModal')" class="bg-red-500 text-white px-4 py-2 rounded cursor-pointer">No</button>
            <button type="button" id="editConfirmBtn" class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer">Yes</button>
        </div>
    </div>
</div>
<script>
    function closeModal(modalId){
        document.getElementById(modalId).style.display = 'none';
    }
    function confirmEdit(articleId){
        window.location.href = `/edit-article/${articleId}`;
    }
    // Attach event listener to the confirm button to use the article ID from the modal's data attribute
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('editConfirmBtn').addEventListener('click', function() {
            const modal = document.getElementById('editModal');
            const articleId = modal.getAttribute('data-article-id');
            if (articleId) {
                confirmEdit(articleId);
            }
        });
    });
</script>
