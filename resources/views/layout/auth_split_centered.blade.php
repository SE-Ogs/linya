{{-- layout/auth_split_centered.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Confirmation')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css') {{-- Adjust if you're using Mix instead --}}
</head>
<body class="bg-[#FFFFFF] h-screen w-full flex items-center justify-center">
    <div class="w-full max-w-md mx-auto text-center px-6">
        @yield('content')
    </div>
</body>
</html>
