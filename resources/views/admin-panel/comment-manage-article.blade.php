@php
    $startYear = 2025;
    $endYear = date('Y') + 5;
@endphp

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <title>Comment Management</title>

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <script defer
                src="//unpkg.com/alpinejs"></script>
        <!-- This apprently help the Filter Button have that pop-up effect to the tag selection -->
    </head>

    <body>
        <!-- Sidebar -->
        @include('partials.admin-header')
        @include('partials.admin-sidebar')

        <!-- Search & Filter Form (both elements side by side) -->
        <div class="max-w-8xl relative ml-64 mt-6 px-8">
            <form action="{{ route('search') }}"
                  method="GET"
                  class="gap-x-152 flex w-full items-start">
                <input type="hidden"
                       name="type"
                       value="comments">
                <input type="hidden"
                       name="per_page"
                       value="5">

                <!-- Search Input -->
                <div class="flex-1">
                    <div class="flex w-full items-center rounded-full bg-gray-100 px-6 py-3 shadow">
                        <svg class="mr-3 h-5 w-5 text-gray-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" />
                        </svg>
                        <input name="query"
                               type="text"
                               placeholder="Search Article..."
                               value="{{ request('query') }}"
                               class="w-full appearance-none border-none bg-transparent leading-tight text-gray-700 placeholder-gray-400 focus:outline-none">
                    </div>
                </div>

                <!-- Filter Dropdown -->
                <div class="relative"
                     x-data="{ open: false }">
                    <button @click="open = !open"
                            type="button"
                            class="flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 shadow hover:bg-gray-100 focus:outline-none">
                        <svg class="h-4 w-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 14.414V18a1 1 0 01-1.447.894l-4-2A1 1 0 018 16V14.414L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filter
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open"
                         @click.away="open = false"
                         class="absolute right-0 z-50 mt-2 w-64 space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-lg"
                         x-cloak>

                        <!-- Tags -->
                        <div>
                            <p class="mb-1 text-sm font-semibold">Tags</p>
                            <div class="flex max-h-32 flex-col gap-1 overflow-y-auto pr-2">
                                @foreach (['Software Engineering', 'Game Development', 'Real Estate Management', 'Animation', 'Multimedia Arts and Design'] as $tag)
                                    <label class="inline-flex items-center text-sm">
                                        <input type="checkbox"
                                               name="tags[]"
                                               value="{{ $tag }}"
                                               {{ in_array($tag, request()->get('tags', [])) ? 'checked' : '' }}
                                               class="mr-2 rounded border-gray-300 text-orange-500 focus:ring-0">
                                        {{ $tag }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Year -->
                        <div>
                            <label class="mb-1 block text-sm font-semibold"
                                   for="year">Year</label>
                            <select name="year"
                                    id="year"
                                    class="w-full rounded border border-gray-300 px-2 py-1 text-sm">
                                <option value="">All</option>
                                @foreach (range($endYear, $startYear) as $y)
                                    <option value="{{ $y }}"
                                            {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Apply Filter Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="rounded bg-gray-800 px-3 py-1.5 text-sm text-white hover:bg-orange-400">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- List of Posts (maximum of 5 posts per page) -->
        <div class="ml-64 mt-6 space-y-4 px-8">
            @forelse ($articles as $post)
                @php
                    $slug = \Illuminate\Support\Str::slug($post->title);
                    $url = route('comment.manage.show', ['slug' => $slug]);
                @endphp

                <div class="flex items-start gap-4 overflow-hidden rounded-2xl bg-white p-4 shadow">
                    <!-- Thumbnail -->
                    <div class="mr-4 h-24 w-32 flex-shrink-0">
                        <img src="{{ $post->image_url ?? 'https://via.placeholder.com/150' }}"
                             alt="Post Image"
                             class="h-full w-full rounded-xl object-cover">
                    </div>

                    <!-- Post Details -->
                    <div class="flex-1">
                        <h2 class="truncate text-lg font-semibold text-gray-800">{{ $post->title }}</h2>
                        <p class="mt-1 line-clamp-2 text-sm leading-snug text-gray-600">
                            {{ $post->excerpt }}
                        </p>
                    </div>

                    <!-- Comment Count + Reports -->
                    <div class="ml-2 mt-6 text-center">
                        <p class="text-xs font-medium text-gray-500">Total Comments:</p>
                        <p class="text-lg font-bold text-gray-800">{{ $post->comments_count }}</p>

                        <!-- View Reports link -->
                        <a href="{{ route('admin.comment-reports-by-article', $post->id) }}"
                           class="mt-2 block text-xs text-blue-600 underline">
                            View Reports
                        </a>
                    </div>
                </div>

            @empty
                <p class="text-gray-500">No articles found.</p>
            @endforelse

        </div>

        <!-- Page Navigation -->
        <div class="mt-8 flex justify-center px-8 lg:ml-64">
            {{ $articles->appends(request()->query())->links('pagination::comment-manage-article-tailwind') }}
        </div>

        <script>
            document.getElementById('toggleAdminSidebar').addEventListener('click', function() {
                const sidebar = document.getElementById('admin_sidebar');
                sidebar.classList.toggle('translate-x-0');
                sidebar.classList.toggle('-translate-x-full');
                const header = document.getElementById('admin_header');
                header.classList.toggle('ml-64');
                header.classList.toggle('ml-16');
            });
        </script>
    </body>

</html>
