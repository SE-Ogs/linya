<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token"
              content="{{ csrf_token() }}">
        <title>Linya - Account Settings</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="overflow-x-hidden bg-[#EDEEFC]">
        <meta name="csrf-token"
              content="{{ csrf_token() }}">
        <div class="flex">
            <!-- Sidebar placeholder -->
            <aside class="fixed left-0 top-0 z-50">
                @include('partials.sidebar')
            </aside>
            <!-- Main content -->
            <div class="ml-95 mt-5 flex-1 p-6">
                <h4 class="mb-4 text-[30px] font-semibold">Account Settings</h4>
                <div class="h-min w-[1010px] rounded-lg bg-white p-8 shadow-sm">
                    <!-- Profile Section -->
                    <div class="mb-8 flex items-start gap-8">
                        <!-- Profile Image -->
                        <div class="flex flex-col items-center">
                            <div class="mb-4 h-32 w-32 overflow-hidden rounded-full bg-gray-300">
                                <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                     alt="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 128 128'%3E%3Ccircle cx='64' cy='64' r='64' fill='%23d1d5db'/%3E%3Cpath d='M64 32c-8.8 0-16 7.2-16 16s7.2 16 16 16 16-7.2 16-16-7.2-16-16-16zM64 96c-13.3 0-24-10.7-24-24v-8c0-8.8 7.2-16 16-16h16c8.8 0 16 7.2 16 16v8c0 13.3-10.7 24-24 24z' fill='%23374151'/%3E%3C/svg%3E"
                                     class="h-full w-full object-cover"
                                     id="profile-picture-preview">
                            </div>
                            <!-- Avatar Upload Form -->
                            <form id="profile-picture-form"
                                  action="{{ route('settings.update') }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden"
                                       name="form_type"
                                       value="avatar">
                                <input type="file"
                                       id="profile-picture-input"
                                       name="avatar"
                                       accept="image/*"
                                       class="hidden">
                            </form>
                            <!-- Styled button that triggers the file input -->
                            <button type="button"
                                    onclick="document.getElementById('profile-picture-input').click()"
                                    class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-600">
                                Upload New Picture
                            </button>
                        </div>
                        <!-- Profile Form -->
                        <div class="flex-1">
                            <form method="POST"
                                  action="{{ route('settings.update') }}">
                                @csrf
                                <input type="hidden"
                                       name="form_type"
                                       value="profile_name">
                                <div class="mb-6 gap-6">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">Display Name</label>
                                        <input type="text"
                                               name="name"
                                               value="{{ auth()->user()->name }}"
                                               class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 transition-colors focus:border-indigo-500 focus:outline-none">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="mb-2 block text-sm font-medium text-gray-700">Username</label>
                                        <div class="flex h-12 items-center px-4 text-gray-700">
                                            {{ auth()->user()->username }}
                                        </div>
                                    </div>
                                    <div class="flex items-end justify-end">
                                        <button type="submit"
                                                class="h-12 w-24 rounded-lg bg-indigo-600 px-4 font-medium text-white transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Section Divider -->
                    <div class="my-8 h-px bg-gray-200"></div>
                    <!-- Change Password Section -->
                    <div class="mb-8">
                        <h3 class="mb-6 text-xl font-semibold">Change Password</h3>
                        <form method="POST"
                              action="{{ route('settings.update') }}">
                            @csrf
                            <input type="hidden"
                                   name="form_type"
                                   value="password">
                            <div class="mb-6 max-w-md">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password"
                                       name="current_password"
                                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 transition-colors focus:border-indigo-500 focus:outline-none"
                                       placeholder="">
                            </div>
                            <div class="grid max-w-2xl grid-cols-2 gap-6">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password"
                                           name="password"
                                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 transition-colors focus:border-indigo-500 focus:outline-none"
                                           placeholder="">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Confirm New
                                        Password</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 transition-colors focus:border-indigo-500 focus:outline-none"
                                           placeholder="">
                                </div>
                            </div>
                            <!-- Save Changes Button -->
                            <div class="mt-6 flex justify-end">
                                <button type="submit"
                                        class="rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white transition-colors hover:bg-indigo-700">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Section Divider -->
                    <div class="my-8 h-px bg-gray-200"></div>
                    <!-- Change Email -->
                    <div class="mb-8">
                        <h3 class="mb-6 text-xl font-semibold">Change Email</h3>
                        <form method="POST"
                              action="{{ route('settings.update') }}">
                            @csrf
                            <input type="hidden"
                                   name="form_type"
                                   value="email">
                            <div class="mb-6 max-w-md">
                                <label class="mb-2 block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password"
                                       name="current_password"
                                       class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 transition-colors focus:border-indigo-500 focus:outline-none"
                                       placeholder="">
                            </div>
                            <div class="grid max-w-2xl grid-cols-2 gap-6">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">New Email</label>
                                    <input type="email"
                                           name="email"
                                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 transition-colors focus:border-indigo-500 focus:outline-none"
                                           placeholder="">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Confirm New
                                        Email</label>
                                    <input type="email"
                                           name="email_confirmation"
                                           class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 transition-colors focus:border-indigo-500 focus:outline-none"
                                           placeholder="">
                                </div>
                            </div>
                            <!-- Edit Button -->
                            <div class="mt-6 flex justify-end">
                                <button type="submit"
                                        class="rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white transition-colors hover:bg-indigo-700">
                                    Update Email
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- Divider -->
                    <div class="my-8 h-px bg-gray-200"></div>
                    <!-- Appearance Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="mb-2 text-xl font-semibold">Appearance</h3>
                                <p class="text-sm text-gray-600">Adjust the appearance to reduce glare and give your
                                    eyes a break.</p>
                            </div>
                            <div class="relative">
                                <select
                                        class="appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-8 focus:border-indigo-500 focus:outline-none">
                                    <option>Light</option>
                                    <option>Dark</option>
                                    <option>System</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                    <svg class="h-4 w-4 text-gray-400"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section Divider -->
                    <div class="my-8 h-px bg-gray-200"></div>
                    <!-- Delete Account Button -->
                    <div class="flex justify-end">
                        <button
                                class="rounded-lg bg-red-600 px-6 py-3 font-medium text-white transition-colors hover:bg-red-700">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
            @include('partials.contact-us')
            @include('partials.are-you-sure-modal');
        </div>
        <script>
            document.getElementById('profile-picture-input').addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    const preview = document.getElementById('profile-picture-preview');
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;

                        const formData = new FormData();
                        formData.append('avatar', file);
                        formData.append('form_type', 'avatar');
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                        fetch("{{ route('settings.update') }}", {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showNotification('Profile picture updated!', 'success');
                                } else {
                                    showNotification(data.message || 'Error updating profile', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('An error occurred. Please try again.', 'error');
                            });
                    };

                    reader.readAsDataURL(file);
                }
            });

            function showNotification(message, type) {
                const existing = document.getElementById('upload-notification');
                if (existing) existing.remove();

                const notification = document.createElement('div');
                notification.id = 'upload-notification';
                notification.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg text-white ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                }`;
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => notification.remove(), 3000);
            }
        </script>

    </body>

</html>
