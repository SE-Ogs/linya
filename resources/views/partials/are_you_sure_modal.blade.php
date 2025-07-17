<!-- Logout Confirmation Modal -->
<div id="logoutModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="w-full max-w-sm rounded-lg bg-white p-6 text-center shadow-lg">
        <h2 class="mb-4 text-lg font-semibold text-gray-800">Are you sure you want to logout?</h2>
        <div class="flex justify-center space-x-4">
            <!-- Cancel Button -->
            <button onclick="document.getElementById('logoutModal').classList.add('hidden')"
                    class="rounded bg-gray-300 px-4 py-2 text-sm text-gray-800 hover:bg-gray-400">
                Cancel
            </button>

            <!-- Confirm Logout (submit form) -->
            <form method="POST"
                  action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="rounded bg-red-500 px-4 py-2 text-sm text-white hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
