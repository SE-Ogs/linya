<div id="editModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40">
    <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
        <h2 class="mb-4 text-xl font-bold">Edit User</h2>
        <form id="editUserForm"
              method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden"
                   name="user_id"
                   id="editUserId">

            <div class="mb-4">
                <label for="editUserName"
                       class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text"
                       id="editUserName"
                       name="name"
                       class="mt-1 block w-full rounded border border-gray-300 p-2"
                       required>
            </div>

            <div class="mb-4">
                <label for="editUserEmail"
                       class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email"
                       id="editUserEmail"
                       name="email"
                       class="mt-1 block w-full rounded border border-gray-300 p-2"
                       required>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="closeEditModal()"
                        class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">Cancel</button>
                <button type="submit"
                        class="rounded bg-orange-500 px-4 py-2 text-white hover:bg-orange-600">Update</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openEditModal(id, name, email) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUserName').value = name;
        document.getElementById('editUserEmail').value = email;

        const form = document.getElementById('editUserForm');
        form.action = `/admin/users/${id}/admin-update`;

        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('flex');
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
