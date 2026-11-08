<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Personal Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your personal information. Professional information can only be updated by administrators.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        @php
            $profile = $user->facultyProfile;
        @endphp

        <!-- Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="surname" :value="__('Surname')" />
                <x-text-input id="surname" name="surname" type="text" class="mt-1 block w-full" :value="old('surname', $profile->surname ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('surname')" />
            </div>

            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $profile->first_name ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')" />
                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full" :value="old('middle_name', $profile->middle_name ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
            </div>

            <div>
                <x-input-label for="extension_name" :value="__('Ext. Name (Jr., Sr., III, etc.)')" />
                <x-text-input id="extension_name" name="extension_name" type="text" class="mt-1 block w-full" :value="old('extension_name', $profile->extension_name ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('extension_name')" />
            </div>
        </div>

        <!-- Civil Status & Gender -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="civil_status" :value="__('Civil Status')" />
                <select id="civil_status" name="civil_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select Status</option>
                    <option value="single" {{ old('civil_status', $profile->civil_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="married" {{ old('civil_status', $profile->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                    <option value="widowed" {{ old('civil_status', $profile->civil_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                    <option value="separated" {{ old('civil_status', $profile->civil_status ?? '') == 'separated' ? 'selected' : '' }}>Separated</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('civil_status')" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="lgbtqia+" {{ old('gender', $profile->gender ?? '') == 'lgbtqia+' ? 'selected' : '' }}>LGBTQ+</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
        </div>

        <!-- Birth Date & Birth Place -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="birth_date" :value="__('Birth Date')" />
                <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $profile?->birth_date?->format('Y-m-d') ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
            </div>

            <div>
                <x-input-label for="birth_place" :value="__('Birth Place')" />
                <x-text-input id="birth_place" name="birth_place" type="text" class="mt-1 block w-full" :value="old('birth_place', $profile->birth_place ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('birth_place')" />
            </div>
        </div>

        <!-- Home Address -->
        <x-ph-address-fields
            :province="old('address_province', $profile->address_province ?? '')"
            :city="old('address_city', $profile->address_city ?? '')"
            :barangay="old('address_barangay', $profile->address_barangay ?? '')"
            :street="old('address_street', $profile->address_street ?? '')"
            inputClass="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $profile->phone_number ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <!-- Employee ID -->
        <div>
            <x-input-label for="employee_id" :value="__('Employee ID (Login)')" />
            <x-text-input id="employee_id" type="text" class="mt-1 block w-full bg-gray-50" :value="$user->employee_id" disabled readonly />
            <p class="mt-1 text-xs text-gray-400">Employee ID is your login credential and cannot be changed.</p>
        </div>

        <!-- ID Picture Section -->
        <div class="pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <span class="material-icons mr-2 text-indigo-600">badge</span>
                ID Picture
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current ID Picture Display -->
                <div>
                    <x-input-label :value="__('Current ID Picture')" class="mb-2" />
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50 flex items-center justify-center" style="min-height: 200px;">
                        @if($profile && $profile->id_picture)
                            <img src="{{ asset('storage/' . $profile->id_picture) }}" alt="ID Picture" class="max-h-48 max-w-full object-contain rounded-lg shadow-md">
                        @else
                            <div class="text-center text-gray-400">
                                <span class="material-icons text-6xl">person</span>
                                <p class="mt-2 text-sm">No ID picture uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upload New ID Picture -->
                <div>
                    <x-input-label for="id_picture" :value="__('Upload New ID Picture')" class="mb-2" />
                    <div class="border-2 border-dashed border-indigo-300 rounded-lg p-6 bg-indigo-50 text-center">
                        <span class="material-icons text-4xl text-indigo-600">cloud_upload</span>
                        <div class="mt-2">
                            <label for="id_picture" class="cursor-pointer text-indigo-600 hover:text-indigo-700 font-medium">
                                <span>Click to upload</span>
                                <input id="id_picture" name="id_picture" type="file" accept="image/*" class="hidden" onchange="previewImage(this)" />
                            </label>
                            <p class="text-xs text-gray-500 mt-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">PNG, JPG, JPEG up to 2MB</p>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('id_picture')" />
                    @if(!$profile || !$profile->id_picture)
                        <p class="mt-2 text-sm text-red-600 font-medium">* ID Picture is required</p>
                    @endif
                </div>
            </div>
        </div>

        <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = input.closest('.grid').querySelector('img');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const container = input.closest('.grid').querySelector('.border-dashed.border-gray-300 .text-center');
                        if (container) {
                            container.innerHTML = '<img src="' + e.target.result + '" class="max-h-48 max-w-full object-contain rounded-lg shadow-md">';
                        }
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        </script>

        <!-- Professional Information (Read-only) -->
        @if($profile)
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
                <p class="text-sm text-gray-600 mb-4">Contact administrator to update these fields.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Employee ID:</span>
                        <span class="text-gray-900">{{ $profile->employee_id }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Date of Service:</span>
                        <span class="text-gray-900">{{ $profile->date_of_service?->format('F d, Y') ?? 'Not set' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Academic Rank:</span>
                        <span class="text-gray-900">{{ $profile->academicRank?->name ?? 'Not set' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Campus:</span>
                        <span class="text-gray-900">{{ $profile->campus?->name ?? 'Not set' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">College:</span>
                        <span class="text-gray-900">{{ $profile->college?->name ?? 'Not set' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Membership Status:</span>
                        <span class="text-gray-900 capitalize">{{ $profile->membership_status }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
