@php
    use Illuminate\Support\Facades\Request;

    $perPage = Request::get('per_page', 10);
    $type = Request::get('type', 'users');
@endphp

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Management</title>

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.css">
        <script defer src="//unpkg.com/alpinejs"></script>
        <!-- Font Awesome for better icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            .user-card-hover {
                transition: all 0.3s ease;
            }
            .user-card-hover:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            }
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .action-btn {
                transition: all 0.2s ease;
                border-radius: 8px;
                font-weight: 500;
            }
            .action-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
            .record-indicator {
                position: relative;
                cursor: pointer;
            }
            .record-indicator:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: #1f2937;
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                white-space: nowrap;
                z-index: 10;
            }
            .tab-gradient {
                background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            }
            .search-glow:focus-within {
                box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
            }
            .modal-backdrop {
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(4px);
            }
        </style>
    </head>

    <body class="bg-gray-50">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Sidebar -->
        @include('partials.admin-header')
        @include('partials.admin-sidebar')

        <!-- Main Content -->
        <div class="ml-64 min-h-screen">
            <!-- Header Section with Search -->
            <div class="bg-white shadow-sm border-b px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                        <p class="text-gray-600 mt-1">Manage users, writers, and administrators</p>
                    </div>

                    <!-- Enhanced Search Bar -->
                    <form action="{{ route('admin.index') }}" method="GET" class="w-full lg:max-w-md">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="relative search-glow transition-all duration-200">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input name="query" type="text" placeholder="Search users by name or email..."
                                   value="{{ request('query') }}"
                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            @if(request('query'))
                                <button type="button" onclick="document.querySelector('input[name=query]').value=''; this.form.submit();"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Controls Section -->
            <div class="px-8 py-6">
                <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <!-- Enhanced Tab Navigation -->
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <a href="{{ route('admin.index', ['type' => 'users', 'per_page' => request('per_page', 10)]) }}"
                               class="{{ $type === 'users' ? 'tab-gradient text-white shadow-lg' : 'text-gray-700 hover:text-orange-600' }}
                                      flex items-center gap-2 px-6 py-2 rounded-md font-medium transition-all duration-200">
                                <i class="fas fa-users text-sm"></i>
                                <span>Users</span>
                                @if($type === 'users')
                                    <span class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full">{{ $users->total() }}</span>
                                @endif
                            </a>

                            <a href="{{ route('admin.index', ['type' => 'writers', 'per_page' => request('per_page', 10)]) }}"
                               class="{{ $type === 'writers' ? 'tab-gradient text-white shadow-lg' : 'text-gray-700 hover:text-orange-600' }}
                                      flex items-center gap-2 px-6 py-2 rounded-md font-medium transition-all duration-200">
                                <i class="fas fa-pen-fancy text-sm"></i>
                                <span>Writers</span>
                                @if($type === 'writers')
                                    <span class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full">{{ $users->total() }}</span>
                                @endif
                            </a>

                            <a href="{{ route('admin.index', ['type' => 'admins', 'per_page' => request('per_page', 10)]) }}"
                               class="{{ $type === 'admins' ? 'tab-gradient text-white shadow-lg' : 'text-gray-700 hover:text-orange-600' }}
                                      flex items-center gap-2 px-6 py-2 rounded-md font-medium transition-all duration-200">
                                <i class="fas fa-user-shield text-sm"></i>
                                <span>Admins</span>
                                @if($type === 'admins')
                                    <span class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full">{{ $users->total() }}</span>
                                @endif
                        </div>

                        <!-- Enhanced Filters -->
                        <form method="GET" class="flex items-center gap-4">
                            @foreach (request()->except('per_page', 'status') as $key => $value)
                                @if (is_array($value))
                                    @foreach ($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700">Show:</label>
                                <select name="per_page" onchange="this.form.submit()"
                                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    @foreach ([5, 10, 15, 20, 50] as $count)
                                        <option value="{{ $count }}" {{ request('per_page', 10) == $count ? 'selected' : '' }}>
                                            {{ $count }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700">Status:</label>
                                <select name="status" onchange="this.form.submit()"
                                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    @php $statuses = ['All', 'Active', 'Banned', 'Suspended']; @endphp
                                    @foreach ($statuses as $s)
                                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Enhanced Users Table -->
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-orange-50 to-red-50 border-b border-orange-100">
                                <tr>
                                    <th class="px-6 py-4 text-left">
                                        <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" />
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Records</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Join Date</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr class="user-card-hover {{ $loop->even ? 'bg-gray-50/50' : 'bg-white' }}">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" />
                                        </td>

                                        <!-- User Info -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="relative">
                                                    <img src="{{ $user->avatar }}" alt="Avatar"
                                                         class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-200">
                                                    @if($user->status === 'Active')
                                                        <div class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full bg-green-500 border-2 border-white"></div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Role -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' :
                                                   ($user->role === 'writer' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                @if($user->role === 'admin')
                                                    <i class="fas fa-crown"></i>
                                                @elseif($user->role === 'writer')
                                                    <i class="fas fa-pen"></i>
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">
                                            <span class="status-badge
                                                {{ $user->status === 'Active' ? 'bg-green-100 text-green-800' :
                                                   ($user->status === 'Suspended' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($user->status === 'Banned' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                @if($user->status === 'Active')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                @elseif($user->status === 'Suspended')
                                                    <i class="fas fa-pause-circle mr-1"></i>
                                                @elseif($user->status === 'Banned')
                                                    <i class="fas fa-ban mr-1"></i>
                                                @endif
                                                {{ $user->status }}
                                            </span>
                                        </td>

                                        <!-- Records Indicators -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <!-- Reports Count -->
                                                <div class="record-indicator" data-tooltip="View Reports"
                                                     onclick="openReportsModal({{ $user->id }})">
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs
                                                        {{ ($user->comment_reports_count ?? 0) > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600' }}">
                                                        <i class="fas fa-flag"></i>
                                                        {{ $user->comment_reports_count ?? 0 }}
                                                    </span>
                                                </div>

                                                <!-- Bans Count -->
                                                <div class="record-indicator" data-tooltip="View Ban History"
                                                     onclick="openBanHistoryModal({{ $user->id }})">
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs
                                                        {{ ($user->user_bans_count ?? 0) > 0 ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-600' }}">
                                                        <i class="fas fa-gavel"></i>
                                                        {{ $user->user_bans_count ?? 0 }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Join Date -->
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- Edit Button -->
                                                <button type="button"
                                                        onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                                        class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </button>

                                                <!-- Ban/Unban Button -->
                                                @if($user->status === 'Banned')
                                                    <button type="button"
                                                            onclick="openUnbanModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                                            class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                                                        <i class="fas fa-unlock"></i>
                                                        Unban
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            onclick="openBanModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                                            class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                                                        <i class="fas fa-ban"></i>
                                                        Ban
                                                    </button>
                                                @endif

                                                <!-- Role Dropdown -->
                                                <form action="{{ route('admin.users.set-role', ['id' => $user->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="role" onchange="this.form.submit()"
                                                            class="border border-gray-200 rounded-lg px-2 py-1 text-xs focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                        <option value="writer" {{ $user->role === 'writer' ? 'selected' : '' }}>Writer</option>
                                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    </select>
                                                </form>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    @if($users->isEmpty())
                        <div class="text-center py-12">
                            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                            <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                        </div>
                    @endif
                </div>

                <!-- Enhanced Pagination -->
                @if($users->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $users->appends(request()->query())->links('pagination::comment-manage-article-tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Include Existing Modals -->
        @include('admin-panel.modals.edit-modal')
        @include('admin-panel.modals.ban-modal')
        @include('admin-panel.modals.unban-modal')

        <!-- New Reports Modal -->
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

        <!-- New Ban History Modal -->
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
            // Existing sidebar toggle
            document.getElementById('toggleAdminSidebar').addEventListener('click', function() {
                const sidebar = document.getElementById('admin_sidebar');
                const header = document.getElementById('admin_header');
                sidebar.classList.toggle('translate-x-[-100%]');
                header.classList.toggle('ml-0');
            });

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

            // Close modals when clicking outside
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeReportsModal();
                    closeBanHistoryModal();
                }
            });

            // Enhanced search functionality
            const searchInput = document.querySelector('input[name="query"]');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (this.value.length >= 2 || this.value.length === 0) {
                            this.form.submit();
                        }
                    }, 500);
                });
            }

            // Bulk selection functionality
            const selectAllCheckbox = document.querySelector('thead input[type="checkbox"]');
            const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    rowCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            // Update select all based on individual selections
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('tbody input[type="checkbox"]:checked').length;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
                    selectAllCheckbox.checked = checkedCount === rowCheckboxes.length;
                });
            });
        </script>
    </body>
</html>
