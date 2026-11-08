<x-app-layout title="Transparency Wall Management">
    @section('page-title', 'Transparency Wall Management')

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-xl sm:text-3xl font-bold text-gray-900">Transparency Wall</h1>
                <p class="mt-1 text-gray-500">Manage all transparency content types</p>
            </div>

            <!-- Content Type Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Memos -->
                <a href="{{ route('admin.transparency.memos.index') }}" 
                   class="material-card p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start justify-between">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <span class="material-icons text-blue-600 text-xl sm:text-2xl">description</span>
                        </div>
                        <span class="material-icons text-gray-400 group-hover:text-blue-600 transition-colors">arrow_forward</span>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Memos</h3>
                    <p class="text-sm text-gray-500 mt-1">Official memorandums and circulars</p>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Memo::count() }} Total
                        </span>
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Memo::published()->count() }} Published
                        </span>
                    </div>
                </a>

                <!-- Resolutions -->
                <a href="{{ route('admin.transparency.resolutions.index') }}" 
                   class="material-card p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start justify-between">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                            <span class="material-icons text-purple-600 text-xl sm:text-2xl">gavel</span>
                        </div>
                        <span class="material-icons text-gray-400 group-hover:text-purple-600 transition-colors">arrow_forward</span>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Resolutions</h3>
                    <p class="text-sm text-gray-500 mt-1">Board decisions and approvals</p>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Resolution::count() }} Total
                        </span>
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Resolution::published()->count() }} Published
                        </span>
                    </div>
                </a>

                <!-- Collections -->
                <a href="{{ route('admin.transparency.collections.index') }}" 
                   class="material-card p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start justify-between">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                            <span class="material-icons text-green-600 text-xl sm:text-2xl">payments</span>
                        </div>
                        <span class="material-icons text-gray-400 group-hover:text-green-600 transition-colors">arrow_forward</span>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Collections</h3>
                    <p class="text-sm text-gray-500 mt-1">Financial collection reports</p>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Collection::count() }} Total
                        </span>
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Collection::published()->count() }} Published
                        </span>
                    </div>
                </a>

                <!-- Releases -->
                <a href="{{ route('admin.transparency.releases.index') }}" 
                   class="material-card p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start justify-between">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                            <span class="material-icons text-orange-600 text-xl sm:text-2xl">campaign</span>
                        </div>
                        <span class="material-icons text-gray-400 group-hover:text-orange-600 transition-colors">arrow_forward</span>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Releases</h3>
                    <p class="text-sm text-gray-500 mt-1">Press releases & statements</p>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Release::count() }} Total
                        </span>
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ \App\Models\Release::published()->count() }} Published
                        </span>
                    </div>
                </a>

                <!-- Training Announcements -->
                <a href="{{ route('admin.transparency.trainings.index') }}" 
                   class="material-card p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start justify-between">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                            <span class="material-icons text-indigo-600 text-xl sm:text-2xl">school</span>
                        </div>
                        <span class="material-icons text-gray-400 group-hover:text-indigo-600 transition-colors">arrow_forward</span>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Trainings/Seminars</h3>
                    <p class="text-sm text-gray-500 mt-1">Training announcements & events</p>
                    <div class="mt-4 flex items-center text-sm">
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium">
                            {{ \App\Models\TrainingAnnouncement::count() }} Total
                        </span>
                        <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                            {{ \App\Models\TrainingAnnouncement::where('status', 'Open for Registration')->count() }} Open
                        </span>
                    </div>
                </a>

                <!-- Quick Actions -->
                <div class="material-card p-6 bg-gradient-to-br from-indigo-600 to-purple-700 text-white">
                    <div class="flex items-start justify-between">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl bg-white/20 flex items-center justify-center">
                            <span class="material-icons text-white text-xl sm:text-2xl">add_circle</span>
                        </div>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold">Quick Create</h3>
                    <p class="text-sm text-indigo-100 mt-1">Create new content</p>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('admin.transparency.memos.create') }}" 
                           class="block py-1 text-sm hover:text-indigo-200 transition-colors">
                            <span class="material-icons text-xs mr-1">add</span> New Memo
                        </a>
                        <a href="{{ route('admin.transparency.resolutions.create') }}" 
                           class="block py-1 text-sm hover:text-indigo-200 transition-colors">
                            <span class="material-icons text-xs mr-1">add</span> New Resolution
                        </a>
                        <a href="{{ route('admin.transparency.collections.create') }}" 
                           class="block py-1 text-sm hover:text-indigo-200 transition-colors">
                            <span class="material-icons text-xs mr-1">add</span> New Collection Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="material-card p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
                <div class="space-y-4">
                    @php
                        $recentMemos = \App\Models\Memo::latest()->take(3)->get();
                        $recentResolutions = \App\Models\Resolution::latest()->take(3)->get();
                    @endphp

                    @forelse($recentMemos as $memo)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <span class="material-icons text-blue-600">description</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $memo->title }}</p>
                                <p class="text-xs text-gray-500">Memo {{ $memo->memo_number }} • {{ $memo->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $memo->status === 'Published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $memo->status }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No memos yet.</p>
                    @endforelse

                    @foreach($recentResolutions as $resolution)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                <span class="material-icons text-purple-600">gavel</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $resolution->title }}</p>
                                <p class="text-xs text-gray-500">{{ $resolution->resolution_number }} • {{ $resolution->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $resolution->status === 'Published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $resolution->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
