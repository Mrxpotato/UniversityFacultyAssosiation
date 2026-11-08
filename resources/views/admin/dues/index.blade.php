<x-app-layout title="Dues Collection">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">account_balance_wallet</span>
                <h1 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    {{ __('Deductions Collection') }}
                </h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dues.export', request()->query()) }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    <span class="material-icons-outlined mr-1 sm:mr-2 text-sm">download</span>
                    Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <span class="material-icons mr-2">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Fund Balance Banner -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-4 sm:p-6 material-shadow mb-6 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-sm text-indigo-200">Available Fund Balance</p>
                        <p class="text-2xl sm:text-3xl font-bold">₱{{ number_format($fundBalance ?? 0, 2) }}</p>
                        <p class="text-xs text-indigo-200 mt-1">Total collected salary deductions minus released claims</p>
                    </div>
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons-outlined text-white text-2xl sm:text-[32px]">savings</span>
                    </div>
                </div>
            </div>

            <!-- Generate + Month Selector -->
            <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                    <!-- Month Selector -->
                    <div>
                        <label for="dues-month" class="block text-sm font-medium text-gray-700 mb-1">Select Month</label>
                        <form method="GET" id="monthForm">
                            <select id="dues-month" name="month" onchange="document.getElementById('monthForm').submit()" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @php
                                    $currentMonth = request('month', now()->format('Y-m'));
                                    $monthOptions = collect([$currentMonth, now()->format('Y-m')])->merge($availableMonths ?? collect())->unique()->sort()->reverse();
                                @endphp
                                @foreach($monthOptions as $m)
                                    <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $m)->format('F Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Generate Button -->
                    @php $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y'); @endphp
                    <div>
                        <form method="POST" action="{{ route('admin.dues.generate') }}" onsubmit="return confirm('Generate salary deductions for {{ $monthLabel }}? This will create deduction records for all active members based on current Deduction Settings.')">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <span class="material-icons-outlined mr-2 text-sm">auto_fix_high</span>
                                Generate for {{ $monthLabel }}
                            </button>
                        </form>
                    </div>

                    <!-- Bulk Mark Campus Collected -->
                    <div>
                        <form method="POST" action="{{ route('admin.dues.mark-campus-collected') }}" class="flex gap-2"
                              onsubmit="return confirm('Mark ALL pending salary deductions for this campus as collected? This means the deductions have been deducted from their salaries.')">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <select name="campus_id" aria-label="Campus to mark as collected" required class="flex-1 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Campus...</option>
                                @foreach($campuses ?? [] as $campus)
                                    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm whitespace-nowrap">
                                <span class="material-icons-outlined mr-1 text-sm">done_all</span>
                                Collect All
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Total Deductions</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-800 truncate">₱{{ number_format($statistics['total_amount'] ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $statistics['total_count'] ?? 0 }} records</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 ml-2">
                            <span class="material-icons-outlined text-indigo-600">account_balance_wallet</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Collected from Salary</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-600 truncate">₱{{ number_format($statistics['collected_amount'] ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $statistics['collected_count'] ?? 0 }} members</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 ml-2">
                            <span class="material-icons-outlined text-green-600">check_circle</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Pending Collection</p>
                            <p class="text-lg sm:text-2xl font-bold text-yellow-600 truncate">₱{{ number_format($statistics['pending_amount'] ?? 0, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $statistics['pending_count'] ?? 0 }} members</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0 ml-2">
                            <span class="material-icons-outlined text-yellow-600">pending</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Collection Rate</p>
                            @php
                                $rate = ($statistics['total_count'] ?? 0) > 0
                                    ? round(($statistics['collected_count'] / $statistics['total_count']) * 100, 1)
                                    : 0;
                            @endphp
                            <p class="text-lg sm:text-2xl font-bold text-blue-600">{{ $rate }}%</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $statistics['collected_count'] ?? 0 }} of {{ $statistics['total_count'] ?? 0 }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 ml-2">
                            <span class="material-icons-outlined text-blue-600">pie_chart</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl p-4 material-shadow mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" id="filterForm">
                    <input type="hidden" name="month" value="{{ $month }}">

                    <select name="campus_id" aria-label="Filter by campus" onchange="document.getElementById('filterForm').submit()" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Campuses</option>
                        @foreach($campuses ?? [] as $campus)
                            <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" aria-label="Filter by status" onchange="document.getElementById('filterForm').submit()" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="collected" {{ request('status') == 'collected' ? 'selected' : '' }}>Collected</option>
                    </select>

                    <div class="relative sm:col-span-2 lg:col-span-2">
                        <input type="text" name="search" aria-label="Search by name or employee ID" value="{{ request('search') }}" placeholder="Search by name or employee ID..."
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 pr-8">
                            <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-indigo-600">
                            <span class="material-icons-outlined text-sm">search</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Deductions Table -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deduction</th>
                                <th class="hidden xl:table-cell px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Salary</th>
                                <th class="hidden xl:table-cell px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($deductions ?? [] as $ded)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                                <span class="material-icons-outlined text-indigo-600 text-sm sm:text-base">person</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 text-xs sm:text-sm truncate">{{ $ded->facultyProfile->user->name ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ $ded->facultyProfile->employee_id ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                        {{ $ded->facultyProfile->campus->name ?? 'N/A' }}
                                    </td>
                                    <td class="hidden lg:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                                        <p class="font-medium text-gray-800">{{ $ded->description }}</p>
                                        <p class="text-xs text-gray-400">{{ ucfirst($ded->deduction_type) }}</p>
                                    </td>
                                    <td class="hidden xl:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm text-gray-500">
                                        ₱{{ number_format($ded->gross_salary, 2) }}
                                    </td>
                                    <td class="hidden xl:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm text-gray-500">
                                        @if($ded->deduction_type === 'percentage')
                                            {{ number_format($ded->deduction_value, 2) }}%
                                        @else
                                            ₱{{ number_format($ded->deduction_value, 2) }}
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right font-semibold text-red-600 text-xs sm:text-sm">
                                        ₱{{ number_format($ded->deduction_amount, 2) }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center">
                                        @if($ded->status === 'collected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-icons text-xs mr-1">check_circle</span>
                                                Collected
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="material-icons text-xs mr-1">pending</span>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                        @if($ded->status !== 'collected')
                                            <form action="{{ route('admin.dues.mark-collected', $ded) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm" onclick="return confirm('Mark this salary deduction as collected?')">
                                                    <span class="material-icons-outlined text-sm mr-1">check</span>
                                                    Collected
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">
                                                {{ $ded->collected_at?->format('M d, Y') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined text-gray-300 mb-2" style="font-size: 48px;">account_balance_wallet</span>
                                            <p class="text-gray-500">No salary deductions for this month</p>
                                            <p class="text-sm text-gray-400 mt-1">Click "Generate" to create deduction records for all active members</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($deductions) && $deductions->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $deductions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
