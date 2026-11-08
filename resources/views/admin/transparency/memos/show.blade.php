<x-app-layout title="Memo Details">
    @section('page-title', $memo->title)

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.memos.index') }}" class="hover:text-indigo-600">Memos</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">{{ $memo->memo_number }}</span>
                </nav>
                
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $memo->title }}</h1>
                        <p class="text-gray-500 mt-1">Memo {{ $memo->memo_number }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.transparency.memos.edit', $memo) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <span class="material-icons mr-1">edit</span> Edit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Bar -->
            <div class="material-card p-4 mb-6">
                <div class="flex flex-wrap items-center gap-4">
                    @php
                        $statusColors = ['Draft' => 'gray', 'Published' => 'green', 'Archived' => 'yellow'];
                        $priorityColors = ['Normal' => 'gray', 'High' => 'yellow', 'Urgent' => 'red'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $statusColors[$memo->status] }}-100 text-{{ $statusColors[$memo->status] }}-800">
                        {{ $memo->status }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $priorityColors[$memo->priority] }}-100 text-{{ $priorityColors[$memo->priority] }}-800">
                        {{ $memo->priority }} Priority
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $memo->category }}
                    </span>
                    @if($memo->requires_acknowledgment)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <span class="material-icons text-sm mr-1">check_circle</span>
                            Requires Acknowledgment
                        </span>
                    @endif
                    @if($memo->is_expired)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Expired
                        </span>
                    @endif
                </div>
            </div>

            <!-- Meta Info -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Issued By</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $memo->issued_by }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Effective Date</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $memo->effective_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Expiration Date</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $memo->expiration_date?->format('F d, Y') ?? 'No expiration' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Created By</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $memo->author->name ?? 'System' }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Content</h2>
                <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ $memo->content }}</div>
            </div>

            <!-- Attachments -->
            @if($memo->attachments && count($memo->attachments) > 0)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="space-y-2">
                        @foreach($memo->attachments as $attachment)
                            <a href="{{ Storage::url($attachment['path']) }}" target="_blank"
                               class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <span class="material-icons text-gray-400 mr-3">attachment</span>
                                <span class="text-sm text-gray-700 flex-1">{{ $attachment['name'] }}</span>
                                <span class="text-xs text-gray-500">({{ number_format($attachment['size'] / 1024, 1) }} KB)</span>
                                <span class="material-icons text-gray-400 ml-2">download</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Analytics -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Analytics</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <span class="material-icons text-3xl text-blue-500">visibility</span>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($memo->views_count) }}</p>
                        <p class="text-sm text-gray-500">Total Views</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <span class="material-icons text-3xl text-green-500">check_circle</span>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $memo->acknowledgment_count }}</p>
                        <p class="text-sm text-gray-500">Acknowledgments</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <span class="material-icons text-3xl text-purple-500">calendar_today</span>
                        <p class="text-sm font-medium text-gray-900 mt-2">{{ $memo->created_at->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500">Created</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <span class="material-icons text-3xl text-orange-500">schedule</span>
                        <p class="text-sm font-medium text-gray-900 mt-2">{{ $memo->published_at?->format('M d, Y') ?? 'Not published' }}</p>
                        <p class="text-sm text-gray-500">Published</p>
                    </div>
                </div>
            </div>

            <!-- Acknowledgments List -->
            @if($memo->requires_acknowledgment && $memo->acknowledgedBy->count() > 0)
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Acknowledgments ({{ $memo->acknowledgedBy->count() }})</h2>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($memo->acknowledgedBy as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $user->pivot->acknowledged_at }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
