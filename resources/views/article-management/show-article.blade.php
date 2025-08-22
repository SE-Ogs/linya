<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token"
              content="{{ csrf_token() }}">
        <title>Linya - Article</title>
        @vite('resources/css/app.css')
        @livewireStyles

        <!-- Add Swiper CSS for carousel -->
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    </head>

    <body class="flex min-h-screen flex-col">
        <main class="flex-grow">
            @php
                use App\Services\HelperFunctionService;
            @endphp
            <div class="mx-auto max-w-7xl p-8">
                <div class="rounded-lg bg-white p-8">
                    <a href="/home"
                       class="mb-6 flex items-center text-indigo-600">
                        <svg class="mr-2 h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <p>Back to all articles</p>
                    </a>

                    @if ($article->tags->count())
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach ($article->tags as $tag)
                                <div
                                     class="{{ HelperFunctionService::getTagColor($tag->name) }} rounded-full px-3 py-1 text-xs font-semibold text-white">
                                    {{ HelperFunctionService::abbreviateTag($tag->name) }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <h1 class="mb-4 text-3xl font-bold text-gray-800">{{ $article->title }}</h1>

                    <div class="mb-6 flex items-center gap-4 border-b border-gray-200 pb-4 text-sm text-gray-600">
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
                            <p>{{ $article->author ?? 'Unknown Author' }}</p>
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
                            <p>{{ $article->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Image Carousel -->
                    @if ($article->images->count() > 0)
                        <div class="swiper mb-8 overflow-hidden rounded-lg">
                            <div class="swiper-wrapper">
                                @foreach ($article->images as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="{{ $article->title }}"
                                             class="h-96 w-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <!-- Add pagination -->
                            <div class="swiper-pagination"></div>
                            <!-- Add navigation buttons -->
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    @else
                        <!-- Placeholder if no images -->
                        <div class="mb-8">
                            <img src="/images/placeholder.jpg"
                                 alt="No images available"
                                 class="h-96 w-full rounded-lg object-cover">
                        </div>
                    @endif

                    <div class="whitespace-pre-line leading-relaxed text-gray-700">
                        {!! $article->article !!}
                    </div>
                </div>
            </div>

            @include('partials.ad-space')

            <div class="mx-auto max-w-7xl p-8">
                <livewire:comments :articleId="$article->id" />
            </div>

        </main>

        <footer>
            @include('partials.footer')
        </footer>

        @include('partials.contact-us')
        @include('partials.report-comment-modal')

        <!-- Add Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            // Initialize Swiper
            document.addEventListener('DOMContentLoaded', function() {
                const swiper = new Swiper('.swiper', {
                    // Optional parameters
                    loop: true,
                    autoplay: {
                        delay: 5000,
                    },

                    // If we need pagination
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },

                    // Navigation arrows
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            });
        </script>
        @vite('resources/js/app.js')
        @livewireScripts
        @push('scripts')
            <script src="{{ asset('js/app.js') }}"></script>
        @endpush

    </body>

</html>
