@php
    if (!isset($articles)) {
        $articles = collect();
    }
@endphp
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
            <form method="GET" action="{{ route('admin.articles') }}" class="flex flex-col gap-4">
                <div id="statusFilters" class="flex space-x-2 bg-white rounded shadow overflow-hidden w-full">
                    @php $statuses = ['All', 'Pending', 'Approved', 'Published', 'Rejected']; @endphp
                    @foreach ($statuses as $status)
                        <a href="{{ route('admin.articles', array_merge(request()->except('status'), ['status' => $status])) }}"
                           class="px-6 py-5 text-base font-semibold w-full text-center {{ request('status', 'All') === $status ? 'bg-blue-600 text-white' : 'hover:bg-gray-100' }}">
                            {{ $status }}
                        </a>
                    @endforeach
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search post..." class="rounded-full px-6 py-3 border border-gray-300 focus:outline-none shadow-sm w-72">
                        <button type="submit" class="px-5 py-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition">Search</button>
                        <button id="addPostBtn" type="button" onclick="window.location.href='/add-article'" class="px-5 py-3 bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-full hover:scale-105 transform transition cursor-pointer">+ Add New Post</button>
                    </div>
                    <div class="relative">
                        <button id="filterBtn" type="button" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none">Filter</button>
                        <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow z-50 p-2 space-y-2 hidden">
                            <form method="GET" action="{{ route('admin.articles') }}">
                                @foreach(request()->except('tags') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <a href="{{ route('admin.articles', array_merge(request()->except('tags'))) }}" class="w-full text-left px-3 py-1 text-sm rounded bg-gray-200 hover:bg-gray-300 mb-2 block">Clear Filters</a>
                                @foreach ($tags as $tag)
                                    <button type="submit" name="tags[]" value="{{ $tag->id }}" class="w-full text-left px-3 py-1 text-sm rounded {{ in_array($tag->id, (array)request('tags', [])) ? 'bg-orange-400 text-white' : 'hover:bg-gray-100' }}">{{ $tag->name }}</button>
                                @endforeach
                            </form>
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
                                    <button type="button" class="reject-btn px-3 py-1 bg-orange-500 text-white rounded hover:bg-orange-600" data-id="{{ $article->id }}">Reject</button>
                                @endif

                                @if($article->status === 'Approved')
                                    <button type="button" class="publish-btn px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600" data-id="{{ $article->id }}">Publish</button>
                                @endif

                                <!-- Edit Button -->
                                <button type="button" class="edit-btn px-3 py-1 bg-gray-200 rounded hover:bg-gray-300" data-id="{{ $article->id }}">
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
@include('admin-panel.modals.approve-popup')
@include('admin-panel.modals.rejection-popup')
@include('admin-panel.modals.publish-popup')

<script>
function openModal(modalId, articleId = null) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        if (articleId) {
            modal.setAttribute('data-article-id', articleId);
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.removeAttribute('data-article-id');
    }
}

// Initialize modal event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.id;
            const form = document.getElementById('deleteForm');
            form.action = `/admin/articles/${articleId}/delete`;
            openModal('deleteModal');
        });
    });

    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => openModal('editModal', btn.dataset.id));
    });

    // Reject buttons
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.id;
            const form = document.getElementById('rejectForm');
            form.action = form.action.replace(':id', articleId);
            openModal('rejectionModal');
        });
    });

    // Approve buttons
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.id;
            const form = document.getElementById('approveForm');
            form.action = `/admin/articles/${articleId}/approve`;
            openModal('approveModal');
        });
    });

    // Publish buttons
    document.querySelectorAll('.publish-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.id;
            const form = document.getElementById('publishForm');
            form.action = `/admin/articles/${articleId}/publish`;
            openModal('publishModal');
        });
    });

    // Filter dropdown toggle
    document.getElementById('filterBtn').addEventListener('click', function() {
        document.getElementById('filterDropdown').classList.toggle('hidden');
    });
});
</script>

</body>
</html>
