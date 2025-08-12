<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
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
        <form method="POST" action="{{ route('admin.articles.update', $article->id) }}" class="flex flex-col flex-1 space-y-6 w-full">
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
                                {{-- Check if the tag is already associated with the article --}}
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


    <!-- QuillJS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

    <!-- QuillJS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        // Initialize Quill
        const quill = new Quill('#editor', {
            theme: 'snow'
        });

        // Load existing article HTML into Quill
        quill.root.innerHTML = `{!! old('article', $article->article) !!}`;

        // On form submit, copy Quill HTML into hidden input
        document.querySelector('form').addEventListener('submit', function () {
            document.getElementById('article').value = quill.root.innerHTML;
        });
    </script>
</body>

</html>
