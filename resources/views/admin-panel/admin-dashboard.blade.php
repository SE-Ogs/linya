<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#f4f4f4] font-lexend">

<div id="app" class="flex min-h-screen">

    <!-- Sidebar -->
    @include('partials.admin_sidebar')

    <!-- Main Content -->
    <div id="mainContent" class="flex-1 flex flex-col transition-[margin] duration-300 ml-64">

    <!-- Header -->
    @include('partials.admin_header')

    <!-- WELCOME MESSAGE -->
    <div class="p-6 space-y-6 font-[Lexend]">

      <h2 class="text-2xl font-bold flex items-center gap-2">
          <img src="/images/Waving-Hand.svg" alt="Waving Hand" class="h-10 w-auto" />
          <!-- To show the user's username -->
          Welcome Back, <span style="color: #24317E;">{{ $currentUser->name }}!</span>
      </h2>

        <!-- QUICK SYSTEM OVERVIEW -->
        <div class="bg-white rounded-2xl shadow p-6">

            <h2 class="text-xl font-semibold flex items-center gap-2 font-[Lexend] mb-4">
              <img src="/images/System-Overview-Icon.svg" alt="System Overview Icon" class="h-10 w-auto" />
              <span style="color: #25317E;">Quick System Overview</span>
            </h2>

        <div class="flex flex-wrap gap-6">

              <!-- Total Users -->
              <div class="flex-1 min-w-[150px] text-center space-y-2">
                <img src="/images/UserIcon.png" alt="Users" class="mx-auto h-10 w-10">
                <div class="text-xl font-semibold" style="color: #24317E;">Total Users: {{ $totalUsers }}</div>
              </div>

              <!-- Posts -->
              <div class="flex-1 min-w-[150px] text-center space-y-2">
                <img src="/images/PostsIcon.png" alt="Posts" class="mx-auto h-10 w-10">
                <div class="text-xl font-semibold" style="color: #24317E;">Posts: {{ $totalPosts }}</div>
              </div>

              <!-- Comments -->
              <div class="flex-1 min-w-[150px] text-center space-y-2">
                <img src="/images/CommentsIcon.png" alt="Comments" class="mx-auto h-10 w-10">
                <div class="text-xl font-semibold" style="color: #24317E;">Comments: {{ $totalComments }}</div>
              </div>

              <!-- Reports -->
              <div class="flex-1 min-w-[150px] text-center space-y-2">
                <img src="/images/ReportsIcon.png" alt="Reports" class="mx-auto h-10 w-10">
                <div class="text-xl font-semibold" style="color: #24317E;">Reports: N/A</div>
              </div>

              <!-- Alerts -->
              <div class="flex-1 min-w-[150px] text-center space-y-2">
                <img src="/images/AlertsIcon.png" alt="Alerts" class="mx-auto h-10 w-10">
                <div class="text-xl font-semibold" style="color: #24317E;">Alerts: N/A</div>
              </div>

          </div>
      </div>

      <!-- Analytics Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Analytics Section Square to section them -->
        <div class="col-span-2 bg-white rounded-2xl shadow p-6 h-[450px] overflow-hidden flex flex-col">

            <h2 class="text-xl font-semibold flex items-center gap-2 font-[Lexend] mb-2">
                <span style="color: #25317E;">Analytics</span>
            </h2>

        <!-- Selected Post Title -->
        <div id="selectedPostTitle" class="text-lg font-bold text-[#24317E] mb-4"></div>

        <!-- Target div where analytics message appears -->
        <div id="analyticsContent" class="text-gray-400 h-full flex-1 overflow-auto flex items-center justify-center text-center">
            <div>
                <p>No Selected Post</p>
                <span class="text-sm">Please Select A Post To View Analytics</span>
            </div>
        </div>
    
        </div>
        <!-- Trending Posts -->
            <!-- Trending Posts Square to section them-->
            <div class="bg-white rounded-2xl shadow p-6">

            <h2 class="text-xl font-semibold flex items-center gap-2 font-[Lexend] mb-4">
                <span class="text-orange-500">🔥</span>
                <span style="color: #25317E;">Trending Posts</span>
            </h2>

                    <!-- Contents of Trending Posts -->
                    <div class="space-y-4">
                    @forelse ($trendingPosts as $post)
                    <div 
                        class="bg-gray-100 rounded-xl p-3 cursor-pointer hover:bg-gray-200"
                        data-id="{{ $post->id }}"
                        data-title="{{ $post->title }}"
                        data-author="{{ $post->author_name ?? 'Unknown' }}"
                        data-tags='@json($post->tags->pluck("name"))'
                        data-views="{{ $post->views }}"
                        data-comments="{{ $post->comments_count ?? 'N/A' }}"
                        onclick="handleTrendingClick(this)">

                    <p class="font-semibold text-black">{{ $post->title }}</p>
                    <p class="text-sm text-gray-600">by: {{ $post->author_name ?? 'Unknown' }}</p>
                    <p class="text-xs text-gray-500">
                        Views: {{ $post->views }} |
                        Comments: {{ $post->comments_count ?? 'N/A' }}
                    </p>
                    </div>
                    @empty
                        <p class="text-gray-500">No trending posts found.</p>
                    @endforelse

                    </div>
                  </div>
              </div>
        </div>
    </div>
