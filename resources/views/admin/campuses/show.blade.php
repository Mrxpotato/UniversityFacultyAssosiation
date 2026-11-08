<x-app-layout title="Campus Details">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $campus->name }}
            </h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.campuses.edit', $campus) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 transition">
                    <span class="material-icons text-sm mr-1">edit</span>
                    Edit
                </a>
                <a href="{{ route('admin.campuses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                    <span class="material-icons text-sm mr-1">arrow_back</span>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Campus Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Campus Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Campus Code</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->code }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->address ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Contact Number</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->contact_number ?? 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->email ?? 'Not specified' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Membership Statistics</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <span class="material-icons text-blue-600 text-xl sm:text-3xl">groups</span>
                                    <p class="text-2xl font-bold text-blue-600">{{ $campus->total_members ?? 0 }}</p>
                                    <p class="text-sm text-gray-600">Total Members</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 text-center">
                                    <span class="material-icons text-green-600 text-xl sm:text-3xl">verified</span>
                                    <p class="text-2xl font-bold text-green-600">{{ $campus->active_members ?? 0 }}</p>
                                    <p class="text-sm text-gray-600">Active Members</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PSGC & GPS Information -->
            @if($campus->psgc_code || $campus->region || $campus->province || $campus->city_municipality || $campus->barangay || $campus->hasCoordinates())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons text-blue-600 mr-2">pin_drop</span>
                                PSGC Classification
                            </h3>
                            <dl class="space-y-3">
                                @if($campus->region)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Region</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->region }} @if($campus->region_code)<span class="text-xs text-gray-400">({{ $campus->region_code }})</span>@endif</dd>
                                </div>
                                @endif
                                @if($campus->province)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Province</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->province }} @if($campus->province_code)<span class="text-xs text-gray-400">({{ $campus->province_code }})</span>@endif</dd>
                                </div>
                                @endif
                                @if($campus->city_municipality)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">City / Municipality</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->city_municipality }} @if($campus->city_municipality_code)<span class="text-xs text-gray-400">({{ $campus->city_municipality_code }})</span>@endif</dd>
                                </div>
                                @endif
                                @if($campus->barangay)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Barangay</dt>
                                    <dd class="text-sm text-gray-900">{{ $campus->barangay }} @if($campus->barangay_code)<span class="text-xs text-gray-400">({{ $campus->barangay_code }})</span>@endif</dd>
                                </div>
                                @endif
                                @if($campus->psgc_code)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">PSGC Code</dt>
                                    <dd class="text-sm font-mono text-gray-900">{{ $campus->psgc_code }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                        @if($campus->hasCoordinates())
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons text-blue-600 mr-2">my_location</span>
                                GPS Coordinates
                            </h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Latitude</dt>
                                    <dd class="text-sm font-mono text-gray-900">{{ $campus->latitude }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Longitude</dt>
                                    <dd class="text-sm font-mono text-gray-900">{{ $campus->longitude }}</dd>
                                </div>
                            </dl>
                            <div class="mt-4">
                                <a href="https://www.google.com/maps?q={{ $campus->latitude }},{{ $campus->longitude }}" target="_blank"
                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    <span class="material-icons text-sm mr-1">map</span>
                                    View on Google Maps
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Members by Rank -->
            @if($membersByRank->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Members by Academic Rank</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($membersByRank as $rank)
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xl font-bold text-gray-800">{{ $rank->count }}</p>
                            <p class="text-xs text-gray-600">{{ $rank->academicRank?->name ?? 'Unknown' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Members -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Recent Members</h3>
                    @if($recentMembers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Academic Rank</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">College</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentMembers as $member)
                                <tr>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->display_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->employee_id }}</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                        {{ $member->academicRank?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                        {{ $member->college?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $member->membership_status === 'active' ? 'bg-green-100 text-green-800' : 
                                               ($member->membership_status === 'inactive' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($member->membership_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-4">No members in this campus yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
