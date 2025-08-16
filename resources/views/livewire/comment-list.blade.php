<div class="comments-list space-y-4 rounded bg-white p-4 shadow-md">

    @if (auth()->check())
        @foreach ($comments as $comment)
            <livewire:comment-item :comment="$comment"
                                   :key="$comment->id" />
        @endforeach
    @else
        <div class="rounded bg-gray-50 p-4 text-center shadow">
            <p class="text-gray-700">
                You need to <a href="{{ route('login') }}"
                   class="text-blue-600 underline">log in</a> to view and post comments.
            </p>
        </div>
    @endif
</div>
