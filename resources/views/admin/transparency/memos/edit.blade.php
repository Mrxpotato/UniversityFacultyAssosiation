<x-app-layout title="Edit Memo">
    @section('page-title', 'Edit Memo')

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.memos.index') }}" class="hover:text-indigo-600">Memos</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">Edit</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Memo</h1>
                <p class="text-gray-500 mt-1">Memo Number: <span class="font-mono font-semibold text-indigo-600">{{ $memo->memo_number }}</span></p>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.transparency.memos.update', $memo) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $memo->title) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   maxlength="200" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                            <textarea name="content" id="content" rows="12" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('content', $memo->content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                                <select name="category" id="category" 
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}" {{ old('category', $memo->category) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Issued By -->
                            <div>
                                <label for="issued_by" class="block text-sm font-medium text-gray-700 mb-1">Issued By *</label>
                                <select name="issued_by" id="issued_by" 
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($issuedBy as $key => $label)
                                        <option value="{{ $key }}" {{ old('issued_by', $memo->issued_by) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('issued_by')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Effective Date -->
                            <div>
                                <label for="effective_date" class="block text-sm font-medium text-gray-700 mb-1">Effective Date *</label>
                                <input type="date" name="effective_date" id="effective_date" 
                                       value="{{ old('effective_date', $memo->effective_date->format('Y-m-d')) }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('effective_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Expiration Date -->
                            <div>
                                <label for="expiration_date" class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                                <input type="date" name="expiration_date" id="expiration_date" 
                                       value="{{ old('expiration_date', $memo->expiration_date?->format('Y-m-d')) }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('expiration_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                                <select name="priority" id="priority" 
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($priorities as $key => $label)
                                        <option value="{{ $key }}" {{ old('priority', $memo->priority) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" id="status" 
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', $memo->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Requires Acknowledgment -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="requires_acknowledgment" value="1" 
                                       {{ old('requires_acknowledgment', $memo->requires_acknowledgment) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Requires acknowledgment from members</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Existing Attachments -->
                @if($memo->attachments && count($memo->attachments) > 0)
                    <div class="material-card p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Current Attachments</h2>
                        <div class="space-y-2">
                            @foreach($memo->attachments as $index => $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400 mr-2">attachment</span>
                                        <span class="text-sm text-gray-700">{{ $attachment['name'] }}</span>
                                        <span class="text-xs text-gray-500 ml-2">({{ number_format($attachment['size'] / 1024, 1) }} KB)</span>
                                    </div>
                                    <form action="{{ route('admin.transparency.memos.remove-attachment', $memo) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="index" value="{{ $index }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700" 
                                                onclick="return confirm('Remove this attachment?')">
                                            <span class="material-icons">close</span>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- New Attachments -->
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Add New Attachments</h2>
                    
                    <div>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                                <div class="flex text-sm text-gray-600">
                                    <label for="attachments" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                        <span>Upload files</span>
                                        <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX up to 10MB each</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics -->
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Analytics</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <span class="material-icons text-2xl text-blue-500">visibility</span>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($memo->views_count) }}</p>
                            <p class="text-xs text-gray-500">Total Views</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <span class="material-icons text-2xl text-green-500">check_circle</span>
                            <p class="text-2xl font-bold text-gray-900">{{ $memo->acknowledgment_count }}</p>
                            <p class="text-xs text-gray-500">Acknowledgments</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <span class="material-icons text-2xl text-purple-500">calendar_today</span>
                            <p class="text-sm font-medium text-gray-900">{{ $memo->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">Created</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <span class="material-icons text-2xl text-orange-500">update</span>
                            <p class="text-sm font-medium text-gray-900">{{ $memo->updated_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">Last Updated</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.transparency.memos.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <span class="material-icons mr-1 text-sm">save</span>
                        Update Memo
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
