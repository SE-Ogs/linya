
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
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center overflow-hidden">
                <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 128 128'%3E%3Ccircle cx='64' cy='64' r='64' fill='%23d1d5db'/%3E%3Cpath d='M64 32c-8.8 0-16 7.2-16 16s7.2 16 16 16 16-7.2 16-16-7.2-16-16-16zM64 96c-13.3 0-24-10.7-24-24v-8c0-8.8 7.2-16 16-16h16c8.8 0 16 7.2 16 16v8c0 13.3-10.7 24-24 24z' fill='%23374151'/%3E%3C/svg%3E" }}"
                     alt="Profile Image" class="h-full w-full object-cover" />
            </div>
            <div class="flex flex-col">
                <span class="font-semibold text-white text-sm">{{ Auth::user()->name }}</span>
                <span class="font-noto text-gray-400 text-xs">
                    @if(Auth::user()->isAdmin())
                        Administrator
                    @elseif(Auth::user()->isWriter())
                        Writer
                    @else
                        User
                    @endif
                </span>
            </div>
        </div>
    </div>
</header>
