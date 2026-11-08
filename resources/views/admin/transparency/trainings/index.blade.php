<x-app-layout title="Transparency Trainings">
    @section('page-title', 'Training & Seminars')

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <nav class="flex items-center text-sm text-gray-500 mb-2">
                        <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                        <span class="material-icons text-sm mx-1">chevron_right</span>
                        <span class="text-gray-900">Training & Seminars</span>
                    </nav>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Training & Seminars</h1>
                </div>
                <a href="{{ route('admin.transparency.trainings.create') }}" 
                   class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <span class="material-icons mr-2">add</span> New Training
                </a>
            </div>

            <!-- Filters -->
            <div class="material-card p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trainings..." 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <select name="training_type" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        @foreach($trainingTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('training_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="mode" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Modes</option>
                        @foreach($modes as $key => $label)
                            <option value="{{ $key }}" {{ request('mode') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Filter</button>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                    <span class="material-icons text-green-600 mr-2">check_circle</span>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Training Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($trainings as $training)
                    <div class="material-card overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center">
                                    <span class="material-icons text-teal-600">{{ \App\Models\TrainingAnnouncement::TRAINING_TYPE_ICONS[$training->training_type] ?? 'school' }}</span>
                                </div>
                                @php $sColor = \App\Models\TrainingAnnouncement::STATUS_COLORS[$training->status] ?? 'gray'; @endphp
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $sColor }}-100 text-{{ $sColor }}-800">{{ $training->status }}</span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $training->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $training->training_number }}</p>
                            
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-400 mr-2 text-base">event</span>
                                    {{ $training->date_start->format('M d, Y') }}
                                    @if($training->date_end && $training->date_end != $training->date_start)
                                        - {{ $training->date_end->format('M d, Y') }}
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-400 mr-2 text-base">{{ $training->mode == 'Online' ? 'videocam' : ($training->mode == 'Hybrid' ? 'sync_alt' : 'location_on') }}</span>
                                    {{ $training->mode }} {{ $training->mode != 'Online' ? '- ' . Str::limit($training->venue, 20) : '' }}
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons text-gray-400 mr-2 text-base">person</span>
                                    {{ $training->speaker_facilitator }}
                                </div>
                            </div>

                            @if($training->registration_deadline)
                                <div class="mt-4 p-3 bg-amber-50 rounded-lg">
                                    <div class="flex items-center text-sm text-amber-700">
                                        <span class="material-icons mr-1 text-base">schedule</span>
                                        Registration until {{ $training->registration_deadline->format('M d, Y') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="material-icons mr-1 text-base">group</span>
                                {{ $training->registrations_count ?? 0 }}/{{ $training->max_participants ?? '∞' }}
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.transparency.trainings.show', $training) }}" class="text-gray-400 hover:text-indigo-600">
                                    <span class="material-icons">visibility</span>
                                </a>
                                <a href="{{ route('admin.transparency.trainings.edit', $training) }}" class="text-gray-400 hover:text-indigo-600">
                                    <span class="material-icons">edit</span>
                                </a>
                                <form action="{{ route('admin.transparency.trainings.destroy', $training) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this training?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600"><span class="material-icons">delete</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <span class="material-icons text-6xl text-gray-300">school</span>
                        <p class="mt-4 text-gray-500">No trainings found.</p>
                    </div>
                @endforelse
            </div>

            @if($trainings->hasPages())
                <div class="mt-6">{{ $trainings->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
