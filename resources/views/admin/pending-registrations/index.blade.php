<x-app-layout title="Pending Registrations">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display font-bold text-2xl text-gray-900">
                    Pending Registrations
                </h1>
                <p class="text-gray-500 mt-1">Review and approve member registration applications</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
                    <span class="material-icons-round text-emerald-600">check_circle</span>
                    <p class="text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3">
                    <span class="material-icons-round text-red-600">error</span>
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
                <form method="GET" class="flex flex-col sm:flex-row flex-wrap items-end gap-3 sm:gap-4">
                    <div class="flex-1 min-w-0 sm:min-w-[200px] w-full sm:w-auto">
                        <label for="filter-status" class="text-sm font-medium text-gray-700 mb-2 block">Status</label>
                        <select id="filter-status" name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <option value="pending" {{ request('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    @if($isAdmin)
                    <div class="flex-1 min-w-0 sm:min-w-[200px] w-full sm:w-auto">
                        <label for="filter-campus_id" class="text-sm font-medium text-gray-700 mb-2 block">Campus</label>
                        <select id="filter-campus_id" name="campus_id" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            <option value="">All Campuses</option>
                            @foreach($campuses as $campus)
                                <option value="{{ $campus->id }}" {{ request('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Registrations Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if($registrations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applicant</th>
                                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Campus</th>
                                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Employee ID</th>
                                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-3 sm:px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Submitted</th>
                                    <th class="px-3 sm:px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($registrations as $registration)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-3 sm:px-6 py-4">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                @if($registration->id_picture)
                                                    <img src="{{ Storage::url($registration->id_picture) }}" alt="{{ $registration->full_name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                                @else
                                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                                        <span class="text-primary-600 font-semibold text-xs sm:text-sm">{{ strtoupper(substr($registration->first_name, 0, 1) . substr($registration->surname, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="font-medium text-gray-900 text-sm truncate">{{ $registration->full_name }}</p>
                                                    <p class="text-xs sm:text-sm text-gray-500 md:hidden">{{ $registration->employee_id_number }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 hidden sm:table-cell">
                                            <span class="text-gray-700 text-sm">{{ $registration->campus->name }}</span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 hidden md:table-cell">
                                            <span class="font-mono text-sm text-gray-700">{{ $registration->employee_id_number }}</span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4">
                                            @if($registration->status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    <span class="material-icons text-sm">pending</span>
                                                    Pending
                                                </span>
                                            @elseif($registration->status === 'approved')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    <span class="material-icons text-sm">check_circle</span>
                                                    Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <span class="material-icons text-sm">cancel</span>
                                                    Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 text-sm text-gray-500 hidden lg:table-cell">
                                            {{ $registration->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 text-right">
                                            <a href="{{ route('admin.pending-registrations.show', $registration) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                                <span class="material-icons-round text-lg">visibility</span>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $registrations->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons-round text-gray-400 text-4xl">person_search</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No registrations found</h3>
                        <p class="text-gray-500">There are no {{ request('status', 'pending') }} registrations at this time.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
