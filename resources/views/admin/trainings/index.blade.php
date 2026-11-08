<x-app-layout title="Trainings">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">school</span>
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Trainings & Seminars Management') }}
                </h1>
            </div>
            <a href="{{ route('admin.trainings.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm text-sm">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                New Training
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 mb-6">
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Total Trainings</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-800">{{ $totalTrainings ?? 0 }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-indigo-600 text-lg sm:text-2xl">school</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Upcoming</p>
                            <p class="text-lg sm:text-2xl font-bold text-blue-600">{{ $upcomingCount ?? 0 }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-blue-600 text-lg sm:text-2xl">event</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Ongoing</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-600">{{ $ongoingCount ?? 0 }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-green-600 text-lg sm:text-2xl">play_circle</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Total Registrations</p>
                            <p class="text-lg sm:text-2xl font-bold text-purple-600">{{ $totalRegistrations ?? 0 }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-purple-600 text-lg sm:text-2xl">groups</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select name="year" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @for($y = date('Y') + 1; $y >= date('Y') - 3; $y--)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trainings..." 
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <span class="material-icons-outlined mr-2 text-sm">filter_list</span>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Trainings Table -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Training</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Date & Time</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Venue</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Registrations</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($trainings ?? [] as $training)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                                <span class="material-icons-outlined text-indigo-600 text-sm sm:text-base">school</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 text-sm sm:text-base truncate">{{ Str::limit($training->title, 40) }}</p>
                                                <p class="text-xs sm:text-sm text-gray-500">{{ $training->speaker ?? 'TBA' }}</p>
                                                <p class="text-xs text-gray-400 sm:hidden">{{ $training->start_date->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $training->start_date->format('M d, Y') }}</p>
                                            <p class="text-sm text-gray-500">{{ $training->start_time }} - {{ $training->end_time }}</p>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
                                        <p class="text-gray-600">{{ Str::limit($training->venue, 30) }}</p>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-800">{{ $training->registrations_count ?? 0 }}</span>
                                            @if($training->max_participants)
                                                <span class="text-gray-400 ml-1">/ {{ $training->max_participants }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        @if($training->status === 'upcoming')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <span class="material-icons text-xs mr-1">event</span>
                                                Upcoming
                                            </span>
                                        @elseif($training->status === 'ongoing')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-icons text-xs mr-1">play_circle</span>
                                                Ongoing
                                            </span>
                                        @elseif($training->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="material-icons text-xs mr-1">check_circle</span>
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="material-icons text-xs mr-1">cancel</span>
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1 sm:gap-2">
                                            <a href="{{ route('admin.trainings.registrations', $training) }}" class="p-2 text-gray-400 hover:text-purple-600 transition-colors" title="View Registrations">
                                                <span class="material-icons-outlined text-sm">groups</span>
                                            </a>
                                            <a href="{{ route('admin.trainings.edit', $training) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Edit">
                                                <span class="material-icons-outlined text-sm">edit</span>
                                            </a>
                                            <form action="{{ route('admin.trainings.destroy', $training) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this training?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                                    <span class="material-icons-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined text-gray-300 mb-2" style="font-size: 48px;">school</span>
                                            <p class="text-gray-500">No trainings found</p>
                                            <a href="{{ route('admin.trainings.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                                                Create your first training
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(isset($trainings) && $trainings->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $trainings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
