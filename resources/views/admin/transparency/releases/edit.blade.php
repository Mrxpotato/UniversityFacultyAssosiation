<x-app-layout title="Edit Release">
    @section('page-title', 'Edit Release')

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.releases.index') }}" class="hover:text-indigo-600">Releases</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">Edit</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Release</h1>
                <p class="text-gray-500 mt-1">{{ $release->release_number }}</p>
            </div>

            <form action="{{ route('admin.transparency.releases.update', $release) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Release Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $release->title) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="release_type" class="block text-sm font-medium text-gray-700 mb-1">Release Type *</label>
                                <select name="release_type" id="release_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($releaseTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('release_type', $release->release_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $release->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                            <textarea name="content" id="content" rows="15" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('content', $release->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person *</label>
                            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $release->contact_person) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Contact Email *</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $release->contact_email) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $release->contact_phone) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                @if($release->attachments && count($release->attachments) > 0)
                    <div class="material-card p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Current Attachments</h2>
                        <div class="space-y-2">
                            @foreach($release->attachments as $index => $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400 mr-2">attachment</span>
                                        <span class="text-sm text-gray-700">{{ $attachment['name'] }}</span>
                                    </div>
                                    <form action="{{ route('admin.transparency.releases.remove-attachment', $release) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="index" value="{{ $index }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Remove?')">
                                            <span class="material-icons">close</span>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Add Attachments</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                        <div class="mt-2">
                            <label for="attachments" class="cursor-pointer text-indigo-600">
                                <span>Upload files</span>
                                <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.transparency.releases.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update Release</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
