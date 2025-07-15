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

<body>
    <!--- Admin Sidebar -->
    <button id="toggleAdminSidebar" class="fixed top-4 left-4 z-50 p-2 bg-gray-800 text-white rounded">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <aside id="admin_sidebar" class="fixed left-0 top-0 z-50 transition-transform duration-300 transform">
        @include('partials.admin_sidebar')
    </aside>

    <div id="admin_header" class="transition-all duration-300 ml-64">
        @include('partials.admin_header')
    </div>

    <!--- Add Article -->
    <div id="main-content" class="transition-all duration-300 ml-64 min-h-screen flex flex-col bg-gray-50 p-8">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 w-full">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/articles" class="flex flex-col flex-1 space-y-6 w-full">
            @csrf

            <!-- Title Field -->
            <div class="w-full">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-[20px] focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900 placeholder-gray-500
                    shadow-md hover:shadow-lg transition-shadow duration-200"
                    placeholder="Enter title of article...">
            </div>

            <!-- Summary Field -->
            <div class="w-full">
                <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">Summary</label>
                <input type="text" id="summary" name="summary" value="{{ old('summary') }}" required
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
                        
                        <label class="tag-checkbox cursor-pointer">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                                class="hidden"
                                {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'checked' : '' }}>
                                <div class="tag-display inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium font-lexend transition-all duration-200 border-2
                                bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200 shadow-md shadow-black/30 hover:shadow-lg hover:shadow-black/40">
                                <span class="w-2 h-2 {{ $dotColor }} rounded-full"></span>
                                <span>{{ $tag->name }}</span>
                                <svg class="w-4 h-4 plus-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <svg class="w-4 h-4 close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            

            <!-- Description Field -->
            <div class="w-full flex-1 flex flex-col">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <div id="editor" class="border border-gray-300 rounded-lg min-h-[200px] flex-1 bg-white"></div>
                <input type="hidden" name="article" id="article">
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
            // imageUpload: {
            //     imageUploadUrl: '/upload-file/',
            //     imageMaxSize: 20 * 1024 * 1024 // 20MB
            // },
            // fileUpload: {
            //     fileUploadUrl: '/upload-file/',
            //     fileMaxSize: 20 * 1024 * 1024 // 20MB
            // },
            textColor: true,
            backgroundColor: true,
            link: true,
            table: true,
            textAlignment: true
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault();
            const content = editor.getRayEditorContent();
            document.getElementById('article').value = content;
            this.submit();
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            const content = editor.getRayEditorContent();
            document.getElementById('article').value = content;
        });

        document.getElementById('previewButton').addEventListener('click', function () {
            const form = this.closest('form');
            form.action = "{{ route('articles.preview') }}";
            form.submit();
        })
    </script>
</body>

</html>
