<x-app-layout title="Admin Dashboard">
    @section('page-title', 'Dashboard')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-indigo-600">dashboard</span>
                {{ __('Admin Dashboard') }}
            </h1>
            <span class="text-sm text-gray-500">{{ now()->format('F d, Y') }}</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 rounded-3xl shadow-lg p-5 sm:p-8 text-white reveal">
            <div class="absolute top-0 right-0 w-40 sm:w-64 h-40 sm:h-64 bg-white/10 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-32 sm:w-48 h-32 sm:h-48 bg-white/5 rounded-full filter blur-2xl translate-y-1/2 -translate-x-1/4"></div>
            <div class="relative flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-primary-200 text-xs sm:text-sm font-medium mb-1 sm:mb-2">Welcome back</p>
                    <h2 class="text-2xl sm:text-3xl font-display font-bold mb-1 sm:mb-2">{{ Auth::user()->facultyProfile?->greeting_name ?? Auth::user()->name }}</h2>
                    <p class="text-white/80 text-sm sm:text-base">Here's an overview of your UFA membership status</p>
                </div>
                <div class="hidden md:flex items-center justify-center w-24 h-24 rounded-3xl bg-white/10">
                    <span class="material-icons-round text-white/90" style="font-size: 56px;">school</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            <!-- Total Members -->
            <div class="bg-white rounded-xl material-shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Total Members</p>
                        <p class="text-xl sm:text-3xl font-bold text-gray-900 mt-1 sm:mt-2">{{ $stats['totalMembers'] ?? 0 }}</p>
                        <p class="text-xs sm:text-sm text-green-600 mt-1 flex items-center">
                            <span class="material-icons text-xs sm:text-sm">trending_up</span>
                            <span class="ml-1">Active Faculty</span>
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-indigo-600 text-lg sm:text-2xl">groups</span>
                    </div>
                </div>
            </div>

            <!-- Pending Claims -->
            <div class="bg-white rounded-xl material-shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Pending Claims</p>
                        <p class="text-xl sm:text-3xl font-bold text-gray-900 mt-1 sm:mt-2">{{ $stats['pendingClaims'] ?? 0 }}</p>
                        <p class="text-xs sm:text-sm text-amber-600 mt-1 flex items-center">
                            <span class="material-icons text-xs sm:text-sm">schedule</span>
                            <span class="ml-1">Awaiting Review</span>
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-amber-600 text-lg sm:text-2xl">receipt_long</span>
                    </div>
                </div>
            </div>

            <!-- Deductions Collected -->
            <div class="bg-white rounded-xl material-shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Deductions Collected</p>
                        <p class="text-xl sm:text-3xl font-bold text-gray-900 mt-1 sm:mt-2">₱{{ number_format($stats['totalDeductionsCollected'] ?? 0, 2) }}</p>
                        <p class="text-xs sm:text-sm text-green-600 mt-1 flex items-center">
                            <span class="material-icons text-xs sm:text-sm">payments</span>
                            <span class="ml-1">From Claims</span>
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-green-600 text-lg sm:text-2xl">account_balance_wallet</span>
                    </div>
                </div>
            </div>

            <!-- Claims Released -->
            <div class="bg-white rounded-xl material-shadow p-4 sm:p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Claims Released</p>
                        <p class="text-xl sm:text-3xl font-bold text-gray-900 mt-1 sm:mt-2">₱{{ number_format($stats['totalClaimsReleased'] ?? 0, 2) }}</p>
                        <p class="text-xs sm:text-sm text-blue-600 mt-1 flex items-center">
                            <span class="material-icons text-xs sm:text-sm">check_circle</span>
                            <span class="ml-1">This Year</span>
                        </p>
                    </div>
                    <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-blue-600 text-lg sm:text-2xl">trending_up</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Claims -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">pending_actions</span>
                        Recent Claims
                    </h3>
                    <a href="{{ route('claims.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center py-1">
                        View All <span class="material-icons text-sm ml-1">arrow_forward</span>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentClaims ?? [] as $claim)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons text-gray-600 text-sm sm:text-base">person</span>
                                    </div>
                                    <div class="ml-2 sm:ml-3 min-w-0">
                                        <p class="font-medium text-gray-900">{{ $claim->facultyProfile->display_name ?? 'Unknown' }}</p>
                                        <p class="text-sm text-gray-500">{{ $claim->claimType->name ?? 'Unknown Type' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">₱{{ number_format($claim->amount, 2) }}</p>
                                    @php
                                        $claimBadge = match($claim->status) {
                                            'pending' => 'bg-amber-100 text-amber-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'released' => 'bg-green-100 text-green-800',
                                            default => 'bg-red-100 text-red-800',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $claimBadge }}">
                                        {{ ucfirst($claim->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <span class="material-icons text-4xl text-gray-300">inbox</span>
                            <p class="mt-2">No recent claims</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Members by Campus -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">location_city</span>
                        Members by Campus
                    </h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="space-y-4">
                        @forelse($membersByCampus ?? [] as $campus)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $campus['campus'] ?? 'Unknown' }}</span>
                                    <span class="text-sm text-gray-500">{{ $campus['count'] ?? 0 }} members</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ (($campus['count'] ?? 0) / max($stats['totalMembers'] ?? 1, 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                <span class="material-icons text-4xl text-gray-300">apartment</span>
                                <p class="mt-2">No campus data available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Upcoming Trainings -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">event</span>
                        Upcoming Trainings
                    </h3>
                    <a href="{{ route('trainings.index') }}" class="inline-block py-1 text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($upcomingTrainings ?? [] as $training)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                            <p class="font-medium text-gray-900">{{ $training->title }}</p>
                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                <span class="material-icons text-sm mr-1">calendar_today</span>
                                {{ $training->start_datetime->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                <span class="material-icons text-sm mr-1">location_on</span>
                                {{ $training->venue }}
                            </p>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <span class="material-icons text-4xl text-gray-300">event_busy</span>
                            <p class="mt-2">No upcoming trainings</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pending Salary Deductions -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-yellow-600">pending</span>
                        Pending Salary Deductions
                    </h3>
                    <a href="{{ route('admin.dues.index', ['status' => 'pending']) }}" class="inline-block py-1 text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($pendingDeductions ?? [] as $ded)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $ded->facultyProfile->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $ded->description }} &middot; {{ $ded->facultyProfile->campus->name ?? 'N/A' }}</p>
                                </div>
                                <span class="text-red-600 font-semibold">₱{{ number_format($ded->deduction_amount, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <span class="material-icons text-4xl text-gray-300">check_circle</span>
                            <p class="mt-2">All salary deductions collected</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">flash_on</span>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-4 sm:p-6 space-y-2 sm:space-y-3">
                    <a href="{{ route('admin.faculty-profiles.create') }}" 
                       class="flex items-center px-4 py-3 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors">
                        <span class="material-icons mr-3">person_add</span>
                        Add New Faculty Member
                    </a>
                    <a href="{{ route('admin.dues.index') }}"
                       class="flex items-center px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                        <span class="material-icons mr-3">account_balance_wallet</span>
                        Deductions Collection
                    </a>
                    <a href="{{ route('admin.fund.index') }}"
                       class="flex items-center px-4 py-3 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition-colors">
                        <span class="material-icons mr-3">savings</span>
                        Fund Monitoring
                    </a>
                    <a href="{{ route('admin.transparency.create') }}" 
                       class="flex items-center px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                        <span class="material-icons mr-3">article</span>
                        Create Transparency Post
                    </a>
                    <a href="{{ route('admin.trainings.create') }}" 
                       class="flex items-center px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors">
                        <span class="material-icons mr-3">event</span>
                        Schedule Training
                    </a>
                    <a href="{{ route('admin.faculty-profiles.export') }}" 
                       class="flex items-center px-4 py-3 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition-colors">
                        <span class="material-icons mr-3">download</span>
                        Export Members List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
