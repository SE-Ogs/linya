@extends('layout.forgot_password')

@section('title', 'Forgot Password')

@section('content')
    <!-- Progress Bar (top of page) -->
    <div class="flex gap-2 justify-center mt-8 mb-10 absolute left-0 right-0 top-0 z-10">
        <div class="h-1.5 w-24 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-24 bg-orange-200 rounded-full"></div>
        <div class="h-1.5 w-24 bg-orange-200 rounded-full"></div>
        <div class="h-1.5 w-24 bg-orange-200 rounded-full"></div>
    </div>

    <!-- Back Button -->
    <div class="absolute top-8 left-8">
        <a href="{{ url('/login') }}">
            <div class="bg-[#23222E] rounded-full w-16 h-16 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col items-center pt-32">
        <div class="text-4xl font-extrabold mb-4 text-black text-center">Forgot your password?</div>
        <p class="text-lg text-center text-black mb-6 max-w-md">
            Enter the email tied to your account and we’ll send a verification code.
            <span class="text-black/70 block mt-1">Check your <span class="text-orange-400">spam</span> folder too.</span>
        </p>

        @if (session('success'))
            <div class="mb-4 text-green-600 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-red-600 font-medium">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('/forgot-password') }}" class="w-full flex flex-col items-center gap-6">
            @csrf
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="you@example.com"
                class="w-full max-w-md rounded-2xl bg-[#E6E5E1] px-6 py-4 text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal"
                required
                autocomplete="email"
            >

            <button
                type="submit"
                class="w-40 bg-orange-400 text-white font-bold text-lg rounded-2xl py-3 transition active:scale-98 active:bg-orange-500 hover:scale-101">
                Send Code
            </button>
        </form>
    </div>
@endsection
