<x-app-layout title="Edit Transparency Post">
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.transparency.index') }}" class="mr-3 text-gray-400 hover:text-gray-600">
                <span class="material-icons">arrow_back</span>
            </a>
            <span class="material-icons-outlined text-indigo-600 mr-3">edit</span>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Transparency Post') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.transparency.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-xl material-shadow overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-100">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Post Details</h3>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Enter post title">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Category & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select name="category" id="category" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Category</option>
                                    <option value="financial_report" {{ old('category', $post->category) == 'financial_report' ? 'selected' : '' }}>Financial Report</option>
                                    <option value="announcement" {{ old('category', $post->category) == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                    <option value="disbursement" {{ old('category', $post->category) == 'disbursement' ? 'selected' : '' }}>Disbursement</option>
                                    <option value="audit" {{ old('category', $post->category) == 'audit' ? 'selected' : '' }}>Audit Report</option>
                                    <option value="other" {{ old('category', $post->category) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Summary -->
                        <div>
                            <label for="summary" class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                            <textarea name="summary" id="summary" rows="2"
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Brief summary for preview (optional)">{{ old('summary', $post->summary) }}</textarea>
                            @error('summary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                            <textarea name="content" id="content" rows="12" required
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Write your post content here...">{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Existing Attachments -->
                        @if($post->attachments && count($post->attachments) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Attachments</label>
                                <div class="space-y-2">
                                    @foreach($post->attachments as $attachment)
                                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center">
                                                <span class="material-icons-outlined text-gray-400 mr-2">attach_file</span>
                                                <span class="text-sm text-gray-600">{{ $attachment->filename }}</span>
                                            </div>
                                            <label class="flex items-center text-sm text-red-600 cursor-pointer">
                                                <input type="checkbox" name="remove_attachments[]" value="{{ $attachment->id }}" class="mr-2">
                                                Remove
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- New Attachments -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Add New Attachments</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors">
                                <input type="file" name="attachments[]" id="attachments" multiple class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                <label for="attachments" class="cursor-pointer">
                                    <span class="material-icons-outlined text-gray-400 mb-2" style="font-size: 48px;">cloud_upload</span>
                                    <p class="text-gray-600">Click to upload or drag and drop</p>
                                    <p class="text-sm text-gray-400">PDF, DOC, XLS, Images (max 10MB each)</p>
                                </label>
                            </div>
                            <div id="file-list" class="mt-2 space-y-1"></div>
                            @error('attachments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Publish Date -->
                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                            <input type="datetime-local" name="published_at" id="published_at" 
                                   value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('published_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="p-4 sm:p-6 bg-gray-50 flex flex-wrap justify-between">
                        <form action="{{ route('admin.transparency.destroy', $post) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-red-600 hover:text-red-800 transition-colors">
                                <span class="material-icons-outlined align-middle mr-1">delete</span>
                                Delete Post
                            </button>
                        </form>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('admin.transparency.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm">
                                Update Post
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('attachments').addEventListener('change', function(e) {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = '';
            
            Array.from(e.target.files).forEach(file => {
                const div = document.createElement('div');
                div.className = 'flex items-center text-sm text-gray-600';
                div.innerHTML = `
                    <span class="material-icons text-gray-400 mr-2 text-sm">attach_file</span>
                    ${file.name} <span class="text-gray-400 ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                `;
                fileList.appendChild(div);
            });
        });
    </script>
    @endpush
</x-app-layout>
