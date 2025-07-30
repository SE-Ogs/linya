<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Code Verification')</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-[#FAF9F6] min-h-screen">
    <div class="w-full h-screen flex">
        <div class="flex-1 flex items-center justify-center">
            <div class="w-full max-w-lg mx-auto">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
