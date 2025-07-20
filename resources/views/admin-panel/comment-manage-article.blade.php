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
                
                <a href="/user-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-semibold">User Management</a>
                
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
                
                <a href="/comment-management" class="py-2 px-3 rounded bg-orange-400 text-white transition text-base font-semibold">Comment Management</a>
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
                <input type="hidden" name="type" value="comments">
                <input type="hidden" name="per_page" value="5">
                <div class="flex items-center bg-gray-100 rounded-full shadow px-6 py-3">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z" />
                    </svg>
                    <input 
                        name="query"
                        type="text"
                        placeholder="Search Article..." 
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