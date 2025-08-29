<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <title>Edit Article</title>

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')

        <!-- QuillJS CSS -->
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css"
              rel="stylesheet">
    </head>

    <body class="flex min-h-screen flex-col">
        @include('partials.admin-sidebar')
        @include('partials.admin-header')

        <div id="mainContent"
             class="ml-64 flex min-h-0 flex-1 flex-col overflow-auto bg-gray-50 p-8 transition-all duration-300">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 w-full rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 w-full rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Article Form -->
            <form id="editArticleForm"
                  method="POST"
                  action="{{ route('admin.articles.update', $article->id) }}"
                  class="flex w-full flex-1 flex-col space-y-6"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Title Field -->
                <div class="w-full">
                    <label for="title"
                           class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', default: $article->title) }}"
                           required
                           class="w-full rounded-[20px] border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Enter title of article...">
                </div>

                <!-- Author Field -->
                <div class="w-full">
                    <label for="author"
                           class="mb-2 block text-sm font-medium text-gray-700">Author</label>
                    <input type="text"
                           id="author"
                           name="author"
                           value="{{ old('author', $article->author) }}"
                           required
                           class="w-full rounded-[20px] border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Enter author of article...">
                </div>

                <!-- Summary Field -->
                <div class="w-full">
                    <label for="summary"
                           class="mb-2 block text-sm font-medium text-gray-700">Summary</label>
                    <input type="text"
                           id="summary"
                           name="summary"
                           value="{{ old('summary', $article->summary) }}"
                           required
                           class="w-full rounded-[20px] border border-gray-300 bg-white px-4 py-2 text-gray-900 placeholder-gray-500 shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="Enter summary of article...">
                </div>

                <!-- Current Images (display what's already saved) -->
                <div class="w-full">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Current Images</label>

                    @php
                        $currentImages = $article->images()->orderBy('order')->get();
                    @endphp

                    @if ($currentImages->count())
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                            @foreach ($currentImages as $image)
                                <div
                                     class="relative overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                                    <div class="aspect-square">
                                        <img src="{{ $image->url }}"
                                             alt="Article image"
                                             class="h-full w-full object-cover">
                                    </div>

                                    @if ($image->is_featured)
                                        <div
                                             class="absolute left-2 top-2 rounded-full bg-blue-500 px-2 py-1 text-xs font-medium text-white">
                                            Featured
                                        </div>
                                    @endif

                                    <div
                                         class="absolute right-2 top-2 rounded-full bg-gray-800 px-2 py-1 text-xs font-medium text-white">
                                        {{ ($image->order ?? 0) + 1 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-sm text-gray-500">No images uploaded for this article yet.</div>
                    @endif
                </div>

                <!-- Image Upload Section (same as Add Article) -->
                <div class="w-full">
                    <label class="mb-2 block text-sm font-medium text-gray-700">Add More Images</label>

                    <!-- Hidden file input -->
                    <input type="file"
                           id="imageInput"
                           name="images[]"
                           accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                           multiple
                           style="position: absolute; left: -9999px; opacity: 0; pointer-events: none;">

                    <!-- Hidden inputs for image metadata -->
                    <div id="imageMetadataInputs"></div>

                    <!-- Upload area -->
                    <div class="upload-area cursor-pointer rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center transition-all duration-200 hover:border-gray-400 hover:bg-gray-100"
                         onclick="triggerFileInput()">
                        <div class="upload-icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="48"
                                 height="48"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.5"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 class="mx-auto text-gray-400">
                                <rect x="3"
                                      y="3"
                                      width="18"
                                      height="18"
                                      rx="2"
                                      ry="2"></rect>
                                <circle cx="8.5"
                                        cy="8.5"
                                        r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>
                        <div class="mb-2 text-lg font-medium text-gray-600">Upload Images</div>
                        <div class="text-sm text-gray-500">Click here to select images for your article</div>
                        <div class="mt-2 text-xs text-gray-400">Supports: JPG, PNG, GIF, WebP</div>
                    </div>

                    <!-- Image Previews -->
                    <div id="imagePreviewContainer"
                         class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6"></div>
                </div>

                <!-- Tags Section -->
                <p class="mt-1 text-sm text-gray-500">Add tags to let the users know who the post is/are directed to!
                </p>
                <div class="w-full">
                    <div class="flex flex-wrap gap-3">
                        @foreach ($tags as $index => $tag)
                            @php
                                $dotColors = ['bg-blue-500', 'bg-black', 'bg-gray-600', 'bg-yellow-500', 'bg-red-500'];
                                $dotColor = $dotColors[$index % count($dotColors)];
                            @endphp

                            <label class="tag-checkbox cursor-pointer">
                                <input type="checkbox"
                                       name="tags[]"
                                       value="{{ $tag->id }}"
                                       id="tag-{{ $tag->id }}"
                                       class="peer hidden"
                                       {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <div
                                     class="tag-display font-lexend inline-flex items-center gap-2 rounded-full border-2 border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 shadow-md shadow-black/30 transition-all duration-200 hover:bg-gray-200 hover:shadow-lg hover:shadow-black/40 peer-checked:border-[#482942] peer-checked:bg-[#482942] peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-[#482942]/40">
                                    <span
                                          class="{{ $dotColor }} h-2 w-2 rounded-full peer-checked:bg-white"></span>
                                    <span>{{ $tag->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Description Field -->
                <div class="flex w-full flex-1 flex-col">
                    <label for="description"
                           class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                    <div id="editor"
                         class="min-h-[200px] flex-1 rounded-lg border border-gray-300 bg-white"></div>
                    <input type="hidden"
                           name="article"
                           id="article"
                           value="{{ old('article', $article->article) }}">
                </div>

                <!-- Submit Button -->
                <div class="flex w-full justify-end space-x-3">
                    <button type="submit"
                            class="transform rounded-[50px] bg-[#482942] px-8 py-1 font-semibold text-white transition duration-200 ease-in-out hover:scale-105 hover:bg-[#3a2136] focus:outline-none focus:ring-2 focus:ring-[#482942] focus:ring-offset-2">
                        Update
                    </button>
                </div>
            </form>
        </div>

        <!-- QuillJS -->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <script>
            // Initialize Quill (same as Add, but simpler toolbar here; customize if you want)
            const quill = new Quill('#editor', {
                theme: 'snow'
            });
            quill.root.innerHTML = `{!! old('article', $article->article) !!}`;

            // On form submit, copy Quill HTML into hidden input
            document.getElementById('editArticleForm').addEventListener('submit', function() {
                document.getElementById('article').value = quill.root.innerHTML;
            });
        </script>

        <script>
            // Same image upload logic as Add Article, adapted for Edit (adds new images; does not remove existing)
            let uploadedImages = [];

            document.addEventListener('DOMContentLoaded', function() {
                initializeImageUploadEdit();
                initializeFormHandlingEdit();
            });

            function initializeImageUploadEdit() {
                const imageInput = document.getElementById('imageInput');
                if (!imageInput) return;
                imageInput.addEventListener('change', handleFileSelection);
            }

            function triggerFileInput() {
                const imageInput = document.getElementById('imageInput');
                if (imageInput) imageInput.click();
            }

            function handleFileSelection(event) {
                const files = event.target.files;
                if (!files || files.length === 0) return;
                Array.from(files).forEach((file) => {
                    if (isValidImageFile(file)) {
                        processImageFile(file);
                    } else {
                        alert(`Invalid file type/size: ${file.name}`);
                    }
                });
                event.target.value = '';
            }

            function isValidImageFile(file) {
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                const maxSize = 50 * 1024 * 1024; // 50MB
                if (!validTypes.includes(file.type)) return false;
                if (file.size > maxSize) return false;
                const isDuplicate = uploadedImages.some(img => img.name === file.name && img.size === file.size && img.type ===
                    file.type);
                if (isDuplicate) return false;
                return true;
            }

            function processImageFile(file) {
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!e.target.result || !e.target.result.startsWith('data:image/')) return;

                    const isFeatured = uploadedImages.length === 0;
                    const order = uploadedImages.length;

                    const imageData = {
                        file,
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        dataUrl: e.target.result,
                        id: Date.now() + Math.random(),
                        order,
                        isFeatured
                    };

                    uploadedImages.push(imageData);
                    displayImagePreview(imageData);
                };
                reader.readAsDataURL(file);
            }

            function displayImagePreview(imageData) {
                const container = document.getElementById('imagePreviewContainer');
                if (!container) return;

                const imageWrapper = document.createElement('div');
                imageWrapper.className =
                    'relative group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow';
                imageWrapper.setAttribute('data-image-id', imageData.id);

                const img = document.createElement('img');
                img.className = 'w-full h-full object-cover';
                img.alt = imageData.name;
                img.src = imageData.dataUrl;

                const featuredBadge = imageData.isFeatured ? `
              <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-medium">Featured</div>
          ` : '';

                const orderBadge = `
              <div class="absolute top-2 right-2 bg-gray-800 text-white text-xs px-2 py-1 rounded-full font-medium">${imageData.order + 1}</div>
          `;

                imageWrapper.innerHTML = `
              <div class="aspect-square">
                  <div class="loading-state w-full h-full flex items-center justify-center bg-gray-100 text-gray-500 text-xs">Loading...</div>
              </div>
              ${featuredBadge}
              ${orderBadge}
              <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-200 flex items-center justify-center pointer-events-none">
                  <button type="button" onclick="removeImage('${imageData.id}')" class="pointer-events-auto bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
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

                const aspectSquareDiv = imageWrapper.querySelector('.aspect-square');
                const loadingDiv = aspectSquareDiv.querySelector('.loading-state');
                if (loadingDiv && aspectSquareDiv) {
                    aspectSquareDiv.replaceChild(img, loadingDiv);
                }

                container.appendChild(imageWrapper);
            }

            function removeImage(imageId) {
                const imageToRemove = uploadedImages.find(img => img.id == imageId);
                if (!imageToRemove) return;
                const wasFeatureImage = imageToRemove.isFeatured;
                const removedOrder = imageToRemove.order;

                uploadedImages = uploadedImages.filter(img => img.id != imageId);
                const el = document.querySelector(`[data-image-id="${imageId}"]`);
                if (el) el.remove();

                uploadedImages.forEach((img, index) => {
                    if (img.order > removedOrder) img.order = img.order - 1;
                    if (wasFeatureImage && index === 0) img.isFeatured = true;
                    else if (wasFeatureImage) img.isFeatured = false;
                });

                refreshImagePreviews();
            }

            function refreshImagePreviews() {
                const container = document.getElementById('imagePreviewContainer');
                if (!container) return;
                container.innerHTML = '';
                uploadedImages.forEach(displayImagePreview);
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function createImageMetadataInputs() {
                const container = document.getElementById('imageMetadataInputs');
                if (!container) return;
                container.innerHTML = '';
                uploadedImages
                    .sort((a, b) => a.order - b.order)
                    .forEach((imageData, index) => {
                        const orderInput = document.createElement('input');
                        orderInput.type = 'hidden';
                        orderInput.name = `image_orders[${index}]`;
                        orderInput.value = imageData.order;
                        container.appendChild(orderInput);

                        const featuredInput = document.createElement('input');
                        featuredInput.type = 'hidden';
                        featuredInput.name = `image_featured[${index}]`;
                        featuredInput.value = imageData.isFeatured ? '1' : '0';
                        container.appendChild(featuredInput);
                    });
            }

            function initializeFormHandlingEdit() {
                const form = document.getElementById('editArticleForm');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    // Add any new images user selected during edit
                    if (uploadedImages.length > 0) {
                        const imageInput = document.getElementById('imageInput');
                        const dt = new DataTransfer();
                        uploadedImages
                            .sort((a, b) => a.order - b.order)
                            .forEach(img => dt.items.add(img.file));
                        imageInput.files = dt.files;

                        createImageMetadataInputs();
                    }
                });
            }
        </script>
    </body>

</html>
