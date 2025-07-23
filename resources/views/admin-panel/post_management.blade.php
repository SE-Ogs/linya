<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Linya CMS</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#f4f4f4] font-lexend">

<div id="app" class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="bg-[#23222E] w-64 h-screen text-white flex flex-col p-4 font-lexend fixed top-0 left-0 z-50 transition-transform duration-300">
        <div class="flex justify-center mb-8 mt-2">
            <img src="/images/linyaText.svg" alt="LINYA Logo" class="h-10 w-auto" />
        </div>
        <nav class="flex flex-col gap-2">
            <a href="/dashboard" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-lg font-bold">Dashboard</a>
            <a href="/user-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">User Management</a>
            <div class="bg-orange-400 text-white rounded">
                <button id="postMgmtToggle" class="w-full flex items-center justify-between py-2 px-3 text-base font-semibold">
                    Post Management
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div id="postMgmtMenu" class="bg-orange-400 text-white rounded hidden">
                <a href="/blog-analytics" class="block py-2 px-3 text-base font-semibold hover:underline">Blog Analytics</a>
                <a href="/article-management" class="block pl-6 py-2 text-sm font-normal hover:underline">Article Management</a>
            </div>
            <a href="/comment-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">Comment Management</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div id="mainContent" class="flex-1 flex flex-col transition-[margin] duration-300 ml-64">
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

        <div class="p-6 space-y-6">
            <form method="GET" action="{{ route('admin.posts') }}" class="flex flex-col gap-4">
                <div id="statusFilters" class="flex space-x-2 bg-white rounded shadow overflow-hidden w-full">
                    @php $statuses = ['All', 'Pending', 'Approved', 'Published', 'Rejected']; @endphp
                    @foreach ($statuses as $status)
                        <button type="submit" name="status" value="{{ $status }}" class="px-6 py-5 text-base font-semibold w-full {{ request('status', 'All') === $status ? 'bg-blue-600 text-white' : 'hover:bg-gray-100' }}">
                            {{ $status }}
                        </button>
                    @endforeach
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search post..." class="rounded-full px-6 py-3 border border-gray-300 focus:outline-none shadow-sm w-72">
                        <button id="addPostBtn" type="button" class="px-5 py-3 bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-full hover:scale-105 transform transition">+ Add New Post</button>
                    </div>
                    <div class="relative">
                        <button id="filterBtn" type="button" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none">Filter</button>
                        <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow z-50 p-2 space-y-2 hidden">
                            @php $allTags = $tags->pluck('name')->toArray(); @endphp
                            @foreach ($allTags as $tag)
                                <button type="submit" name="tags[]" value="{{ $tag }}" class="w-full text-left px-3 py-1 text-sm rounded {{ in_array($tag, (array)request('tags', [])) ? 'bg-orange-400 text-white' : 'hover:bg-gray-100' }}">{{ $tag }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>

            <div id="postsContainer" class="space-y-4">
    @forelse ($articles as $article)
        <div class="article-item relative bg-white rounded-xl shadow p-6
            @switch($article->status)
                @case('Pending')
                    border-l-4 border-yellow-500 bg-yellow-50
                    @break
                @case('Approved')
                    border-l-4 border-blue-500 bg-blue-50
                    @break
                @case('Published')
                    border-l-4 border-green-500 bg-green-50
                    @break
                @case('Rejected')
                    border-l-4 border-red-500 bg-red-50
                    @break
            @endswitch">

            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-semibold text-xl">{{ $article->title }}</h3>
                    <p class="text-sm text-gray-500 mt-2">{{ $article->summary }}</p>
                    <div class="mt-3 flex items-center space-x-4">
                        <span class="px-3 py-1 text-xs rounded-full
                            @switch($article->status)
                                @case('Pending')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('Approved')
                                    bg-blue-100 text-blue-800
                                    @break
                                @case('Published')
                                    bg-green-100 text-green-800
                                    @break
                                @case('Rejected')
                                    bg-red-100 text-red-800
                                    @break
                            @endswitch">
                            {{ ucfirst($article->status) }}
                        </span>
                        <p class="text-xs text-gray-400">Tags: {{ $article->tags->pluck('name')->join(', ') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    @if($article->status === 'Pending')
                        <button type="button" class="approve-btn px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600" data-id="{{ $article->id }}">Approve</button>
                    @endif

                    @if($article->status === 'Approved')
                        <button type="button" class="publish-btn px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600" data-id="{{ $article->id }}">Publish</button>
                    @endif

                    <button type="button" class="edit-btn px-3 py-1 bg-gray-200 rounded hover:bg-gray-300" data-id="{{ $article->id }}">Edit</button>
                    <button type="button" class="delete-btn px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" data-id="{{ $article->id }}">Delete</button>
                </div>
            </div>

            @if($article->status === 'Rejected')
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded">
                    <p class="text-sm text-red-600 font-medium">Rejection Reason:</p>
                    <p class="text-sm text-red-500">{{ $article->rejection_reason ?? 'No reason provided' }}</p>
                </div>
            @endif
        </div>
    @empty
        <div class="text-gray-500 text-center py-12">No posts found.</div>
    @endforelse
</div>
        </div>
    </div>
</div>

@include('admin-panel.modals.delete-popup')

@include('admin-panel.modals.edit-popup')


<script>
function openModal(id, postId = null) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        if (postId) {
            modal.setAttribute('data-post-id', postId);
        }
    }
}
window.closeModal = function(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        modal.removeAttribute('data-post-id');
    }
};
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal('editModal', btn.getAttribute('data-id'));
        });
    });
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal('deleteModal', btn.getAttribute('data-id'));
        });
    });
    document.getElementById('filterBtn').addEventListener('click', function() {
        document.getElementById('filterDropdown').classList.toggle('hidden');
    });
    document.getElementById('postMgmtToggle').addEventListener('click', function() {
        document.getElementById('postMgmtMenu').classList.toggle('hidden');
    });
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('mainContent').classList.toggle('ml-64');
    });
});
</script>

</body>
</html>
