<x-app-layout title="Register New Member">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">person_add</span>
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Register New Member') }}
                </h1>
            </div>
            <a href="{{ route('admin.faculty-profiles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <span class="material-icons-outlined mr-2 text-sm">arrow_back</span>
                Back to Faculty
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.members.store') }}" enctype="multipart/form-data" x-data="memberForm()">
                @csrf

                <!-- Personal Information Section -->
                <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-indigo-100 flex items-center justify-center mr-3">
                                <span class="material-icons-round text-indigo-600">person</span>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Personal Information</h3>
                                <p class="text-sm text-gray-500">Basic details about the member</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-6">
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                            <div>
                                <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1.5">Title</label>
                                <select id="prefix" name="prefix" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="">None</option>
                                    <option value="Dr." {{ old('prefix') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                    <option value="Prof." {{ old('prefix') == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                    <option value="Engr." {{ old('prefix') == 'Engr.' ? 'selected' : '' }}>Engr.</option>
                                    <option value="Atty." {{ old('prefix') == 'Atty.' ? 'selected' : '' }}>Atty.</option>
                                    <option value="Mr." {{ old('prefix') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                    <option value="Ms." {{ old('prefix') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                    <option value="Mrs." {{ old('prefix') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                </select>
                            </div>

                            <div>
                                <label for="surname" class="block text-sm font-medium text-gray-700 mb-1.5">Surname *</label>
                                <input type="text" id="surname" name="surname" value="{{ old('surname') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="Doe">
                                @error('surname') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1.5">First Name *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="John">
                                @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1.5">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="extension_name" class="block text-sm font-medium text-gray-700 mb-1.5">Ext. Name</label>
                                <input type="text" id="extension_name" name="extension_name" value="{{ old('extension_name') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="Jr., Sr., III">
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1.5">Civil Status *</label>
                                <select id="civil_status" name="civil_status" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="separated" {{ old('civil_status') == 'separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                                @error('civil_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1.5">Gender *</label>
                                <select id="gender" name="gender" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="lgbtqia+" {{ old('gender') == 'lgbtqia+' ? 'selected' : '' }}>LGBTQ+</option>
                                </select>
                                @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1.5">Birth Date *</label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                @error('birth_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Location & Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1.5">Birth Place *</label>
                                <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="City, Province">
                                @error('birth_place') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number *</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="09XX XXX XXXX">
                                @error('phone_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Home Address *</label>
                            <x-ph-address-fields 
                                :province="old('address_province', '')"
                                :city="old('address_city', '')"
                                :barangay="old('address_barangay', '')"
                                :street="old('address_street', '')"
                                input-class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                            />
                        </div>

                        <!-- ID Picture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">ID Picture</label>
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden" id="preview-container">
                                    <span class="material-icons-round text-gray-400 text-3xl" id="preview-icon">person</span>
                                    <img id="preview-image" alt="ID picture preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="w-full h-full object-cover hidden">
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="id_picture" name="id_picture" accept="image/*" class="hidden" onchange="previewImage(this)">
                                    <label for="id_picture" class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                        <span class="material-icons-outlined mr-2 text-sm">upload</span>
                                        Choose Photo
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                            @error('id_picture') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Information Section -->
                <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-cyan-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-blue-100 flex items-center justify-center mr-3">
                                <span class="material-icons-round text-blue-600">account_circle</span>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Account Information</h3>
                                <p class="text-sm text-gray-500">Login credentials and account settings</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">Account Role *</label>
                                @if(Auth::user()->isCampusPresident())
                                    <input type="hidden" name="role" value="faculty">
                                    <div class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-600">Faculty</div>
                                @else
                                <select id="role" name="role" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="faculty" {{ old('role', 'faculty') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                                    <option value="campus_president" {{ old('role') == 'campus_president' ? 'selected' : '' }}>Campus President</option>
                                    <option value="university_treasurer" {{ old('role') == 'university_treasurer' ? 'selected' : '' }}>University Treasurer</option>
                                    <option value="university_president" {{ old('role') == 'university_president' ? 'selected' : '' }}>University President</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                @endif
                                @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div x-data="{ show: false }">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password *</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" id="password" name="password" required class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="••••••••">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600">
                                        <span class="material-icons-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div x-data="{ show: false }">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password *</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2.5 pr-10 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="••••••••">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600">
                                        <span class="material-icons-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                            <div class="flex items-start">
                                <span class="material-icons-round text-yellow-600 mr-2 text-lg">info</span>
                                <p class="text-sm text-yellow-700">The member will use their Employee ID and the password you set here to log in.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                        <div class="flex items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-emerald-100 flex items-center justify-center mr-3">
                                <span class="material-icons-round text-emerald-600">work</span>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Professional Information</h3>
                                <p class="text-sm text-gray-500">Employment and membership details</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1.5">Employee ID *</label>
                                <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="EMP-XXXX">
                                @error('employee_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="campus_id" class="block text-sm font-medium text-gray-700 mb-1.5">Campus *</label>
                                <select id="campus_id" name="campus_id" required x-model="selectedCampus" @change="loadColleges()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="">Select Campus</option>
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                                @error('campus_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="college_id" class="block text-sm font-medium text-gray-700 mb-1.5">College/Department</label>
                                <select id="college_id" name="college_id" x-model="selectedCollege" :disabled="!selectedCampus || loadingColleges" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 disabled:bg-gray-100">
                                    <option value="">Select College</option>
                                    <template x-for="college in colleges" :key="college.id">
                                        <option :value="college.id" x-text="college.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label for="academic_rank_id" class="block text-sm font-medium text-gray-700 mb-1.5">Academic Rank</label>
                                <select id="academic_rank_id" name="academic_rank_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="">Select Rank</option>
                                    @foreach($academicRanks as $rank)
                                        <option value="{{ $rank->id }}" {{ old('academic_rank_id') == $rank->id ? 'selected' : '' }}>{{ $rank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_of_service" class="block text-sm font-medium text-gray-700 mb-1.5">Date of Service</label>
                                <input type="date" id="date_of_service" name="date_of_service" value="{{ old('date_of_service') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="campus_position" class="block text-sm font-medium text-gray-700 mb-1.5">Campus Position</label>
                                <input type="text" id="campus_position" name="campus_position" value="{{ old('campus_position') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="e.g., Dean, Program Head">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label for="membership_status" class="block text-sm font-medium text-gray-700 mb-1.5">Membership Status *</label>
                                <select id="membership_status" name="membership_status" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="active" {{ old('membership_status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('membership_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="retired" {{ old('membership_status') == 'retired' ? 'selected' : '' }}>Retired</option>
                                </select>
                            </div>

                            <div>
                                <label for="membership_date" class="block text-sm font-medium text-gray-700 mb-1.5">Membership Date</label>
                                <input type="date" id="membership_date" name="membership_date" value="{{ old('membership_date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="officer_position" class="block text-sm font-medium text-gray-700 mb-1.5">Officer Position</label>
                                <select id="officer_position" name="officer_position" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                    <option value="">None</option>
                                    <option value="President" {{ old('officer_position') == 'President' ? 'selected' : '' }}>President</option>
                                    <option value="Vice President" {{ old('officer_position') == 'Vice President' ? 'selected' : '' }}>Vice President</option>
                                    <option value="Secretary" {{ old('officer_position') == 'Secretary' ? 'selected' : '' }}>Secretary</option>
                                    <option value="Treasurer" {{ old('officer_position') == 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                                    <option value="Assistant Treasurer" {{ old('officer_position') == 'Assistant Treasurer' ? 'selected' : '' }}>Assistant Treasurer</option>
                                    <option value="Auditor" {{ old('officer_position') == 'Auditor' ? 'selected' : '' }}>Auditor</option>
                                    <option value="Business Manager" {{ old('officer_position') == 'Business Manager' ? 'selected' : '' }}>Business Manager</option>
                                    <option value="PIO" {{ old('officer_position') == 'PIO' ? 'selected' : '' }}>PIO</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                            <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500" placeholder="Additional notes about the member...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.faculty-profiles.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-300">
                        <span class="material-icons-round mr-2">person_add</span>
                        Register Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function memberForm() {
            return {
                selectedCampus: '{{ old('campus_id', '') }}',
                selectedCollege: '{{ old('college_id', '') }}',
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
                        return;
                    }
                    
                    this.loadingColleges = true;
                    try {
                        const response = await fetch(`{{ url('admin/members/colleges') }}/${this.selectedCampus}`);
                        this.colleges = await response.json();
                    } catch (error) {
                        console.error('Error loading colleges:', error);
                        this.colleges = [];
                    }
                    this.loadingColleges = false;
                }
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('preview-image');
            const icon = document.getElementById('preview-icon');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    icon.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
