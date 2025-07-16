@extends('layout.auth_split_layout')

@section('title', 'Reset Password')

@section('left')
    <!-- Progress bar -->
    <div class="flex gap-2 mb-6">
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
        <div class="h-1.5 w-16 bg-orange-200 rounded-full"></div>
    </div>

    <div class="text-4xl font-black mb-2 text-black">Don’t forget next time!</div>
    <p class="text-gray-600 mb-8">Enter below your new credentials.</p>

    <form method="POST" action="/dev-placeholder" class="flex flex-col gap-6">
        @csrf

        <input type="password" name="password" placeholder="Password"
            class="rounded-2xl bg-[#E6E5E1] px-6 py-4 text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal" required>

        <input type="password" name="password_confirmation" placeholder="Confirm Password"
            class="rounded-2xl bg-[#E6E5E1] px-6 py-4 text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal" required>

        <button type="submit"
            class="bg-orange-400 text-white font-bold text-lg rounded-3xl py-3 mt-2 mb-1 transition active:scale-98 active:bg-orange-500 hover:scale-101">
            Update
        </button>
    </form>
@endsection

@section('right')
    <div class="text-white text-6xl font-regular mb-8">Resetting?</div>
    <a href="{{ route('login') }}">
        <button type="button"
            class="border-2 border-white text-white text-xl font-bold rounded-full px-12 py-3 transition hover:bg-white hover:text-[#23222E] hover:scale-101 active:scale-98">
            Back to Login
        </button>
    </a>
@endsection
