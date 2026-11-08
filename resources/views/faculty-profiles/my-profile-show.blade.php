<x-app-layout title="My Profile">
    @section('page-title', 'My Profile')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-primary-600">person</span>
                {{ __('My Profile') }}
            </h1>
            <a href="{{ route('my-profile.edit') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm">
                <span class="material-icons mr-2">edit</span>
                Edit Profile
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <span class="material-icons mr-2">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card with ID Picture -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl material-shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-600 to-primary-800 px-4 sm:px-6 py-6 sm:py-8 text-center text-white">
                        @if($facultyProfile->id_picture)
                            <!-- Clickable Profile Photo -->
                            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full mx-auto border-4 border-white/30 overflow-hidden shadow-lg cursor-pointer hover:border-white/60 transition-all hover:scale-105 relative group"
                                 onclick="openPhotoModal()">
                                <img src="{{ Storage::url($facultyProfile->id_picture) }}" 
                                     alt="Profile Picture" 
                                     class="w-full h-full object-cover">
                                <!-- Hover overlay -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="material-icons text-white text-2xl">zoom_in</span>
                                </div>
                            </div>
                            <p class="text-xs text-indigo-200 mt-2">Click to enlarge</p>
                        @else
                            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-white/20 flex items-center justify-center mx-auto border-4 border-white/30">
                                <span class="material-icons text-4xl sm:text-6xl text-white/70">person</span>
                            </div>
                        @endif
                        <h3 class="mt-4 text-xl font-bold">
                            @if($facultyProfile->prefix){{ $facultyProfile->prefix }} @endif
                            {{ $facultyProfile->first_name }} 
                            @if($facultyProfile->middle_name){{ substr($facultyProfile->middle_name, 0, 1) }}. @endif
                            {{ $facultyProfile->surname }}
                            @if($facultyProfile->extension_name) {{ $facultyProfile->extension_name }}@endif
                        </h3>
                        <p class="text-indigo-200">{{ $facultyProfile->academicRank->name ?? 'Rank Pending' }}</p>
                        <span class="inline-flex items-center mt-3 px-3 py-1 rounded-full text-sm font-medium
                            @if($facultyProfile->membership_status === 'active') bg-green-400/20 text-green-100
                            @elseif($facultyProfile->membership_status === 'inactive') bg-yellow-400/20 text-yellow-100
                            @elseif($facultyProfile->membership_status === 'retired') bg-blue-400/20 text-blue-100
                            @else bg-gray-400/20 text-gray-100
                            @endif">
                            <span class="material-icons text-sm mr-1">
                                @if($facultyProfile->membership_status === 'active')check_circle
                                @elseif($facultyProfile->membership_status === 'inactive')pending
                                @elseif($facultyProfile->membership_status === 'retired')elderly
                                @else help_outline
                                @endif
                            </span>
                            {{ ucfirst($facultyProfile->membership_status ?? 'Unknown') }}
                        </span>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4">
                        <div class="flex items-center text-sm">
                            <span class="material-icons text-indigo-500 mr-3">badge</span>
                            <div>
                                <p class="text-gray-500">Employee ID</p>
                                <p class="font-medium">{{ $facultyProfile->user->employee_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="material-icons text-indigo-500 mr-3">phone</span>
                            <div>
                                <p class="text-gray-500">Phone Number</p>
                                <p class="font-medium">{{ $facultyProfile->phone_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="material-icons text-indigo-500 mr-3">home</span>
                            <div>
                                <p class="text-gray-500">Home Address</p>
                                <p class="font-medium">{{ $facultyProfile->home_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-xl material-shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons mr-2 text-primary-600">person</span>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Prefix/Title</p>
                            <p class="font-medium text-gray-900">{{ $facultyProfile->prefix ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Surname</p>
                            <p class="font-medium text-gray-900">{{ $facultyProfile->surname ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">First Name</p>
                            <p class="font-medium text-gray-900">{{ $facultyProfile->first_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Middle Name</p>
                            <p class="font-medium text-gray-900">{{ $facultyProfile->middle_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Extension Name</p>
                            <p class="font-medium text-gray-900">{{ $facultyProfile->extension_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Civil Status</p>
                            <p class="font-medium text-gray-900">
                                @switch($facultyProfile->civil_status)
                                    @case('single') Single (S) @break
                                    @case('married') Married (M) @break
                                    @case('widowed') Widowed (W) @break
                                    @case('separated') Separated @break
                                    @default N/A
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Gender</p>
                            <p class="font-medium text-gray-900">
                                @switch($facultyProfile->gender)
                                    @case('male') Male (M) @break
                                    @case('female') Female (F) @break
                                    @case('lgbtqia+') LGBTQ+ @break
                                    @default N/A
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Birthdate</p>
                            <p class="font-medium text-gray-900">{{ $facultyProfile->birth_date?->format('F d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Birth Place</p>
                            <p class="font-medium text-gray-900">{{ $facultyProfile->birth_place ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Professional Information (Admin-managed) -->
                <div class="bg-white rounded-xl material-shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons mr-2 text-amber-600">work</span>
                            Professional Information
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Employee ID</p>
                            <p class="font-medium text-gray-900">
                                @if($facultyProfile->employee_id && !str_starts_with($facultyProfile->employee_id, 'PENDING'))
                                    {{ $facultyProfile->employee_id }}
                                @else
                                    <span class="text-amber-600 italic">Pending assignment</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date of Service (1st Day)</p>
                            <p class="font-medium text-gray-900">
                                @if($facultyProfile->date_of_service)
                                    {{ $facultyProfile->date_of_service->format('F d, Y') }}
                                @else
                                    <span class="text-amber-600 italic">Pending assignment</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Academic Rank</p>
                            <p class="font-medium text-gray-900">
                                @if($facultyProfile->academicRank)
                                    {{ $facultyProfile->academicRank->name }}
                                @else
                                    <span class="text-amber-600 italic">Pending assignment</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Campus</p>
                            <p class="font-medium text-gray-900">
                                @if($facultyProfile->campus)
                                    {{ $facultyProfile->campus->name }}
                                @else
                                    <span class="text-amber-600 italic">Pending assignment</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">College</p>
                            <p class="font-medium text-gray-900">
                                @if($facultyProfile->college)
                                    {{ $facultyProfile->college->name }}
                                @else
                                    <span class="text-amber-600 italic">Pending assignment</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Membership Status</p>
                            <p class="font-medium">
                                @if($facultyProfile->membership_status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <span class="material-icons text-sm mr-1">check_circle</span> Active Member
                                    </span>
                                @elseif($facultyProfile->membership_status == 'inactive')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <span class="material-icons text-sm mr-1">pending</span> Pending Verification
                                    </span>
                                @elseif($facultyProfile->membership_status == 'retired')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <span class="material-icons text-sm mr-1">elderly</span> Retired
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($facultyProfile->membership_status ?? 'Unknown') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($facultyProfile->membership_status == 'inactive')
                    <div class="px-6 pb-6">
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div class="flex items-start">
                                <span class="material-icons text-yellow-600 mr-3">info</span>
                                <div>
                                    <p class="text-sm text-yellow-800 font-medium">Membership Pending Verification</p>
                                    <p class="text-sm text-yellow-600 mt-1">Your membership is currently being reviewed by the administrator. Once verified, your professional information will be updated and your membership will be activated.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl material-shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons mr-2 text-primary-600">flash_on</span>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('my-profile.edit') }}" 
                           class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                            <span class="material-icons text-primary-600 mr-3">edit</span>
                            <div>
                                <p class="font-medium text-indigo-900">Edit Personal Info</p>
                                <p class="text-sm text-primary-600">Update your personal information</p>
                            </div>
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <span class="material-icons text-gray-600 mr-3">dashboard</span>
                            <div>
                                <p class="font-medium text-gray-900">Dashboard</p>
                                <p class="text-sm text-gray-600">Go back to your dashboard</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
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
                        {{ $facultyProfile->first_name }} {{ $facultyProfile->surname }}
                    </p>
                    <button onclick="closePhotoModal()" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
    @endif
</x-app-layout>
