<x-public-layout title="Trainings & Seminars">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <span class="material-icons text-4xl sm:text-6xl text-primary-300">event</span>
            <h1 class="mt-4 text-2xl sm:text-4xl font-bold">Trainings & Seminars</h1>
            <p class="mt-2 text-base sm:text-xl text-primary-200">Professional development opportunities for faculty members</p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- View Toggle -->
        <div class="mb-8 flex flex-col sm:flex-row flex-wrap items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2 sm:space-x-4">
                <a href="{{ route('trainings.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg transition-colors {{ !request('view') || request('view') == 'list' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <span class="material-icons mr-2">list</span>
                    List View
                </a>
                <a href="{{ route('trainings.calendar') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg transition-colors {{ request('view') == 'calendar' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <span class="material-icons mr-2">calendar_month</span>
                    Calendar View
                </a>
            </div>
            <form method="GET" action="{{ route('trainings.index') }}" class="flex gap-4 w-full sm:w-auto">
                <select name="status" aria-label="Filter by status" onchange="this.form.submit()" class="w-full sm:w-auto rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Trainings</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </form>
        </div>

        <!-- Trainings Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($trainings ?? [] as $training)
                <div class="bg-white rounded-xl material-shadow overflow-hidden hover:shadow-lg transition-shadow">
                    @if($training->image)
                        <img src="{{ Storage::url($training->image) }}" alt="{{ $training->title }}" class="w-full h-36 sm:h-48 object-cover">
                    @else
                        <div class="w-full h-36 sm:h-48 bg-gradient-to-br from-purple-500 to-primary-600 flex items-center justify-center">
                            <span class="material-icons text-4xl sm:text-6xl text-white/50">school</span>
                        </div>
                    @endif
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($training->start_date > now()) bg-green-100 text-green-800
                                @elseif($training->end_date < now()) bg-gray-100 text-gray-800
                                @else bg-primary-100 text-primary-800
                                @endif">
                                @if($training->start_date > now())
                                    Upcoming
                                @elseif($training->end_date < now())
                                    Completed
                                @else
                                    Ongoing
                                @endif
                            </span>
                            @if($training->is_free)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Free
                                </span>
                            @else
                                <span class="text-sm font-semibold text-gray-900">₱{{ number_format($training->fee ?? 0, 2) }}</span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 line-clamp-2">{{ $training->title }}</h3>
                        
                        <div class="mt-3 space-y-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <span class="material-icons text-sm mr-2">calendar_today</span>
                                {{ $training->start_date->format('M d, Y') }}
                                @if($training->end_date && $training->end_date->ne($training->start_date))
                                    - {{ $training->end_date->format('M d, Y') }}
                                @endif
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons text-sm mr-2">schedule</span>
                                {{ $training->start_time ?? 'TBA' }}
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons text-sm mr-2">location_on</span>
                                {{ $training->venue ?? 'TBA' }}
                            </div>
                            @if($training->max_participants)
                                <div class="flex items-center">
                                    <span class="material-icons text-sm mr-2">people</span>
                                    {{ $training->registrations_count ?? 0 }} / {{ $training->max_participants }} slots
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <a href="{{ route('trainings.show', $training) }}" 
                               class="text-primary-600 hover:text-primary-800 font-medium flex items-center">
                                View Details
                                <span class="material-icons text-sm ml-1">arrow_forward</span>
                            </a>
                            @auth
                                @if($training->start_date > now() && \App\Models\SystemSetting::areProcessesEnabled())
                                    <form method="POST" action="{{ route('trainings.register', $training) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition-colors">
                                            <span class="material-icons text-sm mr-1">how_to_reg</span>
                                            Register
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl material-shadow p-12 text-center">
                        <span class="material-icons text-6xl text-gray-300">event_busy</span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Trainings Scheduled</h3>
                        <p class="mt-2 text-gray-500">Check back later for upcoming training opportunities</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($trainings) && $trainings->hasPages())
            <div class="mt-8">
                {{ $trainings->links() }}
            </div>
        @endif
    </div>
</x-public-layout>
