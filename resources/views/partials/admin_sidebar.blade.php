<aside id="sidebar" class="bg-[#23222E] w-64 h-screen text-white flex flex-col p-4 font-lexend fixed top-0 left-0 z-50 transition-transform duration-300">
        <div class="flex justify-center mb-8 mt-2">
            <img src="/images/linyaText.svg" alt="LINYA Logo" class="h-10 w-auto" />
        </div>
        <nav class="flex flex-col gap-2">
            <a href="/dashboard" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-lg font-bold">Dashboard</a>
            <a href="/user-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">User Management</a>
            <div class="bg-orange-400 text-white rounded">
                <button id="postMgmtToggle" class="w-full flex items-center justify-between py-2 px-3 text-base font-semibold">
                    Post Management
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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