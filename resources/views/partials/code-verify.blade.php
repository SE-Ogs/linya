@extends('layout.auth-split-centered')

@section('title', 'Code Verification')

@section('content')
    <!-- Progress Bar (top of page) -->
    <div class="absolute left-0 right-0 top-0 z-10 mb-10 mt-8 flex justify-center gap-2">
        <div class="h-1.5 w-24 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-24 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-24 rounded-full bg-orange-200"></div>
        <div class="h-1.5 w-24 rounded-full bg-orange-200"></div>
    </div>

    <!-- Back Button (top left) -->
    <div class="absolute left-8 top-8">
        <a href="{{ url('/forgot-password') }}">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#23222E]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="36"
                     height="36"
                     fill="none"
                     viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"
                          stroke="#fff"
                          stroke-width="2.5"
                          stroke-linecap="round"
                          stroke-linejoin="round" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col items-center pt-32">
        <div class="mb-4 text-center text-4xl font-extrabold text-black">Almost there!</div>

        @if (session('success'))
            <div class="mb-2 font-medium text-green-600">{{ session('success') }}</div>
        @endif

        <p class="mb-3 max-w-md text-center text-lg text-black">
            We sent a confirmation code to
            <span class="font-semibold">{{ session('email') }}</span>. Make sure to check your
            <span class="text-orange-400">spam</span> folder.
        </p>

        <form method="POST"
              action="{{ url('/code-verify') }}"
              class="flex w-full flex-col items-center gap-4">
            @csrf

            <input type="text"
                   name="code"
                   placeholder="XXXX-XXXX"
                   class="w-full max-w-md rounded-2xl bg-[#E6E5E1] px-6 py-4 text-center text-base font-normal placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400"
                   required>

            @if ($errors->has('code'))
                <span class="text-base font-medium text-red-500">{{ $errors->first('code') }}</span>
            @endif

            <button type="submit"
                    class="active:scale-98 hover:scale-101 w-40 rounded-2xl bg-orange-400 py-3 text-lg font-bold text-white transition active:bg-orange-500">
                Verify
            </button>
        </form>

        <!-- Optional: Resend -->
        <form method="POST"
              action="{{ url('/code-resend') }}"
              class="mt-4">
            @csrf
            <button type="submit"
                    class="text-sm text-black/70 underline hover:text-black">
                Resend code
            </button>
        </form>
    </div>
@endsection
