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
            <!-- Success Message (initially hidden) -->
            <div id="successMessage"
                 class="absolute inset-0 z-10 hidden flex-col items-center justify-center bg-white text-center">
                <div class="mb-6">
                    <svg class="mx-auto h-16 w-16 text-green-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="mb-4 text-2xl font-bold text-green-600">Message Sent Successfully!</h2>
                <p class="mb-6 text-gray-600">Thank you for contacting us. We'll get back to you soon.</p>
                <button onclick="closeModal()"
                        class="rounded-[20px] bg-[#24317E] px-6 py-2 text-white hover:bg-[#1a2559]">
                    Close
                </button>
            </div>

            <!-- Contact Form (initially visible) -->
            <div id="contactForm"
                 class="flex h-full flex-col justify-center">
                <h1 class="mb-6 text-3xl font-black text-[#24317E]">Contact Us</h1>

                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 p-4">
                        <ul class="text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form container -->
                <form action="{{ route('contact.send') }}"
                      method="POST"
                      class="flex w-full flex-col gap-5"
                      onsubmit="handleFormSubmit(event)">
                    @csrf

                    @guest
                        <!-- Only show for guests -->
                        <div class="flex gap-4">
                            <div class="flex flex-1 flex-col">
                                <label>First Name</label>
                                <input type="text"
                                       name="first_name"
                                       value="{{ old('first_name') }}"
                                       required
                                       class="rounded-[20px] border border-gray-300 p-3" />
                            </div>
                            <div class="flex flex-1 flex-col">
                                <label>Last Name</label>
                                <input type="text"
                                       name="last_name"
                                       value="{{ old('last_name') }}"
                                       required
                                       class="rounded-[20px] border border-gray-300 p-3" />
                            </div>
                        </div>

                        <div class="mb-6 flex flex-col">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
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
                                  class="h-32 resize-none rounded-[20px] border border-gray-300 p-3">{{ old('message') }}</textarea>
                    </div>

                    <!-- Submit -->
                    <div class="mt-10 flex justify-end">
                        <button type="submit"
                                id="submitBtn"
                                class="w-[195px] rounded-[20px] bg-orange-500 px-4 py-2 text-white hover:bg-orange-600 disabled:opacity-50">
                            <span id="submitText">Submit</span>
                            <span id="loadingText"
                                  class="hidden">Sending...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function handleFormSubmit(event) {
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const loadingText = document.getElementById('loadingText');

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');
    }

    function showSuccessMessage() {
        document.getElementById('contactForm').classList.add('hidden');
        document.getElementById('contactForm').classList.remove('flex');
        document.getElementById('successMessage').classList.remove('hidden');
        document.getElementById('successMessage').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('contactModal').classList.add('hidden');
        // Reset form state
        document.getElementById('contactForm').classList.remove('hidden');
        document.getElementById('successMessage').classList.add('hidden');
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('submitText').classList.remove('hidden');
        document.getElementById('loadingText').classList.add('hidden');
        // Clear form
        document.querySelector('form').reset();
    }

    // Check for success message on page load
    @if (session('contact_success'))
        document.addEventListener('DOMContentLoaded', function() {
            // Reopen the modal and show success message
            document.getElementById('contactModal').classList.remove('hidden');
            showSuccessMessage();
        });
    @endif
</script>
