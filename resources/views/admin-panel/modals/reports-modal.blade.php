<div id="reportsModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop absolute inset-0" onclick="closeReportsModal()"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-flag text-red-500"></i>
                    User Reports
                </h3>
                <button onclick="closeReportsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-96">
                <div id="reportsContent">
                    <!-- Reports will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Reports Modal Functions
    function openReportsModal(userId) {
        console.log('Opening reports modal for user:', userId);
        document.getElementById('reportsModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Show loading state
        document.getElementById('reportsContent').innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-4"></i><p class="text-gray-500">Loading reports...</p></div>';

        const url = `{{ route('admin.users.reports', ':userId') }}`.replace(':userId', userId);
        console.log('Fetching from URL:', url);

        // Load reports via AJAX
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
                console.log('Reports data received:', data);
                let reportsHtml = '';
                if (!data.reports || data.reports.length === 0) {
                    reportsHtml = '<div class="text-center py-8"><i class="fas fa-inbox text-gray-300 text-4xl mb-4"></i><p class="text-gray-500">No reports found for this user.</p></div>';
                } else {
                    reportsHtml = '<div class="space-y-4">';
                    data.reports.forEach(report => {
                        const statusColor = report.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           report.status === 'resolved' ? 'bg-green-100 text-green-800' :
                                           report.status === 'dismissed' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800';

                        reportsHtml += `
                            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-red-400">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">Report #${report.id}</h4>
                                    <span class="status-badge ${statusColor}">${report.status.toUpperCase()}</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2"><strong>Reason:</strong> ${report.reason}</p>
                                ${report.additional_info ? `<p class="text-sm text-gray-600 mb-2"><strong>Details:</strong> ${report.additional_info}</p>` : ''}
                                <div class="text-xs text-gray-500">
                                    <span>Reported: ${new Date(report.created_at).toLocaleDateString()}</span>
                                    ${report.reviewed_at ? ` • Reviewed: ${new Date(report.reviewed_at).toLocaleDateString()}` : ''}
                                    ${report.reviewed_by ? ` by ${report.reviewed_by.name}` : ''}
                                </div>
                            </div>
                        `;
                    });
                    reportsHtml += '</div>';
                }
                document.getElementById('reportsContent').innerHTML = reportsHtml;
            })
            .catch(error => {
                console.error('Error loading reports:', error);
                document.getElementById('reportsContent').innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle text-2xl mb-4"></i>
                        <p>Error loading reports: ${error.message}</p>
                        <p class="text-sm mt-2">Check console for details.</p>
                    </div>
                `;
            });
    }

    function closeReportsModal() {
        document.getElementById('reportsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
