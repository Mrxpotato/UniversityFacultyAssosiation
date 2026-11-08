<x-app-layout title="Create Collection">
    @section('page-title', 'Create Collection Report')

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.collections.index') }}" class="hover:text-indigo-600">Collections</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">Create</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Create Collection Report</h1>
            </div>

            <form action="{{ route('admin.transparency.collections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Report Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   placeholder="e.g., Monthly Collections Report - January 2026"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Report Type *</label>
                                <select name="report_type" id="report_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($reportTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('report_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="period_covered" class="block text-sm font-medium text-gray-700 mb-1">Period Covered *</label>
                                <input type="text" name="period_covered" id="period_covered" value="{{ old('period_covered') }}" 
                                       placeholder="e.g., January 2026 or Q1 2026"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="total_collected" class="block text-sm font-medium text-gray-700 mb-1">Total Collected (₱) *</label>
                                <input type="number" name="total_collected" id="total_collected" value="{{ old('total_collected') }}" 
                                       step="0.01" min="0"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Executive Summary *</label>
                            <textarea name="content" id="content" rows="10" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Enter the executive summary of the collection report..."
                                      required>{{ old('content') }}</textarea>
                        </div>

                        <div>
                            <label for="breakdown_data" class="block text-sm font-medium text-gray-700 mb-1">Breakdown Data (JSON)</label>
                            <textarea name="breakdown_data" id="breakdown_data" rows="8" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                                      placeholder='{
  "membership_dues": 797500.00,
  "by_campus": {
    "Main Campus": 271200.00,
    "Campus A": 169500.00
  },
  "collection_rate": 95
}'>{{ old('breakdown_data') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Optional JSON data for detailed breakdown</p>
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

                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                        <div class="mt-2">
                            <label for="attachments" class="cursor-pointer text-indigo-600 hover:text-indigo-500">
                                <span>Upload files (PDF, Excel)</span>
                                <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.transparency.collections.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create Report</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
