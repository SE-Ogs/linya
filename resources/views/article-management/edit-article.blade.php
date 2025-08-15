<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- QuillJS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
</head>

<body class="flex flex-col min-h-screen">
    @include('partials.admin-sidebar')
    @include('partials.admin-header')

    <div id="mainContent" class="transition-all duration-300 ml-64 flex-1 min-h-0 flex flex-col bg-gray-50 p-8 overflow-auto">
        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 w-full">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 w-full">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Article Form -->
        <form id="editArticleForm" method="POST" action="{{ route('admin.articles.update', $article->id) }}" class="flex flex-col flex-1 space-y-6 w-full" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title Field -->
            <div class="w-full">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900 placeholder-gray-500
                    shadow-md hover:shadow-lg transition-shadow duration-200"
                    placeholder="Enter title of article...">
            </div>

            <!-- Summary Field -->
            <div class="w-full">
                <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">Summary</label>
                <input type="text" id="summary" name="summary" value="{{ old('summary', $article->summary) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900 placeholder-gray-500
                    shadow-md hover:shadow-lg transition-shadow duration-200"
                    placeholder="Enter summary of article...">
            </div>

            <!-- Current Images (display what's already saved) -->
            <div class="w-full">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>

                @php
                    $currentImages = $article->images()->orderBy('order')->get();
                @endphp

                @if ($currentImages->count())
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($currentImages as $image)
                            <div class="relative bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <div class="aspect-square">
                                    <img src="{{ $image->url }}" alt="Article image" class="w-full h-full object-cover">
                                </div>

                                @if ($image->is_featured)
                                    <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                        Featured
                                    </div>
                                @endif

                                <div class="absolute top-2 right-2 bg-gray-800 text-white text-xs px-2 py-1 rounded-full font-medium">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Add More Images</label>

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

                        <label class="tag-checkbox cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                                class="hidden peer"
                                {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <div class="tag-display inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium font-lexend transition-all duration-200 border-2
                            bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200 shadow-md shadow-black/30 hover:shadow-lg hover:shadow-black/40
                            peer-checked:bg-[#482942] peer-checked:text-white peer-checked:border-[#482942] peer-checked:shadow-lg peer-checked:shadow-[#482942]/40">
                                <span class="w-2 h-2 {{ $dotColor }} rounded-full peer-checked:bg-white"></span>
                                <span>{{ $tag->name }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Description Field -->
            <div class="w-full flex-1 flex flex-col">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <div id="editor" class="border border-gray-300 rounded-lg min-h-[200px] flex-1 bg-white"></div>
                <input type="hidden" name="article" id="article" value="{{ old('article', $article->article) }}">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end w-full space-x-3">
                <button type="submit"
                    class="bg-[#482942] hover:bg-[#3a2136] text-white font-semibold py-1 px-8 rounded-[50px] transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#482942] focus:ring-offset-2">
                    Update
                </button>
            </div>
        </form>
    </div>

    <!-- QuillJS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        // Initialize Quill (same as Add, but simpler toolbar here; customize if you want)
        const quill = new Quill('#editor', { theme: 'snow' });
        quill.root.innerHTML = `{!! old('article', $article->article) !!}`;

        // On form submit, copy Quill HTML into hidden input
        document.getElementById('editArticleForm').addEventListener('submit', function () {
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
          const maxSize = 10 * 1024 * 1024; // 10MB
          if (!validTypes.includes(file.type)) return false;
          if (file.size > maxSize) return false;
          const isDuplicate = uploadedImages.some(img => img.name === file.name && img.size === file.size && img.type === file.type);
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
          imageWrapper.className = 'relative group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow';
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