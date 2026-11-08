<x-app-layout title="My Dashboard">
    @section('page-title', 'Dashboard')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h1 class="font-display font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <span class="material-icons-round text-primary-600">dashboard</span>
                {{ __('My Dashboard') }}
            </h1>
            <span class="text-sm text-gray-500 flex items-center gap-2">
                <span class="material-icons-round text-gray-400 text-lg">calendar_today</span>
                {{ now()->format('F d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Welcome Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 rounded-3xl shadow-lg p-5 sm:p-8 text-white reveal">
            <div class="absolute top-0 right-0 w-40 sm:w-64 h-40 sm:h-64 bg-white/10 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-32 sm:w-48 h-32 sm:h-48 bg-white/5 rounded-full filter blur-2xl translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-primary-200 text-xs sm:text-sm font-medium mb-1 sm:mb-2">Welcome back</p>
                    <h2 class="text-2xl sm:text-3xl font-display font-bold mb-1 sm:mb-2">{{ $profile->greeting_name ?? Auth::user()->name }}</h2>
                    <p class="text-white/80 text-sm sm:text-base">Here's an overview of your UFA membership status</p>
                </div>
                <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-3xl bg-white/10">
                    <span class="material-icons-round text-white/90" style="font-size: 56px;">school</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <!-- Total Claims -->
            <div class="group card-premium reveal">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Total Claims</p>
                        <p class="text-2xl sm:text-4xl font-display font-bold text-gray-900 mt-1 sm:mt-2">{{ $stats['totalClaims'] ?? 0 }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <span class="material-icons-round text-emerald-500 text-xs sm:text-sm">trending_up</span>
                            All time
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center transition-colors duration-300 flex-shrink-0">
                        <span class="material-icons-round text-primary-600 text-xl sm:text-[32px]">receipt_long</span>
                    </div>
                </div>
            </div>

            <!-- Pending Claims -->
            <div class="group card-premium reveal">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Pending Claims</p>
                        <p class="text-2xl sm:text-4xl font-display font-bold text-primary-500 mt-1 sm:mt-2">{{ $stats['pendingClaims'] ?? 0 }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <span class="material-icons-round text-primary-400 text-xs sm:text-sm">schedule</span>
                            Under review
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center transition-colors duration-300 flex-shrink-0">
                        <span class="material-icons-round text-primary-500 text-xl sm:text-[32px]">pending_actions</span>
                    </div>
                </div>
            </div>

            <!-- Beneficiaries -->
            <div class="group card-premium reveal">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Beneficiaries</p>
                        <p class="text-2xl sm:text-4xl font-display font-bold text-gray-900 mt-1 sm:mt-2">{{ $stats['beneficiariesCount'] ?? 0 }}</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 flex items-center gap-1">
                            <span class="material-icons-round text-emerald-500 text-xs sm:text-sm">verified</span>
                            Registered
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center transition-colors duration-300 flex-shrink-0">
                        <span class="material-icons-round text-emerald-600 text-xl sm:text-[32px]">family_restroom</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- My Recent Claims -->
            <div class="card-premium overflow-hidden reveal">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-display font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-icons-round text-primary-500">history</span>
                        My Recent Claims
                    </h3>
                    <a href="{{ route('claims.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1 group py-1">
                        View All 
                        <span class="material-icons-round text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentClaims ?? [] as $claim)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50/50 transition-colors group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons-round text-primary-600 text-sm sm:text-base">description</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm sm:text-base truncate">{{ $claim->claimType->name ?? 'Unknown' }}</p>
                                        <p class="text-sm text-gray-500">{{ $claim->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-display font-bold text-gray-900">₱{{ number_format($claim->amount, 2) }}</p>
                                    @php
                                        $claimBadge = match($claim->status) {
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'released' => 'bg-emerald-100 text-emerald-700',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $claimBadge }}">
                                        {{ ucfirst($claim->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                <span class="material-icons-round text-gray-400" style="font-size: 32px;">inbox</span>
                            </div>
                            <p class="text-gray-500 font-medium">No claims submitted yet</p>
                            <a href="{{ route('claims.create') }}" class="inline-flex items-center gap-2 mt-4 text-primary-600 hover:text-primary-700 font-semibold">
                                <span class="material-icons-round text-lg">add_circle</span>
                                Submit your first claim
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- My Profile Summary -->
            <div class="card-premium overflow-hidden reveal">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">
                    <h3 class="text-base sm:text-lg font-display font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-icons-round text-primary-500">badge</span>
                        Profile Summary
                    </h3>
                </div>
                <div class="p-4 sm:p-6">
                    @if($profile)
                        <div class="text-center mb-4 sm:mb-6">
                            <div class="relative inline-block">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-800 flex items-center justify-center text-white text-xl sm:text-2xl font-display font-bold shadow-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-emerald-500 border-4 border-white flex items-center justify-center">
                                    <span class="material-icons-round text-white text-sm">check</span>
                                </div>
                            </div>
                            <h4 class="mt-4 font-display font-bold text-gray-900 text-lg">{{ Auth::user()->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $profile->academicRank->name ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <span class="material-icons-round text-lg text-gray-400">apartment</span>
                                    Campus
                                </span>
                                <span class="font-semibold text-gray-900">{{ $profile->campus->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <span class="material-icons-round text-lg text-gray-400">school</span>
                                    College
                                </span>
                                <span class="font-semibold text-gray-900">{{ $profile->college->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                <span class="text-gray-500 flex items-center gap-2">
                                    <span class="material-icons-round text-lg text-gray-400">event</span>
                                    Member Since
                                </span>
                                <span class="font-semibold text-gray-900">{{ $profile->membership_date?->format('M Y') ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('my-profile') }}" class="mt-6 flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-primary-50 text-primary-600 font-semibold hover:bg-primary-100 transition-colors">
                            View Full Profile
                            <span class="material-icons-round text-lg">arrow_forward</span>
                        </a>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                <span class="material-icons-round text-gray-400" style="font-size: 32px;">person_off</span>
                            </div>
                            <p class="text-gray-500 font-medium">Profile not set up</p>
                            <p class="text-sm text-gray-400 mt-1">Please contact an administrator</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Trainings -->
            <div class="card-premium overflow-hidden reveal">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-display font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-icons-round text-primary-500">event</span>
                        Upcoming Trainings
                    </h3>
                    <a href="{{ route('trainings.index') }}" class="inline-block py-1 text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($upcomingTrainings ?? [] as $training)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50/50 transition-colors group">
                            <div class="flex items-start gap-2 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons-round text-blue-600 text-sm sm:text-base">school</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $training->title }}</p>
                                    <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                                        <span class="material-icons-round text-sm">calendar_today</span>
                                        {{ $training->start_datetime->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                <span class="material-icons-round text-gray-400" style="font-size: 32px;">event_busy</span>
                            </div>
                            <p class="text-gray-500 font-medium">No upcoming trainings</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card-premium overflow-hidden reveal">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">
                    <h3 class="text-base sm:text-lg font-display font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-icons-round text-primary-500">bolt</span>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-3 sm:p-4 space-y-2 sm:space-y-3">
                    <a href="{{ route('claims.create') }}" 
                       class="flex items-center gap-3 sm:gap-4 px-3 sm:px-4 py-3 sm:py-4 rounded-xl bg-gradient-to-r from-primary-50 to-primary-100/50 text-primary-700 hover:from-primary-100 hover:to-primary-200/50 transition-all group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white shadow-md flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-round text-primary-600 text-sm sm:text-base">add_circle</span>
                        </div>
                        <div>
                            <span class="font-semibold">Submit New Claim</span>
                            <p class="text-sm text-primary-600/70">File a new benefit claim</p>
                        </div>
                    </a>
                    <a href="{{ route('beneficiaries.create') }}" 
                       class="flex items-center gap-3 sm:gap-4 px-3 sm:px-4 py-3 sm:py-4 rounded-xl bg-gradient-to-r from-primary-50 to-primary-100/50 text-primary-700 hover:from-primary-100 hover:to-primary-200/50 transition-all group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white shadow-md flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-round text-emerald-600 text-sm sm:text-base">person_add</span>
                        </div>
                        <div>
                            <span class="font-semibold">Add Beneficiary</span>
                            <p class="text-sm text-emerald-600/70">Register a new beneficiary</p>
                        </div>
                    </a>
                    <a href="{{ route('my-profile.edit') }}" 
                       class="flex items-center gap-3 sm:gap-4 px-3 sm:px-4 py-3 sm:py-4 rounded-xl bg-gradient-to-r from-primary-50 to-primary-100/50 text-primary-700 hover:from-primary-100 hover:to-primary-200/50 transition-all group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white shadow-md flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-round text-blue-600 text-sm sm:text-base">edit</span>
                        </div>
                        <div>
                            <span class="font-semibold">Update Profile</span>
                            <p class="text-sm text-blue-600/70">Edit your information</p>
                        </div>
                    </a>
                    <a href="{{ route('transparency.index') }}" 
                       class="flex items-center gap-3 sm:gap-4 px-3 sm:px-4 py-3 sm:py-4 rounded-xl bg-gradient-to-r from-primary-50 to-primary-100/50 text-primary-700 hover:from-primary-100 hover:to-primary-200/50 transition-all group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white shadow-md flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-round text-purple-600 text-sm sm:text-base">visibility</span>
                        </div>
                        <div>
                            <span class="font-semibold">Transparency Wall</span>
                            <p class="text-sm text-purple-600/70">View financial reports</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
