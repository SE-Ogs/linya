@php
    if (!isset($articles)) {
        $articles = collect();
    }
@endphp
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token"
              content="{{ csrf_token() }}">
        <title>Post Management</title>
        @vite(['resources/css/app.css', 'resource/js/app.js'])
    </head>

    <body class="font-lexend bg-[#f4f4f4]">

        <div id="app"
             class="flex min-h-screen">

            @php
                $rolePrefix = auth()->user()->role === 'admin' ? 'admin' : 'writer';
            @endphp

            <!-- Sidebar -->
            @include('partials.admin-sidebar')

            <!-- Main Content -->
            <div id="mainContent"
                 class="ml-64 flex-1 transition-all duration-300">
                <!-- Header -->
                @include('partials.admin-header')

                <div class="space-y-6 p-6">
                    <form method="GET"
                          action="{{ route($rolePrefix . '.articles') }}"
                          class="flex flex-col gap-4">
                        <div id="statusFilters"
                             class="flex w-full space-x-2 overflow-hidden rounded bg-white shadow">
                            @php $statuses = ['All', 'Pending', 'Approved', 'Published', 'Rejected']; @endphp
                            @foreach ($statuses as $status)
                                <a href="{{ route($rolePrefix . '.articles', array_merge(request()->except('status'), ['status' => $status])) }}"
                                   class="{{ request('status', 'All') === $status ? 'bg-blue-600 text-white' : 'hover:bg-gray-100' }} w-full px-6 py-5 text-center text-base font-semibold">
                                    {{ $status }}
                                </a>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search post..."
                                       class="w-72 rounded-full border border-gray-300 px-6 py-3 shadow-sm focus:outline-none">
                                <button type="submit"
                                        class="rounded-full bg-blue-500 px-5 py-3 text-white transition hover:bg-blue-600">Search</button>
                                <button id="addPostBtn"
                                        type="button"
                                        onclick="window.location.href='{{ route($rolePrefix . '.articles.create') }}'"
                                        class="transform cursor-pointer rounded-full bg-gradient-to-r from-orange-400 to-orange-500 px-5 py-3 text-white transition hover:scale-105">
                                    + Add New Post </button>
                            </div>
                            <div class="relative">
                                <button id="filterBtn"
                                        type="button"
                                        class="flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 shadow hover:bg-gray-100 focus:outline-none">Filter</button>
                                <div id="filterDropdown"
                                     class="absolute right-0 z-50 mt-2 hidden w-48 space-y-2 rounded border border-gray-200 bg-white p-2 shadow">
                                    <form method="GET"
                                          action="{{ route($rolePrefix . '.articles') }}">
                                        @foreach (request()->except('tags') as $key => $value)
                                            <input type="hidden"
                                                   name="{{ $key }}"
                                                   value="{{ $value }}">
                                        @endforeach
                                        <a href="{{ route($rolePrefix . '.articles', array_merge(request()->except('tags'))) }}"
                                           class="mb-2 block w-full rounded bg-gray-200 px-3 py-1 text-left text-sm hover:bg-gray-300">Clear
                                            Filters</a>
                                        @foreach ($tags as $tag)
                                            <button type="submit"
                                                    name="tags[]"
                                                    value="{{ $tag->id }}"
                                                    class="{{ in_array($tag->id, (array) request('tags', [])) ? 'bg-orange-400 text-white' : 'hover:bg-gray-100' }} w-full rounded px-3 py-1 text-left text-sm">{{ $tag->name }}</button>
                                        @endforeach
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="postsContainer"
                         class="space-y-4">
                        @forelse ($articles as $article)
                            <div
                                 class="article-item @switch($article->status)
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
                        @endswitch relative rounded-xl bg-white p-6 shadow">

                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-xl font-semibold">{{ $article->title }}</h3>
                                        <p class="mt-2 text-sm text-gray-500">{{ $article->summary }}</p>
                                        <div class="mt-3 flex items-center space-x-4">
                                            <span
                                                  class="@switch($article->status)
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
                                        @endswitch rounded-full px-3 py-1 text-xs">
                                                {{ ucfirst($article->status) }}
                                            </span>
                                            <p class="text-xs text-gray-400">Tags:
                                                {{ $article->tags->pluck('name')->join(', ') }}</p>
                                        </div>
                                    </div>

                                    @if (auth()->user()->isAdmin())
                                        <div class="flex items-center space-x-3">
                                            @if ($article->status === 'Pending')
                                                <button type="button"
                                                        class="approve-btn rounded bg-blue-500 px-3 py-1 text-white hover:cursor-pointer hover:bg-blue-600"
                                                        data-id="{{ $article->id }}">Approve</button>
                                                <button type="button"
                                                        class="reject-btn rounded bg-orange-500 px-3 py-1 text-white hover:cursor-pointer hover:bg-orange-600"
                                                        data-id="{{ $article->id }}">Reject</button>
                                            @endif

                                            @if ($article->status === 'Approved')
                                                <button type="button"
                                                        class="publish-btn rounded bg-green-500 px-3 py-1 text-white hover:cursor-pointer hover:bg-green-600"
                                                        data-id="{{ $article->id }}">Publish</button>
                                            @endif

                                            <!-- Edit Button -->
                                            <button type="button"
                                                    class="edit-btn rounded bg-gray-200 px-3 py-1 hover:cursor-pointer hover:bg-gray-300"
                                                    data-id="{{ $article->id }}">
                                                Edit
                                            </button>

                                            <form method="POST"
                                                  action="{{ route($rolePrefix . '.articles.previewExisting', $article->id) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="rounded bg-[#482942] px-3 py-1 text-white hover:bg-[#361e31]">
                                                    Preview
                                                </button>
                                            </form>

                                            <!-- Delete Button -->
                                            <button type="button"
                                                    class="delete-btn rounded bg-red-500 px-3 py-1 text-white hover:cursor-pointer hover:bg-red-600"
                                                    data-id="{{ $article->id }}">
                                                Delete
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @if ($article->status === 'Rejected')
                                    <div class="mt-4 rounded border border-red-200 bg-red-50 p-3">
                                        <p class="text-sm font-medium text-red-600">Rejection Reason:</p>
                                        <p class="text-sm text-red-500">
                                            {{ $article->rejection_reason ?? 'No reason provided' }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-500">No posts found.</div>
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

            function setFormAction(action) {
                const form = document.getElementById('addArticleForm');
                prepareFormForSubmission(); // sync Quill + images

                if (action === 'preview') {
                    // Check if article exists before using its ID
                    const articleId = "{{ $article->id ?? '' }}";
                    if (articleId) {
                        form.action = "{{ route($rolePrefix . '.articles.preview', $article->id ?? 0) }}";
                    } else {
                        // Handle case where no article exists (maybe disable preview?)
                        console.error('No article available for preview');
                        return false;
                    }
                } else {
                    form.action = "{{ route($rolePrefix . '.articles.store') }}";
                }
            }
        </script>

    </body>

</html>
