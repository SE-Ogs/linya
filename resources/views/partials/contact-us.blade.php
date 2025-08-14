<div id="contactModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="grid h-[654px] w-[1183px] grid-cols-2">
        <!-- LEFT SIDE with background image, text and extra image at bottom right -->
        <div class="relative h-full w-full">
            <!-- Base background image -->
            <img src="/images/ContactUsBg.svg"
                 class="h-full w-full object-cover" />

            <!-- Overlay text centered -->
            <div class="absolute inset-0 flex flex-col items-start justify-center text-white">
                <h2 class="ml-15 mr-80 text-[40px]">We'd love to hear from you</h2>

            </div>

            <!-- Extra image at bottom right -->
            <img src="/images/linyaText.svg"
                 class="absolute bottom-4 right-4" />
        </div>

        <div class="flex h-full flex-col justify-center bg-white px-8 py-8">
            <h1 class="mb-6 text-3xl font-black text-[#24317E]">Contact Us</h1>

            <!-- Form container -->
            <form action="{{ route('contact.send') }}"
                  method="POST"
                  class="flex w-full flex-col gap-5">
                @csrf

                @guest
                    <!-- Only show for guests -->
                    <div class="flex gap-4">
                        <div class="flex flex-1 flex-col">
                            <label>First Name</label>
                            <input type="text"
                                   name="first_name"
                                   required
                                   class="rounded-[20px] border border-gray-300 p-3" />
                        </div>
                        <div class="flex flex-1 flex-col">
                            <label>Last Name</label>
                            <input type="text"
                                   name="last_name"
                                   required
                                   class="rounded-[20px] border border-gray-300 p-3" />
                        </div>
                    </div>

                    <div class="mb-6 flex flex-col">
                        <label>Email</label>
                        <input type="email"
                               name="email"
                               required
                               class="rounded-[20px] border border-gray-300 p-3" />
                    </div>
                @else
                    <!-- For logged-in users -->
                    <input type="hidden"
                           name="first_name"
                           value="{{ auth()->user()->first_name }}">
                    <input type="hidden"
                           name="last_name"
                           value="{{ auth()->user()->last_name }}">
                    <input type="hidden"
                           name="email"
                           value="{{ auth()->user()->email }}">
                @endguest

                <!-- Message -->
                <div class="mb-6 flex flex-col">
                    <label>Message</label>
                    <textarea name="message"
                              required
                              class="h-32 resize-none rounded-[20px] border border-gray-300 p-3"></textarea>
                </div>

                <!-- Submit -->
                <div class="mt-10 flex justify-end">
                    <button type="submit"
                            class="w-[195px] rounded-[20px] bg-orange-500 px-4 py-2 text-white">
                        Submit
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
