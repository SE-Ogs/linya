<!-- BELOW IS NOT PART OF THE CODE, JUST MOCK DATA FOR TESTING PURPOSES. DELETE WHEN FINALIZING THE PAGE -->
@php
    use Illuminate\Pagination\LengthAwarePaginator;

    $type = request('type', 'users');
    $perPage = request('per_page', 10);
    $currentPage = request()->input('page', 1);

    $allUsers = collect([
        (object)[ 'id' => 1, 'name' => 'Kluwi', 'email' => 'kluwi@iacademy.edu.ph', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/40?img=1', 'role' => 'user' ],
        (object)[ 'id' => 2, 'name' => 'Jamie', 'email' => 'jamie@iacademy.edu.ph', 'status' => 'Suspended', 'avatar' => 'https://i.pravatar.cc/40?img=2', 'role' => 'admin' ],
        (object)[ 'id' => 3, 'name' => 'Deniella', 'email' => 'deniella@iacademy.edu.ph', 'status' => 'Banned', 'avatar' => 'https://i.pravatar.cc/40?img=3', 'role' => 'user' ],
        (object)[ 'id' => 4, 'name' => 'Christian', 'email' => 'christian@iacademy.edu.ph', 'status' => 'Inactive', 'avatar' => 'https://i.pravatar.cc/40?img=4', 'role' => 'admin' ],
        (object)[ 'id' => 5, 'name' => 'Alyssa', 'email' => 'alyssa@iacademy.edu.ph', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/40?img=5', 'role' => 'user' ],
        (object)[ 'id' => 6, 'name' => 'Nathan', 'email' => 'nathan@iacademy.edu.ph', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/40?img=6', 'role' => 'user' ],
        (object)[ 'id' => 7, 'name' => 'Isabelle', 'email' => 'isabelle@iacademy.edu.ph', 'status' => 'Suspended', 'avatar' => 'https://i.pravatar.cc/40?img=7', 'role' => 'admin' ],
        (object)[ 'id' => 8, 'name' => 'Mark', 'email' => 'mark@iacademy.edu.ph', 'status' => 'Inactive', 'avatar' => 'https://i.pravatar.cc/40?img=8', 'role' => 'user' ],
        (object)[ 'id' => 9, 'name' => 'Trisha', 'email' => 'trisha@iacademy.edu.ph', 'status' => 'Banned', 'avatar' => 'https://i.pravatar.cc/40?img=9', 'role' => 'admin' ],
        (object)[ 'id' => 10, 'name' => 'Leo', 'email' => 'leo@iacademy.edu.ph', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/40?img=10', 'role' => 'user' ],
        (object)[ 'id' => 11, 'name' => 'Mae', 'email' => 'mae@iacademy.edu.ph', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/40?img=11', 'role' => 'admin' ],
        (object)[ 'id' => 12, 'name' => 'Kenji', 'email' => 'kenji@iacademy.edu.ph', 'status' => 'Suspended', 'avatar' => 'https://i.pravatar.cc/40?img=12', 'role' => 'user' ],
        (object)[ 'id' => 13, 'name' => 'Yuna', 'email' => 'yuna@iacademy.edu.ph', 'status' => 'Banned', 'avatar' => 'https://i.pravatar.cc/40?img=13', 'role' => 'admin' ],
        (object)[ 'id' => 14, 'name' => 'Zion', 'email' => 'zion@iacademy.edu.ph', 'status' => 'Inactive', 'avatar' => 'https://i.pravatar.cc/40?img=14', 'role' => 'user' ],
        (object)[ 'id' => 15, 'name' => 'Haruki', 'email' => 'haruki@iacademy.edu.ph', 'status' => 'Active', 'avatar' => 'https://i.pravatar.cc/40?img=15', 'role' => 'admin' ],
    ]);

    $filteredUsers = $allUsers->filter(fn($user) => $user->role === ($type === 'admins' ? 'admin' : 'user'))->values();
    $currentItems = $filteredUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $users = new LengthAwarePaginator(
        $currentItems,
        $filteredUsers->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
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
        <!--- Admin Sidebar -->
        <button id="toggleAdminSidebar" class="fixed top-4 left-4 z-50 p-2 bg-gray-800 text-white rounded">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <aside id="admin_sidebar" class="fixed left-0 top-0 z-50 transition-transform duration-300 transform">
            @include('partials.admin_sidebar')
        </aside>

        <div id="admin_header" class="transition-all duration-300 ml-64">
            @include('partials.admin_header')
        </div>

        <!-- Search Bar -->
        <div class="ml-64 mt-6 px-8 relative flex items-center justify-between max-w-17xl">
            <form action="{{ route('search') }}" method="GET" class="w-full max-w-4xl mr-20">
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
                    <input type="hidden" name="type" value="{{ $type }}">
                    <label for="per_page" class="mr-2 text-sm font-medium text-gray-700">Show per page:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1 text-sm">
                        @foreach ([5, 10, 15, 20] as $count)
                            <option value="{{ $count }}" {{ request('per_page', 10) == $count ? 'selected' : '' }}>{{ $count }}</option>
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
                                        <a href="{{ route('users.edit', ['id' => $user->id]) }}" title="Edit Info">
                                            <img src="{{ asset('images/icon-edit.png') }}" alt="Edit" class="w-5 h-5 hover:opacity-75">
                                        </a>

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
            <!-- Shows if Action buttons are working. For testing purposes only, delete on final verison -->
            @if (session('message'))
                <div class="bg-blue-100 border border-blue-300 text-blue-700 px-4 py-2 rounded my-4">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Pagination -->
            <div class="ml-4 mt-4 px-8">
                <div class="flex justify-center">
                    {{ $users->links('pagination::comment-manage-article-tailwind') }}
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
    </body>
</html>