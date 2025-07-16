<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Linya CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-[#F5F5F5] flex h-screen overflow-hidden">

<!-- Sidebar -->
<aside class="w-72 bg-[#2C2C34] text-white flex-shrink-0 h-full sticky top-0 flex flex-col">
    <div class="p-6">
        <img src="/images/linyaText.svg" class="mb-8 w-32" alt="Linya logo">
        <ul class="space-y-1">
            <li><a href="#" class="block px-4 py-2 hover:bg-[#FF884D] rounded">Dashboard</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-[#FF884D] rounded">User Management</a></li>
            <li x-data="{ open: true }">
                <button @click="open = !open" class="flex justify-between items-center w-full px-4 py-2 bg-[#FF884D]">
                    <span>Post Management</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="open" class="space-y-0">
                    <li><a href="#" class="block bg-[#FF884D] pl-8 py-2 w-full">Blog Analytics</a></li>
                    <li><a href="#" class="block bg-[#FF884D] pl-8 py-2 w-full">Article Management</a></li>
                </ul>
            </li>
            <li><a href="#" class="block px-4 py-2 hover:bg-[#FF884D] rounded">Comment Management</a></li>
        </ul>
    </div>
</aside>

<!-- Main -->
<div class="flex-1 flex flex-col h-full">

    <!-- Header -->
    <header class="bg-[#2C2C34] text-white flex justify-end items-center px-8 py-6 space-x-6">
        <button class="relative focus:outline-none">
            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-0 right-0 inline-flex w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        <div class="flex items-center space-x-4">
            <img src="/images/profile.jpg" class="w-10 h-10 rounded-full" alt="profile">
            <div>
                <div class="font-semibold text-lg">Jarod R.</div>
                <div class="text-sm text-gray-300">Administrator</div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main x-data="cmsApp()" class="flex-1 overflow-y-auto custom-scrollbar p-8">

        <!-- Filters -->
        <div class="flex w-full mb-5 space-x-0">
            <template x-for="statusOption in statusOptions" :key="statusOption">
                <button @click="activeStatus = statusOption"
                    :class="activeStatus === statusOption
                        ? 'flex-1 py-2 bg-blue-600 text-white rounded-none'
                        : 'flex-1 py-2 border border-gray-300 rounded-none text-sm hover:bg-blue-50 transition'">
                    <span x-text="statusOption"></span>
                </button>
            </template>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex space-x-3">
                <input type="text" x-model="searchTerm" placeholder="Search post..." class="w-80 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-400 outline-none">
                <button class="bg-gradient-to-r from-orange-500 to-orange-400 text-white px-5 py-2 rounded-lg shadow-md hover:from-orange-600 hover:to-orange-500 transition">
                    + Add New Post
                </button>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 14.414V18a1 1 0 01-1.447.894l-4-2A1 1 0 018 16V14.414L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg p-4 z-10">
                    <h3 class="font-bold mb-2">Tags</h3>
                    <div class="space-y-1">
                        <template x-for="tag in tags" :key="tag">
                            <label class="flex items-center">
                                <input type="checkbox" x-model="selectedTags" :value="tag" class="mr-2"> <span x-text="tag"></span>
                            </label>
                        </template>
                    </div>
                    <button @click="open = false" class="mt-4 w-full bg-[#2C2C34] text-white py-2 rounded">Apply</button>
                </div>
            </div>
        </div>

        <!-- Posts -->
        <div class="space-y-6">
            <template x-for="post in filteredPosts" :key="post.title">
                <div class="flex items-center p-4 bg-white rounded-lg shadow justify-between">
                    <div class="flex items-center">
                        <img :src="post.img" class="h-24 w-32 object-cover rounded-lg shadow-md mr-4">
                        <div>
                            <h3 class="text-lg font-bold" x-text="post.title"></h3>
                            <p class="text-sm text-gray-600">Written by: <span x-text="post.author"></span></p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div :class="statusClass(post.status)" class="px-4 py-2 rounded text-white font-semibold" x-text="post.status"></div>
                        <div x-data="{ menuOpen: false }" class="relative">
                            <button @click="menuOpen = !menuOpen">
                                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM10 11a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM10 16a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                            </button>
                            <div x-show="menuOpen" @click.away="menuOpen = false" class="absolute right-0 mt-2 w-32 bg-white shadow-lg rounded-lg z-10">
                                <a href="javascript:void(0)" @click="openEditPopup()" class="block px-4 py-2 hover:bg-gray-100">Edit</a>
                                <button @click="openDeletePopup()" class="w-full text-left px-4 py-2 hover:bg-gray-100">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </main>
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
        openDeletePopup() {
            openModal('deleteModal');
        },
        openEditPopup() {
            openModal('editModal');
        }
    }
}

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function confirmDelete() {
    alert('Post Deleted!');
    closeModal('deleteModal');
}

function confirmEdit() {
    alert('Redirecting to Edit Page...');
    closeModal('editModal');
}
</script>

</body>
</html>
