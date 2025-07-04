<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Linya</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body>


    <aside id="sidebar" class="fixed left-0 top-0 z-50 transition-transform duration-300 transform -translate-x-full">
        @include('partials.dashboard_header')

        @include('partials.sidebar')
    </aside>



    <button id="toggleSideBar"
        class="m-4 p-2 text-[#9A9A9A] hover:bg-gray-100 rounded-[10px] transition duration-300 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>

    </button>


</body>

</html>
