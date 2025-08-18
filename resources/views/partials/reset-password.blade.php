@extends('layout.auth-split-centered')

@section('title', 'Reset Password')

@section('content')
    <!-- Progress bar -->
    <div class="mb-10 flex justify-center gap-2">
        <div class="h-1.5 w-16 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-16 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-16 rounded-full bg-orange-400"></div>
        <div class="h-1.5 w-16 rounded-full bg-orange-200"></div>
    </div>

    <div class="mb-2 text-center text-4xl font-black text-black">Don’t forget next time!</div>
    <p class="mb-8 text-center text-gray-600">Enter below your new credentials.</p>

    <!-- Success & Error Messages -->
    @if (session('success'))
        <div class="mb-4 text-center text-sm font-semibold text-green-600">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-500">
            <ul class="list-inside list-disc text-left">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('password.update') }}"
          class="mx-auto flex w-full max-w-md flex-col gap-6">
        @csrf

        <div class="relative">
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="New Password"
                   class="w-full rounded-2xl bg-[#E6E5E1] px-6 py-4 text-base font-normal placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400"
                   required>
            <button type="button"
                    onclick="togglePassword('password')"
                    class="absolute right-4 top-4 text-sm text-gray-600 hover:text-orange-500">Show</button>
        </div>

        <div class="relative">
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   placeholder="Confirm New Password"
                   class="w-full rounded-2xl bg-[#E6E5E1] px-6 py-4 text-base font-normal placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-400"
                   required>
            <button type="button"
                    onclick="togglePassword('password_confirmation')"
                    class="absolute right-4 top-4 text-sm text-gray-600 hover:text-orange-500">Show</button>
        </div>

        <button type="submit"
                class="active:scale-98 hover:scale-101 mt-2 rounded-3xl bg-orange-400 py-3 text-lg font-bold text-white transition active:bg-orange-500">
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
