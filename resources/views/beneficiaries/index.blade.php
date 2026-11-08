<x-app-layout title="My Beneficiaries">
    @section('page-title', 'Beneficiaries')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-primary-600">family_restroom</span>
                {{ __('My Beneficiaries') }}
            </h1>
            @if(\App\Models\SystemSetting::areProcessesEnabled())
            <a href="{{ route('beneficiaries.create') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 hover:-translate-y-0.5 active:translate-y-0 shadow-lg shadow-primary-500/30 transition-all duration-300 font-semibold">
                <span class="material-icons mr-2 text-lg">person_add</span>
                Add Beneficiary
            </a>
            @else
            <span class="inline-flex items-center px-4 py-2.5 bg-amber-50 text-amber-700 rounded-xl border border-amber-200">
                <span class="material-icons mr-2 text-lg">visibility</span>
                View Only Mode
            </span>
            @endif
        </div>
    </x-slot>

    @php
        $allBeneficiaries = $beneficiaries ?? collect();
        $totalCount = $allBeneficiaries instanceof \Illuminate\Pagination\LengthAwarePaginator ? $allBeneficiaries->total() : $allBeneficiaries->count();
        $primaryCount = $allBeneficiaries->where('is_primary', true)->count();
        $activeCount = $allBeneficiaries->where('is_active', true)->count();

        $relationshipColors = [
            'wife'           => ['from-pink-500 to-rose-600',    'bg-pink-100',    'text-pink-600',    'border-pink-200',    'bg-pink-50'],
            'husband'        => ['from-blue-500 to-indigo-600',  'bg-blue-100',    'text-blue-600',    'border-blue-200',    'bg-blue-50'],
            'son'            => ['from-sky-500 to-cyan-600',     'bg-sky-100',     'text-sky-600',     'border-sky-200',     'bg-sky-50'],
            'daughter'       => ['from-violet-500 to-purple-600','bg-violet-100',  'text-violet-600',  'border-violet-200',  'bg-violet-50'],
            'mother'         => ['from-amber-500 to-orange-600', 'bg-amber-100',   'text-amber-600',   'border-amber-200',   'bg-amber-50'],
            'father'         => ['from-teal-500 to-emerald-600', 'bg-teal-100',    'text-teal-600',    'border-teal-200',    'bg-teal-50'],
            'mother-in-law'  => ['from-orange-500 to-red-500',   'bg-orange-100',  'text-orange-600',  'border-orange-200',  'bg-orange-50'],
            'father-in-law'  => ['from-emerald-500 to-green-600','bg-emerald-100', 'text-emerald-600', 'border-emerald-200', 'bg-emerald-50'],
            'brother'        => ['from-indigo-500 to-blue-600',  'bg-indigo-100',  'text-indigo-600',  'border-indigo-200',  'bg-indigo-50'],
            'sister'         => ['from-fuchsia-500 to-pink-600', 'bg-fuchsia-100', 'text-fuchsia-600', 'border-fuchsia-200', 'bg-fuchsia-50'],
            'other'          => ['from-gray-500 to-slate-600',   'bg-gray-100',    'text-gray-600',    'border-gray-200',    'bg-gray-50'],
        ];

        $relationshipIcons = [
            'wife' => 'favorite', 'husband' => 'favorite',
            'son' => 'child_care', 'daughter' => 'child_care',
            'mother' => 'elderly_woman', 'father' => 'elderly',
            'mother-in-law' => 'elderly_woman', 'father-in-law' => 'elderly',
            'brother' => 'person', 'sister' => 'person',
            'other' => 'group',
        ];
    @endphp

    {{-- Hero Summary Banner --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-accent-800 rounded-3xl shadow-lg p-5 sm:p-8 text-white mb-6 reveal">
        <div class="absolute top-0 right-0 w-40 sm:w-64 h-40 sm:h-64 bg-white/10 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-32 sm:w-48 h-32 sm:h-48 bg-white/5 rounded-full filter blur-2xl translate-y-1/2 -translate-x-1/4"></div>
        <div class="relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg sm:text-xl font-display font-bold flex items-center gap-2">
                        <span class="material-icons-round text-white/80">family_restroom</span>
                        Family &amp; Beneficiaries Overview
                    </h3>
                    <p class="text-white/70 text-sm mt-1">Manage the people who matter most to you</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="flex -space-x-2">
                        @foreach($allBeneficiaries->take(4) as $preview)
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/20 border-2 border-white/40 flex items-center justify-center text-xs font-bold backdrop-blur-sm">
                                {{ strtoupper(substr($preview->full_name, 0, 1)) }}
                            </div>
                        @endforeach
                        @if($totalCount > 4)
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/30 border-2 border-white/40 flex items-center justify-center text-xs font-bold backdrop-blur-sm">
                                +{{ $totalCount - 4 }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 sm:gap-6 mt-5 sm:mt-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 sm:p-4 border border-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-[11px] sm:text-xs uppercase tracking-wider font-medium">Total</p>
                            <p class="text-2xl sm:text-3xl font-display font-bold mt-0.5">{{ $totalCount }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-white/15 flex items-center justify-center">
                            <span class="material-icons-round text-lg sm:text-xl">groups</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 sm:p-4 border border-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-[11px] sm:text-xs uppercase tracking-wider font-medium">Primary</p>
                            <p class="text-2xl sm:text-3xl font-display font-bold mt-0.5">{{ $primaryCount }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-white/15 flex items-center justify-center">
                            <span class="material-icons-round text-lg sm:text-xl">star</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 sm:p-4 border border-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-[11px] sm:text-xs uppercase tracking-wider font-medium">Active</p>
                            <p class="text-2xl sm:text-3xl font-display font-bold mt-0.5">{{ $activeCount }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-white/15 flex items-center justify-center">
                            <span class="material-icons-round text-lg sm:text-xl">verified</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card-premium p-4 sm:p-5 mb-6 reveal">
        <form method="GET" action="{{ route('beneficiaries.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                <input type="text" name="search" aria-label="Search beneficiaries by name" value="{{ request('search') }}" placeholder="Search beneficiaries by name..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all duration-300">
            </div>
            <div class="flex gap-2">
                <select name="relationship" aria-label="Filter by relationship"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl text-gray-700 bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all duration-300 min-w-[140px]">
                    <option value="">All Relations</option>
                    @foreach(\App\Models\Beneficiary::RELATIONSHIPS as $rel)
                        <option value="{{ $rel }}" {{ request('relationship') === $rel ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $rel)) }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 shadow-md shadow-primary-500/20 transition-all duration-300 font-medium text-sm">
                    <span class="material-icons text-lg mr-1">filter_list</span>
                    Filter
                </button>
                @if(request('search') || request('relationship'))
                    <a href="{{ route('beneficiaries.index') }}"
                       class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all duration-300 font-medium text-sm">
                        <span class="material-icons text-lg mr-1">clear</span>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Beneficiary Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
        @forelse($allBeneficiaries as $index => $beneficiary)
            @php
                $colors = $relationshipColors[$beneficiary->relationship] ?? $relationshipColors['other'];
                $icon = $relationshipIcons[$beneficiary->relationship] ?? 'person';
            @endphp
            <div class="group bg-white rounded-2xl shadow-card hover:shadow-card-hover overflow-hidden transition-all duration-300 hover:-translate-y-1 reveal" style="animation-delay: {{ $index * 60 }}ms">
                {{-- Colored accent strip at top --}}
                <div class="h-1.5 bg-gradient-to-r {{ $colors[0] }}"></div>

                <div class="p-4 sm:p-5">
                    {{-- Header row --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl {{ $colors[1] }} flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                                    <span class="material-icons-round {{ $colors[2] }} text-2xl sm:text-[28px]">{{ $icon }}</span>
                                </div>
                                @if($beneficiary->is_primary)
                                    <div class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center shadow-md ring-2 ring-white">
                                        <span class="material-icons text-white text-[11px]">star</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-display font-semibold text-gray-900 truncate text-base">{{ $beneficiary->full_name }}</h3>
                                <span class="inline-flex items-center gap-1 mt-0.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold uppercase tracking-wider {{ $colors[4] }} {{ $colors[2] }} border {{ $colors[3] }}">
                                    {{ ucfirst(str_replace('-', ' ', $beneficiary->relationship)) }}
                                </span>
                            </div>
                        </div>

                        @if(!$beneficiary->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-500 uppercase tracking-wide">
                                Inactive
                            </span>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="mt-4 space-y-2.5">
                        @if($beneficiary->date_of_birth)
                            <div class="flex items-center gap-2.5 text-sm text-gray-500">
                                <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons text-gray-400 text-base">cake</span>
                                </div>
                                <div>
                                    <span class="text-gray-700 font-medium">{{ $beneficiary->date_of_birth->format('M d, Y') }}</span>
                                    <span class="text-gray-400 mx-1">&middot;</span>
                                    <span>{{ $beneficiary->date_of_birth->age }} yrs old</span>
                                </div>
                            </div>
                        @endif
                        @if($beneficiary->contact_number)
                            <div class="flex items-center gap-2.5 text-sm text-gray-500">
                                <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons text-gray-400 text-base">phone</span>
                                </div>
                                <span class="text-gray-700">{{ $beneficiary->contact_number }}</span>
                            </div>
                        @endif
                        @if($beneficiary->address)
                            <div class="flex items-center gap-2.5 text-sm text-gray-500">
                                <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons text-gray-400 text-base">location_on</span>
                                </div>
                                <span class="text-gray-700 truncate">{{ $beneficiary->address }}</span>
                            </div>
                        @endif
                        @if($beneficiary->pse)
                            <div class="flex items-center gap-2.5 text-sm text-gray-500">
                                <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons text-gray-400 text-base">badge</span>
                                </div>
                                <span class="text-gray-700">{{ $beneficiary->pse }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Actions Footer --}}
                    <div class="mt-4 pt-3.5 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            @if($beneficiary->is_primary)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600">
                                    <span class="material-icons text-sm">star</span>
                                    Primary Beneficiary
                                </span>
                            @elseif($beneficiary->is_active)
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @endif
                        </div>
                        @if(\App\Models\SystemSetting::areProcessesEnabled())
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('beneficiaries.edit', $beneficiary) }}"
                               class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200"
                               title="Edit beneficiary">
                                <span class="material-icons text-xl">edit</span>
                            </a>
                            <form method="POST" action="{{ route('beneficiaries.destroy', $beneficiary) }}"
                                  onsubmit="return confirm('Are you sure you want to remove {{ addslashes($beneficiary->full_name) }} as a beneficiary?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200"
                                        title="Remove beneficiary">
                                    <span class="material-icons text-xl">delete_outline</span>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 xl:col-span-3 reveal">
                <div class="relative overflow-hidden bg-white rounded-3xl shadow-card p-8 sm:p-14 text-center">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-72 h-72 bg-gradient-to-b from-primary-50 to-transparent rounded-full filter blur-3xl opacity-60 -translate-y-1/2"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-3xl bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center mb-5">
                            <span class="material-icons-round text-primary-400 text-4xl sm:text-5xl">family_restroom</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-display font-bold text-gray-900">No Beneficiaries Yet</h3>
                        <p class="mt-2 text-gray-500 max-w-sm mx-auto leading-relaxed">Add your family members as beneficiaries so they can be covered for claims and benefits.</p>
                        @if(\App\Models\SystemSetting::areProcessesEnabled())
                        <a href="{{ route('beneficiaries.create') }}"
                           class="inline-flex items-center mt-6 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl hover:from-primary-700 hover:to-primary-800 hover:-translate-y-0.5 shadow-lg shadow-primary-500/30 transition-all duration-300 font-semibold">
                            <span class="material-icons mr-2">person_add</span>
                            Add Your First Beneficiary
                        </a>
                        @else
                        <div class="mt-6 inline-flex items-center gap-2 px-5 py-3 bg-amber-50 text-amber-700 rounded-xl border border-amber-200">
                            <span class="material-icons text-lg">info</span>
                            <span class="text-sm font-medium">System is in read-only mode. Contact admin to add beneficiaries.</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($allBeneficiaries instanceof \Illuminate\Pagination\LengthAwarePaginator && $allBeneficiaries->hasPages())
        <div class="mt-6 reveal">
            {{ $allBeneficiaries->withQueryString()->links() }}
        </div>
    @endif
</x-app-layout>
