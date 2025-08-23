<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
</head>

<body class="flex min-h-screen flex-col">
    @include('partials.admin-sidebar')
    @include('partials.admin-header')

    @php
        $routePrefix = Auth::user()->isAdmin ? 'admin' : 'writer';
        $formData = $formData ?? [];
        $selectedTags = $selectedTags ?? old('tags', []);
        $images = $images ?? [];
    @endphp

    <div id="mainContent" class="ml-64 flex min-h-0 flex-1 flex-col overflow-auto bg-gray-50 p-8 transition-all duration-300">
        <form id="addArticleForm" method="POST" action="{{ route($routePrefix . '.articles.store') }}" class="flex w-full flex-1 flex-col space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Hidden inputs for image data -->
            <input type="hidden" name="imageData" id="imageDataInput" value="{{ json_encode($images) }}">

            <!-- Title Field -->
            <div class="w-full">
                <label for="title" class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" required
                       value="{{ old('title', $formData['title'] ?? $title ?? '') }}"
                       class="w-full rounded-[20px] border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
                       placeholder="Enter title of article...">
            </div>

            <!-- Author Field -->
            <div class="w-full">
                <label for="author" class="mb-2 block text-sm font-medium text-gray-700">Author</label>
                <input type="text" id="author" name="author" required
                       value="{{ old('author', $formData['author'] ?? $author ?? '') }}"
                       class="w-full rounded-[20px] border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
                       placeholder="Enter author of article...">
            </div>

            <!-- Summary Field -->
            <div class="w-full">
                <label for="summary" class="mb-2 block text-sm font-medium text-gray-700">Summary</label>
                <input type="text" id="summary" name="summary" required
                       value="{{ old('summary', $formData['summary'] ?? $summary ?? '') }}"
                       class="w-full rounded-[20px] border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
                       placeholder="Enter summary of article...">
            </div>

            <!-- Tags Section -->
            <div class="w-full">
                <p class="mb-2 text-sm text-gray-500">Add tags to let users know who the post is directed to!</p>
                <div class="flex flex-wrap gap-3">
                    @foreach ($tags as $index => $tag)
                        @php
                            $dotColors = ['bg-blue-500', 'bg-black', 'bg-gray-600', 'bg-yellow-500', 'bg-red-500'];
                            $dotColor = $dotColors[$index % count($dotColors)];
                        @endphp

                        <label class="inline-flex cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   class="peer sr-only"
                                   {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}>
                            <div class="inline-flex items-center gap-2 rounded-full border-2 border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 shadow-md transition-all duration-200 hover:bg-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:text-white">
                                <span class="{{ $dotColor }} h-2 w-2 rounded-full"></span>
                                <span>{{ $tag->name }}</span>
                                <svg class="h-4 w-4 peer-checked:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <svg class="hidden h-4 w-4 peer-checked:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Image Upload Section -->
            <div class="w-full">
                <label class="mb-2 block text-sm font-medium text-gray-700">Article Images</label>

                <!-- Hidden file input -->
                <input type="file" id="imageInput" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                       multiple style="display: none;">

                <!-- Upload area -->
                <div class="cursor-pointer rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center transition-all duration-200 hover:border-gray-400 hover:bg-gray-100"
                     onclick="document.getElementById('imageInput').click()">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-400">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    <div class="mb-2 text-lg font-medium text-gray-600">Upload Images</div>
                    <div class="text-sm text-gray-500">Click here to select images for your article</div>
                    <div class="mt-2 text-xs text-gray-400">Supports: JPG, PNG, GIF, WebP (Max 10MB each)</div>
                </div>

                <!-- Image Previews -->
                <div id="imagePreviewContainer" class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6"></div>
            </div>

            <!-- Article Content Field -->
            <div class="flex w-full flex-1 flex-col">
                <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Article Content</label>
                <div id="editor" class="min-h-[200px] flex-1 rounded-lg border border-gray-300 bg-white"></div>
                <textarea name="article" id="article" class="hidden">{{ old('article', $formData['article'] ?? $article ?? '') }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex w-full justify-end space-x-3">
                <button type="button" id="previewButton"
                        class="transform rounded-[50px] border border-[#482942] bg-white px-8 py-2 font-semibold text-[#482942] transition duration-200 ease-in-out hover:scale-105 hover:bg-[#f5f0f7] focus:outline-none focus:ring-2 focus:ring-[#482942] focus:ring-offset-2">
                    Preview
                </button>
                <button type="submit" id="publishButton"
                        class="transform rounded-[50px] bg-[#482942] px-8 py-2 font-semibold text-white transition duration-200 ease-in-out hover:scale-105 hover:bg-[#3a2136] focus:outline-none focus:ring-2 focus:ring-[#482942] focus:ring-offset-2">
                    Publish
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        // Global variables
        let quill;
        let uploadedImages = [];
        const routePrefix = '{{ $routePrefix }}';

        // Initialize everything when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeQuillEditor();
            initializeImageUpload();
            initializeFormHandling();
            restoreExistingImages();
        });

        // Initialize Quill editor
        function initializeQuillEditor() {
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{'header': 1}, {'header': 2}],
                        [{'list': 'ordered'}, {'list': 'bullet'}],
                        [{'script': 'sub'}, {'script': 'super'}],
                        [{'indent': '-1'}, {'indent': '+1'}],
                        [{'size': ['small', false, 'large', 'huge']}],
                        [{'header': [1, 2, 3, 4, 5, 6, false]}],
                        [{'color': []}, {'background': []}],
                        [{'align': []}],
                        ['clean'],
                        ['link', 'image']
                    ]
                },
                placeholder: 'Write your article content here...'
            });

            // Load existing content
            const articleContent = document.getElementById('article').value;
            if (articleContent) {
                quill.clipboard.dangerouslyPasteHTML(articleContent);
            }
        }

        // Initialize image upload functionality
        function initializeImageUpload() {
            const imageInput = document.getElementById('imageInput');
            imageInput.addEventListener('change', handleFileSelection);
        }

        // Handle file selection
        function handleFileSelection(event) {
            const files = event.target.files;
            Array.from(files).forEach(file => {
                if (isValidImageFile(file)) {
                    processImageFile(file);
                }
            });
            event.target.value = ''; // Reset input
        }

        // Validate image file
        function isValidImageFile(file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            const maxSize = 10 * 1024 * 1024; // 10MB

            if (!validTypes.includes(file.type)) {
                alert(`Invalid file type: ${file.name}. Please select JPG, PNG, GIF, or WebP images.`);
                return false;
            }

            if (file.size > maxSize) {
                alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
                return false;
            }

            // Check for duplicates
            const isDuplicate = uploadedImages.some(img =>
                img.name === file.name && img.size === file.size
            );

            if (isDuplicate) {
                alert(`File "${file.name}" is already uploaded.`);
                return false;
            }

            return true;
        }

        // Process image file
        function processImageFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageData = {
                    file: file,
                    name: file.name,
                    size: file.size,
                    dataUrl: e.target.result,
                    id: Date.now() + Math.random(),
                    order: uploadedImages.length,
                    isFeatured: uploadedImages.length === 0
                };

                uploadedImages.push(imageData);
                displayImagePreview(imageData);
            };
            reader.readAsDataURL(file);
        }

        // Display image preview
        function displayImagePreview(imageData) {
            const container = document.getElementById('imagePreviewContainer');
            const imageWrapper = document.createElement('div');
            imageWrapper.className = 'relative group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow';
            imageWrapper.setAttribute('data-image-id', imageData.id);

            const img = document.createElement('img');
            img.className = 'w-full aspect-square object-cover';
            img.src = imageData.dataUrl;
            img.alt = imageData.name;

            const featuredBadge = imageData.isFeatured ?
                '<div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Featured</div>' : '';

            const orderBadge = `<div class="absolute top-2 right-2 bg-gray-800 text-white text-xs px-2 py-1 rounded-full">${imageData.order + 1}</div>`;

            imageWrapper.innerHTML = `
                <div class="aspect-square"></div>
                ${featuredBadge}
                ${orderBadge}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-200 flex items-center justify-center">
                    <button type="button" onclick="removeImage('${imageData.id}')"
                            class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-2">
                    <div class="text-xs text-gray-500 truncate" title="${imageData.name}">${imageData.name}</div>
                    <div class="text-xs text-gray-400">${formatFileSize(imageData.size)}</div>
                    ${imageData.isFeatured ? '<div class="text-xs text-blue-600 font-medium">Featured Image</div>' : ''}
                </div>
            `;

            // Replace the aspect-square div with the actual image
            const aspectDiv = imageWrapper.querySelector('.aspect-square');
            aspectDiv.replaceWith(img);

            container.appendChild(imageWrapper);
        }

        // Remove image
        function removeImage(imageId) {
            const imageToRemove = uploadedImages.find(img => img.id == imageId);
            if (!imageToRemove) return;

            const wasFeatureImage = imageToRemove.isFeatured;
            uploadedImages = uploadedImages.filter(img => img.id != imageId);

            // Remove from DOM
            document.querySelector(`[data-image-id="${imageId}"]`).remove();

            // Reorder and reassign featured status
            uploadedImages.forEach((img, index) => {
                img.order = index;
                if (wasFeatureImage && index === 0) {
                    img.isFeatured = true;
                } else if (wasFeatureImage) {
                    img.isFeatured = false;
                }
            });

            if (uploadedImages.length > 0) {
                refreshImagePreviews();
            }
        }

        // Refresh image previews
        function refreshImagePreviews() {
            const container = document.getElementById('imagePreviewContainer');
            container.innerHTML = '';
            uploadedImages.forEach(imageData => {
                displayImagePreview(imageData);
            });
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Restore existing images when coming back from preview
        function restoreExistingImages() {
            const imageDataInput = document.getElementById('imageDataInput');
            if (imageDataInput && imageDataInput.value) {
                try {
                    const parsedImages = JSON.parse(imageDataInput.value);
                    if (parsedImages.length > 0) {
                        uploadedImages = parsedImages.map((img, index) => ({
                            dataUrl: img.dataUrl,
                            name: img.name,
                            size: img.size || 0,
                            id: img.id || Date.now() + Math.random() + index,
                            order: index,
                            isFeatured: index === 0,
                            existing: img.existing || false
                        }));
                        refreshImagePreviews();
                    }
                } catch (e) {
                    console.error('Error parsing imageData:', e);
                }
            }
        }

        // Initialize form handling
        function initializeFormHandling() {
            const form = document.getElementById('addArticleForm');
            const previewButton = document.getElementById('previewButton');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    prepareFormForSubmission();
                    this.submit();
                }
            });

            previewButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    form.action = `/${routePrefix}/articles/preview`;
                    prepareFormForSubmission();
                    form.submit();
                }
            });
        }

        // Validate form
        function validateForm() {
            const errors = [];

            if (!document.getElementById('title').value.trim()) {
                errors.push('• Title is required');
            }

            if (!document.getElementById('author').value.trim()) {
                errors.push('• Author is required');
            }

            if (!document.getElementById('summary').value.trim()) {
                errors.push('• Summary is required');
            }

            if (document.querySelectorAll('input[name="tags[]"]:checked').length === 0) {
                errors.push('• At least one tag must be selected');
            }

            if (!quill.getText().trim() || quill.getText().trim() === '\n') {
                errors.push('• Article content is required');
            }

            if (errors.length > 0) {
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                return false;
            }

            return true;
        }

        // Prepare form for submission
        function prepareFormForSubmission() {
            // Sync Quill content
            if (quill) {
                document.getElementById('article').value = quill.root.innerHTML;
            }

            // Update image data
            const imageDataArray = uploadedImages
                .sort((a, b) => a.order - b.order)
                .map(img => ({
                    dataUrl: img.dataUrl,
                    name: img.name,
                    existing: img.existing || false,
                    id: img.id
                }));

            document.getElementById('imageDataInput').value = JSON.stringify(imageDataArray);
        }
    </script>
</body>
</html>
