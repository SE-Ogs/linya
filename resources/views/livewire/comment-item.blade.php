<div class="border-b p-3">
    <div class="font-semibold">{{ $comment->user->name }}</div>
    <div>{{ $comment->content }}</div>
    <div class="text-sm text-gray-500">
        {{ $comment->created_at->diffForHumans() }}
    </div>

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
</div>
