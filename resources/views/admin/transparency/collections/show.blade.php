<x-app-layout title="Collection Details">
    @section('page-title', $collection->title)

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.collections.index') }}" class="hover:text-indigo-600">Collections</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">{{ $collection->report_number }}</span>
                </nav>
                
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $collection->title }}</h1>
                        <p class="text-gray-500 mt-1">{{ $collection->report_number }} • {{ $collection->period_covered }}</p>
                    </div>
                    <a href="{{ route('admin.transparency.collections.edit', $collection) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <span class="material-icons mr-1">edit</span> Edit
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="material-card p-4 sm:p-6 text-center bg-gradient-to-br from-green-500 to-green-600 text-white">
                    <span class="material-icons text-4xl opacity-80">payments</span>
                    <p class="text-2xl sm:text-3xl font-bold mt-2">{{ $collection->formatted_total }}</p>
                    <p class="text-sm opacity-80">Total Collected</p>
                </div>
                <div class="material-card p-4 sm:p-6 text-center">
                    <span class="material-icons text-4xl text-blue-500">date_range</span>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $collection->period_covered }}</p>
                    <p class="text-sm text-gray-500">Period Covered</p>
                </div>
                <div class="material-card p-4 sm:p-6 text-center">
                    <span class="material-icons text-4xl text-purple-500">assessment</span>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $collection->report_type }}</p>
                    <p class="text-sm text-gray-500">Report Type</p>
                </div>
            </div>

            <!-- Content -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Executive Summary</h2>
                <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ $collection->content }}</div>
            </div>

            <!-- Breakdown Data -->
            @if($collection->breakdown_data)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Breakdown Data</h2>
                    <pre class="bg-gray-50 p-4 rounded-lg overflow-x-auto text-sm">{{ json_encode($collection->breakdown_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            <!-- Attachments -->
            @if($collection->attachments && count($collection->attachments) > 0)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="space-y-2">
                        @foreach($collection->attachments as $attachment)
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
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($collection->views_count) }}</p>
                        <p class="text-xs text-gray-500">Views</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <span class="material-icons text-2xl text-purple-500">calendar_today</span>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $collection->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">Created</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <span class="material-icons text-2xl text-green-500">publish</span>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $collection->published_at?->format('M d, Y') ?? 'Not published' }}</p>
                        <p class="text-xs text-gray-500">Published</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
