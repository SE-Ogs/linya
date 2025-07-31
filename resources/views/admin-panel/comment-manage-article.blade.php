@php
    $startYear = 2025;
    $endYear = date('Y') + 5;
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Comment Management</title>

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.css">
        <script defer src="//unpkg.com/alpinejs" ></script> <!-- This apprently help the Filter Button have that pop-up effect to the tag selection -->
    </head>

    <body>
        <!-- Sidebar -->
        @include('partials.admin_header')
        @include('partials.admin_sidebar')

        <!-- Search & Filter Form (both elements side by side) -->
        <div class="ml-64 mt-6 px-8 relative max-w-8xl">
            <form action="{{ route('search') }}" method="GET" class="flex items-start gap-x-152 w-full ">
                <input type="hidden" name="type" value="comments">
                <input type="hidden" name="per_page" value="5">

                <!-- Search Input -->
                <div class="flex-1">
                    <div class="flex items-center bg-gray-100 rounded-full shadow px-6 py-3 w-full">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" />
                        </svg>
                        <input name="query" type="text" placeholder="Search Article..."
                            value="{{ request('query') }}"
                            class="appearance-none bg-transparent border-none w-full text-gray-700 placeholder-gray-400 leading-tight focus:outline-none">
                    </div>
                </div>

                <!-- Filter Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 14.414V18a1 1 0 01-1.447.894l-4-2A1 1 0 018 16V14.414L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filter
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-lg z-50 p-4 space-y-4"
                        x-cloak>
                        
                        <!-- Tags -->
                        <div>
                            <p class="text-sm font-semibold mb-1">Tags</p>
                            <div class="flex flex-col gap-1 max-h-32 overflow-y-auto pr-2">
                                @foreach (['Software Engineering', 'Game Development', 'Real Estate Management', 'Animation', 'Multimedia Arts and Design'] as $tag)
                                    <label class="inline-flex items-center text-sm">
                                        <input type="checkbox" name="tags[]"
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
                            <select name="year" id="year"
                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="">All</option>
                                @foreach (range($endYear, $startYear) as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Apply Filter Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-gray-800 text-white text-sm px-3 py-1.5 rounded hover:bg-orange-400">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- List of Posts (maximum of 5 posts per page) -->
        <div class="ml-64 mt-6 px-8 space-y-4">
            @forelse ($articles as $post)
                @php
                    $slug = \Illuminate\Support\Str::slug($post->title);
                    $url = route('comment.manage.show', ['slug' => $slug]);
                @endphp

                <a href="{{ $url }}" class="block hover:bg-gray-50 transition duration-200 rounded-2xl">
                    <div class="flex items-start gap-4 bg-white rounded-2xl shadow p-4 overflow-hidden">
                        <!-- Thumbnail -->
                        <div class="w-32 h-24 flex-shrink-0 mr-4">
                            <img src="{{ $post->image_url ?? 'https://via.placeholder.com/150' }}" alt="Post Image" class="w-full h-full object-cover rounded-xl">
                        </div>

                        <!-- Post Details -->
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold text-gray-800 truncate">{{ $post->title }}</h2>
                            <p class="text-sm text-gray-600 leading-snug mt-1 line-clamp-2">
                                {{ $post->excerpt }}
                            </p>
                        </div>

                        <!-- Comment Count -->
                        <div class="ml-2 mt-6 text-center">
                            <p class="text-xs text-gray-500 font-medium">Total Comments:</p>
                            <p class="text-lg font-bold text-gray-800">{{ $post->comments_count }}</p>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-gray-500 text-center py-12">
                    No posts found.
                </div>
            @endforelse
        </div>

        <!-- Page Navigation -->
        <div class="mt-8 px-8 flex justify-center lg:ml-64">
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