<x-app-layout title="Edit Faculty Member">
    @section('page-title', 'Edit Faculty Member')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-indigo-600">edit</span>
                {{ __('Edit Faculty Member') }}
            </h1>
            <a href="{{ route('admin.faculty-profiles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                <span class="material-icons mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto" x-data="facultyProfileEditForm()">
        <form method="POST" action="{{ route('admin.faculty-profiles.update', $facultyProfile) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Employment Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">work</span>
                        Employment Information
                    </h3>
                </div>
                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee ID *</label>
                        <input type="text" name="employee_id" id="employee_id" 
                               value="{{ old('employee_id', $facultyProfile->employee_id) }}" required
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('employee_id') border-red-500 @enderror">
                        @error('employee_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="campus_id" class="block text-sm font-medium text-gray-700 mb-1">Campus *</label>
                        <select name="campus_id" id="campus_id" required
                                x-model="selectedCampus" @change="loadColleges()"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('campus_id') border-red-500 @enderror">
                            <option value="">Select Campus</option>
                            @foreach($campuses ?? [] as $campus)
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
                        <label for="college_id" class="block text-sm font-medium text-gray-700 mb-1">College *</label>
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
                    <div>
                        <label for="academic_rank_id" class="block text-sm font-medium text-gray-700 mb-1">Academic Rank *</label>
                        <select name="academic_rank_id" id="academic_rank_id" required
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('academic_rank_id') border-red-500 @enderror">
                            <option value="">Select Rank</option>
                            @foreach($academicRanks ?? [] as $rank)
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
                        <label for="date_of_service" class="block text-sm font-medium text-gray-700 mb-1">Date of Service</label>
                        <input type="date" name="date_of_service" id="date_of_service" 
                               value="{{ old('date_of_service', $facultyProfile->date_of_service?->format('Y-m-d')) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('date_of_service') border-red-500 @enderror">
                        @error('date_of_service')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">person</span>
                        Personal Information
                    </h3>
                </div>
                <div class="p-4 sm:p-6 space-y-6">
                    <!-- Prefix and Name Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix/Title</label>
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
                            <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">Surname *</label>
                            <input type="text" name="surname" id="surname" 
                                   value="{{ old('surname', $facultyProfile->surname) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('surname') border-red-500 @enderror">
                            @error('surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" id="first_name" 
                                   value="{{ old('first_name', $facultyProfile->first_name) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" 
                                   value="{{ old('middle_name', $facultyProfile->middle_name) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('middle_name') border-red-500 @enderror">
                            @error('middle_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="extension_name" class="block text-sm font-medium text-gray-700 mb-1">Extension (Jr., Sr.)</label>
                            <input type="text" name="extension_name" id="extension_name" 
                                   value="{{ old('extension_name', $facultyProfile->extension_name) }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('extension_name') border-red-500 @enderror">
                            @error('extension_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Other Personal Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1">Civil Status *</label>
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
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
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
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birthdate *</label>
                            <input type="date" name="birth_date" id="birth_date" 
                                   value="{{ old('birth_date', $facultyProfile->birth_date?->format('Y-m-d')) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Birth Place *</label>
                            <input type="text" name="birth_place" id="birth_place" 
                                   value="{{ old('birth_place', $facultyProfile->birth_place) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('birth_place') border-red-500 @enderror">
                            @error('birth_place')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="text" name="phone_number" id="phone_number" 
                                   value="{{ old('phone_number', $facultyProfile->phone_number) }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('phone_number') border-red-500 @enderror">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Home Address *</label>
                            <x-ph-address-fields 
                                :province="old('address_province', $facultyProfile->address_province ?? '')"
                                :city="old('address_city', $facultyProfile->address_city ?? '')"
                                :barangay="old('address_barangay', $facultyProfile->address_barangay ?? '')"
                                :street="old('address_street', $facultyProfile->address_street ?? '')"
                                input-class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                    </div>

                    <!-- ID Picture Section -->
                    <div class="border-t pt-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-indigo-600">badge</span>
                            ID Picture
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current ID Picture Display -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current ID Picture</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center bg-gray-50">
                                    @if($facultyProfile->id_picture)
                                        <img src="{{ Storage::url($facultyProfile->id_picture) }}" 
                                             alt="Current ID Picture" 
                                             class="mx-auto max-h-48 rounded-lg"
                                             id="currentIdPreview">
                                    @else
                                        <div class="py-8">
                                            <span class="material-icons text-gray-400 text-6xl">account_circle</span>
                                            <p class="mt-2 text-sm text-gray-500">No ID picture uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload New ID Picture -->
                            <div>
                                <label for="id_picture" class="block text-sm font-medium text-gray-700 mb-2">Upload New ID Picture</label>
                                <div class="border-2 border-dashed border-indigo-300 rounded-lg p-6 text-center bg-indigo-50 hover:bg-indigo-100 transition-colors cursor-pointer" 
                                     onclick="document.getElementById('id_picture').click()">
                                    <span class="material-icons text-indigo-600 text-5xl">cloud_upload</span>
                                    <p class="mt-2 text-sm text-indigo-600 font-medium">Click to upload new picture</p>
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
                                    <p class="text-sm font-medium text-gray-700 mb-2">New Image Preview:</p>
                                    <div class="border-2 border-green-300 rounded-lg p-4 bg-green-50">
                                        <img id="previewImg" alt="New profile image preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="mx-auto max-h-48 rounded-lg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
            </script>

            <!-- Membership Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">card_membership</span>
                        Membership Information
                    </h3>
                </div>
                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="membership_date" class="block text-sm font-medium text-gray-700 mb-1">Membership Date *</label>
                        <input type="date" name="membership_date" id="membership_date" 
                               value="{{ old('membership_date', $facultyProfile->membership_date?->format('Y-m-d')) }}" required
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('membership_date') border-red-500 @enderror">
                        @error('membership_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="membership_status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="membership_status" id="membership_status" required
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('membership_status') border-red-500 @enderror">
                            <option value="active" {{ old('membership_status', $facultyProfile->membership_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('membership_status', $facultyProfile->membership_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="retired" {{ old('membership_status', $facultyProfile->membership_status) == 'retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                        @error('membership_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('admin.faculty-profiles.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <span class="material-icons mr-2">cancel</span>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm">
                    <span class="material-icons mr-2">save</span>
                    Update Faculty Member
                </button>
            </div>
        </form>
    </div>

    <script>
        function facultyProfileEditForm() {
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
                        const response = await fetch(`{{ url('admin/members/colleges') }}/${this.selectedCampus}`);
                        this.colleges = await response.json();
                        // Restore selection if the college exists in the new list
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
