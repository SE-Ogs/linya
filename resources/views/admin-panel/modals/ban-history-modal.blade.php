<div id="banHistoryModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop absolute inset-0" onclick="closeBanHistoryModal()"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-gavel text-orange-500"></i>
                    Ban History
                </h3>
                <button onclick="closeBanHistoryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-96">
                <div id="banHistoryContent">
                    <!-- Ban history will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Ban History Modal Functions
    function openBanHistoryModal(userId) {
        console.log('Opening ban history modal for user:', userId);
        document.getElementById('banHistoryModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Show loading state
        document.getElementById('banHistoryContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-4"></i><p class="text-gray-500">Loading ban history...</p></div>';

        const url = `{{ route('admin.users.ban-history', ':userId') }}`.replace(':userId', userId);
        console.log('Fetching from URL:', url);

        // Load ban history via AJAX
        fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Ban history data received:', data);
                let historyHtml = '';
                if (!data.bans || data.bans.length === 0) {
                    historyHtml = '<div class="text-center py-8"><i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i><p class="text-gray-500">No ban history found for this user.</p></div>';
                } else {
                    historyHtml = '<div class="space-y-4">';
                    data.bans.forEach(ban => {
                        const isActive = !ban.unbanned_at;
                        historyHtml += `
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 ${isActive ? 'border-red-400' : 'border-gray-400'}">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">Ban #${ban.id}</h4>
                                    <span class="status-badge ${isActive ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'}">
                                        ${isActive ? 'ACTIVE' : 'LIFTED'}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2"><strong>Reason:</strong> ${ban.reason}</p>
                                <div class="text-xs text-gray-500">
                                    <span>Banned: ${new Date(ban.banned_at).toLocaleDateString()}</span>
                                    ${ban.banned_by_name ? ` by ${ban.banned_by_name}` : ' by System'}
                                    ${ban.unbanned_at ? ` • Unbanned: ${new Date(ban.unbanned_at).toLocaleDateString()}` : ''}
                                    ${ban.unbanned_by_name ? ` by ${ban.unbanned_by_name}` : ''}
                                </div>
                                ${ban.unban_reason ? `<p class="text-sm text-gray-600 mt-2"><strong>Unban Reason:</strong> ${ban.unban_reason}</p>` : ''}
                            </div>
                        `;
                    });
                    historyHtml += '</div>';
                }
                document.getElementById('banHistoryContent').innerHTML = historyHtml;
            })
            .catch(error => {
                console.error('Error loading ban history:', error);
                document.getElementById('banHistoryContent').innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-4"></i>
                        <p>Error loading ban history: ${error.message}</p>
                        <p class="text-sm mt-2">Check console for details.</p>
                    </div>
                `;
            });
    }

    function closeBanHistoryModal() {
        document.getElementById('banHistoryModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
