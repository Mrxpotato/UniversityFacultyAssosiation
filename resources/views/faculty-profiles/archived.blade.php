<x-app-layout title="Archived Faculty">
    @section('page-title', 'Archived Faculty Members')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-amber-600">archive</span>
                {{ __('Archived Faculty Members') }}
            </h1>
            <a href="{{ route('admin.faculty-profiles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors material-shadow-sm">
                <span class="material-icons mr-2">arrow_back</span>
                <span class="hidden sm:inline">Back to Active Members</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl material-shadow overflow-hidden">
        <!-- Filters -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('admin.faculty-profiles.archived') }}" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                <div class="flex-1 min-w-0 sm:min-w-64">
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-2.5 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                               placeholder="Search by name, employee ID..." 
                               oninput="clearTimeout(window._searchTimer); window._searchTimer = setTimeout(() => this.form.submit(), 500)"
                               class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>
                <div>
                    <select name="campus_id" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">All Campuses</option>
                        @foreach($campuses ?? [] as $campus)
                            <option value="{{ $campus->id }}" {{ ($filters['campus_id'] ?? '') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.faculty-profiles.archived') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        <span class="material-icons mr-1">clear</span>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Banner -->
        <div class="px-6 py-3 bg-amber-50 border-b border-amber-200">
            <p class="text-sm text-amber-800 flex items-center">
                <span class="material-icons text-sm mr-2">info</span>
                Archived members are retired or removed faculty. You can restore them to active status at any time.
            </p>
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
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Archived On</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($archivedProfiles ?? [] as $profile)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-400 flex items-center justify-center text-white font-medium flex-shrink-0 text-sm">
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    {{ ucfirst($profile->membership_status) }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                {{ $profile->deleted_at?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form method="POST" action="{{ route('admin.faculty-profiles.restore', $profile->id) }}" 
                                      onsubmit="return confirm('Restore this member to active status?')" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium">
                                        <span class="material-icons text-sm mr-1">restore</span>
                                        Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <span class="material-icons text-5xl text-gray-300">inventory_2</span>
                                <p class="mt-2 text-gray-500">No archived faculty members</p>
                                <a href="{{ route('admin.faculty-profiles.index') }}" class="inline-flex items-center mt-3 text-indigo-600 hover:text-indigo-800">
                                    <span class="material-icons mr-1">arrow_back</span>
                                    Back to Active Members
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($archivedProfiles) && $archivedProfiles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $archivedProfiles->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
