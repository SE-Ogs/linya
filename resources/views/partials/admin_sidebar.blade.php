<aside id="sidebar" class="font-lexend bg-[#23222E] w-64 h-screen text-white flex flex-col p-4 fixed top-0 left-0 z-50 transition-transform duration-300 transform-none">
    <div class="flex justify-center mb-8 mt-2">
        <img src="/images/linyaText.svg" alt="LINYA Logo" class="h-10 w-auto" />
    </div>
    <nav class="flex flex-col gap-2">
        @if(Auth::user()->isWriter())
        <a href="/writer/dashboard" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium">Dashboard</a>
        <a href="/writer/articles" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium">Post Management</a>
        @elseif(Auth::user()->isAdmin())
        <a href="/admin/dashboard" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium">Dashboard</a>
        <a href="/admin/articles" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium">Post Management</a>

            <a href="/admin/users" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium">User Management</a>
            <a href="/admin/users?type=writers" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium">Writer Management</a>
            <a href="/admin/comments" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium">Comment Management</a>
        @endif

        <!-- Just a divider for main dashboard button -->
    <div class="my-4 border-t border-gray-400"></div>

        <!-- Back to main dashboard link -->
    <a href="/dashboard" class="py-2 px-3 rounded hover:bg-orange-500 transition text-base font-medium"> Back to Main Dashboard</a>

    </nav>
</aside>
