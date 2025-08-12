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

<body class="flex flex-col min-h-screen">
    @include('partials.admin-sidebar')
    @include('partials.admin-header')

    <!--- Add Article -->
    <div id="mainContent" class="transition-all duration-300 ml-64 flex-1 min-h-0 flex flex-col bg-gray-50 p-8 overflow-auto">

        <form id="addArticleForm" method="POST" action="{{ Auth::user()?->role === 'writer' ? route('writer.articles.store') : route('admin.articles.store') }}" class="flex flex-col flex-1 space-y-6 w-full" enctype="multipart/form-data">
            @csrf

            <!-- Title Field -->
            <div class="w-full">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" id="title" name="title" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900 placeholder-gray-500
                    shadow-md hover:shadow-lg transition-shadow duration-200"
                    placeholder="Enter title of article...">
            </div>

            <!-- Summary Field -->
            <div class="w-full">
                <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">Summary</label>
                <input type="text" id="summary" name="summary" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900 placeholder-gray-500
                    shadow-md hover:shadow-lg transition-shadow duration-200"
                    placeholder="Enter summary of article...">
            </div>

            <!-- Tags Section -->
            <p class="mt-1 text-sm text-gray-500">Add tags to let the users know who the post is/are directed to!</p>
            <div class="w-full">
                <div class="flex flex-wrap gap-3">
                    @foreach ($tags as $index => $tag)
                        @php
                            $dotColors = [
                                'bg-blue-500',
                                'bg-black',
                                'bg-gray-600',
                                'bg-yellow-500',
                                'bg-red-500',
                            ];
                            $dotColor = $dotColors[$index % count($dotColors)];
                        @endphp

                        <label class="tag-checkbox cursor-pointer use-peer inline-flex">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                                class="sr-only peer">
                                <div class="tag-display inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium font-lexend transition-all duration-200 border-2
                                bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200 shadow-md shadow-black/30 hover:shadow-lg hover:shadow-black/40
                                peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500">
                                <span class="w-2 h-2 {{ $dotColor }} rounded-full"></span>
                                <span>{{ $tag->name }}</span>
                                <svg class="w-4 h-4 plus-icon peer-checked:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <svg class="w-4 h-4 close-icon hidden peer-checked:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Image Upload Section -->
            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Article Images</label>

                <!-- Hidden file input -->
                <input type="file" id="imageInput" name="images[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" multiple style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;">

                <!-- Hidden inputs for image metadata -->
                <div id="imageMetadataInputs"></div>

                <!-- Upload area -->
                <div class="upload-area border-2 border-dashed border-gray-300 bg-gray-50 rounded-lg p-8 text-center cursor-pointer hover:border-gray-400 hover:bg-gray-100 transition-all duration-200" onclick="triggerFileInput()">
                    <div class="upload-icon mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-400">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    <div class="text-lg font-medium text-gray-600 mb-2">Upload Images</div>
                    <div class="text-sm text-gray-500">Click here to select images for your article</div>
                    <div class="text-xs text-gray-400 mt-2">Supports: JPG, PNG, GIF, WebP</div>
                </div>

                <!-- Image Previews -->
                <div id="imagePreviewContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4"></div>
            </div>

            <!-- Description Field -->
            <div class="w-full flex-1 flex flex-col">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <div id="editor" class="border border-gray-300 rounded-lg min-h-[200px] flex-1 bg-white"></div>
                <textarea name="article" id="article" class="hidden"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end w-full space-x-3">
                <button type="button"
                    id="previewButton"
                    name="action"
                    value="preview"
                    class="border border-[#482942] text-[#482942] font-semibold py-1 px-8 rounded-[50px] bg-white hover:bg-[#f5f0f7] transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#482942] focus:ring-offset-2">
                    Preview
                </button>
                <button type="submit" id="publishButton"
                    name="action"
                    value="publish"
                    class="bg-[#482942] hover:bg-[#3a2136] text-white font-semibold py-1 px-8 rounded-[50px] transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#482942] focus:ring-offset-2">
                    Publish
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        // Global variables
        const appState = {
            sidebarOpen: true
        };

        // Initialize Quill editor
        let quill;
        let uploadedImages = [];

        // Initialize everything when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing...');
            initializeQuillEditor();
            initializeImageUpload();
            initializeFormHandling();
            initializeSidebarToggle();
        });

        function initializeQuillEditor() {
            quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'header': 1 }, { 'header': 2 }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }],
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'font': [] }],
                        [{ 'align': [] }],
                        ['clean'],
                        ['link', 'image']
                    ]
                },
                placeholder: 'Write your article content here...'
            });

            console.log('Quill editor initialized');
        }

        function initializeImageUpload() {
            console.log('Initializing image upload...');

            const imageInput = document.getElementById('imageInput');
            if (!imageInput) {
                console.error('Image input not found');
                return;
            }

            // Add event listener to the file input
            imageInput.addEventListener('change', handleFileSelection);
            console.log('Image upload initialized successfully');
        }

        // Function to trigger file input (called by onclick in HTML)
        function triggerFileInput() {
            console.log('Trigger file input called');
            const imageInput = document.getElementById('imageInput');
            if (imageInput) {
                console.log('Clicking file input...');
                imageInput.click();
            } else {
                console.error('Image input element not found');
            }
        }

        function handleFileSelection(event) {
            console.log('File selection event triggered');
            const files = event.target.files;
            console.log('Number of files selected:', files.length);

            if (files.length === 0) {
                console.log('No files selected');
                return;
            }

            // Process each selected file
            Array.from(files).forEach((file, index) => {
                console.log(`Processing file ${index + 1}:`, file.name, file.type, file.size);

                if (isValidImageFile(file)) {
                    processImageFile(file);
                } else {
                    console.warn('Invalid file type:', file.name, file.type);
                    alert(`Invalid file type: ${file.name}. Please select JPG, PNG, GIF, or WebP images.`);
                }
            });

            // Reset the file input so the same file can be selected again if needed
            event.target.value = '';
        }

        function isValidImageFile(file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            const maxSize = 10 * 1024 * 1024; // 10MB

            if (!validTypes.includes(file.type)) {
                return false;
            }

            if (file.size > maxSize) {
                alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
                return false;
            }

            // Check for duplicates
            const isDuplicate = uploadedImages.some(img =>
                img.name === file.name &&
                img.size === file.size &&
                img.type === file.type
            );

            if (isDuplicate) {
                alert(`File "${file.name}" is already uploaded.`);
                return false;
            }

            return true;
        }

        function processImageFile(file) {
            // Check file size (limit to 10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            if (file.size > maxSize) {
                alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                console.log('File read successfully:', file.name);
                console.log('Data URL length:', e.target.result.length);

                // Verify the data URL is valid
                if (!e.target.result || !e.target.result.startsWith('data:image/')) {
                    console.error('Invalid data URL generated for:', file.name);
                    alert(`Error processing image: ${file.name}`);
                    return;
                }

                // Determine if this should be the featured image (first image uploaded)
                const isFeatured = uploadedImages.length === 0;
                const order = uploadedImages.length;

                const imageData = {
                    file: file,
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    dataUrl: e.target.result,
                    id: Date.now() + Math.random(), // Unique ID
                    order: order,
                    isFeatured: isFeatured
                };

                uploadedImages.push(imageData);
                displayImagePreview(imageData);
                console.log(`Image added to uploadedImages array. Total images: ${uploadedImages.length}, Featured: ${isFeatured}, Order: ${order}`);
            };

            reader.onerror = function(error) {
                console.error('Error reading file:', file.name, error);
                alert(`Error reading file: ${file.name}. Please try again.`);
            };

            reader.onabort = function() {
                console.error('File reading was aborted for:', file.name);
            };

            try {
                reader.readAsDataURL(file);
            } catch (error) {
                console.error('Exception when reading file:', file.name, error);
                alert(`Error processing file: ${file.name}`);
            }
        }

        function displayImagePreview(imageData) {
            const container = document.getElementById('imagePreviewContainer');
            if (!container) {
                console.error('Image preview container not found');
                return;
            }

            const imageWrapper = document.createElement('div');
            imageWrapper.className = 'relative group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow';
            imageWrapper.setAttribute('data-image-id', imageData.id);

            // Create image element first to handle loading states
            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover';
            img.alt = imageData.name;

            // Add loading and error handling
            img.onload = function() {
                console.log('Image loaded successfully:', imageData.name);
                // Remove loading state if any
                const loadingDiv = imageWrapper.querySelector('.loading-state');
                if (loadingDiv) {
                    loadingDiv.remove();
                }
            };

            img.onerror = function() {
                console.error('Error loading image:', imageData.name);
                // Replace with error state
                const errorDiv = document.createElement('div');
                errorDiv.className = 'w-full h-full flex items-center justify-center bg-gray-100 text-gray-500 text-xs';
                errorDiv.textContent = 'Error loading image';
                img.replaceWith(errorDiv);
            };

            // Set the source after setting up event handlers
            img.src = imageData.dataUrl;

            // Add featured badge if this is the featured image
            const featuredBadge = imageData.isFeatured ?
                `<div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                    Featured
                </div>` : '';

            // Add order badge
            const orderBadge = `<div class="absolute top-2 right-2 bg-gray-800 text-white text-xs px-2 py-1 rounded-full font-medium">
                ${imageData.order + 1}
            </div>`;

            imageWrapper.innerHTML = `
                <div class="aspect-square">
                    <div class="loading-state w-full h-full flex items-center justify-center bg-gray-100 text-gray-500 text-xs">
                        Loading...
                    </div>
                </div>
                ${featuredBadge}
                ${orderBadge}
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                    <button type="button"
                            onclick="removeImage('${imageData.id}')"
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

            // Replace the loading div with the actual image
            const aspectSquareDiv = imageWrapper.querySelector('.aspect-square');
            const loadingDiv = aspectSquareDiv.querySelector('.loading-state');
            if (loadingDiv && aspectSquareDiv) {
                aspectSquareDiv.replaceChild(img, loadingDiv);
            }

            container.appendChild(imageWrapper);
            console.log('Image preview displayed for:', imageData.name);
        }

        function removeImage(imageId) {
            console.log('Removing image with ID:', imageId);

            // Find the image to be removed
            const imageToRemove = uploadedImages.find(img => img.id == imageId);
            if (!imageToRemove) {
                console.error('Image not found in array');
                return;
            }

            const wasFeatureImage = imageToRemove.isFeatured;
            const removedOrder = imageToRemove.order;

            // Remove from uploadedImages array
            uploadedImages = uploadedImages.filter(img => img.id != imageId);

            // Remove from DOM
            const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
            if (imageElement) {
                imageElement.remove();
                console.log('Image removed from DOM');
            }

            // Reorder remaining images and reassign featured status
            uploadedImages.forEach((img, index) => {
                // Update order for images that came after the removed image
                if (img.order > removedOrder) {
                    img.order = img.order - 1;
                }

                // If the featured image was removed, make the first image (order 0) featured
                if (wasFeatureImage && index === 0) {
                    img.isFeatured = true;
                } else if (wasFeatureImage) {
                    img.isFeatured = false;
                }
            });

            // Refresh the display to show updated order and featured status
            if (uploadedImages.length > 0) {
                refreshImagePreviews();
            }

            console.log('Remaining images:', uploadedImages.length);
        }

        function refreshImagePreviews() {
            const container = document.getElementById('imagePreviewContainer');
            if (!container) return;

            // Clear the container
            container.innerHTML = '';

            // Re-display all images with updated information
            uploadedImages.forEach(imageData => {
                displayImagePreview(imageData);
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function initializeFormHandling() {
            const form = document.getElementById('addArticleForm');
            const previewButton = document.getElementById('previewButton');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submit triggered');

                if (validateForm()) {
                    prepareFormForSubmission();
                    console.log('Form validation passed, submitting...');
                    this.submit();
                } else {
                    console.log('Form validation failed');
                }
            });

            previewButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Preview button clicked');

                if (validateForm()) {
                    const originalAction = form.action;
                    form.action = "{{ auth()->user()?->role === 'writer' ? route('writer.articles.preview') : route('admin.articles.preview') }}";

                    prepareFormForSubmission();
                    console.log('Submitting for preview...');
                    form.submit();
                } else {
                    console.log('Form validation failed for preview');
                }
            });
        }

        function validateForm() {
            const errors = [];

            // Validate title
            const title = document.getElementById('title').value.trim();
            if (!title) {
                errors.push('• Title is required');
            }

            // Validate summary
            const summary = document.getElementById('summary').value.trim();
            if (!summary) {
                errors.push('• Summary is required');
            }

            // Validate tags - check if ANY tag is selected
            const checkedTags = document.querySelectorAll('input[name="tags[]"]:checked');
            if (checkedTags.length === 0) {
                errors.push('• At least one tag must be selected');
            }

            // Validate article content using Quill
            let articleContent = '';
            if (quill) {
                const text = quill.getText().trim();
                if (!text || text === '\n') {
                    errors.push('• Article content is required');
                } else {
                    articleContent = text;
                }
            } else {
                errors.push('• Editor not initialized properly');
            }

            // Show errors if any
            if (errors.length > 0) {
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
                return false;
            }

            return true;
        }

        function prepareFormForSubmission() {
            // Sync Quill editor content to hidden textarea
            if (quill) {
                const content = quill.root.innerHTML;
                document.getElementById('article').value = content;
                console.log('Quill editor content synced:', content.length, 'characters');
            } else {
                console.error('Quill editor not available for content sync');
            }

            // Prepare image files for submission - create a new FormData approach
            if (uploadedImages.length > 0) {
                console.log('Preparing images for submission:', uploadedImages.length);

                // Clear the original file input
                const imageInput = document.getElementById('imageInput');

                // Create a new DataTransfer object to hold our files
                const dt = new DataTransfer();

                // Add each file to the DataTransfer object IN ORDER
                uploadedImages
                    .sort((a, b) => a.order - b.order) // Ensure correct order
                    .forEach((imageData, index) => {
                        console.log(`Adding file ${index + 1} to form:`, imageData.name, 'Order:', imageData.order, 'Featured:', imageData.isFeatured);
                        dt.items.add(imageData.file);
                    });

                // Assign the files to the input
                imageInput.files = dt.files;

                // Create hidden inputs for image metadata
                createImageMetadataInputs();

                console.log('Final file input files count:', imageInput.files.length);

                // Log each file for debugging
                Array.from(imageInput.files).forEach((file, index) => {
                    console.log(`Form file ${index + 1}:`, file.name, file.size, file.type);
                });
            } else {
                console.log('No images to prepare for submission');
            }
        }

        function createImageMetadataInputs() {
            const container = document.getElementById('imageMetadataInputs');
            if (!container) return;

            // Clear existing metadata inputs
            container.innerHTML = '';

            // Create hidden inputs for each image's metadata
            uploadedImages
                .sort((a, b) => a.order - b.order)
                .forEach((imageData, index) => {
                    // Create order input
                    const orderInput = document.createElement('input');
                    orderInput.type = 'hidden';
                    orderInput.name = `image_orders[${index}]`;
                    orderInput.value = imageData.order;
                    container.appendChild(orderInput);

                    // Create featured status input
                    const featuredInput = document.createElement('input');
                    featuredInput.type = 'hidden';
                    featuredInput.name = `image_featured[${index}]`;
                    featuredInput.value = imageData.isFeatured ? '1' : '0';
                    container.appendChild(featuredInput);

                    console.log(`Created metadata inputs for image ${index}:`, {
                        order: imageData.order,
                        featured: imageData.isFeatured
                    });
                });
        }

        function initializeSidebarToggle() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    appState.sidebarOpen = !appState.sidebarOpen;
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.getElementById('mainContent');

                    if (sidebar) {
                        sidebar.classList.toggle('-translate-x-full', !appState.sidebarOpen);
                    }
                    if (mainContent) {
                        mainContent.classList.toggle('ml-64', appState.sidebarOpen);
                    }
                });
            }
        }
    </script>
</body>

</html>
