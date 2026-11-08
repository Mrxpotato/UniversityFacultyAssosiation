<x-app-layout title="My Claims">
    @section('page-title', 'My Claims')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-indigo-600">receipt_long</span>
                {{ __('My Claims') }}
            </h1>
            @if(\App\Models\SystemSetting::areProcessesEnabled())
            <a href="{{ route('claims.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm text-sm sm:text-base">
                <span class="material-icons mr-1 sm:mr-2 text-lg">add</span>
                New Claim
            </a>
            @else
            <span class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm">
                <span class="material-icons mr-2">visibility</span>
                View Only Mode
            </span>
            @endif
        </div>
    </x-slot>

    <div class="bg-white rounded-xl material-shadow overflow-hidden">
        <!-- Filters -->
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('claims.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                <div class="flex-1 min-w-0">
                    <select name="status" aria-label="Filter by status" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="billing" {{ request('status') == 'billing' ? 'selected' : '' }}>Billing & Collection</option>
                        <option value="collecting" {{ request('status') == 'collecting' ? 'selected' : '' }}>Collection Monitoring</option>
                        <option value="check_prepared" {{ request('status') == 'check_prepared' ? 'selected' : '' }}>Check Prepared</option>
                        <option value="released" {{ request('status') == 'released' ? 'selected' : '' }}>Released</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="remanded" {{ request('status') == 'remanded' ? 'selected' : '' }}>Remanded</option>
                    </select>
                </div>
                <div class="flex-1 min-w-0">
                    <select name="claim_type_id" aria-label="Filter by claim type" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Types</option>
                        @foreach($claimTypes ?? [] as $type)
                            <option value="{{ $type->id }}" {{ request('claim_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="submit" class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                        <span class="material-icons mr-1 text-sm">filter_list</span>
                        Filter
                    </button>
                    <a href="{{ route('claims.index') }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                        <span class="material-icons mr-1">clear</span>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim Type</th>
                        <th scope="col" class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beneficiary</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Filed</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($claims ?? [] as $claim)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons text-indigo-600">
                                            @if(str_contains(strtolower($claim->claimType->name ?? ''), 'death'))
                                                sentiment_very_dissatisfied
                                            @elseif(str_contains(strtolower($claim->claimType->name ?? ''), 'medical'))
                                                local_hospital
                                            @elseif(str_contains(strtolower($claim->claimType->name ?? ''), 'retire'))
                                                elderly
                                            @else
                                                receipt_long
                                            @endif
                                        </span>
                                    </div>
                                    <div class="ml-2 sm:ml-3">
                                        <p class="font-medium text-gray-900 text-xs sm:text-sm">{{ $claim->claimType->name ?? 'Unknown' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $claim->beneficiary->full_name ?? 'Self' }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-semibold text-gray-900">
                                ₱{{ number_format($claim->amount, 2) }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($claim->status === 'pending') bg-amber-100 text-amber-800
                                    @elseif($claim->status === 'under_review') bg-blue-100 text-blue-800
                                    @elseif($claim->status === 'approved') bg-green-100 text-green-800
                                    @elseif($claim->status === 'billing') bg-indigo-100 text-indigo-800
                                    @elseif($claim->status === 'collecting') bg-cyan-100 text-cyan-800
                                    @elseif($claim->status === 'check_prepared') bg-violet-100 text-violet-800
                                    @elseif($claim->status === 'released') bg-emerald-100 text-emerald-800
                                    @elseif($claim->status === 'remanded') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    <span class="material-icons text-xs mr-1">
                                        @if($claim->status === 'pending') schedule
                                        @elseif($claim->status === 'under_review') rate_review
                                        @elseif($claim->status === 'approved') thumb_up
                                        @elseif($claim->status === 'billing') receipt_long
                                        @elseif($claim->status === 'collecting') account_balance
                                        @elseif($claim->status === 'check_prepared') description
                                        @elseif($claim->status === 'released') check_circle
                                        @elseif($claim->status === 'remanded') replay
                                        @else cancel
                                        @endif
                                    </span>
                                    {{ \App\Models\Claim::STATUSES[$claim->status] ?? ucfirst($claim->status) }}
                                </span>
                            </td>
                            <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $claim->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('claims.show', $claim) }}" 
                                       class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                       title="View">
                                        <span class="material-icons">visibility</span>
                                    </a>
                                    @if($claim->status === 'pending' && \App\Models\SystemSetting::areProcessesEnabled())
                                        <a href="{{ route('claims.edit', $claim) }}" 
                                           class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                           title="Edit">
                                            <span class="material-icons">edit</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <span class="material-icons text-5xl text-gray-300">receipt_long</span>
                                <p class="mt-2 text-gray-500">No claims found</p>
                                @if(\App\Models\SystemSetting::areProcessesEnabled())
                                <a href="{{ route('claims.create') }}" class="inline-flex items-center mt-3 text-indigo-600 hover:text-indigo-800">
                                    <span class="material-icons mr-1">add</span>
                                    Submit Your First Claim
                                </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($claims) && $claims->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $claims->links() }}
            </div>
        @endif
    </div>

    <!-- How to Claim & Required Documents -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- How to Claim Process -->
        <div class="lg:col-span-2 bg-white rounded-xl material-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-indigo-600">info</span>
                    How to Claim — Process Flow
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">1</div>
                        <p class="text-sm text-gray-700"><span class="font-semibold">Member Informs UFA</span> — Submit evidence / supporting documents for possible claim.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">2</div>
                        <p class="text-sm text-gray-700"><span class="font-semibold">UFA Verifies Claim</span> — Verified through membership data and submitted evidence.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">3</div>
                        <p class="text-sm text-gray-700"><span class="font-semibold">Notification</span> — UFA notifies member for additional evidence or approval.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">4</div>
                        <p class="text-sm text-gray-700"><span class="font-semibold">Billing &amp; Collection</span> — UFA prepares list of claims &amp; billing for collection.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">5</div>
                        <p class="text-sm text-gray-700"><span class="font-semibold">Collection Monitoring</span> — Monitors receipt of claims via Campus Cashiers.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">6</div>
                        <p class="text-sm text-gray-700"><span class="font-semibold">Check Preparation</span> — UFA prepares check and notifies the recipient.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-7 h-7 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold">7</div>
                        <p class="text-sm text-gray-700"><span class="font-semibold">Claim Complete</span> — Recipient receives the check.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Required Documents per Claim Type -->
        <div class="bg-white rounded-xl material-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-amber-600">folder_open</span>
                    Required Documents
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @foreach($claimTypes ?? [] as $type)
                    <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                        <div class="flex items-center mb-1">
                            <span class="material-icons text-indigo-600 mr-2 text-sm">
                                @if(str_contains(strtolower($type->name), 'death'))
                                    sentiment_very_dissatisfied
                                @elseif(str_contains(strtolower($type->name), 'medical'))
                                    local_hospital
                                @elseif(str_contains(strtolower($type->name), 'nuptial'))
                                    favorite
                                @else
                                    receipt_long
                                @endif
                            </span>
                            <h4 class="font-semibold text-sm text-gray-800">{{ $type->name }}</h4>
                        </div>
                        @if($type->required_documents && count($type->required_documents))
                            <ul class="text-xs text-gray-600 ml-6 space-y-0.5">
                                @foreach($type->required_documents as $doc)
                                    <li class="flex items-center">
                                        <span class="material-icons text-green-500 text-xs mr-1">check_circle</span>
                                        {{ $doc }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
