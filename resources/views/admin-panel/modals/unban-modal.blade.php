<div id="unbanModal" class="fixed inset-0 z-50 hidden flex items-center justify-center backdrop-blur-sm bg-black/30">
    <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
        <h2 class="mb-4 text-xl font-bold">Unban User</h2>
        <form id="unbanUserForm" method="POST" onsubmit="submitUnbanForm(event)">
            @csrf
            @method('PATCH')

            <input type="hidden" name="user_id" id="unbanUserId">

            <!-- Name -->
            <div class="mb-4">
                <label for="unbanUserName" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="unbanUserName"
                       class="mt-1 block w-full rounded border border-gray-300 p-2 bg-gray-100" readonly>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="unbanUserEmail" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="unbanUserEmail"
                       class="mt-1 block w-full rounded border border-gray-300 p-2 bg-gray-100" readonly>
            </div>

            <!-- Unban Reason -->
            <div class="mb-4">
                <label for="unbanReason" class="block text-sm font-medium text-gray-700">
                    Unban Reason (Optional)
                </label>
                <textarea name="reason" id="unbanReason" rows="4"
                          class="mt-1 block w-full rounded border border-gray-300 p-2"
                          placeholder="Why are you unbanning this user?"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeUnbanModal()"
                        class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit"
                        class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600">
                    Unban User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUnbanModal(userId, name, email) {
        document.getElementById('unbanUserId').value = userId;
        document.getElementById('unbanUserName').value = name;
        document.getElementById('unbanUserEmail').value = email;
        document.getElementById('unbanUserForm').action = `/admin/users/${userId}/unban`;
        document.getElementById('unbanModal').classList.remove('hidden');
    }

    function submitUnbanForm(e) {
        e.preventDefault();
        const form = e.target;
        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn.textContent;

        // Set loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-HTTP-Method-Override': 'PATCH',
                'Accept': 'application/json',
            },
            body: new FormData(form)
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');

            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            return { success: false, message: await response.text() };
        })
        .then(data => {
            if (data.success) {
                closeUnbanModal();
                window.location.reload(); // Or use data.redirect if provided
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Unban failed. Please check console for details.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }

    function closeUnbanModal() {
        document.getElementById('unbanModal').classList.add('hidden');
    }
</script>
