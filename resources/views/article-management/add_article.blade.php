<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- RayEditor CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.css">
</head>

<body class="flex flex-col min-h-screen">
    @include('partials.admin_sidebar')
    @include('partials.admin_header')

    <!--- Add Article -->
    <div id="mainContent" class="transition-all duration-300 ml-64 flex-1 min-h-0 flex flex-col bg-gray-50 p-8 overflow-auto">

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 w-full">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($formData)
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6 w-full">
                <h3 class="font-bold">Debug: Form Data from Session</h3>
                <pre>{{ print_r($formData, true) }}</pre>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.articles.store') }}" class="flex flex-col flex-1 space-y-6 w-full">
            @csrf

            <!-- Title Field -->
            <div class="w-full">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" id="title" name="title" value="{{ $formData['title'] ?? old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900 placeholder-gray-500
                    shadow-md hover:shadow-lg transition-shadow duration-200"
                    placeholder="Enter title of article...">
            </div>

            <!-- Summary Field -->
            <div class="w-full">
                <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">Summary</label>
                <input type="text" id="summary" name="summary" value="{{ $formData['summary'] ?? old('summary') }}" required
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
                            $selectedTags = $formData['tags'] ?? old('tags') ?? [];
                            $isChecked = is_array($selectedTags) && in_array($tag->id, $selectedTags);
                        @endphp

                        <label class="tag-checkbox cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                                class="hidden"
                                {{ $isChecked ? 'checked' : '' }}>
                                <div class="tag-display inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium font-lexend transition-all duration-200 border-2
                                {{ $isChecked ? 'bg-orange-500 text-white border-orange-500' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200' }} shadow-md shadow-black/30 hover:shadow-lg hover:shadow-black/40">
                                <span class="w-2 h-2 {{ $dotColor }} rounded-full"></span>
                                <span>{{ $tag->name }}</span>
                                <svg class="w-4 h-4 plus-icon {{ $isChecked ? 'hidden' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <svg class="w-4 h-4 close-icon {{ $isChecked ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="border border-dashed border-gray-300 bg-gray-50 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-100 transition-all duration-200" id="imageUploadBox">
                    <div class="mb-2 text-3xl text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    <div class="text-sm text-gray-500">Click to upload images for this article</div>
                    <input type="file" id="imageUploadInput" accept="image/*" class="hidden" multiple>
                </div>
                <!-- Image Previews -->
                <div id="imagePreviewContainer" class="flex flex-wrap gap-4 mt-4"></div>
            </div>

            <!-- Description Field -->
            <div class="w-full flex-1 flex flex-col">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <div id="editor" class="border border-gray-300 rounded-lg min-h-[200px] flex-1 bg-white"></div>
                <input type="hidden" name="article" id="article" value="{{ $formData['article'] ?? old('article') }}">
                <input type="hidden" name="imageData" value="{{ $formData['imageData'] ?? old('imageData') }}">
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
                <button type="submit"
                    name="action"
                    value="publish"
                    class="bg-[#482942] hover:bg-[#3a2136] text-white font-semibold py-1 px-8 rounded-[50px] transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#482942] focus:ring-offset-2">
                    Publish
                </button>
            </div>
        </form>
    </div>

    <!-- RayEditor JS -->
    <script src='https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.js'></script>
    <script>
        // Unify sidebar/header logic with post_management.blade.php
        const appState = {
            sidebarOpen: true
        };
        const editor = new RayEditor('editor', {
            bold: true,
            italic: true,
            underline: true,
            strikethrough: true,
            redo: true,
            undo: true,
            headings: true,
            lowercase: true,
            uppercase: true,
            superscript: true,
            subscript: true,
            orderedList: true,
            unorderedList: true,
            toggleCase: true,
            codeBlock: true,
            codeInline: true,
            textColor: true,
            backgroundColor: true,
            link: true,
            table: true,
            textAlignment: true
        });

        // Restore form data if coming back from preview
        document.addEventListener('DOMContentLoaded', function() {
            // Restore editor content
            const articleContent = document.querySelector('input[name="article"]');
            if (articleContent && articleContent.value) {
                editor.setRayEditorContent(articleContent.value);
            }

            // Restore images if coming back from preview
            const imageDataInput = document.querySelector('input[name="imageData"]');
            if (imageDataInput && imageDataInput.value) {
                try {
                    const images = JSON.parse(imageDataInput.value);
                    restoreImages(images);
                } catch (e) {
                    console.error('Error parsing image data:', e);
                }
            }
        });

        function restoreImages(images) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = '';
            
            images.forEach((image, index) => {
                const imgWrapper = document.createElement('div');
                imgWrapper.className = 'relative';
                
                const img = document.createElement('img');
                img.src = image.dataUrl;
                img.className = 'h-24 w-24 object-cover rounded border border-gray-200 shadow';
                img.dataset.index = index;
                img.dataset.name = image.name;
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600';
                removeBtn.innerHTML = '×';
                removeBtn.onclick = function() {
                    imgWrapper.remove();
                    updateImageData();
                };
                
                imgWrapper.appendChild(img);
                imgWrapper.appendChild(removeBtn);
                previewContainer.appendChild(imgWrapper);
            });
        }

        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault();
            const content = editor.getRayEditorContent();
            document.getElementById('article').value = content;
            
            // Add image data to form before submission
            const imageData = getImageData();
            console.log('Form submission - imageData:', imageData);
            
            const imageDataInput = document.createElement('input');
            imageDataInput.type = 'hidden';
            imageDataInput.name = 'imageData';
            imageDataInput.value = JSON.stringify(imageData);
            this.appendChild(imageDataInput);
            
            console.log('Form data before submission:', {
                title: this.querySelector('[name="title"]').value,
                summary: this.querySelector('[name="summary"]').value,
                article: this.querySelector('[name="article"]').value,
                imageData: imageDataInput.value
            });
            
            this.submit();
        });

        document.getElementById('previewButton').addEventListener('click', function () {
            const form = this.closest('form');
            form.action = "{{ route('admin.articles.preview') }}";
            
            // Capture editor content before submission
            const content = editor.getRayEditorContent();
            document.getElementById('article').value = content;
            
            // Add image data to form before submission
            const imageData = getImageData();
            const imageDataInput = document.createElement('input');
            imageDataInput.type = 'hidden';
            imageDataInput.name = 'imageData';
            imageDataInput.value = JSON.stringify(imageData);
            form.appendChild(imageDataInput);
            
            form.submit();
        })

        // Handle image upload
        document.getElementById('imageUploadBox').addEventListener('click', function() {
            document.getElementById('imageUploadInput').click();
        });

        document.getElementById('imageUploadInput').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('imagePreviewContainer');
            
            if (files.length > 0) {
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'relative';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-24 w-24 object-cover rounded border border-gray-200 shadow';
                        img.dataset.index = getNextImageIndex();
                        img.dataset.name = file.name;
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600';
                        removeBtn.innerHTML = '×';
                        removeBtn.onclick = function() {
                            imgWrapper.remove();
                            updateImageData();
                        };
                        
                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(removeBtn);
                        previewContainer.appendChild(imgWrapper);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        // Function to get next available image index
        function getNextImageIndex() {
            const existingImages = document.querySelectorAll('#imagePreviewContainer img');
            return existingImages.length;
        }

        // Function to get image data for form submission
        function getImageData() {
            const images = [];
            const imageElements = document.querySelectorAll('#imagePreviewContainer img');
            
            console.log('Found ' + imageElements.length + ' image elements');
            
            imageElements.forEach((imgElement, index) => {
                const imageData = {
                    name: imgElement.dataset.name || `image_${index}.jpg`,
                    size: 0, // We don't have file size for restored images
                    type: 'image/jpeg', // Default type
                    dataUrl: imgElement.src
                };
                images.push(imageData);
                console.log('Image ' + index + ':', imageData.name, 'Data URL length:', imageData.dataUrl.length);
            });
            
            console.log('Total images to submit:', images.length);
            return images;
        }

        // Function to update image data when images are removed
        function updateImageData() {
            const imageElements = document.querySelectorAll('#imagePreviewContainer img');
            imageElements.forEach((img, index) => {
                img.dataset.index = index;
            });
        }

        document.getElementById('sidebarToggle').addEventListener('click', () => {
            appState.sidebarOpen = !appState.sidebarOpen;
            document.getElementById('sidebar').classList.toggle('-translate-x-full', !appState.sidebarOpen);
            document.getElementById('mainContent').classList.toggle('ml-64', appState.sidebarOpen);
        });
    </script>
</body>

</html>
