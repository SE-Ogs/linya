{{-- auth/reset_success.blade.php --}}
@extends('layout.auth-split-centered')

@section('title', 'Password Reset Successful')

@section('content')
    <!-- Progress bar -->
    <div class="mb-10 flex justify-center gap-2">
        <div class="h-1.5 w-16 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-16 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-16 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-16 rounded-full bg-orange-400"></div>
    </div>

    <!-- Confirmation Text -->
    <div class="mb-4 text-4xl font-extrabold text-black">YEEESSSSS!</div>
    <p class="mb-8 text-base leading-tight text-gray-700">
        Your account has been successfully updated!<br>
        Please re-login with your new password.
    </p>

    <!-- Proceed Button -->
    <a href="{{ route('login') }}">
        <button
                class="active:scale-98 rounded-full bg-orange-400 px-10 py-3 text-sm font-semibold text-white transition hover:bg-orange-500">
            Proceed
        </button>
    </a>
@endsection
