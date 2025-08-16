<!-- Controls Section -->
<div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <!-- Enhanced Tab Navigation -->
        <div class="flex bg-gray-100 rounded-lg p-1">
            <a href="{{ route('admin.index', ['type' => 'users', 'per_page' => request('per_page', 10)]) }}"
               class="{{ $type === 'users' ? 'tab-gradient text-white shadow-lg' : 'text-gray-700 hover:text-orange-600' }}
                      flex items-center gap-2 px-6 py-2 rounded-md font-medium transition-all duration-200">
                <i class="fas fa-users text-sm"></i>
                <span>Users</span>
                @if($type === 'users')
                    <span class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full text-black">{{ $users->total() }}</span>
                @endif
            </a>

            <a href="{{ route('admin.index', ['type' => 'writers', 'per_page' => request('per_page', 10)]) }}"
               class="{{ $type === 'writers' ? 'tab-gradient text-white shadow-lg' : 'text-gray-700 hover:text-orange-600' }}
                      flex items-center gap-2 px-6 py-2 rounded-md font-medium transition-all duration-200">
                <i class="fas fa-pen-fancy text-sm"></i>
                <span>Writers</span>
                @if($type === 'writers')
                    <span class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full text-black">{{ $users->total() }}</span>
                @endif
            </a>

            <a href="{{ route('admin.index', ['type' => 'admins', 'per_page' => request('per_page', 10)]) }}"
               class="{{ $type === 'admins' ? 'tab-gradient text-white shadow-lg' : 'text-gray-700 hover:text-orange-600' }}
                      flex items-center gap-2 px-6 py-2 rounded-md font-medium transition-all duration-200">
                <i class="fas fa-user-shield text-sm"></i>
                <span>Admins</span>
                @if($type === 'admins')
                    <span class="bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full text-black">{{ $users->total() }}</span>
                @endif
            </a>
        </div>

        <!-- Enhanced Filters -->
        <form method="GET" class="flex items-center gap-4">
            @foreach (request()->except('per_page', 'status') as $key => $value)
                @if (is_array($value))
                    @foreach ($value as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Show:</label>
                <select name="per_page" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @foreach ([5, 10, 15, 20, 50] as $count)
                        <option value="{{ $count }}" {{ request('per_page', 10) == $count ? 'selected' : '' }}>
                            {{ $count }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Status:</label>
                <select name="status" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    @php $statuses = ['All', 'Active', 'Banned']; @endphp
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>


