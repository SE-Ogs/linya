<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linya - Article</title>
    @vite('resources/css/app.css')
</head>
<body class="flex min-h-screen flex-col">
    {{-- <div class="flex justify-between"> --}}
    {{--     <button id="toggleSideBar" --}}
    {{--             class="ml-4 mt-3 h-12 cursor-pointer rounded-[10px] p-2 text-[#9A9A9A] transition duration-300 hover:bg-gray-100"> --}}
    {{--         <svg xmlns="http://www.w3.org/2000/svg" --}}
    {{--              fill="none" --}}
    {{--              viewBox="0 0 24 24" --}}
    {{--              stroke-width="1.5" --}}
    {{--              stroke="currentColor" --}}
    {{--              class="size-8"> --}}
    {{--             <path stroke-linecap="round" --}}
    {{--                   stroke-linejoin="round" --}}
    {{--                   d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /> --}}
    {{--         </svg> --}}
    {{--     </button> --}}
    {{--     <div class="m-4 space-x-1"> --}}
    {{--         <button type="button" --}}
    {{--                 id="signup" --}}
    {{--                 class="cursor-pointer rounded-[6px] border border-[#4338CA] px-5 py-2 text-[14px] text-[#4338CA] transition duration-300 hover:bg-[#4338CA] hover:text-white">Sign --}}
    {{--             Up</button> --}}
    {{--         <a href="{{ route('login') }}"> --}}
    {{--             <button type="button" --}}
    {{--                     id="login" --}}
    {{--                     class="cursor-pointer rounded-[6px] border-[#4338CA] px-5 py-2 text-[14px] text-[#4B5563] transition duration-300 hover:bg-[#FF8334] hover:text-white">Log --}}
    {{--                 In</button> --}}
    {{--         </a> --}}
    {{--     </div> --}}
    {{-- </div> --}}

    {{-- <aside id="sidebar" --}}
    {{--        class="fixed left-0 top-0 z-50 -translate-x-full transform transition-transform duration-300"> --}}
    {{--     @include('partials.sidebar') --}}
    {{-- </aside> --}}
    {{----}}
    {{-- <div id="header"> --}}
    {{--     @include('partials.dashboard_header') --}}
    {{-- </div> --}}

    <main class="flex-grow">
        @php
            use App\Services\HelperFunctionService;
        @endphp
        <div class="p-8 max-w-7xl mx-auto">
            <div class="bg-white rounded-lg p-8">
                <a href="/dashboard" class="flex items-center text-indigo-600 mb-6">
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

       @include('partials.ad_space')

        @include('partials.comments')
    </main>

    <footer>
        @include('partials.footer')
    </footer>

    @include('partials.contact_us')
    @vite('resources/js/app.js')
</body>
</html>
