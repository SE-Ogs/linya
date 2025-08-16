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

            <!-- Controls and Table Section -->
            <div class="px-8 py-6">
                @include('admin-panel.partials.users-table-navigation')
                @include('admin-panel.partials.users-table')

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
        @include('admin-panel.modals.reports-modal')
        @include('admin-panel.modals.ban-history-modal')

        <script>
            // Existing sidebar toggle
            document.getElementById('toggleAdminSidebar').addEventListener('click', function() {
                const sidebar = document.getElementById('admin_sidebar');
                const header = document.getElementById('admin_header');
                sidebar.classList.toggle('translate-x-[-100%]');
                header.classList.toggle('ml-0');
            });

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
