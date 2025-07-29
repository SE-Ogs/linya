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
    <div class="text-xl font-semibold" style="color: #24317E;">Total Users: 100</div>
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

  <!-- Slide must be added in the future -->
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
