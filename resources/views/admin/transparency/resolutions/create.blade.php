<x-app-layout title="Create Resolution">
    @section('page-title', 'Create Resolution')

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.resolutions.index') }}" class="hover:text-indigo-600">Resolutions</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">Create</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Create New Resolution</h1>
                <p class="text-gray-500 mt-1">Resolution Number: <span class="font-mono font-semibold text-purple-600">{{ $nextResolutionNumber }}</span></p>
            </div>

            <form action="{{ route('admin.transparency.resolutions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Resolution Details</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   maxlength="200" required>
                            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                            <textarea name="content" id="content" rows="15" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="RESOLUTION NO. {{ $nextResolutionNumber }}

WHEREAS, ...

NOW THEREFORE BE IT RESOLVED, ...

RESOLVED FURTHER, ..."
                                      required>{{ old('content') }}</textarea>
                            @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="resolved_date" class="block text-sm font-medium text-gray-700 mb-1">Resolved Date *</label>
                                <input type="date" name="resolved_date" id="resolved_date" value="{{ old('resolved_date', date('Y-m-d')) }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('resolved_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Draft">Draft</option>
                                    <option value="Published">Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Voting Results</h2>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="votes_for" class="block text-sm font-medium text-gray-700 mb-1">Votes For *</label>
                            <input type="number" name="votes_for" id="votes_for" value="{{ old('votes_for', 0) }}" min="0"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-green-600 font-semibold" required>
                        </div>
                        <div>
                            <label for="votes_against" class="block text-sm font-medium text-gray-700 mb-1">Votes Against *</label>
                            <input type="number" name="votes_against" id="votes_against" value="{{ old('votes_against', 0) }}" min="0"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-red-600 font-semibold" required>
                        </div>
                        <div>
                            <label for="votes_abstain" class="block text-sm font-medium text-gray-700 mb-1">Abstain *</label>
                            <input type="number" name="votes_abstain" id="votes_abstain" value="{{ old('votes_abstain', 0) }}" min="0"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 font-semibold" required>
                        </div>
                    </div>
                </div>

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors">
                        <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                        <div class="mt-2">
                            <label for="attachments" class="cursor-pointer text-indigo-600 hover:text-indigo-500">
                                <span>Upload files</span>
                                <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC up to 10MB each</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.transparency.resolutions.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create Resolution</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
