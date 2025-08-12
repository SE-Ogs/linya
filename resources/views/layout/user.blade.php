<!DOCTYPE html>
<html lang=en>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token"
              content="{{ csrf_token() }}">

        <title>Linya</title>

        @vite(['resources/css/app.css'])

    </head>

    <body class="flex min-h-screen flex-col">
        <div class="flex justify-between">
            <button id="toggleSideBar"
                    class="ml-4 mt-3 h-12 cursor-pointer rounded-[10px] p-2 text-[#9A9A9A] transition duration-300 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     class="size-8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>

            </button>
            @guest
                <div class="m-4 space-x-1">
                    <a href="{{ route('signup') }}"
                       id="signup"
                       class="cursor-pointer rounded-[6px] border border-[#4338CA] px-5 py-2 text-[14px] text-[#4338CA] transition duration-300 hover:bg-[#4338CA] hover:text-white">Sign Up</a>
                    <a href="{{ route('login') }}">
                        <button type="button"
                                id="login"
                                class="cursor-pointer rounded-[6px] border-[#4338CA] px-5 py-2 text-[14px] text-[#4B5563] transition duration-300 hover:bg-[#FF8334] hover:text-white">Log
                            In</button>
                    </a>
                </div>
            @endguest

            @auth
                @if (Auth::user()->isAdmin())
                    <div class="m-4">
                        <a href="{{ route('admin.dashboard') }}">
                            <button
                                    class="cursor-pointer rounded-[6px] bg-[#4338CA] px-5 py-2 text-white transition duration-300 hover:bg-[#2C2891]">
                                Admin Dashboard
                            </button>
                        </a>
                    </div>
                @elseif (Auth::user()->isWriter())
                    <div class="m-4">
                        <a href="{{ route('writer.dashboard') }}">
                            <button
                                    class="cursor-pointer rounded-[6px] bg-[#4338CA] px-5 py-2 text-white transition duration-300 hover:bg-[#2C2891]">
                                Writer Dashboard
                            </button>
                        </a>
                    </div>
                @endif
            @endauth

        </div>

        <aside id="sidebar"
               class="fixed left-0 top-0 z-50 -translate-x-full transform transition-transform duration-300">

            @include('partials.sidebar')
        </aside>

        <div id="header">
            @include('partials.dashboard_header')
        </div>

        <main class="flex-grow">
            @include ('partials.dashboard_content')
        </main>

        <footer>
            @include('partials.footer')

        </footer>

        @include('partials.contact_us')
        @include('partials.are_you_sure_modal')

        @vite('resources/js/app.js')

    </body>

</html>
