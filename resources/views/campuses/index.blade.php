<x-public-layout title="Our Campuses">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <span class="material-icons text-4xl sm:text-6xl text-primary-300">apartment</span>
            <h1 class="mt-3 sm:mt-4 text-2xl sm:text-4xl font-bold">Our Campuses</h1>
            <p class="mt-2 text-base sm:text-xl text-primary-200">University Faculty Association presence across all campuses</p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($campuses ?? [] as $campus)
                <div class="bg-white rounded-xl material-shadow overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-36 sm:h-48 bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center relative">
                        <span class="material-icons text-5xl sm:text-8xl text-white/30">location_city</span>
                        <div class="absolute bottom-4 left-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                                {{ $campus->code }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ $campus->name }}</h3>
                        
                        <div class="mt-4 space-y-3 text-sm">
                            @if($campus->address)
                                <div class="flex items-start text-gray-500">
                                    <span class="material-icons text-sm mr-2 mt-0.5">location_on</span>
                                    <span>{{ $campus->address }}</span>
                                </div>
                            @endif
                            @if($campus->contact_number)
                                <div class="flex items-center text-gray-500">
                                    <span class="material-icons text-sm mr-2">phone</span>
                                    {{ $campus->contact_number }}
                                </div>
                            @endif
                            @if($campus->email)
                                <div class="flex items-center text-gray-500">
                                    <span class="material-icons text-sm mr-2">email</span>
                                    {{ $campus->email }}
                                </div>
                            @endif
                        </div>

                        <!-- Stats -->
                        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4 text-center">
                            <div>
                                <p class="text-xl sm:text-2xl font-bold text-primary-600">{{ $campus->faculty_profiles_count ?? 0 }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Members</p>
                            </div>
                            <div>
                                <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $campus->colleges_count ?? 0 }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Colleges</p>
                            </div>
                        </div>

                        <a href="{{ route('campuses.show', $campus) }}" 
                           class="mt-4 block text-center text-primary-600 hover:text-primary-800 font-medium">
                            View Details →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl material-shadow p-12 text-center">
                        <span class="material-icons text-6xl text-gray-300">apartment</span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Campuses Found</h3>
                        <p class="mt-2 text-gray-500">Campus information will be available soon</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-public-layout>
