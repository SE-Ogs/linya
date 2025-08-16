<!-- Enhanced Users Table -->
<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-orange-50 to-red-50 border-b border-orange-100">
                <tr>
                    <th class="px-6 py-4 text-left">
                        <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" />
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Records</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Join Date</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $user)
                    <tr class="user-card-hover {{ $loop->even ? 'bg-gray-50/50' : 'bg-white' }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" />
                        </td>

                        <!-- User Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <img src="{{ $user->avatar }}" alt="Avatar"
                                         class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-200">
                                    @if($user->status === 'Active')
                                        <div class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full bg-green-500 border-2 border-white"></div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' :
                                   ($user->role === 'writer' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                @if($user->role === 'admin')
                                    <i class="fas fa-crown"></i>
                                @elseif($user->role === 'writer')
                                    <i class="fas fa-pen"></i>
                                @else
                                    <i class="fas fa-user"></i>
                                @endif
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="status-badge
                                {{ $user->status === 'Active' ? 'bg-green-100 text-green-800' :
                                   ($user->status === 'Suspended' ? 'bg-yellow-100 text-yellow-800' :
                                   ($user->status === 'Banned' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                @if($user->status === 'Active')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($user->status === 'Suspended')
                                    <i class="fas fa-pause-circle mr-1"></i>
                                @elseif($user->status === 'Banned')
                                    <i class="fas fa-ban mr-1"></i>
                                @endif
                                {{ $user->status }}
                            </span>
                        </td>

                        <!-- Records Indicators -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <!-- Reports Count -->
                                <div class="record-indicator" data-tooltip="View Reports"
                                     onclick="openReportsModal({{ $user->id }})">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs
                                        {{ ($user->comment_reports_count ?? 0) > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600' }}">
                                        <i class="fas fa-flag"></i>
                                        {{ $user->comment_reports_count ?? 0 }}
                                    </span>
                                </div>

                                <!-- Bans Count -->
                                <div class="record-indicator" data-tooltip="View Ban History"
                                     onclick="openBanHistoryModal({{ $user->id }})">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs
                                        {{ ($user->user_bans_count ?? 0) > 0 ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-600' }}">
                                        <i class="fas fa-gavel"></i>
                                        {{ $user->user_bans_count ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Join Date -->
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit Button -->
                                <button type="button"
                                        onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')"
                                        class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>

                                <!-- Ban/Unban Button -->
                                @if($user->status === 'Banned')
                                    <button type="button"
                                            onclick="openUnbanModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                            class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                                        <i class="fas fa-unlock"></i>
                                        Unban
                                    </button>
                                @else
                                    <button type="button"
                                            onclick="openBanModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                            class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                                        <i class="fas fa-ban"></i>
                                        Ban
                                    </button>
                                @endif

                                <!-- Role Dropdown -->
                                <form action="{{ route('admin.users.set-role', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()"
                                            class="border border-gray-200 rounded-lg px-2 py-1 text-xs focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="writer" {{ $user->role === 'writer' ? 'selected' : '' }}>Writer</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="action-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-lg">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Empty State -->
    @if($users->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
            <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
        </div>
    @endif
</div>


