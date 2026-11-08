<x-public-layout title="Campus Details">
    <style>
        .officer-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .officer-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .member-row {
            transition: background-color 0.2s;
        }
        .member-row:hover {
            background-color: var(--color-primary-50, #f0f9ff);
        }
    </style>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <span class="material-icons text-4xl sm:text-6xl text-primary-300">apartment</span>
            <h1 class="mt-3 sm:mt-4 text-2xl sm:text-4xl font-bold">{{ $campus->name }}</h1>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white mt-2">
                {{ $campus->code }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12">
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('campuses.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-800">
                <span class="material-icons mr-1">arrow_back</span>
                Back to All Campuses
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
            <!-- Campus Info & Stats Column -->
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <!-- Campus Info -->
                <div class="bg-white rounded-xl material-shadow p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Campus Information</h2>
                    
                    <div class="space-y-4">
                        @if($campus->address)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-primary-600">location_on</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Address</p>
                                <p class="text-gray-900">{{ $campus->address }}</p>
                            </div>
                        </div>
                        @endif

                        @if($campus->contact_number)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-primary-600">phone</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Contact Number</p>
                                <p class="text-gray-900">{{ $campus->contact_number }}</p>
                            </div>
                        </div>
                        @endif

                        @if($campus->email)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <span class="material-icons text-primary-600">email</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-gray-900">{{ $campus->email }}</p>
                            </div>
                        </div>
                        @endif

                        @if($campus->description)
                        <div class="pt-2 border-t">
                            <p class="text-sm text-gray-600">{{ $campus->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="bg-white rounded-xl material-shadow p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Membership Statistics</h2>
                    
                    <div class="space-y-3 sm:space-y-4">
                        <div class="bg-primary-50 rounded-lg p-3 sm:p-4 text-center">
                            <span class="material-icons text-primary-600 text-2xl sm:text-3xl">groups</span>
                            <p class="text-2xl sm:text-3xl font-bold text-primary-600 mt-1 sm:mt-2">{{ $campus->total_members ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Total Members</p>
                        </div>

                        <div class="bg-green-50 rounded-lg p-3 sm:p-4 text-center">
                            <span class="material-icons text-green-600 text-2xl sm:text-3xl">verified</span>
                            <p class="text-2xl sm:text-3xl font-bold text-green-600 mt-1 sm:mt-2">{{ $campus->active_members ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Active Members</p>
                        </div>
                    </div>
                </div>

                <!-- Colleges in this Campus -->
                @if($colleges->count() > 0)
                <div class="bg-white rounded-xl material-shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Colleges</h2>
                    <div class="space-y-2">
                        @foreach($colleges as $college)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $college->code }}</p>
                                <p class="text-xs text-gray-500">{{ $college->name }}</p>
                            </div>
                            <span class="text-sm text-primary-600 font-semibold">{{ $college->faculty_profiles_count }} members</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Join CTA -->
                <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl material-shadow p-4 sm:p-6 text-white text-center">
                    <span class="material-icons text-3xl sm:text-4xl">how_to_reg</span>
                    <h3 class="text-lg font-semibold mt-2">Join Our Association</h3>
                    <p class="text-sm text-primary-100 mt-1">Be part of the faculty community</p>
                    @guest
                    <a href="{{ route('register') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-white text-primary-600 rounded-lg font-medium hover:bg-primary-50 transition">
                        <span class="material-icons mr-1">person_add</span>
                        Request Account
                    </a>
                    @endguest
                </div>
            </div>

            <!-- Officers & Members Column -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                <!-- Campus Officers Section -->
                <div class="bg-white rounded-xl material-shadow p-4 sm:p-6">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <span class="material-icons text-primary-600 text-2xl sm:text-3xl mr-2 sm:mr-3">badge</span>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Campus Officers</h2>
                    </div>

                    @if($campusOfficers->count() > 0)
                        <!-- Campus President -->
                        @php $president = $campusOfficers->firstWhere('campus_position', 'Campus President'); @endphp
                        @if($president)
                        <div class="flex justify-center mb-8">
                            <div class="officer-card bg-gradient-to-br from-primary-50 to-white rounded-xl shadow-lg p-6 text-center border-l-4 border-primary-600" style="width: 280px;">
                                <div class="w-28 h-28 mx-auto mb-4 bg-gradient-to-br from-primary-100 to-primary-50 rounded-full flex items-center justify-center overflow-hidden ring-4 ring-primary-200">
                                    @if($president->id_picture)
                                        <img src="{{ Storage::url($president->id_picture) }}" alt="{{ $president->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-icons text-primary-300" style="font-size: 56px;">person</span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $president->full_name }}</h3>
                                <p class="text-primary-600 font-bold mt-1">Campus President</p>
                                @if($president->officer_position)
                                    <p class="text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded-full inline-block mt-2">
                                        University {{ $president->officer_position }}
                                    </p>
                                @endif
                                <p class="text-sm text-gray-500 mt-2">{{ $president->academicRank?->name }}</p>
                                <p class="text-xs text-gray-400">{{ $president->college?->code }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Other Officers Grid -->
                        @php $otherOfficers = $campusOfficers->where('campus_position', '!=', 'Campus President'); @endphp
                        @if($otherOfficers->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($otherOfficers as $officer)
                            <div class="officer-card bg-white rounded-xl shadow p-4 text-center border-l-4 
                                {{ $officer->campus_position == 'Campus Vice President' ? 'border-primary-500' : '' }}
                                {{ $officer->campus_position == 'Campus Secretary' ? 'border-green-500' : '' }}
                                {{ $officer->campus_position == 'Campus Treasurer' ? 'border-amber-500' : '' }}
                                {{ $officer->campus_position == 'Campus Auditor' ? 'border-purple-500' : '' }}
                                {{ $officer->campus_position == 'Campus PIO' ? 'border-accent-500' : '' }}
                            ">
                                <div class="w-20 h-20 mx-auto mb-3 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden ring-2 ring-gray-200">
                                    @if($officer->id_picture)
                                        <img src="{{ Storage::url($officer->id_picture) }}" alt="{{ $officer->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-icons text-gray-400" style="font-size: 40px;">person</span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">{{ $officer->full_name }}</h3>
                                <p class="text-xs font-semibold mt-1
                                    {{ $officer->campus_position == 'Campus Vice President' ? 'text-primary-600' : '' }}
                                    {{ $officer->campus_position == 'Campus Secretary' ? 'text-green-600' : '' }}
                                    {{ $officer->campus_position == 'Campus Treasurer' ? 'text-amber-600' : '' }}
                                    {{ $officer->campus_position == 'Campus Auditor' ? 'text-purple-600' : '' }}
                                    {{ $officer->campus_position == 'Campus PIO' ? 'text-accent-600' : '' }}
                                ">{{ str_replace('Campus ', '', $officer->campus_position) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $officer->academicRank?->name }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <span class="material-icons text-4xl text-gray-300">group_off</span>
                            <p class="mt-2">No campus officers assigned yet.</p>
                        </div>
                    @endif
                </div>

                <!-- Members List Section -->
                <div class="bg-white rounded-xl material-shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <span class="material-icons text-green-600 text-3xl mr-3">people</span>
                            <h2 class="text-2xl font-bold text-gray-900">Faculty Members</h2>
                        </div>
                        <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ $members->count() }} Members
                        </span>
                    </div>

                    @if($members->count() > 0)
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Academic Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">College</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($members as $member)
                                <tr class="member-row">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                                    @if($member->id_picture)
                                                        <img src="{{ Storage::url($member->id_picture) }}" alt="{{ $member->full_name }}" class="h-full w-full object-cover">
                                                    @else
                                                        <span class="material-icons text-gray-400 text-xl">person</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $member->full_name }}</p>
                                                <p class="text-xs text-gray-500 sm:hidden">{{ $member->academicRank?->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap hidden sm:table-cell">
                                        <p class="text-sm text-gray-900">{{ $member->academicRank?->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap hidden md:table-cell">
                                        <p class="text-sm text-gray-600">{{ $member->college?->code ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $member->membership_status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $member->membership_status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $member->membership_status === 'retired' ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ ucfirst($member->membership_status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="material-icons text-4xl text-gray-300">group_off</span>
                        <p class="mt-2">No regular members found in this campus.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
