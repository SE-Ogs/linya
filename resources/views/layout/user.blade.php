<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Linya</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="min-h-screen flex flex-col">

    <div class="flex justify-between">
        <button id="toggleSideBar"
            class="ml-4 mt-3 p-2 h-12 text-[#9A9A9A] hover:bg-gray-100 rounded-[10px] transition duration-300 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>

        </button>
        <div class="m-4 space-x-1">
            <button type="button" id="signup"
                class="px-5 py-2 hover:bg-[#4338CA] hover:text-white border-[#4338CA] transition duration-300 border rounded-[6px] text-[14px] text-[#4338CA] cursor-pointer">Sign
                Up</button>
            <button type="button" id="login"
                class="px-5 py-2 hover:bg-[#FF8334] hover:text-white border-[#4338CA] transition duration-300 rounded-[6px] text-[14px] text-[#4B5563] cursor-pointer">Log
                In</button>
        </div>
    </div>

    <aside id="sidebar" class="fixed left-0 top-0 z-50 transition-transform duration-300 transform -translate-x-full">

        @include('partials.sidebar')
    </aside>

    <div id="header">
        @include('partials.dashboard_header')
    </div>

    <main class="flex-grow">

    </main>


    <footer>
        @include('partials.footer')
    </footer>

</body>



</html>
