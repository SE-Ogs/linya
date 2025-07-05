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
                            class="border border-[#D4D4D4] p-2 rounded-[20px] shadow-[0_10px_19px_0_rgba(0,0,0,0.2)]" />
                    </div>
                    <div class="flex flex-col flex-1">
                        <label>Last Name</label>
                        <input type="text"
                            class="border border-[#D4D4D4] p-2 rounded-[20px] shadow-[0_10px_19px_0_rgba(0,0,0,0.2)]" />
                    </div>
                </div>

                <!-- Email -->
                <div class="flex flex-col mb-6">
                    <label>Email</label>
                    <input type="email"
                        class="border border-[#D4D4D4] p-2 rounded-[20px] shadow-[0_10px_19px_0_rgba(0,0,0,0.2)]" />
                </div>

                <!-- Message -->
                <div class="flex flex-col mb-6">
                    <label>Message</label>
                    <textarea class="border border-[#D4D4D4] p-2 h-32 resize-none rounded-[20px] shadow-[0_10px_19px_0_rgba(0,0,0,0.2)]"></textarea>
                </div>

                <!-- Submit button (optional) -->
                <div class="flex justify-end mt-10">
                    <button type="submit" class="bg-[#FF884D] w-[195px] text-white px-4 py-2 rounded">
                        Submit </button>
                </div>
            </form>
        </div>
    </div>
</div>
