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

          <div class="col-span-2 bg-white rounded-2xl shadow p-6">
              <h2 class="text-xl font-semibold flex items-center gap-2 font-[Lexend] mb-4">
                  <span style="color: #25317E;">Analytics</span>
              </h2>

              <div class="text-gray-400 text-center h-full flex items-center justify-center">
                  <p>No Selected Post<br><span class="text-sm">Please Select A Post To View Analytics</span></p>
              </div>
          </div>

      <!-- Trending Posts -->
          <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-xl font-semibold flex items-center gap-2 font-[Lexend] mb-4">
              <span class="text-orange-500">🔥</span>
              <span style="color: #25317E;">Trending Posts</span>
          </h2>

                    <!-- Contents of Trending Posts -->
                    <div class="space-y-4">
                        @forelse ($trendingPosts as $post)
                          <div class="bg-gray-100 rounded-xl p-3">
                            <p class="font-semibold">{{ $post->title }}</p>
                            <p class="text-sm text-gray-600">by: {{ $post->author_name ?? 'Unknown' }}</p>
                          <p class="text-xs text-gray-500">
                                Views: {{ $post->views }} |
                                <!-- No database for comments yet -->
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

</body>
</html>