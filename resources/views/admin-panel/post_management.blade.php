<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        <div class="p-6 space-y-6">

            <div id="statusFilters" class="flex space-x-2 bg-white rounded shadow overflow-hidden w-full"></div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <input type="text" id="searchInput" placeholder="Search post..." class="rounded-full px-6 py-3 border border-gray-300 focus:outline-none shadow-sm w-72">
                    <button id="addPostBtn" class="px-5 py-3 bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-full hover:scale-105 transform transition">+ Add New Post</button>
                </div>
                <div class="relative">
                    <button id="filterBtn" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow hover:bg-gray-100 focus:outline-none">Filter</button>
                    <div id="filterDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow z-50 p-2 space-y-2 hidden"></div>
                </div>
            </div>


            <div id="postsContainer" class="space-y-4">
                 @foreach ($articles as $article)
        <div class="relative flex items-center justify-between bg-white p-6 rounded-xl shadow">
            <div>
                <h3 class="font-semibold text-xl">{{ $article->title }}</h3>
                <p class="text-sm text-gray-500">{{ $article->summary }}</p>
                <p class="text-xs text-gray-400">Tags: {{ $article->tags->pluck('name')->join(', ') }}</p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('articles.edit', $article->id) }}" class="text-blue-500 hover:underline">Edit</a>

                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
            </div>

        </div>

    </div>
</div>

@include('admin-panel.modals.delete-popup')
@include('admin-panel.modals.edit-popup')

<script>
    window.articles = @json($articles);
    window.tags = @json($tags);
document.addEventListener('DOMContentLoaded', function () {
    const appState = {
        sidebarOpen: true,
        searchTerm: '',
        activeStatus: 'All',
        selectedTags: [],
        statusOptions: ['All', 'Pending Review', 'Approved', 'Published', 'Rejected'],
        tags: ['Software Engineering', 'Animation', 'Game Development', 'Real Estate', 'Multimedia Arts'],
        posts: [
                posts: window.articles || [],
                tags: window.tags.map(tag => tag.name) || []
        ]
    };

    function renderStatusFilters() {
        const container = document.getElementById('statusFilters');
        container.innerHTML = '';
        appState.statusOptions.forEach(status => {
            const btn = document.createElement('button');
            btn.textContent = status;
            btn.className = `px-6 py-5 text-base font-semibold w-full ${appState.activeStatus === status ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'}`;
            btn.onclick = () => {
                appState.activeStatus = status;
                renderPosts();
                renderStatusFilters();
            };
            container.appendChild(btn);
        });
    }

    function renderFilterDropdown() {
        const container = document.getElementById('filterDropdown');
        container.innerHTML = '';
        appState.tags.forEach(tag => {
            const btn = document.createElement('button');
            btn.textContent = tag;
            btn.className = `w-full text-left px-3 py-1 text-sm rounded ${appState.selectedTags.includes(tag) ? 'bg-orange-400 text-white' : 'hover:bg-gray-100'}`;
            btn.onclick = () => {
                const index = appState.selectedTags.indexOf(tag);
                if (index > -1) appState.selectedTags.splice(index, 1);
                else appState.selectedTags.push(tag);
                renderPosts();
                renderFilterDropdown();
            };
            container.appendChild(btn);
        });
    }

function renderPosts() {
    const container = document.getElementById('postsContainer');
    container.innerHTML = '';

    const filtered = appState.posts.filter(post => {
        const statusMatch = appState.activeStatus === 'All' || post.status === appState.activeStatus;
        const searchMatch = post.title.toLowerCase().includes(appState.searchTerm.toLowerCase()) || post.author.toLowerCase().includes(appState.searchTerm.toLowerCase());
        const tagMatch = appState.selectedTags.length === 0 || appState.selectedTags.some(tag => post.tags.includes(tag));
        return statusMatch && searchMatch && tagMatch;
    });

    filtered.forEach(post => {
        const div = document.createElement('div');
        div.className = 'relative flex items-center justify-between bg-white p-6 rounded-xl shadow hover:shadow-lg transition-transform';

        div.innerHTML = `
            <div class="flex items-center space-x-4">
                <img src="${post.img}" class="w-20 h-20 rounded-lg object-cover" alt="post image">
                <div>
                    <h3 class="font-semibold text-xl">${post.title}</h3>
                    <p class="text-sm text-gray-500 font-noto">Written by: ${post.author}</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-white text-sm font-semibold px-5 py-3 rounded-md ${statusClass(post.status)}">${post.status}</span>
                <div class="relative">
                    <button class="menu-btn p-2 rounded hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6h.01M12 12h.01M12 18h.01"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu absolute right-0 top-full mt-2 w-32 bg-white rounded shadow text-sm z-50 hidden">
                        <!-- Edit Form -->
                        <form method="GET" action="{{ route('articles.edit', $article->id) }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-blue-500">Edit</button>
                        </form>

                        <!-- Delete Form -->
                         <form method="POST" action="{{ route('articles.destroy', $article->id) }}" onsubmit="return confirm('Are you sure you want to delete this article?')">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500">Delete</button>
                             </form>
                             </div>
                        </div>
                     </div>
        `;
        container.appendChild(div);
    });

    attachDropdownActions();
}


    function attachDropdownActions() {
        document.querySelectorAll('.menu-btn').forEach(btn => {
            btn.onclick = function (e) {
                e.stopPropagation();
                closeAllDropdowns();
                btn.parentElement.querySelector('.dropdown-menu').classList.toggle('hidden');
            };
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = () => {
                openModal('editModal');
                closeAllDropdowns();
            };
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                openModal('deleteModal');
                closeAllDropdowns();
            };
        });

        document.addEventListener('click', closeAllDropdowns);
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.display = 'none';
        }
    };

    window.confirmDeletion = function() {
        alert('Post deleted!'); // replace this with actual deletion logic
        closeModal('deleteModal');
    };

    function statusClass(status) {
        switch(status) {
            case 'Approved': return 'bg-green-400';
            case 'Published': return 'bg-blue-500';
            case 'Rejected': return 'bg-red-500';
            case 'Pending Review': return 'bg-gradient-to-r from-orange-400 to-orange-500';
            default: return 'bg-gray-400';
        }
    }

    // Event listeners
    document.getElementById('sidebarToggle').addEventListener('click', () => {
        appState.sidebarOpen = !appState.sidebarOpen;
        document.getElementById('sidebar').classList.toggle('-translate-x-full', !appState.sidebarOpen);
        document.getElementById('mainContent').classList.toggle('ml-64', appState.sidebarOpen);
    });

    document.getElementById('postMgmtToggle').addEventListener('click', () => {
        document.getElementById('postMgmtMenu').classList.toggle('hidden');
    });

    document.getElementById('filterBtn').addEventListener('click', () => {
        document.getElementById('filterDropdown').classList.toggle('hidden');
    });

    document.getElementById('searchInput').addEventListener('input', (e) => {
        appState.searchTerm = e.target.value;
        renderPosts();
    });

    // Initial render
    renderStatusFilters();
    renderFilterDropdown();
    renderPosts();
});

</script>

</body>
</html>
