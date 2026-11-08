<x-app-layout title="Resolution Details">
    @section('page-title', $resolution->title)

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.resolutions.index') }}" class="hover:text-indigo-600">Resolutions</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">{{ $resolution->resolution_number }}</span>
                </nav>
                
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $resolution->title }}</h1>
                        <p class="text-gray-500 mt-1">{{ $resolution->resolution_number }}</p>
                    </div>
                    <a href="{{ route('admin.transparency.resolutions.edit', $resolution) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <span class="material-icons mr-1">edit</span> Edit
                    </a>
                </div>
            </div>

            <!-- Status & Voting -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Status</h2>
                    <div class="flex items-center space-x-4">
                        @php $sColor = ['Draft' => 'gray', 'Published' => 'green', 'Archived' => 'yellow'][$resolution->status] ?? 'gray'; @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $sColor }}-100 text-{{ $sColor }}-800">{{ $resolution->status }}</span>
                        <span class="text-sm text-gray-500">Resolved: {{ $resolution->resolved_date->format('F d, Y') }}</span>
                    </div>
                </div>
                
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Voting Results</h2>
                    <div class="flex items-center justify-between">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $resolution->votes_for }}</p>
                            <p class="text-xs text-gray-500">FOR</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-red-600">{{ $resolution->votes_against }}</p>
                            <p class="text-xs text-gray-500">AGAINST</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-500">{{ $resolution->votes_abstain }}</p>
                            <p class="text-xs text-gray-500">ABSTAIN</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $resolution->approval_percentage }}%</p>
                            <p class="text-xs text-gray-500">APPROVAL</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Resolution Content</h2>
                <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ $resolution->content }}</div>
            </div>

            <!-- Attachments -->
            @if($resolution->attachments && count($resolution->attachments) > 0)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="space-y-2">
                        @foreach($resolution->attachments as $attachment)
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

            <!-- Analytics -->
            <div class="material-card p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Analytics</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <span class="material-icons text-2xl text-blue-500">visibility</span>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($resolution->views_count) }}</p>
                        <p class="text-xs text-gray-500">Views</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <span class="material-icons text-2xl text-purple-500">calendar_today</span>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $resolution->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">Created</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <span class="material-icons text-2xl text-green-500">publish</span>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $resolution->published_at?->format('M d, Y') ?? 'Not published' }}</p>
                        <p class="text-xs text-gray-500">Published</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
