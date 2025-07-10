<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-white min-h-screen overflow-x-hidden">
    <div id="authContainer" class="flex w-[200vw] min-h-screen transition-transform duration-500"
         style="transform: translateX({{ $show === 'signup' ? '-100vw' : '0' }});">
        <div class="w-screen flex-shrink-0 h-screen">
            @include('partials.login')
        </div>
        <div class="w-screen flex-shrink-0 h-screen">
            @include('partials.signup')
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('authContainer');
            const toSignupBtn = document.getElementById('toSignupBtn');
            const toLoginBtn = document.getElementById('toLoginBtn');

            // Set initial transform based on URL
            function setTransformFromPath(path) {
                if (path.endsWith('/signup')) {
                    container.style.transform = 'translateX(-100vw)';
                } else {
                    container.style.transform = 'translateX(0)';
                }
            }
            setTransformFromPath(window.location.pathname);

            // Handle button clicks and update URL
            if (toSignupBtn) {
                toSignupBtn.addEventListener('click', function () {
                    container.style.transform = 'translateX(-100vw)';
                    if (window.location.pathname !== '/signup') {
                        window.history.pushState({}, '', '/signup');
                    }
                });
            }
            if (toLoginBtn) {
                toLoginBtn.addEventListener('click', function () {
                    container.style.transform = 'translateX(0)';
                    if (window.location.pathname !== '/login') {
                        window.history.pushState({}, '', '/login');
                    }
                });
            }

            // Handle browser navigation (back/forward)
            window.addEventListener('popstate', function () {
                setTransformFromPath(window.location.pathname);
            });
        });
    </script>
</body>
</html>
