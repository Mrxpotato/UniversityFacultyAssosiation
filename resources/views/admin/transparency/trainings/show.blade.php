<x-app-layout title="Transparency Training Details">
    @section('page-title', $training->title)

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.trainings.index') }}" class="hover:text-indigo-600">Trainings</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">{{ $training->training_number }}</span>
                </nav>
                
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $training->title }}</h1>
                        <p class="text-gray-500 mt-1">{{ $training->training_number }}</p>
                    </div>
                    <a href="{{ route('admin.transparency.trainings.edit', $training) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <span class="material-icons mr-1">edit</span> Edit
                    </a>
                </div>
            </div>

            <!-- Status & Type Badges -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <div class="flex flex-wrap gap-4">
                    @php $sColor = \App\Models\TrainingAnnouncement::STATUS_COLORS[$training->status] ?? 'gray'; @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $sColor }}-100 text-{{ $sColor }}-800">{{ $training->status }}</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">{{ $training->training_type }}</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">{{ $training->mode }}</span>
                    @if($training->registration_fee > 0)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">₱{{ number_format($training->registration_fee, 2) }}</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">FREE</span>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
                <div class="material-card p-4 text-center">
                    <span class="material-icons text-2xl text-indigo-500">event</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $training->date_start->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">Start Date</p>
                </div>
                <div class="material-card p-4 text-center">
                    <span class="material-icons text-2xl text-teal-500">group</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $training->registrations_count ?? 0 }} / {{ $training->max_participants ?? '∞' }}</p>
                    <p class="text-xs text-gray-500">Registrations</p>
                </div>
                <div class="material-card p-4 text-center">
                    <span class="material-icons text-2xl text-blue-500">visibility</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ number_format($training->views_count) }}</p>
                    <p class="text-xs text-gray-500">Views</p>
                </div>
                <div class="material-card p-4 text-center">
                    <span class="material-icons text-2xl text-amber-500">schedule</span>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{ $training->registration_deadline?->format('M d') ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">Reg. Deadline</p>
                </div>
            </div>

            <!-- Schedule & Venue -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Schedule & Venue</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div class="flex items-start">
                        <span class="material-icons text-gray-400 mr-3">event</span>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium text-gray-900">
                                {{ $training->date_start->format('F d, Y') }}
                                @if($training->date_end && $training->date_end != $training->date_start)
                                    - {{ $training->date_end->format('F d, Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <span class="material-icons text-gray-400 mr-3">{{ $training->mode == 'Online' ? 'videocam' : 'location_on' }}</span>
                        <div>
                            <p class="text-sm text-gray-500">Venue ({{ $training->mode }})</p>
                            <p class="font-medium text-gray-900">{{ $training->venue ?? 'TBA' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Speaker -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Speaker</h2>
                <div class="flex items-start">
                    <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center mr-4">
                        <span class="material-icons text-2xl text-teal-600">person</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $training->speaker_facilitator }}</p>
                        @if($training->target_participants)
                            <p class="text-sm text-gray-500">Target: {{ $training->target_participants }}</p>
                        @endif
                        @if($training->speaker_bio)
                            <p class="mt-2 text-sm text-gray-600">{{ $training->speaker_bio }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Description</h2>
                <div class="prose max-w-none text-gray-700">{{ $training->content }}</div>
            </div>

            <!-- Learning Objectives -->
            @if($training->learning_objectives && count($training->learning_objectives) > 0)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Learning Objectives</h2>
                    <ul class="space-y-2">
                        @foreach($training->learning_objectives as $objective)
                            <li class="flex items-start">
                                <span class="material-icons text-green-500 mr-2 text-base mt-0.5">check_circle</span>
                                <span class="text-gray-700">{{ $objective }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Requirements -->
            @if($training->requirements)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Requirements</h2>
                    <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ $training->requirements }}</div>
                </div>
            @endif

            <!-- Contact Information -->
            @if($training->contact_person || $training->contact_details)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                    <div class="flex items-start">
                        <span class="material-icons text-gray-400 mr-3">contact_phone</span>
                        <div>
                            @if($training->contact_person)
                                <p class="font-medium text-gray-900">{{ $training->contact_person }}</p>
                            @endif
                            @if($training->contact_details)
                                <p class="text-sm text-gray-600">{{ $training->contact_details }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Attachments -->
            @if($training->attachments && count($training->attachments) > 0)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="space-y-2">
                        @foreach($training->attachments as $attachment)
                            <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                               class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <span class="material-icons text-gray-400 mr-3">attachment</span>
                                <span class="text-sm text-gray-700 flex-1">{{ $attachment['name'] }}</span>
                                <span class="material-icons text-gray-400">download</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Registrations List -->
            @if(isset($registrations) && $registrations->count() > 0)
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Registrations ({{ $registrations->count() }})</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registrations as $reg)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $reg->user->name ?? $reg->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $reg->user->email ?? $reg->email }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $reg->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $reg->status ?? 'Registered' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
