<body class="bg-gray-50">
    @php
        use App\Services\HelperFunctionService;
    @endphp

    <!-- Check if we should show a single article -->
    @if(request()->has('article_id'))
        @php
            $article = $articles->firstWhere('id', request('article_id'));
            if (!$article) {
                abort(404);
            }
        @endphp

        <!-- Single Article View -->
        <div class="p-8 max-w-7xl mx-auto">
            <div class="bg-white rounded-lg p-8">
                <a href="?" class="flex items-center text-indigo-600 mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <p>Back to all articles</p>
                </a>

                @if($article->tags->count())
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($article->tags as $tag)
                            <div class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-3 py-1 text-xs font-semibold text-white">
                                {{ HelperFunctionService::abbreviateTag($tag->name) }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $article->title }}</h1>

                <div class="flex items-center gap-4 text-sm text-gray-600 mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <p>{{ $article->author->name ?? 'Unknown Author' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <p>{{ $article->created_at->format('F d, Y') }}</p>
                    </div>
                </div>

                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                    <p>{{ $article->article }}</p>
                </div>
            </div>
        </div>
    @else
        <!-- Regular List View -->
        <div class="p-8 max-w-7xl mx-auto">
            <div id="content" class="bg-white flex flex-col lg:flex-row gap-20">
                <!-- Left Side -->
                <div id="left_side" class="flex-1">
                    <div id="posts" class="bg-white flex flex-col gap-6">
                        <!-- Main Post -->
                        @if($articles->count())
                            @php $main = $articles->first(); @endphp
                            <a href="?article_id={{ $main->id }}" id="main_post" class="relative h-96 rounded-lg overflow-hidden cursor-pointer bg-gradient-to-br from-indigo-50 to-pink-50">
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
                            @foreach($articles->skip(1)->take(2) as $sub)
                                <a href="?article_id={{ $sub->id }}" class="flex-1 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm cursor-pointer">
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

                        <!-- News Item 1 -->
                        <div class="py-4 border-b border-gray-100">
                            <div
                                class="border border-orange-400 text-orange-400 rounded-full px-3 py-1 text-xs font-semibold w-fit mb-2">
                                General
                            </div>
                            <h4 class="font-medium text-sm text-gray-800 mb-2 leading-tight">
                                New Study Spaces Open in Library Wing
                            </h4>
                            <div class="text-xs text-gray-500">2 hours ago</div>
                        </div>

                        <!-- News Item 2 -->
                        <div class="py-4 border-b border-gray-100">
                            <div
                                class="border border-indigo-600 text-indigo-600 rounded-full px-3 py-1 text-xs font-semibold w-fit mb-2">
                                Politics
                            </div>
                            <h4 class="font-medium text-sm text-gray-800 mb-2 leading-tight">
                                Student Government Passes Sustainability Resolution
                            </h4>
                            <div class="text-xs text-gray-500">5 hours ago</div>
                        </div>

                        <!-- News Item 3 -->
                        <div class="py-4 border-b border-gray-100">
                            <div
                                class="border border-indigo-600 text-indigo-600 rounded-full px-3 py-1 text-xs font-semibold w-fit mb-2">
                                Software Engineering
                            </div>
                            <h4 class="font-medium text-sm text-gray-800 mb-2 leading-tight">
                                Tech Conference Speakers Announced
                            </h4>
                            <div class="text-xs text-gray-500">1 day ago</div>
                        </div>

                        <!-- News Item 4 -->
                        <div class="py-4">
                            <div class="flex gap-2 mb-2">
                                <div
                                    class="border border-yellow-400 text-yellow-400 rounded-full px-3 py-1 text-xs font-semibold">
                                    Animation
                                </div>
                                <div
                                    class="border border-red-700 text-red-700 rounded-full px-3 py-1 text-xs font-semibold">
                                    MMA
                                </div>
                            </div>
                            <h4 class="font-medium text-sm text-gray-800 mb-2 leading-tight">
                                Artist Tips
                            </h4>
                            <div class="text-xs text-gray-500">2 days ago</div>
                        </div>
                    </div>

                    <!-- Most Read -->
                    <div id="most_read" class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-800 mb-6">Most Read</h2>

                        <!-- Most Read Item 1 -->
                        <div class="flex items-start gap-4 py-3 border-b border-gray-100">
                            <div class="text-2xl font-bold text-orange-400 min-w-fit">1</div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800 mb-1 leading-tight">
                                    Student Wins National Coding Competition
                                </h4>
                                <div class="text-xs text-gray-500">12 hours ago</div>
                            </div>
                        </div>

                        <!-- Most Read Item 2 -->
                        <div class="flex items-start gap-4 py-3 border-b border-gray-100">
                            <div class="text-2xl font-bold text-orange-400 min-w-fit">2</div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800 mb-1 leading-tight">
                                    Campus Sustainability Initiative Launches
                                </h4>
                                <div class="text-xs text-gray-500">18 hours ago</div>
                            </div>
                        </div>

                        <!-- Most Read Item 3 -->
                        <div class="flex items-start gap-4 py-3 border-b border-gray-100">
                            <div class="text-2xl font-bold text-orange-400 min-w-fit">3</div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800 mb-1 leading-tight">
                                    New Research Lab Opens Next Month
                                </h4>
                                <div class="text-xs text-gray-500">1 day ago</div>
                            </div>
                        </div>

                        <!-- Most Read Item 4 -->
                        <div class="flex items-start gap-4 py-3">
                            <div class="text-2xl font-bold text-orange-400 min-w-fit">4</div>
                            <div class="flex-1">
                                <h4 class="font-medium text-sm text-gray-800 mb-1 leading-tight">
                                    Student Art Exhibition Breaks Records
                                </h4>
                                <div class="text-xs text-gray-500">2 days ago</div>
                            </div>
                        </div>
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
                    @foreach($articles->skip(3)->take(8) as $blog)
                        <a href="?article_id={{ $blog->id }}" class="flex-shrink-0 bg-gray-200 rounded p-6 w-full max-w-90 h-auto flex flex-col">
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
    @endif
</body>
