{{-- auth/reset_success.blade.php --}}
@extends('layout.auth_split_centered')

@section('title', 'Password Reset Successful')

@section('content')
    <!-- Progress bar -->
    <div class="flex justify-center gap-2 mb-10">
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
    </div>

    <!-- Confirmation Text -->
    <div class="text-4xl font-extrabold text-black mb-4">YEEESSSSS!</div>
    <p class="text-gray-700 text-base mb-8 leading-tight">
        Your account has been successfully updated!<br>
        Please re-login with your new password.
    </p>

    <!-- Proceed Button -->
    <a href="{{ route('login') }}">
        <button class="bg-orange-400 text-white font-semibold rounded-full px-10 py-3 text-sm transition hover:bg-orange-500 active:scale-98">
            Proceed
        </button>
    </a>
@endsection
