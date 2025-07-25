<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Linya CMS</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#f4f4f4] font-lexend">

<div id="app" class="flex min-h-screen">

    <!-- Sidebar -->
    <aside id="sidebar" class="bg-[#23222E] w-64 h-screen text-white flex flex-col p-4 font-lexend fixed top-0 left-0 z-50 transition-transform duration-300">
        <div class="flex justify-center mb-8 mt-2">
            <img src="/images/linyaText.svg" alt="LINYA Logo" class="h-10 w-auto" />
        </div>
        <nav class="flex flex-col gap-2">
            <div class="bg-orange-400 text-white rounded">
                <button id="dashboardbutton" class="w-full flex items-center justify-between py-2 px-3 rounded hover:bg-[#35344a] transition text-lg font-bold">
                    Dashboard
                </button>
            </div>

            <a href="/user-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">User Management</a>

            <div class="w-full flex items-center justify-between py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">
                <a href="/admin/posts" class="flex-1">Post Management</a>
                <button id="postMgmtToggle" class="ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div id="postMgmtMenu" class="bg-orange-400 text-white rounded hidden">
                <a href="/blog-analytics" class="block py-2 px-3 text-base font-semibold hover:underline">Blog Analytics</a>
                <a href="/article-management" class="block pl-6 py-2 text-sm font-normal hover:underline">Article Management</a>
            </div>

            <a href="/comment-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">Comment Management</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div id="mainContent" class="flex-1 flex flex-col transition-[margin] duration-300 ml-64">

        <!-- Header -->
        <header class="bg-[#23222E] text-white px-8 py-4 flex justify-between items-center shadow-md h-20">
            <button id="sidebarToggle" class="p-2 bg-[#2C2B3C] hover:bg-[#35344a] transition">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center space-x-4">
                <button class="relative p-2 hover:bg-gray-700 rounded-full">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">3</span>
                </button>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">JR</span>
                    </div>
                    <div>
                        <span class="font-semibold text-sm">Jarod R.</span>
                        <p class="text-xs text-gray-400 font-noto">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- WELCOME MESSAGE -->
<div class="p-6 space-y-6 font-[Lexend]">
  <h2 class="text-2xl font-bold flex items-center gap-2">
    <img src="/images/Waving-Hand.svg" alt="Waving Hand" class="h-10 w-auto" />
    Welcome Back, <span style="color: #24317E;">Jarod!</span>
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
    <div class="text-xl font-semibold" style="color: #24317E;">Total Users: 200</div>
    </div>

    <!-- Posts -->
    <div class="flex-1 min-w-[150px] text-center space-y-2">
    <img src="/images/PostsIcon.png" alt="Posts" class="mx-auto h-10 w-10">
    <div class="text-xl font-semibold" style="color: #24317E;">Posts: 100</div>
    </div>

    <!-- Comments -->
    <div class="flex-1 min-w-[150px] text-center space-y-2">
    <img src="/images/CommentsIcon.png" alt="Comments" class="mx-auto h-10 w-10">
    <div class="text-xl font-semibold" style="color: #24317E;">Comments: 200</div>
    </div>

    <!-- Reports -->
    <div class="flex-1 min-w-[150px] text-center space-y-2">
    <img src="/images/ReportsIcon.png" alt="Reports" class="mx-auto h-10 w-10">
    <div class="text-xl font-semibold" style="color: #24317E;">Reports: 200</div>
    </div>

    <!-- Alerts -->
    <div class="flex-1 min-w-[150px] text-center space-y-2">
    <img src="/images/AlertsIcon.png" alt="Alerts" class="mx-auto h-10 w-10">
    <div class="text-xl font-semibold" style="color: #24317E;">Alerts: 200</div>
    </div>

  </div>
</div>

  <!-- Analytics -->
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

      <div class="space-y-4">
        <div class="bg-gray-100 rounded-xl p-3">
          <p class="font-semibold">We are the World</p>
          <p class="text-sm text-gray-600">by: Judd Tagalog</p>
          <p class="text-xs text-gray-500">Views: 129 | Comments: 90</p>
        </div>
        <div class="bg-gray-100 rounded-xl p-3">
          <p class="font-semibold">Why Choose Computer?</p>
          <p class="text-sm text-gray-600">by: Spyke Lim</p>
          <p class="text-xs text-gray-500">Views: 120 | Comments: 62</p>
        </div>
        <div class="bg-gray-100 rounded-xl p-3">
          <p class="font-semibold">Top Students For This Year</p>
          <p class="text-sm text-gray-600">by: Edwell Cotajar</p>
          <p class="text-xs text-gray-500">Views: 112 | Comments: 54</p>
        </div>
      </div>
    </div>
  </div>
</div>


    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const appState = {
            sidebarOpen: true
        };

        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', () => {
            appState.sidebarOpen = !appState.sidebarOpen;
            document.getElementById('sidebar').classList.toggle('-translate-x-full', !appState.sidebarOpen);
            document.getElementById('mainContent').classList.toggle('ml-64', appState.sidebarOpen);
        });

        // Dropdown toggle
        document.getElementById('postMgmtToggle').addEventListener('click', () => {
            document.getElementById('postMgmtMenu').classList.toggle('hidden');
        });
    });
</script>
</body>
</html>
