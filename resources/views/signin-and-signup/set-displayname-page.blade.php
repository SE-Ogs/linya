<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linya</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-white min-h-screen overflow-x-hidden">
    <div class="bg-white min-h-screen w-screen h-screen relative">
    <!-- Main Content -->
    <div class="flex items-center justify-center h-full">
        <div class="w-full max-w-2xl mx-auto px-6">
            <div class="text-center">
                <h1 class="text-6xl font-black mb-8 text-black">One more step!</h1>

                <p class="text-gray-600 text-base mb-8 leading-relaxed text-lg">
                    Provide us with your desired display name. Worry not!
                    It's not permanent; you can change it. (If you entered
                    none, your username will be your display name)
                </p>

                <div class="max-w-lg mx-auto">
                    <form method="POST" action="/set-display-name" class="space-y-6">
                        @csrf
                        <input type="text"
                            name="display_name"
                            id="display_name"
                            placeholder="Enter here pls :)"
                            class="w-full rounded-2xl bg-[#E6E5E1] px-6 py-5 text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal text-center" />

                        <button type="submit"
                                class="w-full bg-orange-400 text-white font-bold text-lg rounded-3xl py-4 transition active:scale-98 active:bg-orange-500 hover:scale-101">
                            Proceed
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
