{{-- resources/views/auth/forgot_password_email.blade.php --}}
@extends('layout.auth_split_centered')

@section('title', 'Reset Password')

@section('content')
    <!-- Progress bar -->
    <div class="flex justify-center gap-2 mb-10">
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-200 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-200 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-200 rounded-full"></div>
    </div>

    <!-- Title and Instructions -->
    <div class="text-4xl font-extrabold text-black mb-4 text-center">Woopsies…</div>
    <p class="text-gray-700 text-base mb-8 leading-tight text-center">
        Provide us the email of the account you want the password to be reset.
    </p>

    <!-- Email Input Form -->
    <form method="POST" action="{{ route('send.reset.code') }}" class="flex flex-col gap-6 max-w-md mx-auto w-full">
        @csrf
        <input type="email" name="email" placeholder="Email"
            class="rounded-full bg-gray-200 px-6 py-4 text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400" required>

        <button type="submit"
            class="bg-orange-400 text-white font-semibold rounded-full px-10 py-3 text-sm transition hover:bg-orange-500 active:scale-98">
            Submit
        </button>
    </form>
@endsection
