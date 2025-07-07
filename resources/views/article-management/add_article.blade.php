<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <!-- RayEditor CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/yeole-rohan/ray-editor@main/ray-editor.css">
</head>
<body>
    <div class="container">
        <h1>Add New Article</h1>
        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="/articles">
            @csrf
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>

            <label for="description">Description</label>
            <div id="editor"></div>
            <input type="hidden" name="article" id="article">

            <div class="tags">
                <label>Tags</label><br>
                @foreach ($tags as $tag)
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}" {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'checked' : '' }}>
                    <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                @endforeach
            </div>

            <button type="submit">Submit Article</button>
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

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); 
            const content = editor.getRayEditorContent();
            document.getElementById('article').value = content;
            this.submit(); 
        });
    </script>
</body>
</html>
