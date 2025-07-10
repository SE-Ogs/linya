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
