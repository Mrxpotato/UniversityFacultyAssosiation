<x-app-layout title="Memos">
    @section('page-title', 'Memos')

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <nav class="flex items-center text-sm text-gray-500 mb-2">
                        <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                        <span class="material-icons text-sm mx-1">chevron_right</span>
                        <span class="text-gray-900">Memos</span>
                    </nav>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Memos Management</h1>
                </div>
                <a href="{{ route('admin.transparency.memos.create') }}" 
                   class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <span class="material-icons mr-2">add</span>
                    New Memo
                </a>
            </div>

            <!-- Filters -->
            <div class="material-card p-4 mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search memos..." 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <select name="category" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="priority" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Priorities</option>
                            @foreach($priorities as $key => $label)
                                <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <span class="material-icons text-sm">filter_list</span> Filter
                        </button>
                        <a href="{{ route('admin.transparency.memos.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700">
                            <span class="material-icons">refresh</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Success Message -->
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
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Memo</th>
                            <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effective</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                            <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($memos as $memo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <span class="material-icons text-blue-600">{{ \App\Models\Memo::CATEGORY_ICONS[$memo->category] ?? 'description' }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($memo->title, 40) }}</div>
                                            <div class="text-sm text-gray-500">{{ $memo->memo_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                        {{ $memo->category }}
                                    </span>
                                </td>
                                <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    @php
                                        $priorityColors = ['Normal' => 'gray', 'High' => 'yellow', 'Urgent' => 'red'];
                                        $color = $priorityColors[$memo->priority] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                        {{ $memo->priority }}
                                    </span>
                                </td>
                                <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $memo->effective_date->format('M d, Y') }}
                                    @if($memo->is_expired)
                                        <span class="text-red-500 text-xs block">Expired</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = ['Draft' => 'gray', 'Published' => 'green', 'Archived' => 'yellow'];
                                        $sColor = $statusColors[$memo->status] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $sColor }}-100 text-{{ $sColor }}-800">
                                        {{ $memo->status }}
                                    </span>
                                </td>
                                <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="material-icons text-sm text-gray-400">visibility</span>
                                    {{ number_format($memo->views_count) }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex flex-wrap items-center justify-end gap-1 sm:gap-2">
                                        <a href="{{ route('admin.transparency.memos.show', $memo) }}" 
                                           class="text-gray-400 hover:text-indigo-600" title="View">
                                            <span class="material-icons">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.transparency.memos.edit', $memo) }}" 
                                           class="text-gray-400 hover:text-indigo-600" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                        <form action="{{ route('admin.transparency.memos.toggle-publish', $memo) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-400 hover:text-green-600" 
                                                    title="{{ $memo->status === 'Published' ? 'Unpublish' : 'Publish' }}">
                                                <span class="material-icons">{{ $memo->status === 'Published' ? 'unpublished' : 'publish' }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.transparency.memos.destroy', $memo) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this memo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600" title="Delete">
                                                <span class="material-icons">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <span class="material-icons text-6xl text-gray-300">description</span>
                                    <p class="mt-4 text-gray-500">No memos found.</p>
                                    <a href="{{ route('admin.transparency.memos.create') }}" 
                                       class="mt-4 inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                        <span class="material-icons mr-1">add</span> Create your first memo
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($memos->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $memos->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
