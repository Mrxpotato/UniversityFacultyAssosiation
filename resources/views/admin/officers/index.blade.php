<x-app-layout title="Officers">
    @section('page-title', 'Officer Management')

    <x-slot name="header">
        <div class="flex items-center">
            <span class="material-icons-outlined text-indigo-600 mr-3">shield</span>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Officer Management') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center">
                    <span class="material-icons-round text-green-600 mr-3">check_circle</span>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center">
                    <span class="material-icons-round text-red-600 mr-3">error</span>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ============================================================= --}}
            {{-- SECTION 1: UFA OFFICER POSITIONS                              --}}
            {{-- ============================================================= --}}
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-indigo-100 flex items-center justify-center mr-3">
                            <span class="material-icons-round text-indigo-600">stars</span>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">UFA Officer Positions</h3>
                            <p class="text-sm text-gray-500">Assign university-level officer positions to campus presidents</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    {{-- Info box --}}
                    <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex">
                            <span class="material-icons-round text-blue-600 mr-3 flex-shrink-0">info</span>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-1">How officer positions work:</p>
                                <ul class="list-disc list-inside space-y-1 ml-2">
                                    <li>All UFA officers must first be assigned as <strong>Campus President</strong> of their respective campus</li>
                                    <li>The <strong>President</strong> position grants claim approval/rejection permissions</li>
                                    <li>The <strong>Treasurer</strong> position grants claim release and financial management permissions</li>
                                    <li>Other positions (VP, Secretary, etc.) maintain campus president access</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Get all campus presidents for the dropdown --}}
                    @php
                        $campusPresidentsList = \App\Models\User::whereIn('role', ['campus_president', 'university_president', 'university_treasurer'])
                            ->where('is_active', true)
                            ->whereNotNull('campus_id')
                            ->with(['facultyProfile', 'campus'])
                            ->get()
                            ->filter(fn($u) => $u->facultyProfile)
                            ->sortBy(fn($u) => $u->facultyProfile->surname);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($officerPositions as $position)
                            @php
                                $currentOfficer = $universityOfficers->get($position);
                                $positionColors = match($position) {
                                    'President' => ['from-yellow-500 to-amber-600', 'bg-yellow-100', 'text-yellow-700', 'border-yellow-200', 'bg-yellow-50'],
                                    'Treasurer' => ['from-emerald-500 to-teal-600', 'bg-emerald-100', 'text-emerald-700', 'border-emerald-200', 'bg-emerald-50'],
                                    'Vice President' => ['from-blue-500 to-indigo-600', 'bg-blue-100', 'text-blue-700', 'border-blue-200', 'bg-blue-50'],
                                    'Secretary' => ['from-purple-500 to-violet-600', 'bg-purple-100', 'text-purple-700', 'border-purple-200', 'bg-purple-50'],
                                    default => ['from-gray-500 to-gray-600', 'bg-gray-100', 'text-gray-700', 'border-gray-200', 'bg-gray-50'],
                                };
                            @endphp
                            <div class="rounded-xl border {{ $positionColors[3] }} {{ $positionColors[4] }} overflow-hidden">
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $positionColors[0] }} flex items-center justify-center mr-3 shadow-md">
                                                <span class="material-icons-round text-white text-lg">
                                                    @switch($position)
                                                        @case('President') military_tech @break
                                                        @case('Vice President') workspace_premium @break
                                                        @case('Secretary') edit_note @break
                                                        @case('Treasurer') account_balance @break
                                                        @case('Auditor') fact_check @break
                                                        @default badge
                                                    @endswitch
                                                </span>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $position }}</h4>
                                                @if($position === 'President' || $position === 'Treasurer')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $positionColors[1] }} {{ $positionColors[2] }}">
                                                        Special Permissions
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($currentOfficer)
                                            <form method="POST" action="{{ route('admin.officers.remove-position') }}"
                                                  onsubmit="return confirm('Remove {{ $currentOfficer->display_name }} from {{ $position }} position?')">
                                                @csrf
                                                <input type="hidden" name="position" value="{{ $position }}">
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove officer">
                                                    <span class="material-icons-round text-lg">close</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- Current officer display --}}
                                    @if($currentOfficer)
                                        <div class="flex items-center p-3 bg-white rounded-xl border border-gray-200 mb-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $positionColors[0] }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0 overflow-hidden">
                                                @if($currentOfficer->id_picture)
                                                    <img src="{{ Storage::url($currentOfficer->id_picture) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($currentOfficer->first_name, 0, 1) . substr($currentOfficer->surname, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $currentOfficer->display_name }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $currentOfficer->campus->name ?? 'No campus' }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center p-3 bg-white/60 rounded-xl border border-dashed border-gray-300 mb-3">
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 flex-shrink-0">
                                                <span class="material-icons-round">person_outline</span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-400 italic text-sm">Vacant</p>
                                                <p class="text-xs text-gray-400">No officer assigned</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Assignment form --}}
                                    <form method="POST" action="{{ route('admin.officers.assign-position') }}" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="position" value="{{ $position }}">
                                        <select name="faculty_profile_id" required
                                                class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                            <option value="">Select campus president...</option>
                                            @foreach($campusPresidentsList as $user)
                                                @if($user->facultyProfile)
                                                    <option value="{{ $user->facultyProfile->id }}"
                                                            {{ $currentOfficer && $currentOfficer->id === $user->facultyProfile->id ? 'selected' : '' }}>
                                                        {{ $user->facultyProfile->display_name }} — {{ $user->campus->name ?? 'N/A' }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors flex-shrink-0">
                                            Assign
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ============================================================= --}}
            {{-- SECTION 2: CAMPUS PRESIDENTS                                  --}}
            {{-- ============================================================= --}}
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-emerald-100 flex items-center justify-center mr-3">
                            <span class="material-icons-round text-emerald-600">location_city</span>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Campus Presidents</h3>
                            <p class="text-sm text-gray-500">Assign or change the president for each campus chapter</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="space-y-4">
                        @foreach($campuses as $campus)
                            @php
                                $president = $campusPresidents->get($campus->id);
                                $campusFaculty = $allFaculty->get($campus->id) ?? collect();
                                $hasPresident = $president && $president->facultyProfile;
                            @endphp
                            <div class="rounded-xl border {{ $hasPresident ? 'border-emerald-200 bg-emerald-50/50' : 'border-gray-200 bg-gray-50/50' }} overflow-hidden">
                                <div class="p-4 sm:p-5">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                        {{-- Campus Info --}}
                                        <div class="flex items-center flex-shrink-0 min-w-0 sm:w-56">
                                            <div class="w-10 h-10 rounded-xl {{ $hasPresident ? 'bg-emerald-100' : 'bg-gray-200' }} flex items-center justify-center mr-3 flex-shrink-0">
                                                <span class="material-icons-round {{ $hasPresident ? 'text-emerald-600' : 'text-gray-400' }}">school</span>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-gray-900 text-sm truncate">{{ $campus->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $campusFaculty->count() }} member{{ $campusFaculty->count() !== 1 ? 's' : '' }}</p>
                                            </div>
                                        </div>

                                        {{-- Current President --}}
                                        <div class="flex-1 min-w-0">
                                            @if($hasPresident)
                                                <div class="flex items-center p-2.5 bg-white rounded-xl border border-gray-200">
                                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0 overflow-hidden">
                                                        @if($president->facultyProfile->id_picture)
                                                            <img src="{{ Storage::url($president->facultyProfile->id_picture) }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ strtoupper(substr($president->facultyProfile->first_name, 0, 1) . substr($president->facultyProfile->surname, 0, 1)) }}
                                                        @endif
                                                    </div>
                                                    <div class="ml-2.5 min-w-0 flex-1">
                                                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $president->facultyProfile->display_name }}</p>
                                                        <div class="flex items-center gap-2">
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">
                                                                Campus President
                                                            </span>
                                                            @if($president->facultyProfile->officer_position)
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">
                                                                    UFA {{ $president->facultyProfile->officer_position }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.officers.remove-campus-president', $campus) }}"
                                                          onsubmit="return confirm('Remove {{ $president->facultyProfile->display_name }} as campus president? They will be demoted to regular faculty.')"
                                                          class="ml-2 flex-shrink-0">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove president">
                                                            <span class="material-icons-round text-lg">person_remove</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="flex items-center p-2.5 bg-white/60 rounded-xl border border-dashed border-gray-300">
                                                    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 flex-shrink-0">
                                                        <span class="material-icons-round text-lg">person_outline</span>
                                                    </div>
                                                    <p class="ml-2.5 text-sm text-gray-400 italic">No president assigned</p>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Assignment Form --}}
                                        <div class="sm:w-auto flex-shrink-0">
                                            <form method="POST" action="{{ route('admin.officers.assign-campus-president', $campus) }}" class="flex gap-2">
                                                @csrf
                                                <select name="faculty_profile_id" required
                                                        class="w-full sm:w-52 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                                                    <option value="">Select member...</option>
                                                    @foreach($campusFaculty as $faculty)
                                                        <option value="{{ $faculty->id }}"
                                                                {{ $hasPresident && $president->facultyProfile->id === $faculty->id ? 'selected' : '' }}>
                                                            {{ $faculty->display_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="px-3 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors flex-shrink-0 whitespace-nowrap">
                                                    <span class="material-icons-round text-lg leading-none">how_to_reg</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
