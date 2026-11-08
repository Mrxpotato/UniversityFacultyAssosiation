<x-app-layout title="Faculty Profile">
    @section('page-title', 'View Faculty Member')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-primary-600">person</span>
                {{ __('Faculty Member Details') }}
            </h1>
            <div class="flex items-center space-x-2">
                @if($facultyProfile->user && $facultyProfile->user->is_active)
                    <form method="POST" action="{{ route('admin.faculty-profiles.deactivate', $facultyProfile) }}" 
                          onsubmit="return confirm('Are you sure you want to deactivate this member\'s account? They will no longer be able to log in.')" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors material-shadow-sm text-sm">
                            <span class="material-icons mr-1 sm:mr-2">person_off</span>
                            Deactivate
                        </button>
                    </form>
                @elseif($facultyProfile->user && !$facultyProfile->user->is_active)
                    <form method="POST" action="{{ route('admin.faculty-profiles.reactivate', $facultyProfile) }}" 
                          onsubmit="return confirm('Reactivate this member\'s account? They will be able to log in again.')" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors material-shadow-sm text-sm">
                            <span class="material-icons mr-1 sm:mr-2">person_add</span>
                            Reactivate
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.faculty-profiles.edit', $facultyProfile) }}" 
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm text-sm">
                    <span class="material-icons mr-1 sm:mr-2">edit</span>
                    Edit
                </a>
                <a href="{{ route('admin.faculty-profiles.index') }}" 
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                    <span class="material-icons mr-1 sm:mr-2">arrow_back</span>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="bg-gradient-to-r from-primary-600 to-primary-800 px-4 sm:px-6 py-6 sm:py-8 text-center text-white">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-white/20 flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto">
                        {{ strtoupper(substr($facultyProfile->first_name ?? 'U', 0, 1) . substr($facultyProfile->surname ?? '', 0, 1)) }}
                    </div>
                    <h3 class="mt-4 text-xl font-bold">{{ $facultyProfile->display_name ?? 'Unknown' }}</h3>
                    <p class="text-indigo-200">{{ $facultyProfile->academicRank->name ?? 'N/A' }}</p>
                    @if($facultyProfile->user && !$facultyProfile->user->is_active)
                        <span class="inline-flex items-center mt-3 px-3 py-1 rounded-full text-sm font-medium bg-red-500/30 text-red-100">
                            <span class="material-icons text-sm mr-1">block</span>
                            Account Deactivated
                        </span>
                    @else
                        <span class="inline-flex items-center mt-3 px-3 py-1 rounded-full text-sm font-medium
                            @if($facultyProfile->membership_status === 'active') bg-green-400/20 text-green-100
                            @elseif($facultyProfile->membership_status === 'inactive') bg-gray-400/20 text-gray-100
                            @else bg-amber-400/20 text-amber-100
                            @endif">
                            {{ ucfirst($facultyProfile->membership_status) }}
                        </span>
                    @endif
                </div>
                <div class="p-4 sm:p-6 space-y-4">
                    <div class="flex items-center text-sm">
                        <span class="material-icons text-gray-400 mr-3">badge</span>
                        <div>
                            <p class="text-gray-500">Employee ID</p>
                            <p class="font-medium">{{ $facultyProfile->employee_id }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="material-icons text-gray-400 mr-3">badge</span>
                        <div>
                            <p class="text-gray-500">Employee ID (Login)</p>
                            <p class="font-medium">{{ $facultyProfile->user->employee_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="material-icons text-gray-400 mr-3">phone</span>
                        <div>
                            <p class="text-gray-500">Contact Number</p>
                            <p class="font-medium">{{ $facultyProfile->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="material-icons text-gray-400 mr-3">location_on</span>
                        <div>
                            <p class="text-gray-500">Address</p>
                            <p class="font-medium">{{ $facultyProfile->home_address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employment Details -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">work</span>
                        Employment Information
                    </h3>
                </div>
                <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Campus</p>
                        <p class="font-medium">{{ $facultyProfile->campus->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">College</p>
                        <p class="font-medium">{{ $facultyProfile->college->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Department</p>
                        <p class="font-medium">{{ $facultyProfile->department ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Academic Rank</p>
                        <p class="font-medium">{{ $facultyProfile->academicRank->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date Hired</p>
                        <p class="font-medium">{{ $facultyProfile->date_hired?->format('F d, Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Personal Details -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">person</span>
                        Personal Information
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Date of Birth</p>
                        <p class="font-medium">{{ $facultyProfile->date_of_birth?->format('F d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="font-medium">{{ ucfirst($facultyProfile->gender ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Civil Status</p>
                        <p class="font-medium">{{ ucfirst($facultyProfile->civil_status ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Membership Details -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">card_membership</span>
                        Membership Information
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Membership Date</p>
                        <p class="font-medium">{{ $facultyProfile->membership_date?->format('F d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Monthly Contribution</p>
                        <p class="font-medium">₱{{ number_format($facultyProfile->monthly_contribution ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Member Status</p>
                        @if($facultyProfile->user && !$facultyProfile->user->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <span class="material-icons text-xs mr-0.5">block</span>
                                Deactivated
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($facultyProfile->membership_status === 'active') bg-green-100 text-green-800
                                @elseif($facultyProfile->membership_status === 'inactive') bg-gray-100 text-gray-800
                                @else bg-amber-100 text-amber-800
                                @endif">
                                {{ ucfirst($facultyProfile->membership_status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Beneficiaries -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">family_restroom</span>
                        Beneficiaries ({{ $facultyProfile->beneficiaries->count() }})
                    </h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($facultyProfile->beneficiaries as $beneficiary)
                        <div class="px-4 sm:px-6 py-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-start gap-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $beneficiary->full_name }}</p>
                                    <p class="text-sm text-gray-500">{{ ucfirst($beneficiary->relationship) }}</p>
                                </div>
                                <div class="sm:text-right">
                                    <p class="text-sm text-gray-500">{{ $beneficiary->date_of_birth?->format('M d, Y') }}</p>
                                    @if($beneficiary->is_primary)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">Primary</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <span class="material-icons text-4xl text-gray-300">person_off</span>
                            <p class="mt-2">No beneficiaries registered</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Claims -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">receipt_long</span>
                        Recent Claims
                    </h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($facultyProfile->claims->take(5) as $claim)
                        <div class="px-4 sm:px-6 py-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $claim->claimType->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $claim->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="sm:text-right">
                                    <p class="font-semibold text-gray-900">₱{{ number_format($claim->amount, 2) }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($claim->status === 'pending') bg-amber-100 text-amber-800
                                        @elseif($claim->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($claim->status === 'released') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($claim->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <span class="material-icons text-4xl text-gray-300">inbox</span>
                            <p class="mt-2">No claims submitted</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
