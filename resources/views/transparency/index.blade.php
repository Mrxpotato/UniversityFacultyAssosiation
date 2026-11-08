<x-public-layout title="Transparency Wall">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <span class="material-icons text-4xl sm:text-6xl text-primary-300">visibility</span>
            <h1 class="mt-4 text-2xl sm:text-4xl font-bold">Transparency Wall</h1>
            <p class="mt-2 text-base sm:text-xl text-primary-200">Financial reports, announcements, and updates</p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filters -->
        <div class="mb-8 flex flex-col sm:flex-row flex-wrap gap-4">
            <form method="GET" action="{{ route('transparency.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-4 w-full">
                <div class="flex-1 min-w-0 sm:min-w-64">
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-2.5 text-gray-400">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search posts..." 
                               aria-label="Search posts"
                               oninput="clearTimeout(window._searchTimer); window._searchTimer = setTimeout(() => this.form.submit(), 500)"
                               class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <div class="w-full sm:w-auto">
                    <select name="category" aria-label="Filter by category" onchange="this.form.submit()" class="w-full sm:w-auto rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Categories</option>
                        <option value="financial" {{ request('category') == 'financial' ? 'selected' : '' }}>Financial Reports</option>
                        <option value="announcement" {{ request('category') == 'announcement' ? 'selected' : '' }}>Announcements</option>
                        <option value="minutes" {{ request('category') == 'minutes' ? 'selected' : '' }}>Meeting Minutes</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                </form>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($posts ?? [] as $post)
                <article class="bg-white rounded-xl material-shadow overflow-hidden hover:shadow-lg transition-shadow">
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-36 sm:h-48 object-cover">
                    @else
                        <div class="w-full h-36 sm:h-48 bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                            <span class="material-icons text-4xl sm:text-6xl text-white/50">
                                @if($post->category === 'financial')
                                    account_balance
                                @elseif($post->category === 'announcement')
                                    campaign
                                @elseif($post->category === 'minutes')
                                    description
                                @else
                                    article
                                @endif
                            </span>
                        </div>
                    @endif
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                {{ ucfirst($post->category ?? 'General') }}
                            </span>
                            <span class="ml-auto text-sm text-gray-500">{{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 line-clamp-2">{{ $post->title }}</h2>
                        <p class="mt-2 text-gray-600 line-clamp-3">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                        <a href="{{ route('transparency.show', $post) }}" 
                           class="mt-4 inline-flex items-center text-primary-600 hover:text-primary-800 font-medium">
                            Read More
                            <span class="material-icons ml-1 text-sm">arrow_forward</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl material-shadow p-12 text-center">
                        <span class="material-icons text-6xl text-gray-300">article</span>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Posts Yet</h3>
                        <p class="mt-2 text-gray-500">Check back later for updates and announcements</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($posts) && $posts->hasPages())
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-public-layout>
