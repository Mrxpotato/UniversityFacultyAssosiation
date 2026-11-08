<x-app-layout title="Collections">
    @section('page-title', 'Collections')

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <nav class="flex items-center text-sm text-gray-500 mb-2">
                        <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                        <span class="material-icons text-sm mx-1">chevron_right</span>
                        <span class="text-gray-900">Collections</span>
                    </nav>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Financial Collections Reports</h1>
                </div>
                <a href="{{ route('admin.transparency.collections.create') }}" 
                   class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <span class="material-icons mr-2">add</span> New Report
                </a>
            </div>

            <div class="material-card p-4 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reports..." 
                               oninput="clearTimeout(window._searchTimer); window._searchTimer = setTimeout(() => this.form.submit(), 500)"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <select name="report_type" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        @foreach($reportTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('report_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                    <span class="material-icons text-green-600 mr-2">check_circle</span>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            @endif

            <div class="material-card overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report</th>
                            <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Collected</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($collections as $collection)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                            <span class="material-icons text-green-600">payments</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($collection->title, 40) }}</div>
                                            <div class="text-sm text-gray-500">{{ $collection->report_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">{{ $collection->period_covered }}</td>
                                <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $collection->report_type }}</span>
                                </td>
                                <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    {{ $collection->formatted_total }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $sColor = ['Draft' => 'gray', 'Published' => 'green', 'Archived' => 'yellow'][$collection->status] ?? 'gray'; @endphp
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $sColor }}-100 text-{{ $sColor }}-800">{{ $collection->status }}</span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex flex-wrap items-center justify-end gap-1 sm:gap-2">
                                        <a href="{{ route('admin.transparency.collections.show', $collection) }}" class="text-gray-400 hover:text-indigo-600">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.transparency.collections.edit', $collection) }}" class="text-gray-400 hover:text-indigo-600">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <form action="{{ route('admin.transparency.collections.destroy', $collection) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Delete this report?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600"><span class="material-icons">delete</span></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <span class="material-icons text-6xl text-gray-300">payments</span>
                                    <p class="mt-4 text-gray-500">No collection reports found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($collections->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">{{ $collections->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
