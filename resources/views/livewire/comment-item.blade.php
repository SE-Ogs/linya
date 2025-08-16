<div class="border-b p-3">
    {{-- Comment Content --}}
    <div class="font-semibold">{{ $comment->user->name }}</div>
    <div>{{ $comment->content }}</div>
    <div class="text-sm text-gray-500">
        {{ $comment->created_at->diffForHumans() }}
    </div>

    {{-- Like / Dislike --}}
    <div class="mt-1 flex items-center space-x-2">
        <button wire:click="like"
                class="{{ $userVote === 'like' ? 'bg-blue-100 text-blue-500' : 'bg-gray-100 text-gray-500' }} flex items-center space-x-1 rounded px-2 py-1">
            👍 <span>{{ $likes }}</span>
        </button>

        <button wire:click="dislike"
                class="{{ $userVote === 'dislike' ? 'bg-red-100 text-red-500' : 'bg-gray-100 text-gray-500' }} flex items-center space-x-1 rounded px-2 py-1">
            👎 <span>{{ $dislikes }}</span>
        </button>
    </div>

    {{-- Reply --}}
    @if (auth()->check() && $depth < $maxDepth)
        <button wire:click="$toggle('showReplyForm')"
                class="mt-2 text-sm text-blue-600">
            Reply
        </button>

        @if ($showReplyForm ?? false)
            <div class="mt-2">
                <textarea wire:model="replyContent"
                          class="w-full rounded border p-2"
                          placeholder="Write a reply..."></textarea>
                <button wire:click="postReply"
                        class="mt-1 rounded bg-blue-600 px-3 py-1 text-white">
                    Post Reply
                </button>
            </div>
        @endif
    @endif

    {{-- Nested Replies --}}
    @if ($depth < $maxDepth && $comment->replies->count())
        <div class="ml-6 mt-3 space-y-3 border-l border-gray-200 pl-4">
            @foreach ($comment->replies as $reply)
                <livewire:comment-item :comment="$reply"
                                       :depth="$depth + 1"
                                       :max-depth="$maxDepth"
                                       :key="$reply->id" />
            @endforeach
        </div>
    @endif
</div>
