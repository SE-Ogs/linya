{{-- resources/views/auth/verify_code.blade.php --}}
@extends('layout.auth_split_centered')

@section('title', 'Verify Code')

@section('content')
    <!-- Progress bar -->
    <div class="flex justify-center gap-2 mb-10">
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-200 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-200 rounded-full"></div>
    </div>

    <!-- Title and Instructions -->
    <div class="text-4xl font-extrabold text-black mb-4 text-center">Almost there!</div>
    <p class="text-gray-700 text-base mb-4 leading-tight text-center">
        Check your inbox, we’ve sent you a confirmation code!<br>
        Make sure to check your <span class="text-orange-500 font-semibold">spam</span> folder.
    </p>

    @if(session('error'))
        <div class="text-red-500 text-sm text-center mb-2">{{ session('error') }}</div>
    @endif

    <!-- Verification Code Form -->
    <form method="POST" action="{{ route('verify.reset.code') }}" class="flex flex-col gap-6 max-w-md mx-auto w-full">
        @csrf
        <input type="text" name="verification_code" placeholder="XXXX-XXXX"
            class="rounded-full bg-gray-200 px-6 py-4 text-sm text-center tracking-widest placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400" required>

        <button type="submit"
            class="bg-orange-400 text-white font-semibold rounded-full px-10 py-3 text-sm transition hover:bg-orange-500 active:scale-98">
            Verify
        </button>
    </form>
@endsection
