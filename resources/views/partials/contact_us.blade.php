<div id="contactModal" class="fixed hidden inset-0 z-50 items-center justify-center bg-black/50">
    <div class="w-[1183px] h-[654px] grid grid-cols-2">
        <!-- LEFT SIDE with background image, text and extra image at bottom right -->
        <div class="relative w-full h-full">
            <!-- Base background image -->
            <img src="/images/ContactUsBg.svg" class="w-full h-full object-cover" />

            <!-- Overlay text centered -->
            <div class="absolute inset-0 flex flex-col items-start justify-center text-white">
                <h2 class="text-[40px] ml-15 mr-80">We'd love to hear from you</h2>

            </div>

            <!-- Extra image at bottom right -->
            <img src="/images/linyaText.svg" class="absolute bottom-4 right-4" />
        </div>

        <div class="bg-white flex flex-col justify-center h-full px-8 py-8">
            <h1 class="text-[#24317E] font-black mb-6 text-3xl ">Contact Us</h1>

            <!-- Form container -->
            <form class="flex flex-col gap-5 w-full">
                <!-- Row: First & Last Name -->
                <div class="flex gap-4">
                    <div class="flex flex-col flex-1">
                        <label>First Name</label>
                        <input type="text"
                            class="backdrop-blur-md bg-white/30 border border-white/30 text-black placeholder:text-gray-500 p-3 rounded-[20px] shadow-[0_8px_40px_rgba(24,36,79,0.3)] focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                    <div class="flex flex-col flex-1">
                        <label>Last Name</label>
                        <input type="text"
                            class="backdrop-blur-md bg-white/30 border border-white/30 text-black placeholder:text-gray-500 p-3 rounded-[20px] shadow-[0_8px_40px_rgba(24,36,79,0.3)] focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>
                </div>

                <!-- Email -->
                <div class="flex flex-col mb-6">
                    <label>Email</label>
                    <input type="email"
                        class="backdrop-blur-md bg-white/30 border border-white/30 text-black placeholder:text-gray-500 p-3 rounded-[20px] shadow-[0_8px_40px_rgba(24,36,79,0.3)] focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Message -->
                <div class="flex flex-col mb-6">
                    <label>Message</label>
                    <textarea
                        class="backdrop-blur-md bg-white/30 border border-white/30 text-black placeholder:text-gray-500 p-3 rounded-[20px] shadow-[0_8px_40px_rgba(24,36,79,0.3)] focus:outline-none focus:ring-2 focus:ring-blue-400 h-32 resize-none"></textarea>
                </div>

                <!-- Submit button (optional) -->
                <div class="flex justify-end mt-10">
                    <button type="submit" class="backdrop-blur-md bg-orange-500/60 hover:bg-orange-500/80 text-white px-4 py-2 rounded-[20px] shadow-[0_8px_40px_rgba(255,136,77,0.3)] transition duration-300 w-[195px]">
                        Submit </button>
                </div>
            </form>
        </div>
    </div>
</div>
