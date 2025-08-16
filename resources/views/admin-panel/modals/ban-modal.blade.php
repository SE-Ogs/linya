<div id="banModal" class="fixed inset-0 z-50 hidden flex items-center justify-center backdrop-blur-sm bg-black/30">
    <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
        <h2 class="mb-4 text-xl font-bold">Ban User</h2>
        <form id="banUserForm" method="POST">
            @csrf
            @method('PATCH')

            <!-- User ID -->
            <input type="hidden" name="user_id" id="banUserId">

            <!-- Name -->
            <div class="mb-4">
                <label for="banUserName" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text"
                       id="banUserName"
                       class="mt-1 block w-full rounded border border-gray-300 p-2 bg-gray-100"
                       readonly>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="banUserEmail" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email"
                       id="banUserEmail"
                       class="mt-1 block w-full rounded border border-gray-300 p-2 bg-gray-100"
                       readonly>
            </div>

            <!-- Ban Reason -->
            <div class="mb-4">
                <label for="banReason" class="block text-sm font-medium text-gray-700">Ban Reason</label>
                <textarea id="banReason"
                          name="reason"
                          rows="4"
                          class="mt-1 block w-full rounded border border-gray-300 p-2"
                          placeholder="Enter the reason for banning this user..."
                          required></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="closeBanModal()"
                        class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">Cancel</button>
                <button type="submit"
                        class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600">Ban</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBanModal(userId, name, email) {
        // Set form values
        document.getElementById('banUserId').value = userId;
        document.getElementById('banUserName').value = name;
        document.getElementById('banUserEmail').value = email;

        // Set form action URL
        document.getElementById('banUserForm').action = `/admin/users/${userId}/ban`;

        // Show modal
        document.getElementById('banModal').classList.remove('hidden');
    }

    function closeBanModal() {
        document.getElementById('banModal').classList.add('hidden');
    }
</script>
