<x-app-layout title="Claims Monitoring">
    @section('page-title', 'Claims Monitoring')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h1 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-primary-600">monitoring</span>
                {{ __('Claims Monitoring Dashboard') }}
            </h1>
            <span class="text-sm text-gray-500">{{ now()->format('F d, Y') }}</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-xl material-shadow p-4 sm:p-6">
            <form method="GET" action="{{ route('admin.claims-monitoring') }}" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 items-stretch sm:items-end">
                @if(!auth()->user()->isCampusPresident())
                <div>
                    <label for="monitor-campus_id" class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
                    <select id="monitor-campus_id" name="campus_id" class="rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Campuses</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}" {{ $campusId == $campus->id ? 'selected' : '' }}>
                                {{ $campus->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label for="monitor-date_from" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                    <input type="date" id="monitor-date_from" name="date_from" value="{{ $dateFrom }}" 
                           class="rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label for="monitor-date_to" class="block text-sm font-medium text-gray-700 mb-1">To</label>
                    <input type="date" id="monitor-date_to" name="date_to" value="{{ $dateTo }}" 
                           class="rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <span class="material-icons mr-1 text-sm">filter_list</span>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <!-- Total Claims -->
            <div class="bg-white rounded-xl material-shadow p-3 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Total Claims</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalClaims) }}</p>
                    </div>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-indigo-600 text-lg sm:text-2xl">receipt_long</span>
                    </div>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="bg-white rounded-xl material-shadow p-3 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1 truncate">₱{{ number_format($totalAmount, 2) }}</p>
                    </div>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-blue-600 text-lg sm:text-2xl">payments</span>
                    </div>
                </div>
            </div>

            <!-- Released Amount -->
            <div class="bg-white rounded-xl material-shadow p-3 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Released</p>
                        <p class="text-lg sm:text-2xl font-bold text-green-600 mt-1 truncate">₱{{ number_format($releasedAmount, 2) }}</p>
                    </div>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-green-600 text-lg sm:text-2xl">check_circle</span>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white rounded-xl material-shadow p-3 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</p>
                        <p class="text-lg sm:text-2xl font-bold text-amber-600 mt-1">{{ number_format($pendingCount) }}</p>
                    </div>
                    <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons text-amber-600 text-lg sm:text-2xl">schedule</span>
                    </div>
                </div>
            </div>

            <!-- Avg Processing -->
            <div class="bg-white rounded-xl material-shadow p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Processing</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $avgProcessingDays ? number_format($avgProcessingDays, 1) . 'd' : 'N/A' }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <span class="material-icons text-purple-600">speed</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Status Breakdown -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">donut_large</span>
                        Claims by Status
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $statuses = [
                            'pending' => ['label' => 'Pending', 'color' => 'amber', 'icon' => 'schedule'],
                            'under_review' => ['label' => 'Under Review', 'color' => 'blue', 'icon' => 'find_in_page'],
                            'approved' => ['label' => 'Approved', 'color' => 'green', 'icon' => 'thumb_up'],
                            'billing' => ['label' => 'Billing & Collection', 'color' => 'indigo', 'icon' => 'receipt_long'],
                            'collecting' => ['label' => 'Collection Monitoring', 'color' => 'cyan', 'icon' => 'account_balance'],
                            'check_prepared' => ['label' => 'Check Prepared', 'color' => 'violet', 'icon' => 'description'],
                            'released' => ['label' => 'Released', 'color' => 'emerald', 'icon' => 'check_circle'],
                            'rejected' => ['label' => 'Rejected', 'color' => 'red', 'icon' => 'cancel'],
                            'remanded' => ['label' => 'Remanded', 'color' => 'orange', 'icon' => 'replay'],
                        ];
                        $maxCount = $statusBreakdown->max('count') ?: 1;
                    @endphp
                    <div class="space-y-4">
                        @foreach($statuses as $key => $status)
                            @php $data = $statusBreakdown[$key] ?? null; @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700 flex items-center">
                                        <span class="material-icons text-sm mr-1 text-{{ $status['color'] }}-500">{{ $status['icon'] }}</span>
                                        {{ $status['label'] }}
                                    </span>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-gray-900">{{ $data ? number_format($data->count) : 0 }}</span>
                                        <span class="text-xs text-gray-500 ml-1">(₱{{ $data ? number_format($data->total_amount, 2) : '0.00' }})</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-{{ $status['color'] }}-500 h-3 rounded-full transition-all duration-500" 
                                         style="width: {{ $data ? ($data->count / $maxCount * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Claims by Type -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">category</span>
                        Claims by Type
                    </h3>
                </div>
                <div class="p-6">
                    @php $maxTypeCount = $claimsByType->max('count') ?: 1; @endphp
                    <div class="space-y-4">
                        @forelse($claimsByType as $item)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $item->claimType->name ?? 'Unknown' }}</span>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($item->count) }}</span>
                                        <span class="text-xs text-gray-500 ml-1">(₱{{ number_format($item->total_amount, 2) }})</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-primary-500 h-3 rounded-full transition-all duration-500" 
                                         style="width: {{ ($item->count / $maxTypeCount) * 100 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                <span class="material-icons text-4xl text-gray-300">donut_large</span>
                                <p class="mt-2">No claims data available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Claims by Campus -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">location_city</span>
                        Claims by Campus
                    </h3>
                </div>
                <div class="p-6">
                    @php $maxCampusCount = $claimsByCampus->max('count') ?: 1; @endphp
                    <div class="space-y-4">
                        @forelse($claimsByCampus as $item)
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $item->campus_name }}</span>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($item->count) }}</span>
                                        <span class="text-xs text-gray-500 ml-1">(₱{{ number_format($item->total_amount, 2) }})</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-primary-500 h-3 rounded-full transition-all duration-500" 
                                         style="width: {{ ($item->count / $maxCampusCount) * 100 }}%"></div>
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

            <!-- Monthly Trend -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">trending_up</span>
                        Monthly Claims Trend (Last 12 Months)
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $months = collect();
                        for ($i = 11; $i >= 0; $i--) {
                            $m = now()->subMonths($i)->format('Y-m');
                            $monthData = $monthlyTrend[$m] ?? collect();
                            $monthTotal = $monthData->sum('count');
                            $monthAmount = $monthData->sum('total_amount');
                            $months->push([
                                'label' => now()->subMonths($i)->format('M Y'),
                                'short' => now()->subMonths($i)->format('M'),
                                'count' => $monthTotal,
                                'amount' => $monthAmount,
                            ]);
                        }
                        $maxMonthCount = $months->max('count') ?: 1;
                    @endphp
                    <div class="flex items-end justify-between space-x-0.5 sm:space-x-1" style="height: 200px;">
                        @foreach($months as $month)
                            <div class="flex-1 flex flex-col items-center justify-end h-full group relative">
                                <!-- Tooltip -->
                                <div class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap z-10">
                                    <p class="font-semibold">{{ $month['label'] }}</p>
                                    <p>{{ $month['count'] }} claims</p>
                                    <p>₱{{ number_format($month['amount'], 2) }}</p>
                                </div>
                                <!-- Bar -->
                                <div class="w-full rounded-t-md transition-all duration-500 {{ $month['count'] > 0 ? 'bg-primary-500 hover:bg-primary-600' : 'bg-gray-200' }}" 
                                     style="height: {{ $month['count'] > 0 ? max(($month['count'] / $maxMonthCount) * 100, 5) : 5 }}%">
                                </div>
                                <!-- Label -->
                                <span class="text-[9px] sm:text-xs text-gray-500 mt-1 transform -rotate-45 origin-top-left">{{ $month['short'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Claims Activity -->
        <div class="bg-white rounded-xl material-shadow overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-primary-600">history</span>
                    Recent Claims Activity
                </h3>
                <a href="{{ route('admin.claims-monitoring') }}" class="text-sm text-primary-600 hover:text-primary-800 flex items-center py-1">
                    View All Claims <span class="material-icons text-sm ml-1">arrow_forward</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Claim #</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Faculty</th>
                            <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campus</th>
                            <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Filed</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentClaims as $claim)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.claims.show', $claim) }}" class="inline-flex items-center py-1 text-xs sm:text-sm font-medium text-primary-600 hover:text-primary-800">
                                        {{ $claim->claim_number }}
                                    </a>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                    {{ $claim->facultyProfile->display_name ?? 'Unknown' }}
                                </td>
                                <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    {{ $claim->facultyProfile->campus->name ?? 'N/A' }}
                                </td>
                                <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    {{ $claim->claimType->name ?? 'N/A' }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">
                                    ₱{{ number_format($claim->amount, 2) }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-100 text-amber-800',
                                            'under_review' => 'bg-blue-100 text-blue-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'billing' => 'bg-indigo-100 text-indigo-800',
                                            'collecting' => 'bg-cyan-100 text-cyan-800',
                                            'check_prepared' => 'bg-violet-100 text-violet-800',
                                            'released' => 'bg-emerald-100 text-emerald-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'remanded' => 'bg-orange-100 text-orange-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$claim->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                    </span>
                                </td>
                                <td class="hidden lg:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    {{ $claim->date_applied?->format('M d, Y') ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <span class="material-icons text-5xl text-gray-300">receipt_long</span>
                                    <p class="mt-2 text-gray-500">No claims found for this period</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
