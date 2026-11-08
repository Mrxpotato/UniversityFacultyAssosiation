<x-app-layout title="Resolutions">
    @section('page-title', 'Resolutions')

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <nav class="flex items-center text-sm text-gray-500 mb-2">
                        <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                        <span class="material-icons text-sm mx-1">chevron_right</span>
                        <span class="text-gray-900">Resolutions</span>
                    </nav>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Resolutions Management</h1>
                </div>
                <a href="{{ route('admin.transparency.resolutions.create') }}" 
                   class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <span class="material-icons mr-2">add</span>
                    New Resolution
                </a>
            </div>

            <!-- Filters -->
            <div class="material-card p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search resolutions..." 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <select name="status" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <span class="material-icons text-sm">filter_list</span> Filter
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                    <span class="material-icons text-green-600 mr-2">check_circle</span>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Table -->
            <div class="material-card overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolution</th>
                            <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolved Date</th>
                            <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Votes</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($resolutions as $resolution)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <span class="material-icons text-purple-600">gavel</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($resolution->title, 50) }}</div>
                                            <div class="text-sm text-gray-500">{{ $resolution->resolution_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $resolution->resolved_date->format('M d, Y') }}
                                </td>
                                <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <span class="text-green-600">{{ $resolution->votes_for }} For</span> /
                                        <span class="text-red-600">{{ $resolution->votes_against }} Against</span> /
                                        <span class="text-gray-500">{{ $resolution->votes_abstain }} Abstain</span>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $resolution->approval_percentage }}% Approval</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $sColor = ['Draft' => 'gray', 'Published' => 'green', 'Archived' => 'yellow'][$resolution->status] ?? 'gray'; @endphp
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $sColor }}-100 text-{{ $sColor }}-800">
                                        {{ $resolution->status }}
                                    </span>
                                </td>
                                <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($resolution->views_count) }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex flex-wrap items-center justify-end gap-1 sm:gap-2">
                                        <a href="{{ route('admin.transparency.resolutions.show', $resolution) }}" class="text-gray-400 hover:text-indigo-600">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.transparency.resolutions.edit', $resolution) }}" class="text-gray-400 hover:text-indigo-600">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <form action="{{ route('admin.transparency.resolutions.destroy', $resolution) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this resolution?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600">
                                                <span class="material-icons">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <span class="material-icons text-6xl text-gray-300">gavel</span>
                                    <p class="mt-4 text-gray-500">No resolutions found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($resolutions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">{{ $resolutions->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
