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
            <div class="add-comment-area-wrapper">
                <div class="comment-user-info">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                         alt="Your Avatar"
                         class="user-avatar">
                </div>
                <div
                     class="add-comment-input-box flex w-full items-center rounded-[10px] border border-black bg-white px-3 py-2">
                    <form wire:submit.prevent="addComment"
                          class="flex flex-grow flex-wrap gap-2">

                        <textarea wire:model.defer="newComment"
                                  class="comment-box flex-grow resize-none overflow-hidden border-none px-3 py-2 text-sm leading-snug outline-none transition-all duration-300 focus:shadow-none focus:outline-none"
                                  rows="1"
                                  required
                                  placeholder="Write a comment..."
                                  oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px';"></textarea>

                        @error('newcomment')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror

                        <button type="submit"
                                class="ml-2 text-xl text-orange-500 hover:text-orange-600">
                            &#10148;
                        </button>

                        <div id="below-textarea"
                             class="flex w-full items-center justify-between text-right">
                            <span class="spooky-warning ml-2 mt-1 block text-xs text-red-500"></span>
                            <small class="char-count text-gray-500">0/500</small>
                        </div>
                    </form>
                </div>
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
