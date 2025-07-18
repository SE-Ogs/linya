<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Auth Page')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Include Tailwind via Vite (or Mix if you're using it) --}}
    @vite('resources/css/app.css') {{-- Update this if you use Laravel Mix --}}
</head>
<body class="bg-[#FFFFFF] text-base">
    <div class="w-full h-screen flex">
        <!-- Left Side -->
        <div class="flex-1 flex items-center justify-center">
            <div class="w-full max-w-140 mx-auto">
                @yield('left')
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex-1 bg-[#23222E] flex flex-col items-center justify-center">
            @yield('right')
        </div>
    </div>
</body>
</html>
