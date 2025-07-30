<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview Article</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.css">
</head>
<body class="bg-gray-50 font-inter">
    @include('partials.admin_sidebar')
    @include('partials.admin_header')
    <div class="ml-0 lg:ml-64 pt-20">
        <div class="mx-auto max-w-4xl mt-10 mb-10">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Article Images Carousel -->
                @if (!empty($images) && count($images) > 0)
                    <div class="relative">
                        <div id="imageCarousel" class="relative h-96 overflow-hidden">
                            @foreach ($images as $index => $image)
                                <div class="carousel-slide absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-slide="{{ $index }}">
                                    <img src="{{ $image['dataUrl'] }}" 
                                         alt="{{ $image['name'] }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Navigation Arrows -->
                        @if (count($images) > 1)
                            <button id="prevBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button id="nextBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dots Indicator -->
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                @foreach ($images as $index => $image)
                                    <button class="carousel-dot w-3 h-3 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white bg-opacity-50' }} transition-all duration-200" data-slide="{{ $index }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Article Content -->
                <div class="p-8">
                    <!-- Article Title & Summary -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $title ?? 'Article Title' }}</h1>
                        @if (!empty($summary))
                            <p class="text-lg text-gray-600 mb-2">{{ $summary }}</p>
                        @endif
                        <div class="flex items-center gap-4 text-sm text-gray-400">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span>{{ auth()->user()->name ?? 'Unknown Author' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                <span>{{ now()->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Display -->
                    @if (!empty($tagModels) && count($tagModels) > 0)
                        <div class="mb-6">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($tagModels as $tag)
                                    <div class="bg-orange-500 rounded-full px-4 py-1 text-xs font-semibold text-white">
                                        {{ $tag->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="mb-8">
                        <div class="prose max-w-none">
                            {!! $article ?? 'No content available...' !!}
                        </div>
                    </div>

                    <!-- Status Bar & Actions -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-t border-gray-200 pt-6">
                        <div class="text-sm text-gray-500">Status: <span class="font-semibold text-orange-500">Preview</span></div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.articles.back-to-editor') }}" class="inline">
                                @csrf
                                <input type="hidden" name="title" value="{{ $title }}">
                                <input type="hidden" name="summary" value="{{ $summary }}">
                                <input type="hidden" name="article" value="{{ $article }}">
                                <input type="hidden" name="imageData" value="{{ json_encode($images) }}">
                                @if (!empty($tags))
                                    @foreach ($tags as $tagId)
                                        <input type="hidden" name="tags[]" value="{{ $tagId }}">
                                    @endforeach
                                @endif
                                <button type="submit" class="rounded-full border border-orange-500 bg-white px-6 py-2 font-semibold text-orange-600 transition duration-200 hover:bg-orange-50">
                                    Back to Editor
                                </button>
                            </form>
                            <button id="publishButton" class="rounded-full bg-orange-500 px-6 py-2 font-semibold text-white transition duration-200 hover:bg-orange-600">
                                Publish
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel JavaScript -->
    @if (!empty($images) && count($images) > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.getElementById('imageCarousel');
                const slides = carousel.querySelectorAll('.carousel-slide');
                const dots = document.querySelectorAll('.carousel-dot');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                let currentSlide = 0;

                function showSlide(index) {
                    // Hide all slides
                    slides.forEach(slide => {
                        slide.classList.remove('opacity-100');
                        slide.classList.add('opacity-0');
                    });
                    
                    // Update dots
                    dots.forEach(dot => {
                        dot.classList.remove('bg-white');
                        dot.classList.add('bg-white', 'bg-opacity-50');
                    });
                    
                    // Show current slide
                    slides[index].classList.remove('opacity-0');
                    slides[index].classList.add('opacity-100');
                    
                    // Update current dot
                    dots[index].classList.remove('bg-opacity-50');
                    dots[index].classList.add('bg-white');
                    
                    currentSlide = index;
                }

                // Next button
                nextBtn.addEventListener('click', function() {
                    const nextIndex = (currentSlide + 1) % slides.length;
                    showSlide(nextIndex);
                });

                // Previous button
                prevBtn.addEventListener('click', function() {
                    const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(prevIndex);
                });

                // Dot navigation
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', function() {
                        showSlide(index);
                    });
                });

                // Auto-advance carousel every 5 seconds
                setInterval(function() {
                    const nextIndex = (currentSlide + 1) % slides.length;
                    showSlide(nextIndex);
                }, 5000);
            });
        </script>
    @endif
</body>
</html>
