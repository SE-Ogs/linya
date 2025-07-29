
<header class="bg-[#23222E] text-white px-6 py-3 flex items-center justify-between shadow-lg h-16 sticky top-0 z-40 font-lexend">
    <!-- Hamburger only visible when sidebar is collapsed -->
    <div class="flex items-center space-x-4">
        <button id="toggleAdminSidebar" class="p-2 bg-[#23222E] hover:bg-[#35344a] rounded transition-colors duration-200 hidden">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-500 text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">3</span>
        </button>
    </div>

    <!-- Right side - User info and notification -->
    <div class="flex items-center space-x-4">
        <div class="relative">
            <button class="p-2 hover:bg-gray-700 rounded-full transition-colors duration-200">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">3</span>
        </div>

        <div class="flex items-center space-x-3">

<!-- Backend username and avatar -->
<!-- NOT PERFECT -->
<div class="w-10 h-10 rounded-full overflow-hidden">
    @if($currentUser && $currentUser->avatar)
        <img src="{{ asset('/images/Jarodpfp.png') }}" alt="Default Avatar" class="w-full h-full object-cover">
    @else
        <img src="{{ asset('/images/Jarodpfp.png') }}" alt="Default Avatar" class="w-full h-full object-cover">
    @endif
</div>
<div class="flex flex-col">
    <span class="font-semibold text-white text-sm">{{ $currentUser->name }}</span>
    <span class="font-noto text-gray-400 text-xs">Administrator</span>
</div>


        </div>
    </div>
</header>