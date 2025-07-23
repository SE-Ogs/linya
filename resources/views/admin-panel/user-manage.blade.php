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
        <title>Document</title>

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.css">
        <script defer src="//unpkg.com/alpinejs" ></script> <!-- This apprently help the Filter Button have that pop-up effect to the tag selection -->
    </head>

    <body>
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-[#23222E] w-64 h-screen text-white flex flex-col p-4 font-lexend fixed top-0 left-0 z-50 transition-transform duration-300">
            <div class="flex justify-center mb-8 mt-2">
                <img src="/images/linyaText.svg" alt="LINYA Logo" class="h-10 w-auto" />
            </div>
            <nav class="flex flex-col gap-2">
                <a href="/dashboard" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-lg font-bold">Dashboard</a>
                
                <a href="/user-management" class="py-2 px-3 rounded bg-orange-400 text-white transition text-base font-semibold">User Management</a>
                
                <div class="rounded">
                    <button id="postMgmtToggle" class="w-full flex items-center justify-between py-2 px-3 text-base font-semibold hover:bg-[#35344a] transition">
                        Post Management
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <div id="postMgmtMenu" class="bg-[#35344a] text-white rounded hidden">
                    <a href="/blog-analytics" class="block py-2 px-3 text-base font-semibold hover:underline">Blog Analytics</a>
                    <a href="/article-management" class="block pl-6 py-2 text-sm font-normal hover:underline">Article Management</a>
                </div>
                
                <a href="/comment-manage-article" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-semibold">Comment Management</a>
            </nav>
        </aside>

        <!-- Header -->
        <header class="bg-[#23222E] text-white px-8 py-4 flex justify-between items-center shadow-md h-20">
            <button id="sidebarToggle" class="p-2 bg-[#2C2B3C] hover:bg-[#35344a] transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center space-x-4">
                <button class="relative p-2 hover:bg-gray-700 rounded-full">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">3</span>
                </button>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">JR</span>
                    </div>
                    <div>
                        <span class="font-semibold text-sm">Jarod R.</span>
                        <p class="text-xs text-gray-400 font-noto">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Search Bar -->
        <div class="ml-64 mt-6 px-8 relative flex items-center justify-between max-w-17xl">
            <form action="{{ route('search') }}" method="GET" class="w-full max-w-4xl mr-20">
                <input type="hidden" name="type" value="users">
                <div class="flex items-center bg-gray-100 rounded-full shadow px-6 py-3">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" />
                    </svg>
                    <input 
                        name="query"
                        type="text"
                        placeholder="Search User..." 
                        value="{{ request('query') }}"
                        class="appearance-none bg-transparent border-none w-full text-gray-700 placeholder-gray-400 leading-tight focus:outline-none">
                </div>
            </form>

            <!-- Filter Button with Dropdown  -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 14.414V18a1 1 0 01-1.447.894l-4-2A1 1 0 018 16V14.414L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter
                </button>

                <!-- Dropdown Panel -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50 p-4 space-y-4" x-cloak>
                    <form action="{{ route('search') }}" method="GET" class="space-y-4">
                        
                    <!-- Preserve current query -->
                        <input type="hidden" name="query" value="{{ request('query') }}">

                        <!-- Tags -->
                        <div>
                            <p class="text-sm font-semibold mb-1">Tags</p>
                            <div class="flex flex-col gap-1">
                                @foreach (['Software Engineering', 'Game Development', 'Real Estate Management', 'Animation', 'Multimedia Arts and Design'] as $tag)
                                    <label class="inline-flex items-center text-sm">
                                        <input 
                                            type="checkbox" 
                                            name="tags[]" 
                                            value="{{ $tag }}" 
                                            {{ in_array($tag, request()->get('tags', [])) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-orange-500 focus:ring-0 mr-2">
                                        {{ $tag }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Year -->
                        <div>
                            <label class="block text-sm font-semibold mb-1" for="year">Year</label>
                            <select name="year" id="year" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="">All</option>
                                @foreach (range(date('Y'), 2020) as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-gray-800 text-white text-sm px-3 py-1.5 rounded hover:bg-orange-400">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabs + Per Page Dropdown in one row -->
        <div class="ml-64 mt-10 px-8">
            <div class="flex flex-wrap justify-between items-center gap-y-4 mb-4">
                
                <!-- Toggle to Switch Between Users and Admins -->
                <div class="flex space-x-2 w-full md:w-auto">
                    <a href="{{ route('user.management', ['type' => 'users', 'per_page' => request('per_page', 10)]) }}"
                        class="px-6 py-2 rounded-t-lg font-medium shadow transition-all duration-150
                        {{ $type === 'users' 
                            ? 'bg-gradient-to-r from-red-400 to-orange-300 text-white scale-105' 
                            : 'bg-white text-gray-700 shadow-sm hover:bg-orange-50' }}">
                        Users
                    </a>

                    <a href="{{ route('user.management', ['type' => 'admins', 'per_page' => request('per_page', 10)]) }}"
                        class="px-6 py-2 rounded-t-lg font-medium shadow transition-all duration-150
                        {{ $type === 'admins' 
                            ? 'bg-gradient-to-r from-red-400 to-orange-300 text-white scale-105' 
                            : 'bg-white text-gray-700 shadow-sm hover:bg-orange-50' }}">
                        Admins
                    </a>
                </div>

                <!-- Per Page Dropdown -->
                <form method="GET" class="flex items-center w-full md:w-auto justify-end md:justify-start">
                    @foreach(request()->except('per_page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <label for="per_page" class="mr-2 text-sm font-medium text-gray-700">Show per page:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1 text-sm">
                        @foreach ([5, 10, 15, 20] as $count)
                            <option value="{{ $count }}" {{ request('per_page', 5) == $count ? 'selected' : '' }}>{{ $count }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- User's Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gradient-to-r from-red-100 to-orange-100">
                        <tr class="text-gray-700 text-center">
                            <th class="p-4"><input type="checkbox" /></th>
                            <th class="p-4">Picture</th>
                            <th class="p-4">Name</th>
                            <th class="p-4">Email Address</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-orange-50 text-center">
                                <td class="p-4 align-middle"><input type="checkbox" /></td>
                                <td class="p-4 align-middle">
                                    <img src="{{ $user->avatar }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover mx-auto">
                                </td>
                                <td class="p-4 align-middle font-medium text-orange-600">{{ $user->name }}</td>
                                <td class="p-4 align-middle text-gray-600">{{ $user->email }}</td>
                                <td class="p-4 align-middle">
                                    <span class="text-sm font-semibold 
                                        {{ 
                                            $user->status === 'Active' ? 'text-green-600' : 
                                            ($user->status === 'Suspended' ? 'text-orange-600' : 
                                            ($user->status === 'Banned' ? 'text-red-600' : 'text-gray-500')) 
                                        }}">
                                        {{ $user->status }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="p-4 align-middle">
                                    <div class="flex justify-center gap-2">
                                        <!-- Edit -->
                                        <button type="button" title="Edit Info" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                                            <img src="{{ asset('images/icon-edit.png') }}" alt="Edit" class="w-5 h-5 hover:opacity-75">
                                        </button>

                                        <!-- Report -->
                                        <form action="{{ route('users.report', ['id' => $user->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" title="Report">
                                                <img src="{{ asset('images/icon-report.png') }}" alt="Report" class="w-5 h-5 hover:opacity-75">
                                            </button>
                                        </form>

                                        <!-- Suspend -->
                                        <form action="{{ route('users.suspend', ['id' => $user->id]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Suspend">
                                                <img src="{{ asset('images/icon-suspend.png') }}" alt="Suspend" class="w-5 h-5 hover:opacity-75">
                                            </button>
                                        </form>

                                        <!-- Delete -->
                                        <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete">
                                                <img src="{{ asset('images/icon-delete.png') }}" alt="Delete" class="w-5 h-5 hover:opacity-75">
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Edit Modal -->
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                    <h2 class="text-xl font-bold mb-4">Edit User</h2>
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="user_id" id="editUserId">

                        <div class="mb-4">
                            <label for="editUserName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="editUserName" name="name" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                        </div>

                        <div class="mb-4">
                            <label for="editUserEmail" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="editUserEmail" name="email" class="mt-1 block w-full border border-gray-300 rounded p-2" required>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pagination -->
            <div class="ml-4 mt-4 px-8">
                <div class="flex justify-center">
                    {{ $users->appends(request()->query())->links('pagination::comment-manage-article-tailwind') }}
                </div>
            </div>
        </div>

        <script>
            document.getElementById('toggleAdminSidebar').addEventListener('click', function() {
                const sidebar = document.getElementById('admin_sidebar');
                const header = document.getElementById('admin_header');
                sidebar.classList.toggle('translate-x-[-100%]');
                header.classList.toggle('ml-0');
            });
        </script>

        <script>
            function openEditModal(id, name, email) {
                document.getElementById('editUserId').value = id;
                document.getElementById('editUserName').value = name;
                document.getElementById('editUserEmail').value = email;

                const form = document.getElementById('editUserForm');
                form.action = `/admin/users/${id}`; // PATCH route

                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.remove('flex');
                document.getElementById('editModal').classList.add('hidden');
            }
        </script>
    </body>
</html>