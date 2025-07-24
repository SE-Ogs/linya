<body class="bg-gray-50">
    @php
        use App\Services\HelperFunctionService;
    @endphp

    @if(isset($tag))
        <div class="bg-white border-b border-gray-200 px-8 py-6 max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-black">{{ $tag->name }}</h1>
                <a href="/dashboard" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                    ← View All Articles
                </a>
            </div>
        </div>
    @endif
    <!-- Regular List View -->
    <div class="p-8 max-w-7xl mx-auto">
        <div id="content" class="bg-white flex flex-col lg:flex-row gap-20">
            <!-- Left Side -->
            <div id="left_side" class="flex-1">
                <div id="posts" class="bg-white flex flex-col gap-6">
                    <!-- Main Post -->
                    @php
                        // $articles is already filtered and sorted by views descending
                        $main = $articles->first();
                        $subPosts = $articles->where('id', '!=', $main?->id)->sortByDesc('created_at')->take(2);
                        $blogs = $articles->whereNotIn('id', [$main?->id, ...$subPosts->pluck('id')])->sortByDesc('views')->take(8);
                        $breakingNews = $articles->sortByDesc('created_at')->take(4);
                        $mostRead = $articles->sortByDesc('views')->take(4);
                    @endphp
                    @if($main)
                        <a href="{{ route('articles.show', $main->id) }}" id="main_post" class="relative h-96 rounded-lg overflow-hidden cursor-pointer bg-gradient-to-br from-indigo-50 to-pink-50">
                            <div class="absolute inset-0 gradient-overlay">
                                <img src="/images/placeholder.jpg" alt="placeholder image"
                                    class="w-full h-full object-cover object-center">
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                            <div class="relative z-10 h-full flex flex-col justify-end p-8 text-white">
                                <div>
                                    @if($main->tags->count())
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @foreach($main->tags as $tag)
                                                <div class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-4 py-1 text-xs font-semibold">
                                                    {{ HelperFunctionService::abbreviateTag($tag->name) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <h1 class="text-3xl lg:text-4xl font-bold leading-tight mb-4">
                                        {{ $main->title }}
                                    </h1>
                                    <p class="text-gray-200 text-lg leading-relaxed">
                                        {{ $main->summary}}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-300">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <span>{{ $main->author->name ?? 'Unknown Author' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                        <span>{{ $main->created_at->format('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif

                    <!-- Sub Posts -->
                    <div id="sub_posts" class="bg-white flex flex-col lg:flex-row gap-6">
                        @foreach($subPosts as $sub)
                            <a href="{{ route('articles.show', $sub->id) }}" class="flex-1 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm cursor-pointer">
                                <div>
                                    <img src="/images/placeholder.jpg" alt="placeholder image" class="w-full h-48 object-cover">
                                </div>
                                <div class="px-6 pb-6 min-h-[4.5rem]">
                                    @if($sub->tags->count())
                                        <div class="flex flex-wrap gap-2 mb-4 mt-3">
                                            @foreach($sub->tags as $tag)
                                                <div class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-3 py-1 text-xs font-semibold text-white">
                                                    {{ HelperFunctionService::abbreviateTag($tag->name) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <h3 class="text-xl font-bold text-gray-800 mb-4 leading-tight min-h-[3rem]">
                                        {{ $sub->title }}
                                    </h3>
                                    <p class="text-gray-600 text-base leading-relaxed mb-5 min-h-[10rem]">
                                        {{ $sub->summary }}
                                    </p>
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <p>{{ $sub->author->name ?? 'Unknown Author' }}</p>
                                        <p>{{ $sub->created_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div id="right_side" class="w-full lg:w-96">
                <!-- Breaking News -->
                <div id="breaking_news"
                    class="bg-white border-l-4 border-gray-200 border-r border-t border-b rounded-lg p-7 shadow-sm mb-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <h2 class="text-lg font-bold text-gray-800">Breaking News</h2>
                    </div>
                    @foreach($breakingNews as $index => $article)
                        <div class="py-4 {{ $index < 3 ? 'border-b border-gray-100' : '' }}">
                            @if($article->tags->count())
                                <div class="flex gap-2 mb-2">
                                    @foreach($article->tags->take(2) as $tag)
                                        <div class="border {{ HelperFunctionService::getTagBorderColor($tag->name) }} {{ HelperFunctionService::getTagTextColor($tag->name) }} rounded-full px-3 py-1 text-[0.5rem] font-semibold">
                                        {{ $tag->name }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <h4 class="font-medium text-sm text-gray-800 mb-2 leading-tight">
                                <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
                            </h4>
                            <div class="text-xs text-gray-500">{{ $article->created_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Most Read -->
                <div id="most_read" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Most Read</h2>
                    @foreach($mostRead as $index => $article)
                        <div class="flex items-start gap-4 py-3 {{ $index < 3 ? 'border-b border-gray-100' : '' }}">
                            <div class="text-2xl font-bold text-orange-400 min-w-fit">{{ $index + 1 }}</div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800 mb-1 leading-tight">
                                    <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
                                </h4>
                                <div class="text-xs text-gray-500">{{ $article->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-12">
            <!-- Orange top border line -->
            <div class="border-t-4 border-orange-500 w-full mb-10"></div>

            <!-- Heading -->
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Blogs</h2>

            <!-- Carousel container -->
            <div class="overflow-x-auto flex flex-nowrap space-x-4 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 hover:scrollbar-thumb-gray-600 p-7">
                @foreach($blogs as $blog)
                    <a href="{{ route('articles.show', $blog->id) }}" class="flex-shrink-0 bg-gray-200 rounded p-6 w-full max-w-90 h-auto flex flex-col">
                        <div>
                            <img src="/images/placeholder.jpg" alt="placeholder image" class="w-full h-24 object-cover">
                        </div>
                        <div class="min-h-[4.5rem]">
                            <h3 class="text-lg font-bold mb-2 line-clamp-2">{{ $blog->title }}</h3>
                        </div>
                        @if($blog->tags->count())
                            <div class="flex flex-row gap-2 mb-4">
                                @foreach($blog->tags as $tag)
                                    <div class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-5 py-0.5 text-[0.75rem] font-semibold text-white">
                                        {{ HelperFunctionService::abbreviateTag($tag->name) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <p class="text-sm text-gray-600 flex-1 line-clamp-4 overflow-hidden">{{ Str::limit($blog->summary, 120) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</body>
