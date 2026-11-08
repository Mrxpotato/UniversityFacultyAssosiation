<x-app-layout title="Edit Collection">
    @section('page-title', 'Edit Collection Report')

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.collections.index') }}" class="hover:text-indigo-600">Collections</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">Edit</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Collection Report</h1>
                <p class="text-gray-500 mt-1">{{ $collection->report_number }}</p>
            </div>

            <form action="{{ route('admin.transparency.collections.update', $collection) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Report Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $collection->title) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Report Type *</label>
                                <select name="report_type" id="report_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($reportTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('report_type', $collection->report_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="period_covered" class="block text-sm font-medium text-gray-700 mb-1">Period Covered *</label>
                                <input type="text" name="period_covered" id="period_covered" value="{{ old('period_covered', $collection->period_covered) }}" 
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="total_collected" class="block text-sm font-medium text-gray-700 mb-1">Total Collected (₱) *</label>
                                <input type="number" name="total_collected" id="total_collected" value="{{ old('total_collected', $collection->total_collected) }}" 
                                       step="0.01" min="0" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Executive Summary *</label>
                            <textarea name="content" id="content" rows="10" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('content', $collection->content) }}</textarea>
                        </div>

                        <div>
                            <label for="breakdown_data" class="block text-sm font-medium text-gray-700 mb-1">Breakdown Data (JSON)</label>
                            <textarea name="breakdown_data" id="breakdown_data" rows="8" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm">{{ old('breakdown_data', $collection->breakdown_data ? json_encode($collection->breakdown_data, JSON_PRETTY_PRINT) : '') }}</textarea>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $collection->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                @if($collection->attachments && count($collection->attachments) > 0)
                    <div class="material-card p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Current Attachments</h2>
                        <div class="space-y-2">
                            @foreach($collection->attachments as $index => $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="material-icons text-gray-400 mr-2">attachment</span>
                                        <span class="text-sm text-gray-700">{{ $attachment['name'] }}</span>
                                    </div>
                                    <form action="{{ route('admin.transparency.collections.remove-attachment', $collection) }}" method="POST">
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
                    <a href="{{ route('admin.transparency.collections.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update Report</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
