<div class="border p-4 rounded bg-gray-50">
    <div class="text-sm text-gray-700">
        <strong>{{ $comment->user->name }}</strong> · <span>{{ $comment->created_at->diffForHumans() }}</span>
    </div>
    <p class="mt-2 text-gray-800">{{ $comment->content }}</p>

    @auth
        <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')" class="text-sm text-blue-500 mt-2">Reply</button>

       <form method="POST" action="{{ route('comments.store', ['article' => $comment->article_id]) }}" class="mt-2 hidden" id="reply-{{ $comment->id }}">
            @csrf
            <textarea name="content" class="w-full border p-2 mb-2" placeholder="Reply..." required></textarea>
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <button class="bg-gray-300 px-3 py-1 rounded">Reply</button>
        </form>

        @can('delete', $comment)
            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline-block ml-4">
                @csrf
                @method('DELETE')
                <button class="text-sm text-red-500">Delete</button>
            </form>
        @endcan
    @endauth

    {{-- Replies --}}
    @if($comment->replies->count())
        <div class="ml-6 mt-4 space-y-2">
            @foreach($comment->replies as $reply)
                @include('partials.comment', ['comment' => $reply, 'article' => $comment->article])
            @endforeach
        </div>
    @endif
</div>