</div>

<script>
    let currentSelectedPostId = null;

    function handleTrendingClick(element) {
        const postId = element.dataset.id;
        const postTitle = element.dataset.title;
        const authorName = element.dataset.author;
        const views = element.dataset.views ?? 'N/A';
        const comments = element.dataset.comments ?? 'N/A';

        let tags = [];

        try {
            tags = JSON.parse(element.dataset.tags);
        } catch (e) {
            console.error("Failed to parse tags:", e);
        }

        const analyticsContent = document.getElementById('analyticsContent');
        const selectedPostTitle = document.getElementById('selectedPostTitle');

        if (currentSelectedPostId === postId) {
            // Deselect
            analyticsContent.innerHTML = `
                <p>No Selected Post<br><span class="text-sm">Please Select A Post To View Analytics</span></p>
            `;
            selectedPostTitle.innerHTML = '';
            currentSelectedPostId = null;
        } else {
        // Show it to analytics section
        const tagsHtml = tags.length
            ? `<div class="text-sm text-gray-600 mt-1">Tags: 
                    ${tags.map(tag => `<span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs mr-1">${tag}</span>`).join('')}
               </div>`
            : `<div class="text-sm text-gray-400 mt-1">No tags</div>`;

                analyticsContent.innerHTML = `
                    <div class="relative flex flex-col w-full h-full text-[#24317E] justify-between py-4">

                        <!-- Analytics Stats -->
                        <div class="flex justify-center gap-6 mt-6 mb-6">
                            <!-- Views -->
                            <div class="flex-1 min-w-[150px] text-center space-y-2 bg-gray-100 rounded-2xl p-4 shadow">
                                <img src="/images/ViewsIcon.png" alt="Views" class="mx-auto h-10 w-10">
                                <div class="text-xl font-semibold">Views: ${views}</div>
                            </div>

                            <!-- Comments -->
                            <div class="flex-1 min-w-[150px] text-center space-y-2 bg-gray-100 rounded-2xl p-4 shadow">
                                <img src="/images/CommentsIcon.png" alt="Comments" class="mx-auto h-10 w-10">
                                <div class="text-xl font-semibold">Comments: ${comments}</div>
                            </div>

                            <!-- Reports -->
                            <div class="flex-1 min-w-[150px] text-center space-y-2 bg-gray-100 rounded-2xl p-4 shadow">
                                <img src="/images/ReportsIcon.png" alt="Reports" class="mx-auto h-10 w-10">
                                <div class="text-xl font-semibold">Reports: N/A</div>
                            </div>
                        </div>

                        <!-- Visit Post Button (unchanged) -->
                        <div class="mt-4 flex justify-center">
                            <a href="/articles/${postId}" class="bg-[#FF8334] text-[#25317E] px-6 py-2 rounded-full font-semibold shadow hover:brightness-110 transition">
                                Visit Post
                            </a>
                        </div>
                    </div>`;

                    selectedPostTitle.innerHTML = `
                        <div class="mb-1">
                            <span class="text-[#24317E] mr-1">Title:</span>
                            <span style="color: #FF8334;">"${postTitle}"</span>
                        </div>
                        <div class="text-sm text-gray-600">by: ${authorName}</div>
                        ${tagsHtml}
                    `;

                    currentSelectedPostId = postId;
                }
            }
</script>

</body>
</html>