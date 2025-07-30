@extends('layout.auth_split_centered')

@section('title', 'Reset Password')

@section('content')
<!-- Progress bar -->
<div class="flex justify-center gap-2 mb-10">
    <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
    <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
    <div class="h-1.5 w-16 bg-orange-400 rounded-full"></div>
    <div class="h-1.5 w-16 bg-orange-200 rounded-full"></div>
</div>

<div class="text-4xl font-black mb-2 text-black text-center">Don’t forget next time!</div>
<p class="text-gray-600 mb-8 text-center">Enter below your new credentials.</p>

<!-- Success & Error Messages -->
@if (session('success'))
    <div class="text-green-600 font-semibold text-sm mb-4 text-center">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="text-red-500 text-sm mb-4">
        <ul class="list-disc list-inside text-left">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6 w-full max-w-md mx-auto">
    @csrf

    <div class="relative">
        <input type="password" id="password" name="password" placeholder="New Password"
            class="w-full rounded-2xl bg-[#E6E5E1] px-6 py-4 text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal"
            required>
        <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-4 text-sm text-gray-600 hover:text-orange-500">Show</button>
    </div>

    <div class="relative">
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password"
            class="w-full rounded-2xl bg-[#E6E5E1] px-6 py-4 text-base placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400 font-normal"
            required>
        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-4 text-sm text-gray-600 hover:text-orange-500">Show</button>
    </div>

    <button type="submit"
        class="bg-orange-400 text-white font-bold text-lg rounded-3xl py-3 mt-2 transition active:scale-98 active:bg-orange-500 hover:scale-101">
        Update
    </button>
</form>

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const button = input.nextElementSibling;
        if (input.type === "password") {
            input.type = "text";
            button.textContent = "Hide";
        } else {
            input.type = "password";
            button.textContent = "Show";
        }
    }
</script>
@endsection
