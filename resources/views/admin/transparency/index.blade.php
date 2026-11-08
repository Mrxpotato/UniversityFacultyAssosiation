<x-app-layout title="Transparency Posts">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">visibility</span>
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Transparency Posts Management') }}
                </h1>
            </div>
            <a href="{{ route('admin.transparency.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm text-sm">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                New Post
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            <option value="financial_report" {{ request('category') == 'financial_report' ? 'selected' : '' }}>Financial Report</option>
                            <option value="announcement" {{ request('category') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="disbursement" {{ request('category') == 'disbursement' ? 'selected' : '' }}>Disbursement</option>
                            <option value="audit" {{ request('category') == 'audit' ? 'selected' : '' }}>Audit Report</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." 
                               oninput="clearTimeout(window._searchTimer); window._searchTimer = setTimeout(() => this.form.submit(), 500)"
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </form>
            </div>

            <!-- Posts Table -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Category</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Published</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                                <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($posts ?? [] as $post)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-green-100 flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                                <span class="material-icons-outlined text-green-600 text-sm sm:text-base">article</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 text-sm sm:text-base truncate">{{ Str::limit($post->title, 40) }}</p>
                                                <p class="text-xs sm:text-sm text-gray-500">By {{ $post->author->name ?? 'Unknown' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($post->category === 'financial_report') bg-blue-100 text-blue-800
                                            @elseif($post->category === 'announcement') bg-purple-100 text-purple-800
                                            @elseif($post->category === 'disbursement') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucwords(str_replace('_', ' ', $post->category)) }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        @if($post->status === 'published')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-icons text-xs mr-1">public</span>
                                                Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="material-icons text-xs mr-1">edit_note</span>
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-gray-500 hidden md:table-cell">
                                        {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-gray-500">
                                        {{ number_format($post->views ?? 0) }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1 sm:gap-2">
                                            <a href="{{ route('transparency.show', $post) }}" target="_blank" class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                                <span class="material-icons-outlined text-sm">visibility</span>
                                            </a>
                                            <a href="{{ route('admin.transparency.edit', $post) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Edit">
                                                <span class="material-icons-outlined text-sm">edit</span>
                                            </a>
                                            <form action="{{ route('admin.transparency.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                                    <span class="material-icons-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined text-gray-300 mb-2" style="font-size: 48px;">article</span>
                                            <p class="text-gray-500">No transparency posts yet</p>
                                            <a href="{{ route('admin.transparency.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                                                Create your first post
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(isset($posts) && $posts->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
