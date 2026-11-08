<x-app-layout title="Add Beneficiary">
    @section('page-title', 'Add Beneficiary')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-primary-600">person_add</span>
                {{ __('Add Beneficiary') }}
            </h1>
            <a href="{{ route('beneficiaries.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <span class="material-icons mr-2">arrow_back</span>
                Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('beneficiaries.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="faculty_profile_id" value="{{ $selectedProfileId }}">

            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">person</span>
                        Beneficiary Information
                    </h3>
                </div>
                <div class="p-4 sm:p-6 space-y-6">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                               class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('full_name') border-red-500 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship *</label>
                        <select name="relationship" id="relationship" required
                                class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('relationship') border-red-500 @enderror">
                            <option value="">Select Relationship</option>
                            @foreach($relationships as $key => $label)
                                <option value="{{ $key }}" {{ old('relationship') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('relationship')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('date_of_birth') border-red-500 @enderror">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pse" class="block text-sm font-medium text-gray-700 mb-1">PSE</label>
                        <input type="text" name="pse" id="pse" value="{{ old('pse') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('pse') border-red-500 @enderror">
                        @error('pse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('contact_number') border-red-500 @enderror">
                        @error('contact_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="is_primary" value="0">
                        <input type="checkbox" name="is_primary" id="is_primary" value="1"
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                               {{ old('is_primary') ? 'checked' : '' }}>
                        <label for="is_primary" class="ml-2 text-sm text-gray-700">
                            Set as primary beneficiary
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('beneficiaries.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <span class="material-icons mr-2">cancel</span>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 shadow-lg shadow-primary-500/30 transition-all duration-300 font-semibold">
                    <span class="material-icons mr-2">save</span>
                    Save Beneficiary
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
