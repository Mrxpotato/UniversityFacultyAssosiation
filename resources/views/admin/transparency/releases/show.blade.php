<x-app-layout title="Release Details">
    @section('page-title', $release->title)

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.releases.index') }}" class="hover:text-indigo-600">Releases</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">{{ $release->release_number }}</span>
                </nav>
                
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $release->title }}</h1>
                        <p class="text-gray-500 mt-1">{{ $release->release_number }}</p>
                    </div>
                    <a href="{{ route('admin.transparency.releases.edit', $release) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <span class="material-icons mr-1">edit</span> Edit
                    </a>
                </div>
            </div>

            <!-- Meta -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <div class="flex flex-wrap gap-4">
                    @php $sColor = ['Draft' => 'gray', 'Published' => 'green', 'Archived' => 'yellow'][$release->status] ?? 'gray'; @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $sColor }}-100 text-{{ $sColor }}-800">{{ $release->status }}</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">{{ $release->release_type }}</span>
                </div>
            </div>

            <!-- Contact -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Contact Person</p>
                        <p class="font-medium text-gray-900">{{ $release->contact_person }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Email</p>
                        <p class="font-medium text-gray-900">{{ $release->contact_email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Phone</p>
                        <p class="font-medium text-gray-900">{{ $release->contact_phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="material-card p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Content</h2>
                <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ $release->content }}</div>
            </div>

            <!-- Attachments -->
            @if($release->attachments && count($release->attachments) > 0)
                <div class="material-card p-4 sm:p-6 mb-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="space-y-2">
                        @foreach($release->attachments as $attachment)
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
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($release->views_count) }}</p>
                        <p class="text-xs text-gray-500">Views</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <span class="material-icons text-2xl text-purple-500">calendar_today</span>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $release->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">Created</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <span class="material-icons text-2xl text-green-500">publish</span>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $release->published_at?->format('M d, Y') ?? 'Not published' }}</p>
                        <p class="text-xs text-gray-500">Published</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
