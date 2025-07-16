<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Linya CMS</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-[#f4f4f4] font-lexend">

<div x-data="{ sidebarOpen: true, openMenu: null }" class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="bg-[#23222E] w-64 h-screen text-white flex flex-col p-4 font-lexend fixed top-0 left-0 z-50 transition-transform duration-300"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex justify-center mb-8 mt-2">
            <img src="/images/linyaText.svg" alt="LINYA Logo" class="h-10 w-auto" />
        </div>
        <nav class="flex flex-col gap-2">
            <a href="/dashboard" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-lg font-bold">Dashboard</a>
            <a href="/user-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">User Management</a>
            <div class="bg-orange-400 text-white rounded">
                <button @click="openMenu === 1 ? openMenu = null : openMenu = 1"
                        class="w-full flex items-center justify-between py-2 px-3 text-base font-semibold">
                    Post Management
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div x-show="openMenu === 1" class="bg-orange-400 text-white rounded">
                <a href="/blog-analytics" class="block py-2 px-3 text-base font-semibold hover:underline">Blog Analytics</a>
                <a href="/article-management" class="block pl-6 py-2 text-sm font-normal hover:underline">Article Management</a>
            </div>
            <a href="/comment-management" class="py-2 px-3 rounded hover:bg-[#35344a] transition text-base font-medium">Comment Management</a>
        </nav>
    </aside>

    <!-- Main -->
    <div :class="sidebarOpen ? 'ml-64' : 'ml-0'" class="flex-1 flex flex-col transition-[margin] duration-300">

        <!-- Header -->
        <header class="bg-gradient-to-r from-[#2C2B3C] to-[#1E1D2A] text-white px-8 py-4 flex justify-between items-center shadow-md h-20">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 bg-[#2C2B3C] hover:bg-[#35344a] transition">
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

        <!-- Content -->
        <div x-data="cmsApp()" class="p-6 space-y-6">

            <!-- Filters -->
            <div class="flex space-x-2 bg-white rounded shadow overflow-hidden w-full">
                <template x-for="status in statusOptions" :key="status">
                    <button @click="activeStatus = status"
                            :class="{'bg-blue-600 text-white': activeStatus === status, 'hover:bg-gray-100': activeStatus !== status}"
                            class="px-6 py-5 text-base font-semibold transition-colors duration-200 w-full">
                        <span x-text="status"></span>
                    </button>
                </template>
            </div>

<!-- Search + Filter Button -->
<div class="flex justify-between items-center">
    <div class="flex space-x-2">
        <input type="text" x-model="searchTerm" placeholder="Search post..."
               class="rounded-full px-6 py-3 border border-gray-300 focus:outline-none shadow-sm w-72">
        <button @click="window.location.href='/add-post'" class="px-5 py-3 bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-full hover:scale-105 transform transition">
            + Add New Post
        </button>
    </div>
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" type="button"
            class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 14.414V18a1 1 0 01-1.447.894l-4-2A1 1 0 018 16V14.414L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
            Filter
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow z-50 p-2 space-y-2">
            <template x-for="tag in tags" :key="tag">
                <button @click="selectedTags.includes(tag) ? selectedTags = selectedTags.filter(t => t !== tag) : selectedTags.push(tag)"
                        :class="{'bg-orange-400 text-white': selectedTags.includes(tag), 'hover:bg-gray-100': !selectedTags.includes(tag)}"
                        class="w-full text-left px-3 py-1 text-sm rounded transition-colors duration-200">
                    <span x-text="tag"></span>
                </button>
            </template>
        </div>
    </div>
</div>

            <!-- Posts -->
            <div class="space-y-4">
                <template x-for="post in filteredPosts" :key="post.title">
                    <div class="flex items-center justify-between bg-white p-6 rounded-xl shadow hover:shadow-lg transition-transform hover:scale-[1.02]">
                        <div class="flex items-center space-x-4">
                            <img :src="post.img" class="w-20 h-20 rounded-lg object-cover" alt="post image">
                            <div>
                                <h3 class="font-semibold text-xl" x-text="post.title"></h3>
                                <p class="text-sm text-gray-500 font-noto" x-text="'Written by: ' + post.author"></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span :class="statusClass(post.status)"
                                  class="text-white text-sm font-semibold px-5 py-3 rounded-md"
                                  x-text="post.status"></span>
                            <div class="relative">
                                <button @click="post.dropdown = !post.dropdown"
                                        class="p-2 rounded hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6h.01M12 12h.01M12 18h.01"/>
                                    </svg>
                                </button>
                                <div x-show="post.dropdown" @click.away="post.dropdown = false" class="absolute right-0 mt-2 w-32 bg-white rounded shadow text-sm z-50">
                                    <button @click="openEditPopup(); post.dropdown = false" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Edit</button>
                                    <button @click="openDeletePopup(); post.dropdown = false" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>
</div>

@include('admin-panel.modals.delete-popup')
@include('admin-panel.modals.edit-popup')

<script>
function cmsApp() {
    return {
        searchTerm: '',
        activeStatus: 'All',
        selectedTags: [],
        statusOptions: ['All', 'Pending Review', 'Approved', 'Published', 'Rejected'],
        tags: ['Software Engineering', 'Animation', 'Game Development', 'Real Estate', 'Multimedia Arts'],
        posts: [
            { title: 'Valorant Tournament', author: 'Spyke Lim', status: 'Approved', tags: ['Game Development'], img: '/images/valorant.png' },
            { title: 'Final Exam ADVAWEB', author: 'Edwell Cotajar', status: 'Published', tags: ['Software Engineering'], img: '/images/exam.png' },
            { title: 'Grades Got Cooked', author: 'Judd Tagalog', status: 'Rejected', tags: ['Real Estate'], img: '/images/dog.png' },
            { title: 'Top 10 Design Tips', author: 'Xavier Viduya', status: 'Pending Review', tags: ['Multimedia Arts'], img: '/images/chicken.png' },
        ],
        statusClass(status) {
            switch(status) {
                case 'Approved': return 'bg-green-400 drop-shadow-[0_5px_15px_rgba(34,197,94,0.6)]';
                case 'Published': return 'bg-blue-500 drop-shadow-[0_5px_15px_rgba(59,130,246,0.6)]';
                case 'Rejected': return 'bg-red-500 drop-shadow-[0_5px_15px_rgba(239,68,68,0.6)]';
                case 'Pending Review': return 'bg-gradient-to-r from-orange-400 to-orange-500 drop-shadow-[0_5px_15px_rgba(249,115,22,0.6)]';
            }
            return 'bg-gray-400';
        },
        get filteredPosts() {
            return this.posts.filter(post => {
                const matchesStatus = this.activeStatus === 'All' || post.status === this.activeStatus;
                const matchesSearch = post.title.toLowerCase().includes(this.searchTerm.toLowerCase()) || post.author.toLowerCase().includes(this.searchTerm.toLowerCase());
                const matchesTags = this.selectedTags.length === 0 || this.selectedTags.some(tag => post.tags.includes(tag));
                return matchesStatus && matchesSearch && matchesTags;
            });
        },
        openEditPopup() { openModal('editModal'); },
        openDeletePopup() { openModal('deleteModal'); }
    }
}
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
</body>
</html>
