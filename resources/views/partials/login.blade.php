<div class="w-full h-screen flex">
        <!-- Left Side -->
        <div class="flex-1 flex items-center justify-center">
            <div class="w-full max-w-140 mx-auto">
                <div class="text-6xl font-black mb-10 mt-8 text-black">Welcome!</div>
                @if ($errors->any())
                    <div class="mb-4 text-red-500 text-sm">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div><br>
                        @endforeach
                    </div>
                @endif
                <form class="flex flex-col gap-7" method="POST" action="/login">
                    @csrf
                    <input type="text" name="username" id="username" autocomplete="off" placeholder="Username" required
                        class="rounded-2xl bg-[#E6E5E1] px-6 py-5 text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal" />
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Password" required
                            class="rounded-2xl bg-[#E6E5E1] px-6 py-5 text-base placeholder-gray-500 w-full focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal pr-12" />
                        <button type="button" id="togglePassword" tabindex="-1"
                            class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer">
                            <svg id="eyeClosed" width="24" height="24" viewBox="0 0 48 48" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-6 h-6">
                                <path
                                    d="M35.88 35.88C32.4612 38.486 28.2982 39.9297 24 40C10 40 2 24 2 24C4.48778 19.3638 7.93827 15.3132 12.12 12.12M19.8 8.48C21.1767 8.15776 22.5861 7.99668 24 8C38 8 46 24 46 24C44.786 26.2712 43.3381 28.4095 41.68 30.38M28.24 28.24C27.6907 28.8295 27.0283 29.3023 26.2923 29.6302C25.5563 29.9582 24.7618 30.1345 23.9562 30.1487C23.1506 30.1629 22.3503 30.0148 21.6032 29.713C20.8561 29.4112 20.1774 28.9621 19.6077 28.3923C19.0379 27.8226 18.5888 27.1439 18.287 26.3968C17.9852 25.6497 17.8371 24.8494 17.8513 24.0438C17.8655 23.2382 18.0418 22.4437 18.3698 21.7077C18.6977 20.9717 19.1705 20.3093 19.76 19.76M2 2L46 46"
                                    stroke="#1E1E1E" stroke-width="4.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <svg id="eyeOpen" width="24" height="24" viewBox="0 0 48 48" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden">
                                <path d="M2 24C2 24 10 8 24 8C38 8 46 24 46 24C46 24 38 40 24 40C10 40 2 24 2 24Z"
                                    stroke="#fb923c" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M24 30C27.3137 30 30 27.3137 30 24C30 20.6863 27.3137 18 24 18C20.6863 18 18 20.6863 18 24C18 27.3137 20.6863 30 24 30Z"
                                    stroke="#fb923c" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit"
                        class="bg-orange-400 text-white font-bold text-lg rounded-3xl py-4 mt-2 mb-1 transition active:scale-98 active:bg-orange-500 hover:scale-101">Login</button>
                    <a href="{{ route('password.request') }}"
                        class="text-orange-400 text-base underline transition hover:text-orange-500 hover:scale-102 w-fit">
                        Forgot your password?
                    </a>
                </form>
            </div>
        </div>
        <!-- Right Side -->
        <div class="flex-1 rightside bg-[#23222E] flex flex-col items-center justify-center">
            <div class="text-white text-6xl font-regular mb-8">New to Linya?</div>
            <button type="button" id="toSignupBtn"
                class="border-2 border-white text-white text-xl font-bold rounded-full px-12 py-3 transition hover:bg-white hover:text-[#23222E] hover:scale-101 active:scale-98">
                Create Account
            </button>
        </div>
    </div>