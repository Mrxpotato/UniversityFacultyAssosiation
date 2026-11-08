<x-app-layout title="Add College">
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.campuses.colleges', $campus) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">
                <span class="material-icons">arrow_back</span>
            </a>
            <span class="material-icons-outlined text-green-600 mr-3">add_circle</span>
            <div>
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Add College') }}
                </h1>
                <p class="text-sm text-gray-500">{{ $campus->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <form action="{{ route('admin.campuses.colleges.store', $campus) }}" method="POST" class="p-4 sm:p-6 space-y-6">
                    @csrf

                    <!-- College Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            College Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                               placeholder="e.g., College of Engineering" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- College Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">
                            College Code
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                               placeholder="e.g., COE">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                  placeholder="Brief description of the college">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Active
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.campuses.colleges', $campus) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <span class="material-icons-outlined mr-2 text-sm">save</span>
                            Create College
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
