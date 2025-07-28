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
                        <button id="addPostBtn" type="button" onclick="window.location.href='/add-article'" class="px-5 py-3 bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-full hover:scale-105 transform transition cursor-pointer">+ Add New Post</button>
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
                        <button type="button" class="reject-btn px-3 py-1 bg-orange-500 text-white rounded hover:bg-orange-600" data-id="{{ $article->id }}">
                        Reject
                        </button>
                    @endif

                    @if($article->status === 'Approved')
                        <button type="button" class="publish-btn px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600" data-id="{{ $article->id }}">Publish</button>
                    @endif

                    <!-- Edit Button -->
                    <button type="button" class="edit-btn px-3 py-1 bg-gray-200 rounded hover:bg-gray-300" onclick="openModal('editModal', {{ $article->id }})">
                        Edit
                    </button>

                    <!-- Delete Button -->
                    <button type="button" class="delete-btn px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" data-id="{{ $article->id }}">
                        Delete
                    </button>
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
        modal.style.display = 'flex'; // Show the modal
        if (postId) {
            modal.setAttribute('data-post-id', postId); // Dynamically set the article ID
        }
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        modal.removeAttribute('data-post-id'); // Clear the article ID when closing the modal
    }
}

function confirmEdit() {
    const modal = document.getElementById('editModal');
    const articleId = modal.getAttribute('data-post-id'); // Get the article ID from the modal
    if (articleId) {
        window.location.href = `/edit-article/${articleId}`; // Redirect to the correct edit page
    }
}

function submitRejection() {
    const modal = document.getElementById('rejectionModal');
    const reason = modal.querySelector('textarea').value.trim();
    const articleId = modal.getAttribute('data-post-id');

    if (!reason) {
        alert("Please enter a reason for rejection.");
        return;
    }

    window.location.href = `/admin/articles/${articleId}/reject?reason=${encodeURIComponent(reason)}`;
}

document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const articleId = btn.getAttribute('data-id');
            openModal('rejectionModal', articleId);
        });
    });

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const articleId = btn.getAttribute('data-id'); // Get the article ID
                openModal('editModal', articleId); // Open the modal with the article ID
            });
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
