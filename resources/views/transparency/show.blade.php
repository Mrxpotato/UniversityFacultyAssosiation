<x-public-layout title="Transparency">
    <!-- Post Content -->
    <article>
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 py-8 sm:py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
                <a href="{{ route('transparency.index') }}" class="inline-flex items-center text-primary-200 hover:text-white mb-3 sm:mb-4">
                    <span class="material-icons mr-1">arrow_back</span>
                    Back to Transparency Wall
                </a>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-400/30 text-white">
                    {{ ucfirst($post->category ?? 'General') }}
                </span>
                <h1 class="mt-3 text-2xl sm:text-4xl font-bold">{{ $post->title }}</h1>
                <div class="mt-3 sm:mt-4 flex flex-wrap items-center gap-1 text-primary-200 text-sm sm:text-base">
                    <span class="material-icons text-sm mr-1">calendar_today</span>
                    {{ $post->published_at?->format('F d, Y') ?? $post->created_at->format('F d, Y') }}
                    @if($post->author)
                        <span class="mx-2">•</span>
                        <span class="material-icons text-sm mr-1">person</span>
                        {{ $post->author->name ?? 'Admin' }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-12">
            @if($post->image)
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" 
                     class="w-full rounded-xl material-shadow mb-6 sm:mb-8">
            @endif

            <div class="bg-white rounded-xl material-shadow p-4 sm:p-8">
                <div class="prose prose-lg max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>

                @if($post->attachments && count($post->attachments) > 0)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="material-icons mr-2 text-primary-600">attach_file</span>
                            Attachments
                        </h3>
                        <div class="mt-4 space-y-2">
                            @foreach($post->attachments as $attachment)
                                <a href="{{ Storage::url($attachment) }}" target="_blank"
                                   class="flex items-center px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <span class="material-icons text-gray-400 mr-3">description</span>
                                    <span class="text-gray-700">{{ basename($attachment) }}</span>
                                    <span class="material-icons ml-auto text-gray-400">download</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Share -->
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <span class="text-gray-500">Share:</span>
                    <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                       class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank"
                       class="p-2 text-gray-500 hover:text-primary-400 hover:bg-primary-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
                    </a>
                </div>
                <a href="{{ route('transparency.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-800">
                    <span class="material-icons mr-1">arrow_back</span>
                    Back to all posts
                </a>
            </div>
        </div>
    </article>
</x-public-layout>
