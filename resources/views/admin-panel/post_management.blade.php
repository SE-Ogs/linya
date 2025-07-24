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
    @include('partials.admin_sidebar')


    <!-- Main Content -->
    <div id="mainContent" class="ml-64 flex-1 transition-all duration-300">
        <!-- Header -->
        @include('partials.admin_header')
        
        <div class="p-6 space-y-6">
            <form method="GET" action="{{ route('admin.posts') }}" class="flex flex-col gap-4">
                <div id="statusFilters" class="flex space-x-2 bg-white rounded shadow overflow-hidden w-full">
                    @php $statuses = ['All', 'Pending Review', 'Approved', 'Published', 'Rejected']; @endphp
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
                    <div class="relative flex items-center justify-between bg-white p-6 rounded-xl shadow">
                        <div>
                            <h3 class="font-semibold text-xl">{{ $article->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $article->summary }}</p>
                            <p class="text-xs text-gray-400">Tags: {{ $article->tags->pluck('name')->join(', ') }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="button" class="text-blue-500 hover:underline edit-btn" data-id="{{ $article->id }}">Edit</button>
                            <button type="button" class="text-red-500 hover:underline delete-btn" data-id="{{ $article->id }}">Delete</button>
                        </div>
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
