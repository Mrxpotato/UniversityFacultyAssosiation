<x-app-layout title="Colleges">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center">
                <a href="{{ route('admin.campuses.index') }}" class="text-indigo-600 hover:text-indigo-800 mr-3">
                    <span class="material-icons">arrow_back</span>
                </a>
                <span class="material-icons-outlined text-green-600 mr-3">school</span>
                <div>
                    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Colleges Management') }}
                    </h1>
                    <p class="text-sm text-gray-500">{{ $campus->name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.campuses.colleges.create', $campus) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors material-shadow-sm">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                Add College
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Colleges</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $colleges->count() }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-green-600">school</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Active Colleges</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $colleges->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-blue-600">check_circle</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Faculty</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $colleges->sum('faculty_profiles_count') }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-purple-600">groups</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colleges Table -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">College</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Code</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Faculty Members</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($colleges as $college)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                                                <span class="material-icons-outlined text-green-600">school</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $college->name }}</p>
                                                @if($college->description)
                                                    <p class="text-sm text-gray-500">{{ Str::limit($college->description, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        @if($college->code)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $college->code }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="font-medium text-gray-800">{{ $college->faculty_profiles_count }}</span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        @if($college->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-icons text-xs mr-1">check_circle</span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="material-icons text-xs mr-1">pause_circle</span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.campuses.colleges.edit', [$campus, $college]) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Edit">
                                                <span class="material-icons-outlined text-sm">edit</span>
                                            </a>
                                            <form action="{{ route('admin.campuses.colleges.destroy', [$campus, $college]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this college?')">
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
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <span class="material-icons text-6xl text-gray-300">school</span>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Colleges Found</h3>
                                        <p class="mt-2 text-gray-500">Get started by creating a new college for this campus.</p>
                                        <a href="{{ route('admin.campuses.colleges.create', $campus) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            <span class="material-icons-outlined mr-2 text-sm">add</span>
                                            Add College
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
