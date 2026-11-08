<x-app-layout title="Campus Management">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">location_city</span>
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Campus Management') }}
                </h1>
            </div>
            <a href="{{ route('admin.campuses.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm text-sm">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                New Campus
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Campuses</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalCampuses ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-indigo-600">location_city</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Colleges</p>
                            <p class="text-2xl font-bold text-green-600">{{ $totalColleges ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-green-600">school</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Members</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $totalMembers ?? 0 }}</p>
                        </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-purple-600">groups</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campuses Table -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Code</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Address</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Colleges</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Members</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($campuses ?? [] as $campus)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-2 sm:mr-3">
                                                <span class="material-icons-outlined text-indigo-600 text-lg sm:text-2xl">apartment</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 truncate">{{ $campus->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $campus->code }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
                                        <p class="text-gray-600 text-sm">{{ Str::limit($campus->address, 40) }}</p>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="font-medium text-gray-800">{{ $campus->colleges_count ?? 0 }}</span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="font-medium text-gray-800">{{ $campus->faculty_profiles_count ?? 0 }}</span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        @if($campus->is_active ?? true)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-icons text-xs mr-1">check_circle</span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="material-icons text-xs mr-1">pause_circle</span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1 sm:gap-2">
                                            <a href="{{ route('admin.campuses.colleges', $campus) }}" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm" title="Manage Colleges">
                                                <span class="material-icons-outlined text-sm mr-1">school</span>
                                                Colleges
                                            </a>
                                            <a href="{{ route('admin.campuses.edit', $campus) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm" title="Edit">
                                                <span class="material-icons-outlined text-sm mr-1">edit</span>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.campuses.destroy', $campus) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this campus? This will also affect all related data.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm" title="Delete">
                                                    <span class="material-icons-outlined text-sm mr-1">delete</span>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined text-gray-300 mb-2" style="font-size: 48px;">location_city</span>
                                            <p class="text-gray-500">No campuses found</p>
                                            <a href="{{ route('admin.campuses.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                                                Add your first campus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(isset($campuses) && $campuses->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $campuses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
