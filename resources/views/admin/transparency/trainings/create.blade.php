<x-app-layout title="Create Transparency Training">
    @section('page-title', 'Create Training')

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('admin.transparency.dashboard') }}" class="hover:text-indigo-600">Transparency Wall</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <a href="{{ route('admin.transparency.trainings.index') }}" class="hover:text-indigo-600">Trainings</a>
                    <span class="material-icons text-sm mx-1">chevron_right</span>
                    <span class="text-gray-900">Create</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Create New Training/Seminar</h1>
                <p class="text-gray-500 mt-1">Training Number: <span class="font-mono font-semibold text-teal-600">{{ $nextTrainingNumber }}</span></p>
            </div>

            <form action="{{ route('admin.transparency.trainings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="training_type" class="block text-sm font-medium text-gray-700 mb-1">Training Type *</label>
                                <select name="training_type" id="training_type" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($trainingTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('training_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ old('status', 'Draft') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                            <textarea name="content" id="content" rows="4" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('content') }}</textarea>
                        </div>

                        <div>
                            <label for="learning_objectives" class="block text-sm font-medium text-gray-700 mb-1">Learning Objectives</label>
                            <textarea name="learning_objectives" id="learning_objectives" rows="4" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Enter each objective on a new line">{{ old('learning_objectives') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">One objective per line</p>
                        </div>
                    </div>
                </div>

                <!-- Speaker Information -->
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Speaker Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="speaker_facilitator" class="block text-sm font-medium text-gray-700 mb-1">Speaker / Facilitator *</label>
                            <input type="text" name="speaker_facilitator" id="speaker_facilitator" value="{{ old('speaker_facilitator') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="target_participants" class="block text-sm font-medium text-gray-700 mb-1">Target Participants</label>
                            <input type="text" name="target_participants" id="target_participants" value="{{ old('target_participants') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., Faculty Members, Staff">
                        </div>
                        <div class="md:col-span-2">
                            <label for="speaker_bio" class="block text-sm font-medium text-gray-700 mb-1">Speaker Bio</label>
                            <textarea name="speaker_bio" id="speaker_bio" rows="3" 
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('speaker_bio') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Schedule & Venue -->
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Schedule & Venue</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input type="date" name="date_start" id="date_start" value="{{ old('date_start') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input type="date" name="date_end" id="date_end" value="{{ old('date_end') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="mode" class="block text-sm font-medium text-gray-700 mb-1">Mode *</label>
                            <select name="mode" id="mode" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach($modes as $key => $label)
                                    <option value="{{ $key }}" {{ old('mode') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="venue" class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                        <input type="text" name="venue" id="venue" value="{{ old('venue') }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="e.g., CSU Convention Center or Zoom Meeting Link">
                    </div>
                </div>

                <!-- Registration Details -->
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Registration Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline</label>
                            <input type="date" name="registration_deadline" id="registration_deadline" value="{{ old('registration_deadline') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-1">Max Participants</label>
                            <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}" min="1"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Leave empty for unlimited">
                        </div>
                        <div>
                            <label for="registration_fee" class="block text-sm font-medium text-gray-700 mb-1">Registration Fee (₱)</label>
                            <input type="number" name="registration_fee" id="registration_fee" value="{{ old('registration_fee', 0) }}" min="0" step="0.01"
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="0 for free">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="requirements" class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                        <textarea name="requirements" id="requirements" rows="3" 
                                  class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Enter any requirements for participants">{{ old('requirements') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., Dr. Ricardo dela Cruz">
                        </div>
                        <div>
                            <label for="contact_details" class="block text-sm font-medium text-gray-700 mb-1">Contact Details</label>
                            <input type="text" name="contact_details" id="contact_details" value="{{ old('contact_details') }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., email@csu.edu.ph | 0917-123-4567">
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="material-card p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Attachments</h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                        <div class="mt-2">
                            <label for="attachments" class="cursor-pointer text-indigo-600">
                                <span>Upload files</span> (flyers, schedules, etc.)
                                <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('admin.transparency.trainings.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create Training</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
