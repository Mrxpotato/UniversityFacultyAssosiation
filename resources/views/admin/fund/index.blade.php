<x-app-layout title="Fund Monitoring">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-teal-600 mr-3">savings</span>
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Fund Monitoring') }}
                </h1>
            </div>
            <a href="{{ route('admin.fund.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                <span class="material-icons-outlined mr-2 text-sm">download</span>
                Export CSV
            </a>
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

            <!-- Fund Balance Hero Card -->
            <div class="mb-6 rounded-2xl p-4 sm:p-8 text-white" style="background: linear-gradient(135deg, #0d9488, #14b8a6, #2dd4bf);">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-teal-100 text-sm font-medium uppercase tracking-wider">Current Fund Balance</p>
                        <p class="text-3xl sm:text-5xl font-bold mt-2">₱{{ number_format($summary['current_balance'] ?? 0, 2) }}</p>
                        <p class="text-teal-100 mt-2 text-sm">
                            {{ $summary['transaction_count'] ?? 0 }} total transactions recorded
                        </p>
                    </div>
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 flex items-center justify-center">
                        <span class="material-icons-outlined text-white" style="font-size: 36px;">savings</span>
                    </div>
                </div>
            </div>

            <!-- Summary Stat Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 mb-6">
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Total Inflows</p>
                            <p class="text-lg sm:text-2xl font-bold text-green-600 truncate">₱{{ number_format($summary['total_inflows'] ?? 0, 2) }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-green-600 text-lg sm:text-2xl">trending_up</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Total Outflows</p>
                            <p class="text-lg sm:text-2xl font-bold text-red-600 truncate">₱{{ number_format($summary['total_outflows'] ?? 0, 2) }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-red-600 text-lg sm:text-2xl">trending_down</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Deductions Collected</p>
                            <p class="text-lg sm:text-2xl font-bold text-indigo-600 truncate">₱{{ number_format($summary['deductions_collected'] ?? 0, 2) }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-indigo-600 text-lg sm:text-2xl">account_balance_wallet</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Claims Released</p>
                            <p class="text-lg sm:text-2xl font-bold text-amber-600 truncate">₱{{ number_format($summary['claims_released'] ?? 0, 2) }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-amber-600 text-lg sm:text-2xl">payments</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two-Column Layout: Transaction Log + Record Form -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

                <!-- Transaction Log (2/3) -->
                <div class="lg:col-span-2 bg-white rounded-xl material-shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons-outlined mr-2 text-indigo-600">receipt_long</span>
                            Transaction Log
                        </h3>
                    </div>

                    <!-- Filters -->
                    <div class="px-3 sm:px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3" id="txnFilterForm">
                            <select name="type" aria-label="Filter by transaction type" onchange="document.getElementById('txnFilterForm').submit()" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Types</option>
                                <option value="inflow" {{ request('type') == 'inflow' ? 'selected' : '' }}>Inflow</option>
                                <option value="outflow" {{ request('type') == 'outflow' ? 'selected' : '' }}>Outflow</option>
                            </select>

                            <select name="category" aria-label="Filter by category" onchange="document.getElementById('txnFilterForm').submit()" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\FundTransaction::CATEGORIES as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>

                            <input type="date" name="date_from" aria-label="From date" value="{{ request('date_from') }}" placeholder="From" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="document.getElementById('txnFilterForm').submit()">

                            <input type="date" name="date_to" aria-label="To date" value="{{ request('date_to') }}" placeholder="To" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="document.getElementById('txnFilterForm').submit()">

                            <div class="relative">
                                <input type="text" name="search" aria-label="Search transactions" value="{{ request('search') }}" placeholder="Search..." class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 pr-8">
                                <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 p-1.5 text-gray-400 hover:text-indigo-600">
                                    <span class="material-icons-outlined text-sm">search</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Reference</th>
                                    <th class="px-3 sm:px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Category</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Description</th>
                                    <th class="px-3 sm:px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($transactions ?? [] as $txn)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $txn->transacted_at->format('M d, Y') }}
                                            <br><span class="text-xs text-gray-400">{{ $txn->transacted_at->format('h:i A') }}</span>
                                        </td>
                                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-xs text-gray-400 font-mono hidden md:table-cell">
                                            {{ $txn->reference_number }}
                                        </td>
                                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-center">
                                            @if($txn->type === 'inflow')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <span class="material-icons text-xs mr-1">arrow_downward</span>
                                                    Inflow
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <span class="material-icons text-xs mr-1">arrow_upward</span>
                                                    Outflow
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm hidden sm:table-cell">
                                            <div class="flex items-center">
                                                <span class="material-icons-outlined text-gray-400 text-sm mr-1.5">{{ \App\Models\FundTransaction::CATEGORY_ICONS[$txn->category] ?? 'label' }}</span>
                                                {{ \App\Models\FundTransaction::CATEGORIES[$txn->category] ?? $txn->category }}
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-4 py-3 text-sm text-gray-700 max-w-xs truncate hidden lg:table-cell">
                                            {{ $txn->description }}
                                            @if($txn->notes)
                                                <br><span class="text-xs text-gray-400">{{ $txn->notes }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-right text-sm sm:text-base font-semibold {{ $txn->type === 'inflow' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $txn->type === 'inflow' ? '+' : '-' }}₱{{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $txn->recorder->name ?? 'System' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <span class="material-icons-outlined text-gray-300 mb-2" style="font-size: 48px;">receipt_long</span>
                                                <p class="text-gray-500">No transactions recorded yet</p>
                                                <p class="text-sm text-gray-400 mt-1">Use the form to record your first transaction</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($transactions) && $transactions->hasPages())
                        <div class="p-4 border-t border-gray-100">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>

                <!-- Record Transaction Form (1/3) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl material-shadow overflow-hidden sticky top-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <span class="material-icons-outlined mr-2 text-teal-600">add_circle</span>
                                Record Transaction
                            </h3>
                        </div>

                        <form method="POST" action="{{ route('admin.fund.store-transaction') }}" class="p-6 space-y-4" x-data="{ txnType: 'inflow' }" onsubmit="return confirm('Record this transaction?')">
                            @csrf

                            <!-- Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                <div class="flex gap-3">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="type" value="inflow" x-model="txnType" class="sr-only peer">
                                        <div class="flex items-center justify-center px-4 py-2.5 rounded-lg border-2 transition-all peer-checked:border-green-500 peer-checked:bg-green-50 border-gray-200 hover:border-gray-300">
                                            <span class="material-icons text-sm mr-1.5" :class="txnType === 'inflow' ? 'text-green-600' : 'text-gray-400'">arrow_downward</span>
                                            <span class="text-sm font-medium" :class="txnType === 'inflow' ? 'text-green-700' : 'text-gray-600'">Inflow</span>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="type" value="outflow" x-model="txnType" class="sr-only peer">
                                        <div class="flex items-center justify-center px-4 py-2.5 rounded-lg border-2 transition-all peer-checked:border-red-500 peer-checked:bg-red-50 border-gray-200 hover:border-gray-300">
                                            <span class="material-icons text-sm mr-1.5" :class="txnType === 'outflow' ? 'text-red-600' : 'text-gray-400'">arrow_upward</span>
                                            <span class="text-sm font-medium" :class="txnType === 'outflow' ? 'text-red-700' : 'text-gray-600'">Outflow</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="fund-category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select id="fund-category" name="category" required class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="manual_adjustment">Manual Adjustment</option>
                                    <option value="donation">Donation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label for="fund-amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                                <input type="number" id="fund-amount" name="amount" step="0.01" min="0.01" required placeholder="0.00"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('amount') }}">
                                @error('amount')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="fund-description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <input type="text" id="fund-description" name="description" required maxlength="255" placeholder="e.g. Added from campus funds"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('description') }}">
                                @error('description')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="fund-notes" class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400">(optional)</span></label>
                                <textarea id="fund-notes" name="notes" rows="2" maxlength="255" placeholder="Additional details..."
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="fund-transacted_at" class="block text-sm font-medium text-gray-700 mb-1">Transaction Date</label>
                                <input type="date" id="fund-transacted_at" name="transacted_at" value="{{ old('transacted_at', now()->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-white font-medium transition-colors"
                                :class="txnType === 'inflow' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'">
                                <span class="material-icons-outlined mr-2 text-sm">add_circle</span>
                                Record Transaction
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
