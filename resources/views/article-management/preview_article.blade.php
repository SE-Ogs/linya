<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Preview Article</title>
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.css">

    </head>

    <body class="font-inter m-0 h-full bg-[#ececff] p-0">
        <div class="flex h-screen flex-col p-0">
            <!-- Image Upload Box -->
            <div class="cursor-pointer border-b border-dashed border-gray-300 bg-gray-50 p-6 text-center transition-all duration-200 hover:bg-gray-100"
                 id="imageUploadBox">
                <div class="mb-2 text-3xl text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="32"
                         height="32"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         class="mx-auto">
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
                <div class="text-sm text-gray-500">Click to upload featured image</div>
                <input type="file"
                       id="imageUploadInput"
                       accept="image/*"
                       class="hidden">
            </div>

            <!-- Editor Container -->
            <div class="flex flex-1 flex-col p-6 pb-0">
                <div id="editor"
                     class="min-h-[400px] flex-1 overflow-y-auto border border-gray-300 bg-white">
                    {!! $article ?? 'Start writing your blog post here...' !!}
                </div>
            </div>

            <!-- Status Bar -->
            <div class="flex items-center justify-between border-t border-gray-300 bg-white px-6 py-3">
                <div class="text-sm text-gray-500">Status: Draft</div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.articles.create') }}"
                       class="rounded-full border border-[#482942] bg-white px-6 py-1 font-semibold text-[#482942] transition duration-200 hover:bg-[#f5f0f7]">
                        Back to Editor
                    </a>
                    <button id="publishButton"
                            class="rounded-full bg-[#482942] px-6 py-1 font-semibold text-white transition duration-200 hover:bg-[#3a2136]">
                        Publish
                    </button>
                </div>
            </div>
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
                textColor: true,
                backgroundColor: true,
                link: true,
                table: true,
                textAlignment: true,
                // imageUpload: {
                //     imageUploadUrl: '/upload-file/',
                //     imageMaxSize: 20 * 1024 * 1024 // 20MB
                // },
                // fileUpload: {
                //     fileUploadUrl: '/upload-file/',
                //     fileMaxSize: 20 * 1024 * 1024 // 20MB
                // },
            });

            // Handle publish button click
            document.getElementById('publishButton').addEventListener('click', function() {
                const content = editor.getRayEditorContent();
                console.log('Content to publish:', content);
                // Future implementation: handle publishing
            });

            // Handle image upload
            document.getElementById('imageUploadBox').addEventListener('click', function() {
                document.getElementById('imageUploadInput').click();
            });

            document.getElementById('imageUploadInput').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You can handle the image preview here
                        console.log('Image uploaded:', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
    </body>

</html>
