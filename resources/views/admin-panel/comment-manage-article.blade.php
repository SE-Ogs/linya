<!-- BELOW IS NOT PART OF THE CODE, JUST MOCK DATA FOR TESTING PURPOSES. DELETE WHEN FINALIZING THE PAGE -->
@php
    use Illuminate\Pagination\LengthAwarePaginator;

    $mockData = collect([
    (object)[
        'title' => 'Astolfo: The Iconic Trap from Fate/Apocrypha',
        'excerpt' => 'Astolfo continues to be one of the most beloved and recognizable femboys in anime history. Learn what makes him so iconic.',
        'image_url' => 'https://wallpapers.com/images/high/astolfo-pictures-7cn21cvphffvrg6d.webp',
        'comments_count' => 42
    ],
    (object)[
        'title' => 'Felix Argyle: Re:Zero’s Adorable Knight',
        'excerpt' => 'Felix, or Ferris, is a catboy knight that stole the hearts of Re:Zero fans. Here’s why everyone says “nya~”.',
        'image_url' => 'https://i.pinimg.com/originals/7d/e4/5f/7de45f6cfa9f6d3672d77c53c76aef45.jpg',
        'comments_count' => 36
    ],
    (object)[
        'title' => 'Hideri Kanzaki: Blend S’ Idol-in-Training',
        'excerpt' => 'Don’t let Hideri’s cute idol dreams fool you—he’s a boy with serious energy and comedic timing.',
        'image_url' => 'https://i.pinimg.com/originals/b4/2c/63/b42c6349297d97e3dbb40317a9dc8184.jpg',
        'comments_count' => 22
    ],
    (object)[
        'title' => 'Ruka Urushibara: Steins;Gate’s Elegant Time-Traveler',
        'excerpt' => 'Often mistaken for a girl, Ruka brings elegance and softness to the chaotic Steins;Gate crew.',
        'image_url' => 'https://static.wikia.nocookie.net/steins-gate/images/e/e2/Ruka_Urushibara.png',
        'comments_count' => 29
    ],
    (object)[
        'title' => 'Nagisa Shiota: Assassination Classroom’s Hidden Threat',
        'excerpt' => 'With his soft looks and deadly skills, Nagisa proves femboys can be cute and terrifying.',
        'image_url' => 'https://i.pinimg.com/originals/0b/f1/0c/0bf10c80c739fc9bd231cc16cf916e5b.jpg',
        'comments_count' => 34
    ],
    (object)[
        'title' => 'Mari Kurihara: Prison School’s Secret Weapon',
        'excerpt' => 'Some femboys fly under the radar, but Mari’s elegance and intelligence add complexity to the archetype.',
        'image_url' => 'https://static.zerochan.net/Kurihara.Mari.full.1953566.jpg',
        'comments_count' => 19
    ],
    (object)[
        'title' => 'Trap Archetypes in Anime: More Than Just a Trope?',
        'excerpt' => 'Femboys and "traps" have long been part of anime culture — but what makes them resonate with fans?',
        'image_url' => 'https://i.pinimg.com/originals/96/9d/11/969d116b009bd973382d89e1c7db0711.jpg',
        'comments_count' => 50
    ],
    (object)[
        'title' => 'Top 10 Anime Femboys You Should Know',
        'excerpt' => 'From Felix to Hideri, these anime characters blend gender presentation with serious fan appeal.',
        'image_url' => 'https://i.pinimg.com/736x/ef/57/f5/ef57f5897df2e3c5b8c163cb2c50c53f.jpg',
        'comments_count' => 77
    ],
    (object)[
        'title' => 'Why Anime Loves Pretty Boys in Dresses',
        'excerpt' => 'We explore why the femboy aesthetic is so beloved in anime—and what it says about modern fandom.',
        'image_url' => 'https://pbs.twimg.com/media/FZkQuT7XkAEKDo3.jpg',
        'comments_count' => 18
    ],
    (object)[
        'title' => 'The Psychology of Femboy Popularity in Anime',
        'excerpt' => 'It’s not just looks. Let’s unpack the emotional, aesthetic, and narrative power of femboy characters.',
        'image_url' => 'https://cdn.donmai.us/sample/91/37/sample-9137c979a2b7c2bd4cbd6fc47b9200e6.jpg',
        'comments_count' => 63
    ],
    (object)[
        'title' => 'Felix Argyle: Re:Zero’s Adorable Knight',
        'excerpt' => 'Felix, or Ferris, is a catboy knight that stole the hearts of Re:Zero fans. Here’s why everyone says “nya~”.',
        'image_url' => 'https://i.pinimg.com/originals/7d/e4/5f/7de45f6cfa9f6d3672d77c53c76aef45.jpg',
        'comments_count' => 36
    ],
    (object)[
        'title' => 'Hideri Kanzaki: Blend S’ Idol-in-Training',
        'excerpt' => 'Don’t let Hideri’s cute idol dreams fool you—he’s a boy with serious energy and comedic timing.',
        'image_url' => 'https://i.pinimg.com/originals/b4/2c/63/b42c6349297d97e3dbb40317a9dc8184.jpg',
        'comments_count' => 22
    ],
    (object)[
        'title' => 'Ruka Urushibara: Steins;Gate’s Elegant Time-Traveler',
        'excerpt' => 'Often mistaken for a girl, Ruka brings elegance and softness to the chaotic Steins;Gate crew.',
        'image_url' => 'https://static.wikia.nocookie.net/steins-gate/images/e/e2/Ruka_Urushibara.png',
        'comments_count' => 29
    ],
    (object)[
        'title' => 'Nagisa Shiota: Assassination Classroom’s Hidden Threat',
        'excerpt' => 'With his soft looks and deadly skills, Nagisa proves femboys can be cute and terrifying.',
        'image_url' => 'https://i.pinimg.com/originals/0b/f1/0c/0bf10c80c739fc9bd231cc16cf916e5b.jpg',
        'comments_count' => 34
    ],
    (object)[
        'title' => 'Mari Kurihara: Prison School’s Secret Weapon',
        'excerpt' => 'Some femboys fly under the radar, but Mari’s elegance and intelligence add complexity to the archetype.',
        'image_url' => 'https://static.zerochan.net/Kurihara.Mari.full.1953566.jpg',
        'comments_count' => 19
    ],
    (object)[
        'title' => 'Trap Archetypes in Anime: More Than Just a Trope?',
        'excerpt' => 'Femboys and "traps" have long been part of anime culture — but what makes them resonate with fans?',
        'image_url' => 'https://i.pinimg.com/originals/96/9d/11/969d116b009bd973382d89e1c7db0711.jpg',
        'comments_count' => 50
    ],
    (object)[
        'title' => 'Top 10 Anime Femboys You Should Know',
        'excerpt' => 'From Felix to Hideri, these anime characters blend gender presentation with serious fan appeal.',
        'image_url' => 'https://i.pinimg.com/736x/ef/57/f5/ef57f5897df2e3c5b8c163cb2c50c53f.jpg',
        'comments_count' => 77
    ],
    (object)[
        'title' => 'Why Anime Loves Pretty Boys in Dresses',
        'excerpt' => 'We explore why the femboy aesthetic is so beloved in anime—and what it says about modern fandom.',
        'image_url' => 'https://pbs.twimg.com/media/FZkQuT7XkAEKDo3.jpg',
        'comments_count' => 18
    ],
    (object)[
        'title' => 'The Psychology of Femboy Popularity in Anime',
        'excerpt' => 'It’s not just looks. Let’s unpack the emotional, aesthetic, and narrative power of femboy characters.',
        'image_url' => 'https://cdn.donmai.us/sample/91/37/sample-9137c979a2b7c2bd4cbd6fc47b9200e6.jpg',
        'comments_count' => 63
    ],
]);


    $currentPage = request()->input('page', 1);
    $perPage = 5;
    $currentItems = $mockData->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $results = new LengthAwarePaginator(
        $currentItems,
        $mockData->count(),
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
            @forelse ($results as $post)
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
            {{ $results->appends(request()->query())->links('pagination::comment-manage-article-tailwind') }}
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