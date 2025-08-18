<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <title>Coming Soon</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body
          class="relative flex min-h-screen items-center justify-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white">

        <!-- Back to Dashboard Button (Top Left) -->
        <a href="{{ route('home') }}"
           class="absolute left-6 top-6 rounded-lg border border-white px-4 py-2 font-semibold text-white transition hover:bg-white hover:text-indigo-600">
            ⬅ Back to Dashboard
        </a>

        <!-- Main Content -->
        <div class="space-y-6 text-center">
            <h1 class="text-5xl font-extrabold">🚀 Coming Soon</h1>
            <p class="text-lg">We’re working hard to bring you something amazing. Stay tuned!</p>
        </div>

    </body>

</html>
