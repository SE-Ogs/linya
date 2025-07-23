<div class="bg-white min-h-screen w-screen h-screen relative">
    <!-- Back Button -->
    <div class="absolute top-8 left-8">
        <form method="POST" action="/clear-signup-data" class="inline">
            @csrf
            <button type="submit" class="w-18 h-18 bg-[#23222E] rounded-full flex items-center justify-center hover:scale-105 active:scale-98 transition">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 19L5 12L12 5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </form>
    </div>

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
                            placeholder="mine is mjart :)" 
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
    <script>
       window.addEventListener('beforeunload', function(e) {
            if (!document.activeElement.closest('form[action="/set-display-name"]')) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                navigator.sendBeacon('/clear-signup-data', formData);
            }
        });
    </script>
</div>