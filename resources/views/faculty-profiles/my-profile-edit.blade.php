<x-app-layout title="Edit My Profile">
    @section('page-title', 'Edit My Profile')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-primary-600">person</span>
                {{ __('Edit My Profile') }}
            </h1>
            <a href="{{ route('my-profile') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <span class="material-icons mr-2">arrow_back</span>
                Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6" x-data="myProfileForm()">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <span class="material-icons mr-2">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('my-profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Personal Information (Editable by User) -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">person</span>
                        Personal Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">You can update your personal information below</p>
                </div>
                <div class="p-4 sm:p-6 space-y-6">
                    <!-- Prefix and Name Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">
                                Prefix/Title
                            </label>
                            <select name="prefix" id="prefix"
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('prefix') border-red-500 @enderror">
                                <option value="">None</option>
                                <option value="Dr." {{ old('prefix', $facultyProfile->prefix) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                <option value="Prof." {{ old('prefix', $facultyProfile->prefix) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                <option value="Engr." {{ old('prefix', $facultyProfile->prefix) == 'Engr.' ? 'selected' : '' }}>Engr.</option>
                                <option value="Atty." {{ old('prefix', $facultyProfile->prefix) == 'Atty.' ? 'selected' : '' }}>Atty.</option>
                                <option value="Mr." {{ old('prefix', $facultyProfile->prefix) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Ms." {{ old('prefix', $facultyProfile->prefix) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                <option value="Mrs." {{ old('prefix', $facultyProfile->prefix) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            </select>
                            @error('prefix')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">
                                Surname <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="surname" id="surname" 
                                   value="{{ old('surname', $facultyProfile->surname) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('surname') border-red-500 @enderror">
                            @error('surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name" 
                                   value="{{ old('first_name', $facultyProfile->first_name) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Middle Name
                            </label>
                            <input type="text" name="middle_name" id="middle_name" 
                                   value="{{ old('middle_name', $facultyProfile->middle_name) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('middle_name') border-red-500 @enderror">
                            @error('middle_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="extension_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Extension Name
                            </label>
                            <input type="text" name="extension_name" id="extension_name" 
                                   value="{{ old('extension_name', $facultyProfile->extension_name) }}"
                                   placeholder="Jr., Sr., III, etc."
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('extension_name') border-red-500 @enderror">
                            @error('extension_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Civil Status, Gender, Birthdate, Birth Place -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1">
                                Civil Status <span class="text-red-500">*</span>
                            </label>
                            <select name="civil_status" id="civil_status" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('civil_status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                <option value="single" {{ old('civil_status', $facultyProfile->civil_status) == 'single' ? 'selected' : '' }}>Single (S)</option>
                                <option value="married" {{ old('civil_status', $facultyProfile->civil_status) == 'married' ? 'selected' : '' }}>Married (M)</option>
                                <option value="widowed" {{ old('civil_status', $facultyProfile->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed (W)</option>
                                <option value="separated" {{ old('civil_status', $facultyProfile->civil_status) == 'separated' ? 'selected' : '' }}>Separated</option>
                            </select>
                            @error('civil_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select name="gender" id="gender" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $facultyProfile->gender) == 'male' ? 'selected' : '' }}>Male (M)</option>
                                <option value="female" {{ old('gender', $facultyProfile->gender) == 'female' ? 'selected' : '' }}>Female (F)</option>
                                <option value="lgbtqia+" {{ old('gender', $facultyProfile->gender) == 'lgbtqia+' ? 'selected' : '' }}>LGBTQ+</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Birthdate <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="birth_date" id="birth_date" 
                                   value="{{ old('birth_date', $facultyProfile->birth_date?->format('Y-m-d')) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">
                                Birth Place <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="birth_place" id="birth_place" 
                                   value="{{ old('birth_place', $facultyProfile->birth_place) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('birth_place') border-red-500 @enderror">
                            @error('birth_place')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Home Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Home Address <span class="text-red-500">*</span>
                        </label>
                        <x-ph-address-fields 
                            :province="old('address_province', $facultyProfile->address_province ?? '')"
                            :city="old('address_city', $facultyProfile->address_city ?? '')"
                            :barangay="old('address_barangay', $facultyProfile->address_barangay ?? '')"
                            :street="old('address_street', $facultyProfile->address_street ?? '')"
                            input-class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>

                    <!-- Phone Number and Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone_number" id="phone_number" 
                                   value="{{ old('phone_number', $facultyProfile->phone_number) }}" required
                                   placeholder="09XX-XXX-XXXX"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('phone_number') border-red-500 @enderror">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Employee ID (Login)
                            </label>
                            <input type="text" 
                                   value="{{ $facultyProfile->user->employee_id }}" disabled readonly
                                   class="w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-400">Employee ID cannot be changed. Contact your administrator if needed.</p>
                        </div>
                    </div>

                    <!-- Profile Picture Section -->
                    <div class="border-t pt-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-primary-600">account_circle</span>
                            Profile Picture <span class="text-red-500 ml-1">*</span>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Profile Picture Display -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Photo</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-gray-50 min-h-[200px] flex flex-col items-center justify-center">
                                    @if($facultyProfile->id_picture)
                                        <!-- Photo Preview -->
                                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-indigo-200 shadow-lg cursor-pointer hover:border-indigo-400 transition-all hover:scale-105 relative group"
                                             onclick="openPhotoModal()">
                                            <img src="{{ Storage::url($facultyProfile->id_picture) }}" 
                                                 alt="Current Photo" 
                                                 class="w-full h-full object-cover"
                                                 id="currentIdPreview">
                                            <!-- Hover overlay -->
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <span class="material-icons text-white text-xl">zoom_in</span>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-xs text-gray-500">Click to enlarge</p>
                                    @else
                                        <div class="text-center">
                                            <span class="material-icons text-gray-400 text-6xl">account_circle</span>
                                            <p class="mt-2 text-sm text-gray-500">No photo uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload New Photo -->
                            <div>
                                <label for="id_picture" class="block text-sm font-medium text-gray-700 mb-2">Upload New Photo</label>
                                <div class="border-2 border-dashed border-indigo-300 rounded-lg p-6 text-center bg-indigo-50 hover:bg-indigo-100 transition-colors cursor-pointer min-h-[200px] flex flex-col items-center justify-center" 
                                     onclick="document.getElementById('id_picture').click()">
                                    <span class="material-icons text-primary-600 text-5xl">add_a_photo</span>
                                    <p class="mt-2 text-sm text-primary-600 font-medium">Click to upload your photo</p>
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF (max 2MB)</p>
                                </div>
                                <input type="file" name="id_picture" id="id_picture" 
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(event)">
                                @error('id_picture')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <!-- Live Preview -->
                                <div id="newImagePreview" class="mt-4 hidden">
                                    <p class="text-sm font-medium text-green-700 mb-2">✓ New Image Selected:</p>
                                    <div class="border-2 border-green-300 rounded-lg p-4 bg-green-50 text-center">
                                        <img id="previewImg" alt="New profile image preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="mx-auto max-h-48 rounded-lg shadow-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information (Editable by User) -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">work</span>
                        Professional Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">You can update your professional information below</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Employee ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="employee_id" id="employee_id" 
                                   value="{{ old('employee_id', $facultyProfile->employee_id) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('employee_id') border-red-500 @enderror">
                            @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="date_of_service" class="block text-sm font-medium text-gray-700 mb-1">
                                Date of Service (1st Day)
                            </label>
                            <input type="date" name="date_of_service" id="date_of_service" 
                                   value="{{ old('date_of_service', $facultyProfile->date_of_service?->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('date_of_service') border-red-500 @enderror">
                            @error('date_of_service')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="academic_rank_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Academic Rank <span class="text-red-500">*</span>
                            </label>
                            <select name="academic_rank_id" id="academic_rank_id" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('academic_rank_id') border-red-500 @enderror">
                                <option value="">Select Rank</option>
                                @foreach($academicRanks as $rank)
                                    <option value="{{ $rank->id }}" {{ old('academic_rank_id', $facultyProfile->academic_rank_id) == $rank->id ? 'selected' : '' }}>
                                        {{ $rank->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_rank_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="campus_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Campus <span class="text-red-500">*</span>
                            </label>
                            <select name="campus_id" id="campus_id" required
                                    x-model="selectedCampus" @change="loadColleges()"
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('campus_id') border-red-500 @enderror">
                                <option value="">Select Campus</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->id }}" {{ old('campus_id', $facultyProfile->campus_id) == $campus->id ? 'selected' : '' }}>
                                        {{ $campus->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('campus_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="college_id" class="block text-sm font-medium text-gray-700 mb-1">
                                College <span class="text-red-500">*</span>
                            </label>
                            <select name="college_id" id="college_id" required
                                    x-model="selectedCollege" :disabled="!selectedCampus || loadingColleges"
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 @error('college_id') border-red-500 @enderror">
                                <option value="">Select College</option>
                                <template x-for="college in colleges" :key="college.id">
                                    <option :value="college.id" x-text="college.name"></option>
                                </template>
                            </select>
                            <p x-show="loadingColleges" class="mt-1 text-sm text-gray-500">Loading colleges...</p>
                            @error('college_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('my-profile') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <span class="material-icons mr-2">cancel</span>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm">
                    <span class="material-icons mr-2">save</span>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Profile Picture Modal -->
    @if($facultyProfile->id_picture)
    <div id="photoModal" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closePhotoModal()"></div>
        
        <!-- Modal Content -->
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="relative max-w-2xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-800 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <span class="material-icons mr-2">account_circle</span>
                        Profile Picture
                    </h3>
                    <button onclick="closePhotoModal()" class="text-white/80 hover:text-white transition-colors">
                        <span class="material-icons text-2xl">close</span>
                    </button>
                </div>
                
                <!-- Picture Display -->
                <div class="p-6 bg-gray-100">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="{{ Storage::url($facultyProfile->id_picture) }}" 
                             alt="Profile Picture" 
                             class="w-full h-auto max-h-[70vh] object-contain">
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        <span class="material-icons text-sm align-middle mr-1">person</span>
                        Your Profile Picture
                    </p>
                    <button onclick="closePhotoModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('newImagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function openPhotoModal() {
            document.getElementById('photoModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePhotoModal();
            }
        });

        function myProfileForm() {
            return {
                selectedCampus: '{{ old('campus_id', $facultyProfile->campus_id ?? '') }}',
                selectedCollege: '{{ old('college_id', $facultyProfile->college_id ?? '') }}',
                colleges: [],
                loadingColleges: false,

                init() {
                    if (this.selectedCampus) {
                        this.loadColleges();
                    }
                },

                async loadColleges() {
                    if (!this.selectedCampus) {
                        this.colleges = [];
                        this.selectedCollege = '';
                        return;
                    }

                    this.loadingColleges = true;
                    const previousCollege = this.selectedCollege;
                    try {
                        const response = await fetch(`{{ url('api/colleges') }}/${this.selectedCampus}`);
                        this.colleges = await response.json();
                        const exists = this.colleges.some(c => c.id == previousCollege);
                        this.selectedCollege = exists ? previousCollege : '';
                    } catch (error) {
                        console.error('Error loading colleges:', error);
                        this.colleges = [];
                    }
                    this.loadingColleges = false;
                }
            }
        }
    </script>
</x-app-layout>
