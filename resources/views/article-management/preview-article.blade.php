<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Preview Article</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <style>
            .prose {
                max-width: none;
                color: #374151;
                line-height: 1.75;
            }

            .prose h1,
            .prose h2,
            .prose h3,
            .prose h4,
            .prose h5,
            .prose h6 {
                color: #111827;
                font-weight: 600;
                margin-top: 1.5em;
                margin-bottom: 0.5em;
            }

            .prose h1 {
                font-size: 2.25rem;
            }

            .prose h2 {
                font-size: 1.875rem;
            }

            .prose h3 {
                font-size: 1.5rem;
            }

            .prose h4 {
                font-size: 1.25rem;
            }

            .prose p {
                margin-bottom: 1em;
            }

            .prose ul,
            .prose ol {
                margin-bottom: 1em;
                padding-left: 1.5em;
            }

            .prose li {
                margin-bottom: 0.5em;
            }

            .prose blockquote {
                border-left: 4px solid #e5e7eb;
                padding-left: 1rem;
                font-style: italic;
                margin: 1.5em 0;
            }

            .prose code {
                background-color: #f3f4f6;
                padding: 0.125rem 0.25rem;
                border-radius: 0.25rem;
                font-size: 0.875em;
            }

            .prose pre {
                background-color: #1f2937;
                color: #f9fafb;
                padding: 1rem;
                border-radius: 0.5rem;
                overflow-x: auto;
                margin: 1.5em 0;
            }

            .prose img {
                max-width: 100%;
                height: auto;
                border-radius: 0.5rem;
                margin: 1.5em 0;
            }

            /* Imported Carousel Styles */
            .article-carousel {
                position: relative;
                width: 100%;
                max-width: 100%;
                margin: 0 auto 2rem;
                background: #f8f9fa;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }

            .carousel-container {
                position: relative;
                width: 100%;
                height: 400px;
                overflow: hidden;
            }

            .carousel-track {
                display: flex;
                transition: transform 0.3s ease-in-out;
                height: 100%;
            }

            .carousel-slide {
                min-width: 100%;
                height: 100%;
                position: relative;
            }

            .carousel-slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            /* Navigation Arrows */
            .carousel-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.95);
                border: none;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                color: #333;
                transition: all 0.3s ease;
                z-index: 10;
                backdrop-filter: blur(10px);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .carousel-nav:hover {
                background: rgba(255, 255, 255, 1);
                transform: translateY(-50%) scale(1.1);
                color: #007bff;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            }

            .carousel-nav.prev {
                left: 20px;
            }

            .carousel-nav.next {
                right: 20px;
            }

            .carousel-nav:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .carousel-nav:disabled:hover {
                transform: translateY(-50%) scale(1);
                background: rgba(255, 255, 255, 0.95);
                color: #333;
            }

            /* Dot Indicators */
            .carousel-indicators {
                display: flex;
                justify-content: center;
                gap: 8px;
                padding: 20px;
                background: rgba(0, 0, 0, 0.05);
            }

            .carousel-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: rgba(0, 0, 0, 0.3);
                cursor: pointer;
                transition: all 0.3s ease;
                border: none;
            }

            .carousel-dot.active {
                background: #007bff;
                transform: scale(1.2);
            }

            .carousel-dot:hover {
                background: #007bff;
                opacity: 0.8;
            }

            /* Image Counter */
            .carousel-counter {
                position: absolute;
                top: 20px;
                right: 20px;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 8px 12px;
                border-radius: 20px;
                font-size: 14px;
                font-weight: 500;
                z-index: 10;
                backdrop-filter: blur(5px);
            }

            /* Thumbnail Strip */
            .carousel-thumbnails {
                display: flex;
                gap: 8px;
                padding: 15px;
                overflow-x: auto;
                background: #f8f9fa;
                scrollbar-width: thin;
                scrollbar-color: #ccc transparent;
            }

            .carousel-thumbnails::-webkit-scrollbar {
                height: 4px;
            }

            .carousel-thumbnails::-webkit-scrollbar-track {
                background: transparent;
            }

            .carousel-thumbnails::-webkit-scrollbar-thumb {
                background: #ccc;
                border-radius: 2px;
            }

            .thumbnail {
                flex-shrink: 0;
                width: 60px;
                height: 60px;
                border-radius: 8px;
                overflow: hidden;
                cursor: pointer;
                border: 2px solid transparent;
                transition: all 0.3s ease;
            }

            .thumbnail:hover {
                border-color: #007bff;
                transform: scale(1.05);
            }

            .thumbnail.active {
                border-color: #007bff;
                box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
            }

            .thumbnail img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            /* Loading State */
            .carousel-loading {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 400px;
                background: #f8f9fa;
                color: #666;
            }

            .loading-spinner {
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #007bff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* No Images State */
            .no-images-state {
                background: #f8f9fa;
                border-radius: 12px;
                margin-bottom: 2rem;
            }

            .no-images-state .h-64 {
                height: 16rem;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .carousel-container {
                    height: 300px;
                }

                .carousel-nav {
                    width: 40px;
                    height: 40px;
                    font-size: 16px;
                }

                .carousel-nav.prev {
                    left: 10px;
                }

                .carousel-nav.next {
                    right: 10px;
                }

                .carousel-counter {
                    top: 10px;
                    right: 10px;
                    padding: 6px 10px;
                    font-size: 12px;
                }

                .thumbnail {
                    width: 50px;
                    height: 50px;
                }
            }

            /* Touch/Swipe Animation */
            .carousel-track.dragging {
                transition: none;
            }
        </style>
    </head>

    <body class="font-inter bg-gray-50">
        @include('partials.admin-sidebar')
        @include('partials.admin-header')

        @php
            $routePrefix = Auth::user()->isAdmin ? 'admin' : 'writer';
        @endphp
        <div class="ml-0 pt-20 lg:ml-64">
            <div class="mx-auto mb-10 mt-10 max-w-5xl">
                <div class="overflow-hidden rounded-lg bg-white shadow-lg">

                    <!-- Imported Article Image Carousel Component -->
                    @if (!empty($images) && count($images) > 0)
                        <div class="article-carousel" id="articleCarousel-preview">
                            <!-- Loading State -->
                            <div class="carousel-loading" id="carouselLoading-preview">
                                <div class="loading-spinner"></div>
                            </div>

                            <!-- Main Carousel -->
                            <div class="carousel-container" id="carouselContainer-preview" style="display: none;">
                                <div class="carousel-track" id="carouselTrack-preview">
                                    @foreach($images as $image)
                                        <div class="carousel-slide">
                                            <img src="{{ $image['dataUrl'] }}"
                                                 alt="{{ $image['alt_text'] ?? ($image['name'] ?? 'Article image') }}"
                                                 loading="lazy">
                                        </div>
                                    @endforeach
                                </div>

                                @if(count($images) > 1)
                                    <!-- Navigation Arrows -->
                                    <button class="carousel-nav prev" id="prevBtn-preview">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M15 18L9 12L15 6"/>
                                        </svg>
                                    </button>
                                    <button class="carousel-nav next" id="nextBtn-preview">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 18L15 12L9 6"/>
                                        </svg>
                                    </button>
                                @endif

                                <!-- Image Counter -->
                                <div class="carousel-counter" id="carouselCounter-preview">
                                    1 / {{ count($images) }}
                                </div>
                            </div>

                            @if(count($images) > 1)
                                <!-- Dot Indicators -->
                                <div class="carousel-indicators" id="carouselIndicators-preview">
                                    @foreach ($images as $index => $image)
                                        <button class="carousel-dot {{ $index === 0 ? 'active' : '' }}"
                                                data-slide="{{ $index }}"></button>
                                    @endforeach
                                </div>

                                <!-- Thumbnail Strip -->
                                <div class="carousel-thumbnails" id="carouselThumbnails-preview">
                                    @foreach ($images as $index => $image)
                                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}"
                                             data-slide="{{ $index }}">
                                            <img src="{{ $image['dataUrl'] }}"
                                                 alt="Thumbnail {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- No Images State -->
                        <div class="no-images-state">
                            <div class="h-64 flex items-center justify-center bg-gray-100 rounded-lg">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No images available</p>
                                    <p class="text-sm">This article doesn't have any images yet.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="p-8">
                        <!-- Article Title & Summary -->
                        <div class="mb-8">
                            <h1 class="mb-4 text-4xl font-bold text-gray-900">{{ $title ?? 'Article Title' }}</h1>
                            @if (!empty($summary))
                                <p class="mb-4 text-xl leading-relaxed text-gray-600">{{ $summary }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm text-gray-400">
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
                                    <span>{{ $author ?? 'Unknown Author' }}</span>
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
                                    <span>{{ now()->format('F d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tags Display -->
                        @if (!empty($tagModels) && count($tagModels) > 0)
                            <div class="mb-8">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($tagModels as $tag)
                                        <div class="rounded-full bg-orange-500 px-4 py-2 text-sm font-semibold text-white">
                                            {{ $tag->name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Article Content -->
                        <div class="mb-8">
                            <div class="prose prose-lg max-w-none">
                                {!! $article ?? 'No content available...' !!}
                            </div>
                        </div>

                        <!-- Status Bar & Actions -->
                        <div class="flex flex-col gap-4 border-t border-gray-200 pt-6 md:flex-row md:items-center md:justify-between">
                            <div class="text-sm text-gray-500">Status: <span class="font-semibold text-orange-500">Preview</span></div>
                            <div class="flex items-center gap-2">
                                @if ($fromManagement)
                                    <a href="{{ route('admin.articles') }}"
                                       class="rounded-full border border-orange-500 bg-white px-6 py-2 font-semibold text-orange-600 transition duration-200 hover:bg-orange-50">
                                        Back to Articles
                                    </a>
                                @else
                                    <form method="POST"
                                          action="{{ route($routePrefix . '.articles.back-to-editor') }}"
                                          class="inline">
                                        @csrf
                                        <input type="hidden" name="title" value="{{ $title }}">
                                        <input type="hidden" name="summary" value="{{ $summary }}">
                                        <input type="hidden" name="author" value="{{ $author }}">
                                        <input type="hidden" name="article" value="{{ $article }}">
                                        <textarea name="imageData" hidden>@json($images)</textarea>

                                        @if (!empty($tags))
                                            @foreach ($tags as $tagId)
                                                <input type="hidden" name="tags[]" value="{{ $tagId }}">
                                            @endforeach
                                        @endif

                                        <button type="submit"
                                                class="rounded-full border border-orange-500 bg-white px-6 py-2 font-semibold text-orange-600 transition duration-200 hover:bg-orange-50">
                                            Back to Editor
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Imported Carousel JavaScript -->
        @if (!empty($images) && count($images) > 0)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const carousel = document.getElementById('articleCarousel-preview');

                    if (!carousel) return;

                    const track = document.getElementById('carouselTrack-preview');
                    const slides = track.querySelectorAll('.carousel-slide');
                    const prevBtn = document.getElementById('prevBtn-preview');
                    const nextBtn = document.getElementById('nextBtn-preview');
                    const indicators = document.getElementById('carouselIndicators-preview');
                    const thumbnails = document.getElementById('carouselThumbnails-preview');
                    const counter = document.getElementById('carouselCounter-preview');
                    const loading = document.getElementById('carouselLoading-preview');
                    const container = document.getElementById('carouselContainer-preview');

                    let currentIndex = 0;
                    let isDragging = false;
                    let startX = 0;
                    let currentX = 0;
                    let initialTransform = 0;

                    // Initialize
                    function init() {
                        if (slides.length === 0) return;

                        // Show carousel
                        if (loading) loading.style.display = 'none';
                        if (container) container.style.display = 'block';

                        updateCarousel();
                        bindEvents();

                        // Start autoplay if multiple images
                        if (slides.length > 1) {
                            startAutoPlay();
                        }
                    }

                    function bindEvents() {
                        // Navigation buttons
                        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
                        if (nextBtn) nextBtn.addEventListener('click', nextSlide);

                        // Dot indicators
                        if (indicators) {
                            indicators.addEventListener('click', function(e) {
                                if (e.target.classList.contains('carousel-dot')) {
                                    const index = parseInt(e.target.dataset.slide);
                                    goToSlide(index);
                                }
                            });
                        }

                        // Thumbnails
                        if (thumbnails) {
                            thumbnails.addEventListener('click', function(e) {
                                const thumbnail = e.target.closest('.thumbnail');
                                if (thumbnail) {
                                    const index = parseInt(thumbnail.dataset.slide);
                                    goToSlide(index);
                                }
                            });
                        }

                        // Keyboard navigation
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'ArrowLeft') prevSlide();
                            if (e.key === 'ArrowRight') nextSlide();
                        });

                        // Touch/Mouse events for swiping
                        if (track) {
                            track.addEventListener('mousedown', handleStart);
                            track.addEventListener('mousemove', handleMove);
                            track.addEventListener('mouseup', handleEnd);
                            track.addEventListener('mouseleave', handleEnd);

                            track.addEventListener('touchstart', handleStart, { passive: false });
                            track.addEventListener('touchmove', handleMove, { passive: false });
                            track.addEventListener('touchend', handleEnd);
                        }
                    }

                    function handleStart(e) {
                        isDragging = true;
                        startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
                        initialTransform = -currentIndex * 100;
                        track.classList.add('dragging');
                    }

                    function handleMove(e) {
                        if (!isDragging) return;
                        e.preventDefault();

                        currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
                        const diffX = currentX - startX;
                        const movePercent = (diffX / carousel.offsetWidth) * 100;

                        track.style.transform = `translateX(${initialTransform + movePercent}%)`;
                    }

                    function handleEnd() {
                        if (!isDragging) return;

                        isDragging = false;
                        const diffX = currentX - startX;
                        const threshold = carousel.offsetWidth * 0.1; // 10% threshold

                        if (Math.abs(diffX) > threshold) {
                            if (diffX > 0) {
                                prevSlide();
                            } else {
                                nextSlide();
                            }
                        } else {
                            goToSlide(currentIndex);
                        }

                        track.classList.remove('dragging');
                    }

                    function prevSlide() {
                        if (currentIndex > 0) {
                            goToSlide(currentIndex - 1);
                        }
                    }

                    function nextSlide() {
                        if (currentIndex < slides.length - 1) {
                            goToSlide(currentIndex + 1);
                        }
                    }

                    function goToSlide(index) {
                        currentIndex = index;
                        updateCarousel();
                    }

                    function updateCarousel() {
                        // Update track position
                        if (track) {
                            track.style.transform = `translateX(-${currentIndex * 100}%)`;
                        }

                        // Update indicators
                        const dots = indicators ? indicators.querySelectorAll('.carousel-dot') : [];
                        dots.forEach((dot, index) => {
                            dot.classList.toggle('active', index === currentIndex);
                        });

                        // Update thumbnails
                        const thumbs = thumbnails ? thumbnails.querySelectorAll('.thumbnail') : [];
                        thumbs.forEach((thumb, index) => {
                            thumb.classList.toggle('active', index === currentIndex);
                        });

                        // Update counter
                        if (counter) {
                            counter.textContent = `${currentIndex + 1} / ${slides.length}`;
                        }

                        // Update navigation buttons
                        if (prevBtn) prevBtn.disabled = currentIndex === 0;
                        if (nextBtn) nextBtn.disabled = currentIndex === slides.length - 1;
                    }

                    function startAutoPlay(interval = 5000) {
                        if (slides.length <= 1) return;

                        let autoPlayInterval = setInterval(() => {
                            if (currentIndex >= slides.length - 1) {
                                goToSlide(0);
                            } else {
                                nextSlide();
                            }
                        }, interval);

                        // Pause on hover
                        carousel.addEventListener('mouseenter', () => {
                            clearInterval(autoPlayInterval);
                        });

                        carousel.addEventListener('mouseleave', () => {
                            autoPlayInterval = setInterval(() => {
                                if (currentIndex >= slides.length - 1) {
                                    goToSlide(0);
                                } else {
                                    nextSlide();
                                }
                            }, interval);
                        });
                    }

                    // Initialize the carousel
                    init();
                });
            </script>
        @endif
    </body>

</html>
