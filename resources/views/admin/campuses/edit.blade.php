<x-app-layout title="Edit Campus">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Campus: {{ $campus->name }}
            </h1>
            <a href="{{ route('admin.campuses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                <span class="material-icons text-sm mr-1">arrow_back</span>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <form method="POST" action="{{ route('admin.campuses.update', $campus) }}">
                        @csrf
                        @method('PUT')

                        <!-- Campus Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Campus Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $campus->name) }}" required
                                class="w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campus Code -->
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Campus Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" id="code" value="{{ old('code', $campus->code) }}" required
                                class="w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PSGC Section -->
                        <div class="mb-6 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-1 flex items-center">
                                <span class="material-icons text-blue-600 mr-2 text-lg">pin_drop</span>
                                PSGC (Philippine Standard Geographic Code)
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Geographic classification per PSA standards</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                                    <input type="text" name="region" id="region" value="{{ old('region', $campus->region) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., Region II - Cagayan Valley">
                                    @error('region') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="region_code" class="block text-sm font-medium text-gray-700 mb-1">Region Code</label>
                                    <input type="text" name="region_code" id="region_code" value="{{ old('region_code', $campus->region_code) }}" maxlength="10"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., 02">
                                    @error('region_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                    <input type="text" name="province" id="province" value="{{ old('province', $campus->province) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., Cagayan">
                                    @error('province') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="province_code" class="block text-sm font-medium text-gray-700 mb-1">Province Code</label>
                                    <input type="text" name="province_code" id="province_code" value="{{ old('province_code', $campus->province_code) }}" maxlength="10"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., 0215">
                                    @error('province_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="city_municipality" class="block text-sm font-medium text-gray-700 mb-1">City / Municipality</label>
                                    <input type="text" name="city_municipality" id="city_municipality" value="{{ old('city_municipality', $campus->city_municipality) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., Tuguegarao City">
                                    @error('city_municipality') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="city_municipality_code" class="block text-sm font-medium text-gray-700 mb-1">City/Municipality Code</label>
                                    <input type="text" name="city_municipality_code" id="city_municipality_code" value="{{ old('city_municipality_code', $campus->city_municipality_code) }}" maxlength="10"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., 021528">
                                    @error('city_municipality_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" value="{{ old('barangay', $campus->barangay) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., Carig Sur">
                                    @error('barangay') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="barangay_code" class="block text-sm font-medium text-gray-700 mb-1">Barangay Code</label>
                                    <input type="text" name="barangay_code" id="barangay_code" value="{{ old('barangay_code', $campus->barangay_code) }}" maxlength="10"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., 021528012">
                                    @error('barangay_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="psgc_code" class="block text-sm font-medium text-gray-700 mb-1">Full PSGC Code</label>
                                <input type="text" name="psgc_code" id="psgc_code" value="{{ old('psgc_code', $campus->psgc_code) }}" maxlength="10"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="e.g., 021528012 (9-digit PSA code)">
                                @error('psgc_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- GPS Coordinates -->
                        <div class="mb-6 border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-1 flex items-center">
                                <span class="material-icons text-blue-600 mr-2 text-lg">my_location</span>
                                GPS Coordinates
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Optional latitude and longitude for mapping</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                    <input type="number" step="0.0000001" name="latitude" id="latitude" value="{{ old('latitude', $campus->latitude) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., 17.6132">
                                    @error('latitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                    <input type="number" step="0.0000001" name="longitude" id="longitude" value="{{ old('longitude', $campus->longitude) }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., 121.7270">
                                    @error('longitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('address') ? 'border-red-500' : 'border-gray-300' }}">{{ old('address', $campus->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-4">
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $campus->contact_number) }}"
                                class="w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('contact_number') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('contact_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $campus->email) }}"
                                class="w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $campus->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Campus is active</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.campuses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 transition">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <span class="material-icons text-sm mr-1">save</span>
                                Update Campus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
