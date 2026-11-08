<x-app-layout title="Edit Resolution">
    @section('page-title', 'Edit Resolution')

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.resolutions.index') }}" class="hover:text-indigo-600">Resolutions</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">Edit</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Resolution</h1>
                <p class="text-gray-500 mt-1">{{ $resolution->resolution_number }}</p>
            </div>

            <form action="{{ route('admin.transparency.resolutions.update', $resolution) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Resolution Details</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $resolution->title) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                            <textarea name="content" id="content" rows="15" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('content', $resolution->content) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="resolved_date" class="block text-sm font-medium text-gray-700 mb-1">Resolved Date *</label>
                                <input type="date" name="resolved_date" id="resolved_date" 
                                       value="{{ old('resolved_date', $resolution->resolved_date->format('Y-m-d')) }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $resolution->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
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
                            <input type="number" name="votes_for" id="votes_for" value="{{ old('votes_for', $resolution->votes_for) }}" min="0"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-green-600 font-semibold" required>
                        </div>
                        <div>
                            <label for="votes_against" class="block text-sm font-medium text-gray-700 mb-1">Votes Against *</label>
                            <input type="number" name="votes_against" id="votes_against" value="{{ old('votes_against', $resolution->votes_against) }}" min="0"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-red-600 font-semibold" required>
                        </div>
                        <div>
                            <label for="votes_abstain" class="block text-sm font-medium text-gray-700 mb-1">Abstain *</label>
                            <input type="number" name="votes_abstain" id="votes_abstain" value="{{ old('votes_abstain', $resolution->votes_abstain) }}" min="0"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-600 font-semibold" required>
                        </div>
                    </div>
                </div>

                @if($resolution->attachments && count($resolution->attachments) > 0)
                    <div class="material-card p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Current Attachments</h2>
                        <div class="space-y-2">
                            @foreach($resolution->attachments as $index => $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400 mr-2">attachment</span>
                                        <span class="text-sm text-gray-700">{{ $attachment['name'] }}</span>
                                    </div>
                                    <form action="{{ route('admin.transparency.resolutions.remove-attachment', $resolution) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="index" value="{{ $index }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Remove this attachment?')">
                                            <span class="material-icons">close</span>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Add New Attachments</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                        <div class="mt-2">
                            <label for="attachments" class="cursor-pointer text-indigo-600 hover:text-indigo-500">
                                <span>Upload files</span>
                                <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.transparency.resolutions.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update Resolution</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
