@php
    if (!function_exists('renderComment')) {
        function renderComment($comment) {
@endphp

            <div class="space-y-3" id="comment-{{ $comment['id'] }}">
                <div class="flex space-x-3 p-3 bg-white rounded-lg shadow-sm">
                    <img src="/images/placeholder.jpg" alt="Avatar" class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0">
                    <div class="flex-grow">
                        {{-- ... (The inner HTML of a comment remains the same) ... --}}
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="font-bold text-gray-900">{{ $comment['username'] }}</span>
                            <span class="text-gray-500">{{ $comment['time_ago'] }} ago</span>
                        </div>
                        <div class="mt-1 text-gray-800">{{ nl2br($comment['text']) }}</div>
                        <div class="flex items-center space-x-4 mt-2 text-sm font-medium text-gray-600">
                             <a href="#" class="reply-button hover:underline" data-comment-id="{{ $comment['id'] }}">Reply</a>
                        </div>
                        <div id="reply-form-{{ $comment['id'] }}" class="reply-form-container mt-3" style="display: none;">
                            <form action="{{ route('comments.store') }}" method="post" class="flex items-center space-x-2">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment['id'] }}">
                                <input type="text" name="comment_text" placeholder="Add a reply..." required class="flex-grow bg-gray-100 border border-gray-300 rounded-full py-1.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <button type="submit" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Reply</button>
                            </form>
                        </div>
                    </div>
                </div>

                @if ($comment['reply_count'] > 0)
                    <div class="pl-5">
                        <button class="toggle-replies-btn text-sm font-bold text-indigo-600 hover:underline" data-comment-id="{{ $comment['id'] }}" data-replies-count="{{ $comment['reply_count'] }}">
                            See Replies ({{ $comment['reply_count'] }})
                        </button>
                    </div>
                    <div id="replies-container-{{ $comment['id'] }}" class="hidden space-y-3 pl-5 border-l-2 border-gray-200">
                        @foreach ($comment['replies'] as $reply)
                            @php renderComment($reply); @endphp
                        @endforeach
                    </div>
                @endif
            </div>
@php
        }
    }
@endphp

{{-- Main Comment Section --}}
<div class="max-w-3xl mx-auto bg-gray-50 p-6 rounded-xl space-y-6">
    <form action="{{ route('comments.store') }}" method="post" class="flex items-start space-x-4">
        @csrf
        <img src="/images/placeholder.jpg" alt="Your Avatar" class="w-10 h-10 rounded-full">
        <div class="flex-grow">
            <input type="text" name="comment_text" placeholder="Add a comment..." required class="w-full bg-white border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition">Post</button>
    </form>

    <div class="flex items-center space-x-2 ml-14">
        @foreach (['newest', 'oldest', 'most_liked'] as $filter)
            <a href="?sort={{ $filter }}" class="py-1 px-3 rounded-full text-sm font-medium transition-colors {{ $sort === $filter ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                {{ ucfirst($filter) }}
            </a>
        @endforeach
    </div>

    <div class="space-y-5">
        @forelse ($comments as $comment)
            @php renderComment($comment); @endphp
        @empty
            <p class="text-center text-gray-500 py-8">No comments yet. Be the first to comment! 🚀</p>
        @endforelse
    </div>
</div>
