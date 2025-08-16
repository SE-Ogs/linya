<div class="mx-auto mb-20 w-full max-w-4xl overflow-x-hidden rounded-lg bg-white p-4 shadow-sm sm:p-6">
    <h2 class="mb-4 text-xl font-semibold text-gray-800">Comments</h2>

    {{-- Comment Form --}}
    @auth
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
                              id="comment-box"
                              class="flex-grow resize-none overflow-hidden border-none px-3 py-2 text-sm leading-snug outline-none transition-all duration-300 focus:shadow-none focus:outline-none"
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
                        <small id="char-count"
                               class="text-gray-500">0/500</small>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sort Filters Below Textarea --}}
        <div class="comment-filters scrollbar-hide my-4 flex gap-2 overflow-x-auto whitespace-nowrap">
            @php
                $filters = [
                    'all' => 'All',
                    'newest' => 'Newest',
                    'oldest' => 'Oldest',
                    'most_liked' => 'Most Liked',
                ];
            @endphp

            @foreach ($filters as $key => $label)
                <button wire:click="$dispatch('setSort', { sort: '{{ $key }}' })"
                        class="filter-button {{ $currentSort === $key ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300 bg-gray-100 text-gray-800' }} rounded-md border px-4 py-1.5 text-sm font-medium transition-all duration-200 ease-in-out hover:border-blue-600 hover:bg-blue-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Please log in to comment.</p>
    @endauth

    {{-- Comment List --}}
    <livewire:comment-list :articleId="$articleId"
                           :key="$articleId" />
</div>
