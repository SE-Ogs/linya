<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <title>Linya</title>

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>

    <body class="min-h-screen overflow-x-hidden bg-white"
          data-banned="{{ session('Banned') ? 'true' : 'false' }}">
        <div id="authContainer"
             class="duration-800 flex min-h-screen w-[200vw] transition-transform"
             style="transform: translateX({{ $show === 'signup' ? '-100vw' : '0' }});">
            <div class="h-screen w-screen flex-shrink-0">
                @include('partials.login')
            </div>

            <div class="h-screen w-screen flex-shrink-0">
                @include('partials.signup')
            </div>
        </div>

        @include('partials.banned-account-modal')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('authContainer');
                const toSignupBtn = document.getElementById('toSignupBtn');
                const toLoginBtn = document.getElementById('toLoginBtn');
                const signupForm = document.querySelector('form[action="/signup"]');

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
                    toSignupBtn.addEventListener('click', function() {
                        container.style.transform = 'translateX(-100vw)';
                        if (window.location.pathname !== '/signup') {
                            window.history.pushState({}, '', '/signup');
                        }
                    });
                }
                if (toLoginBtn) {
                    toLoginBtn.addEventListener('click', function() {
                        container.style.transform = 'translateX(0)';
                        if (window.location.pathname !== '/login') {
                            window.history.pushState({}, '', '/login');
                        }
                    });
                }

                // Handle browser navigation (back/forward)
                window.addEventListener('popstate', function() {
                    setTransformFromPath(window.location.pathname);
                });

                const banned = document.body.dataset.banned === 'true';
                if (banned) {
                    const bannedModal = document.getElementById('bannedModal');
                    if (bannedModal) {
                        bannedModal.classList.remove('hidden');
                        bannedModal.classList.add('flex');

                        const closeBtn = document.getElementById('closeBannedModal');
                        if (closeBtn) {
                            closeBtn.addEventListener('click', () => {
                                bannedModal.classList.add('hidden');
                                bannedModal.classList.remove('flex');
                            });
                        }
                    }
                }
            });
        </script>

    </body>

</html>
