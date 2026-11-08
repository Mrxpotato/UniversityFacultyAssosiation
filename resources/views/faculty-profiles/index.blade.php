<x-app-layout title="Faculty Members">
    @section('page-title', 'Faculty Members')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-indigo-600">people</span>
                {{ __('Faculty Members') }}
                @if(Auth::user()->isCampusPresident())
                    <span class="ml-2 text-sm font-normal text-gray-500">— {{ Auth::user()->campus->name ?? 'My Campus' }}</span>
                @endif
            </h1>
            <div class="flex items-center space-x-2 w-full sm:w-auto overflow-x-auto">
                <a href="{{ route('admin.faculty-profiles.archived') }}" 
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors material-shadow-sm text-sm whitespace-nowrap">
                    <span class="material-icons mr-1 sm:mr-2 text-lg">archive</span>
                    <span class="hidden sm:inline">Archived</span>
                </a>
                <a href="{{ route('admin.faculty-profiles.export') }}" 
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors material-shadow-sm text-sm whitespace-nowrap">
                    <span class="material-icons mr-1 sm:mr-2 text-lg">download</span>
                    <span class="hidden sm:inline">Export</span>
                </a>
                <a href="{{ route('admin.faculty-profiles.create') }}" 
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm text-sm whitespace-nowrap">
                    <span class="material-icons mr-1 sm:mr-2 text-lg">person_add</span>
                    <span class="hidden sm:inline">Add New Member</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl material-shadow overflow-hidden">
        <!-- Filters -->
        <div class="px-3 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('admin.faculty-profiles.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                <div class="flex-1 min-w-0 sm:min-w-64">
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-2.5 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by name, employee ID..." 
                               aria-label="Search faculty by name or employee ID"
                               oninput="clearTimeout(window._searchTimer); window._searchTimer = setTimeout(() => this.form.submit(), 500)"
                               class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                @if(!Auth::user()->isCampusPresident())
                <div class="w-full sm:w-auto">
                    <select name="campus_id" aria-label="Filter by campus" onchange="this.form.submit()" class="w-full sm:w-auto rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Campuses</option>
                        @foreach($campuses ?? [] as $campus)
                            <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="w-full sm:w-auto">
                    <select name="status" aria-label="Filter by status" onchange="this.form.submit()" class="w-full sm:w-auto rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.faculty-profiles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <span class="material-icons mr-1">clear</span>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Employee ID</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Campus</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Rank</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Member Since</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($facultyProfiles ?? [] as $profile)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-medium text-sm sm:text-base">
                                        {{ strtoupper(substr($profile->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="ml-2 sm:ml-3 min-w-0">
                                        <p class="font-medium text-gray-900 text-sm sm:text-base truncate">{{ $profile->display_name ?? 'Unknown' }}</p>
                                        <p class="text-xs sm:text-sm text-gray-500 md:hidden">{{ $profile->employee_id ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                {{ $profile->employee_id }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                {{ $profile->campus->name ?? 'N/A' }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                {{ $profile->academicRank->name ?? 'N/A' }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                @php
                                    $memberBadge = match($profile->membership_status) {
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-amber-100 text-amber-800',
                                    };
                                @endphp
                                @if($profile->user && !$profile->user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="material-icons text-xs mr-0.5">block</span>
                                        Deactivated
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $memberBadge }}">
                                        {{ ucfirst($profile->membership_status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                {{ $profile->membership_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.faculty-profiles.show', $profile) }}" 
                                       class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                       title="View">
                                        <span class="material-icons">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.faculty-profiles.edit', $profile) }}" 
                                       class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                       title="Edit">
                                        <span class="material-icons">edit</span>
                                    </a>
                                    @if($profile->user && $profile->user->is_active)
                                        <form method="POST" action="{{ route('admin.faculty-profiles.deactivate', $profile) }}" 
                                              onsubmit="return confirm('Are you sure you want to deactivate this member\'s account? They will no longer be able to log in.')" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Deactivate Account">
                                                <span class="material-icons">person_off</span>
                                            </button>
                                        </form>
                                    @elseif($profile->user && !$profile->user->is_active)
                                        <form method="POST" action="{{ route('admin.faculty-profiles.reactivate', $profile) }}" 
                                              onsubmit="return confirm('Reactivate this member\'s account? They will be able to log in again.')" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Reactivate Account">
                                                <span class="material-icons">person_add</span>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.faculty-profiles.archive', $profile) }}" 
                                          onsubmit="return confirm('Are you sure you want to archive this member? They can be restored later.')" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                                title="Archive">
                                            <span class="material-icons">archive</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <span class="material-icons text-5xl text-gray-300">people_outline</span>
                                <p class="mt-2 text-gray-500">No faculty members found</p>
                                <a href="{{ route('admin.faculty-profiles.create') }}" class="inline-flex items-center mt-3 text-indigo-600 hover:text-indigo-800">
                                    <span class="material-icons mr-1">add</span>
                                    Add First Member
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($facultyProfiles) && $facultyProfiles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $facultyProfiles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
