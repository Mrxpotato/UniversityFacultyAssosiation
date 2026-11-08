<x-app-layout title="Add Faculty Member">
    @section('page-title', 'Add Faculty Member')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-indigo-600">person_add</span>
                {{ __('Add New Faculty Member') }}
            </h1>
            <a href="{{ route('admin.faculty-profiles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                <span class="material-icons mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto" x-data="facultyProfileForm()">
        <form method="POST" action="{{ route('admin.faculty-profiles.store') }}" class="space-y-6">
            @csrf

            <!-- User Account Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">person</span>
                        Personal Information
                    </h3>
                </div>
                <div class="p-4 sm:p-6 space-y-6">
                    <!-- Name Fields Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div>
                            <label for="prefix" class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                            <select name="prefix" id="prefix"
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
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
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('first_name') border-red-500 @enderror"
                                   placeholder="Juan">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Santos">
                        </div>
                        <div>
                            <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">Surname *</label>
                            <input type="text" name="surname" id="surname" value="{{ old('surname') }}" required
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('surname') border-red-500 @enderror"
                                   placeholder="Dela Cruz">
                            @error('surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="extension_name" class="block text-sm font-medium text-gray-700 mb-1">Ext. Name</label>
                            <input type="text" name="extension_name" id="extension_name" value="{{ old('extension_name') }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Jr., Sr., III">
                        </div>
                    </div>

                    <!-- Personal Details Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                            <select name="gender" id="gender" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="lgbtqia+" {{ old('gender') == 'lgbtqia+' ? 'selected' : '' }}>LGBTQ+</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1">Civil Status *</label>
                            <select name="civil_status" id="civil_status" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('civil_status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="separated" {{ old('civil_status') == 'separated' ? 'selected' : '' }}>Separated</option>
                            </select>
                            @error('civil_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact & Address Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-1">Birth Place</label>
                            <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="City, Province">
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                   class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="09XX XXX XXXX">
                        </div>
                    </div>
                    <x-ph-address-fields
                        :province="old('address_province', '')"
                        :city="old('address_city', '')"
                        :barangay="old('address_barangay', '')"
                        :street="old('address_street', '')"
                        inputClass="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">account_circle</span>
                        Account Information
                    </h3>
                </div>
                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div></div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" id="password" required
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

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
                        <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}" required
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
                                <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>
                                    {{ $campus->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('campus_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="college_id" class="block text-sm font-medium text-gray-700 mb-1">College</label>
                        <select name="college_id" id="college_id"
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
                        <label for="academic_rank_id" class="block text-sm font-medium text-gray-700 mb-1">Academic Rank</label>
                        <select name="academic_rank_id" id="academic_rank_id"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('academic_rank_id') border-red-500 @enderror">
                            <option value="">Select Rank</option>
                            @foreach($academicRanks ?? [] as $rank)
                                <option value="{{ $rank->id }}" {{ old('academic_rank_id') == $rank->id ? 'selected' : '' }}>
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
                        <input type="date" name="date_of_service" id="date_of_service" value="{{ old('date_of_service') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('date_of_service') border-red-500 @enderror">
                        @error('date_of_service')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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
                        <label for="membership_date" class="block text-sm font-medium text-gray-700 mb-1">Membership Date</label>
                        <input type="date" name="membership_date" id="membership_date" value="{{ old('membership_date', date('Y-m-d')) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('membership_date') border-red-500 @enderror">
                        @error('membership_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="membership_status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="membership_status" id="membership_status" required
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('membership_status') border-red-500 @enderror">
                            <option value="active" {{ old('membership_status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('membership_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="retired" {{ old('membership_status') == 'retired' ? 'selected' : '' }}>Retired</option>
                        </select>
                        @error('membership_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Additional notes about the member...">{{ old('notes') }}</textarea>
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
                    Save Faculty Member
                </button>
            </div>
        </form>
    </div>

    <script>
        function facultyProfileForm() {
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
                        this.selectedCollege = '';
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
    </script>
</x-app-layout>
