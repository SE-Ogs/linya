<body class="bg-gray-50">
    @php
        use App\Services\HelperFunctionService;
    @endphp

    @if (isset($tag))
        <div class="mx-auto max-w-7xl border-b border-gray-200 bg-white px-8 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-black">{{ $tag->name }}</h1>
                <a href="/home"
                   class="text-sm font-medium text-blue-600 transition-colors duration-200 hover:text-blue-800">
                    ← View All Articles
                </a>
            </div>
        </div>
    @endif
    <!-- Regular List View -->
    <div class="mx-auto max-w-7xl p-8">

        <!--Search Content -->
        <div id="search-results-container"
             class="mb-12 hidden">

            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Search Results</h2>
                <button onclick="clearSearch()"
                        class="text-sm text-blue-600 hover:underline">← Back to Dashboard</button>
            </div>

            <div id="search-loading"
                 class="mb-4 hidden text-gray-500">Searching...</div>

            <div id="search-results"
                 class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2">

            </div>

        </div>
        <div id="content"
             class="flex flex-col gap-20 bg-white lg:flex-row">
            <!-- Left Side -->
            <div id="left_side"
                 class="flex-1">
                <div id="posts"
                     class="flex flex-col gap-6 bg-white">
                    <!-- Main Post -->
                    @php
                        // $articles is already filtered and sorted by views descending
                        $main = $articles->first();
                        $subPosts = $articles->where('id', '!=', $main?->id)->sortByDesc('created_at')->take(2);
                        $blogs = $articles
                            ->whereNotIn('id', [$main?->id, ...$subPosts->pluck('id')])
                            ->sortByDesc('views')
                            ->take(8);
                        $breakingNews = $articles->sortByDesc('created_at')->take(4);
                        $mostRead = $articles->sortByDesc('views')->take(4);
                    @endphp
                    @if ($main)
                        <a href="{{ route('articles.show', $main->id) }}"
                           id="main_post"
                           class="relative h-96 cursor-pointer overflow-hidden rounded-lg bg-gradient-to-br from-indigo-50 to-pink-50">
                            <div class="gradient-overlay absolute inset-0">
                                @if ($main->first_image)
                                    <img src="{{ Storage::disk('s3')->url($main->first_image->image_path) }}"
                                         alt="{{ $main->title }}"
                                         class="h-full w-full object-cover object-center">
                                @else
                                    <img src="/images/placeholder.jpg"
                                         alt="placeholder image"
                                         class="h-full w-full object-cover object-center">
                                @endif
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                            </div>
                            <div class="relative z-10 flex h-full flex-col justify-end p-8 text-white">
                                <div>
                                    @if ($main->tags->count())
                                        <div class="mb-4 flex flex-wrap gap-2">
                                            @foreach ($main->tags as $tag)
                                                <div
                                                     class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-4 py-1 text-xs font-semibold">
                                                    {{ HelperFunctionService::abbreviateTag($tag->name) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <h1 class="mb-4 text-3xl font-bold leading-tight lg:text-4xl">
                                        {{ $main->title }}
                                    </h1>
                                    <p class="text-lg leading-relaxed text-gray-200">
                                        {!! Str::limit(strip_tags($main->summary), 200) !!}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-300">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12"
                                                    cy="7"
                                                    r="4" />
                                        </svg>
                                        <span>{{ $main->author ?? 'Unknown Author' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <rect x="3"
                                                  y="4"
                                                  width="18"
                                                  height="18"
                                                  rx="2"
                                                  ry="2" />
                                            <line x1="16"
                                                  y1="2"
                                                  x2="16"
                                                  y2="6" />
                                            <line x1="8"
                                                  y1="2"
                                                  x2="8"
                                                  y2="6" />
                                            <line x1="3"
                                                  y1="10"
                                                  x2="21"
                                                  y2="10" />
                                        </svg>
                                        <span>{{ $main->created_at->format('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif

                    <!-- Sub Posts -->
                    <div id="sub_posts"
                         class="flex flex-col gap-6 bg-white lg:flex-row">
                        @foreach ($subPosts as $sub)
                            <a href="{{ route('articles.show', $sub->id) }}"
                               class="flex-1 cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                <div>
                                    @if ($sub->first_image)
                                        <img src="{{ asset('storage/' . $sub->first_image->image_path) }}"
                                             alt="{{ $sub->title }}"
                                             class="h-48 w-full object-cover">
                                    @else
                                        <img src="/images/placeholder.jpg"
                                             alt="placeholder image"
                                             class="h-48 w-full object-cover">
                                    @endif
                                </div>
                                <div class="min-h-[4.5rem] px-6 pb-6">
                                    @if ($sub->tags->count())
                                        <div class="mb-4 mt-3 flex flex-wrap gap-2">
                                            @foreach ($sub->tags as $tag)
                                                <div
                                                     class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-3 py-1 text-xs font-semibold text-white">
                                                    {{ HelperFunctionService::abbreviateTag($tag->name) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <h3 class="mb-4 min-h-[3rem] text-xl font-bold leading-tight text-gray-800">
                                        {{ $sub->title }}
                                    </h3>
                                    <p class="mb-5 min-h-[10rem] text-base leading-relaxed text-gray-600">
                                        {!! Str::limit(strip_tags($sub->summary), 150) !!}
                                    </p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <p>{{ $sub->author ?? 'Unknown Author' }}</p>
                                        <p>{{ $sub->created_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div id="right_side"
                 class="w-full lg:w-96">
                <!-- Breaking News -->
                <div id="breaking_news"
                     class="mb-8 rounded-lg border-b border-l-4 border-r border-t border-gray-200 bg-white p-7 shadow-sm">
                    <div class="mb-6 flex items-center gap-4">
                        <div class="h-2 w-2 rounded-full bg-red-500"></div>
                        <h2 class="text-lg font-bold text-gray-800">Breaking News</h2>
                    </div>
                    @foreach ($breakingNews as $index => $article)
                        <div class="{{ $index < 3 ? 'border-b border-gray-100' : '' }} py-4">
                            @if ($article->tags->count())
                                <div class="mb-2 flex gap-2">
                                    @foreach ($article->tags->take(2) as $tag)
                                        <div
                                             class="{{ HelperFunctionService::getTagBorderColor($tag->name) }} {{ HelperFunctionService::getTagTextColor($tag->name) }} rounded-full border px-3 py-1 text-[0.5rem] font-semibold">
                                            {{ $tag->name }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <h4 class="mb-2 text-sm font-medium leading-tight text-gray-800">
                                <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
                            </h4>
                            <div class="text-xs text-gray-500">{{ $article->created_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Most Read -->
                <div id="most_read"
                     class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-lg font-bold text-gray-800">Most Read</h2>
                    @foreach ($mostRead as $index => $article)
                        <div class="{{ $index < 3 ? 'border-b border-gray-100' : '' }} flex items-start gap-4 py-3">
                            <div class="min-w-fit text-2xl font-bold text-orange-400">{{ $index + 1 }}</div>
                            <div class="flex-1">
                                <h4 class="mb-1 text-sm font-medium leading-tight text-gray-800">
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
            <div class="mb-10 w-full border-t-4 border-orange-500"></div>

            <!-- Heading -->
            <h2 class="mb-6 text-2xl font-bold text-gray-800">Blogs</h2>

            <!-- Carousel container -->
            <div
                 class="scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100 hover:scrollbar-thumb-gray-600 flex flex-nowrap space-x-4 overflow-x-auto p-7">
                @foreach ($blogs as $blog)
                    <a href="{{ route('articles.show', $blog->id) }}"
                       class="max-w-90 flex h-auto w-full flex-shrink-0 flex-col rounded bg-gray-200 p-6">
                        <div>
                            @if ($blog->first_image)
                                <img src="{{ asset('storage/' . $blog->first_image->image_path) }}"
                                     alt="{{ $blog->title }}"
                                     class="h-24 w-full object-cover">
                            @else
                                <img src="/images/placeholder.jpg"
                                     alt="placeholder image"
                                     class="h-24 w-full object-cover">
                            @endif
                        </div>
                        <div class="min-h-[4.5rem]">
                            <h3 class="mb-2 line-clamp-2 text-lg font-bold">{{ $blog->title }}</h3>
                        </div>
                        @if ($blog->tags->count())
                            <div class="mb-4 flex flex-row gap-2">
                                @foreach ($blog->tags as $tag)
                                    <div
                                         class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-5 py-0.5 text-[0.75rem] font-semibold text-white">
                                        {{ HelperFunctionService::abbreviateTag($tag->name) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <p class="line-clamp-4 flex-1 overflow-hidden text-sm text-gray-600">
                            {!! Str::limit(strip_tags($blog->summary), 120) !!}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</body>
