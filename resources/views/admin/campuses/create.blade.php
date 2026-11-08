<x-app-layout title="Add Campus">
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.campuses.index') }}" class="mr-3 text-gray-400 hover:text-gray-600">
                <span class="material-icons">arrow_back</span>
            </a>
            <span class="material-icons-outlined text-indigo-600 mr-3">add_circle</span>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Campus') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.campuses.store') }}" method="POST">
                @csrf
                
                <div class="bg-white rounded-xl material-shadow overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Campus Information</h3>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-6">
                        <!-- Name & Code -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Campus Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., Main Campus">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Campus Code <span class="text-red-500">*</span></label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}" required maxlength="10"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 uppercase"
                                       placeholder="e.g., MAIN">
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- PSGC Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-1 flex items-center">
                                <span class="material-icons text-indigo-600 mr-2 text-lg">pin_drop</span>
                                PSGC (Philippine Standard Geographic Code)
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Geographic classification per PSA standards</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="region" class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                                    <input type="text" name="region" id="region" value="{{ old('region') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., Region II - Cagayan Valley">
                                    @error('region')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="region_code" class="block text-sm font-medium text-gray-700 mb-1">Region Code</label>
                                    <input type="text" name="region_code" id="region_code" value="{{ old('region_code') }}" maxlength="10"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., 02">
                                    @error('region_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                    <input type="text" name="province" id="province" value="{{ old('province') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., Cagayan">
                                    @error('province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="province_code" class="block text-sm font-medium text-gray-700 mb-1">Province Code</label>
                                    <input type="text" name="province_code" id="province_code" value="{{ old('province_code') }}" maxlength="10"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., 0215">
                                    @error('province_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label for="city_municipality" class="block text-sm font-medium text-gray-700 mb-1">City / Municipality</label>
                                    <input type="text" name="city_municipality" id="city_municipality" value="{{ old('city_municipality') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., Tuguegarao City">
                                    @error('city_municipality')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="city_municipality_code" class="block text-sm font-medium text-gray-700 mb-1">City/Municipality Code</label>
                                    <input type="text" name="city_municipality_code" id="city_municipality_code" value="{{ old('city_municipality_code') }}" maxlength="10"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., 021528">
                                    @error('city_municipality_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" value="{{ old('barangay') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., Carig Sur">
                                    @error('barangay')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="barangay_code" class="block text-sm font-medium text-gray-700 mb-1">Barangay Code</label>
                                    <input type="text" name="barangay_code" id="barangay_code" value="{{ old('barangay_code') }}" maxlength="10"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., 021528012">
                                    @error('barangay_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="psgc_code" class="block text-sm font-medium text-gray-700 mb-1">Full PSGC Code</label>
                                <input type="text" name="psgc_code" id="psgc_code" value="{{ old('psgc_code') }}" maxlength="10"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., 021528012 (9-digit PSA code)">
                                @error('psgc_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- GPS Coordinates -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-gray-800 mb-1 flex items-center">
                                <span class="material-icons text-indigo-600 mr-2 text-lg">my_location</span>
                                GPS Coordinates
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Optional latitude and longitude for mapping</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                    <input type="number" step="0.0000001" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., 17.6132">
                                    @error('latitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                    <input type="number" step="0.0000001" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="e.g., 121.7270">
                                    @error('longitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" id="address" rows="2"
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Enter campus address">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Enter phone number">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="campus@university.edu">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Brief description of the campus">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Active Campus</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="p-6 bg-gray-50 flex justify-end gap-3">
                        <a href="{{ route('admin.campuses.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm">
                            Create Campus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
