<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#1e3a8a">

        <title>{{ isset($title) && $title ? $title.' — '.config('app.name', 'UFA Management System') : config('app.name', 'UFA Management System') }}</title>
        <meta name="description" content="{{ $description ?? 'CSU-UFA Faculty Association management system.' }}">

        <!-- Preconnect to external domains -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <!-- Premium Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Material Icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dynamic Theme Colors -->
        <style>
            :root {
                @isset($themeColors)
                @foreach($themeColors as $name => $hex)
                --color-{{ $name }}: {{ $hex }};
                @endforeach
                @endisset

                @isset($themeSidebar)
                --sidebar-top: {{ $themeSidebar[0] ?? '#2d0000' }};
                --sidebar-mid: {{ $themeSidebar[1] ?? '#450000' }};
                --sidebar-bot: {{ $themeSidebar[2] ?? '#780000' }};
                @endisset

                @isset($themeSelection)
                --selection-bg: {{ $themeSelection }};
                @endisset

                /* Computed gradients from theme */
                --primary-gradient: linear-gradient(135deg, var(--color-primary-700) 0%, var(--color-primary-500) 100%);
                --secondary-gradient: linear-gradient(135deg, var(--color-primary-600) 0%, var(--color-primary-700) 100%);
                --accent-gradient: linear-gradient(135deg, var(--color-accent-400) 0%, var(--color-accent-800) 100%);
                --dark-gradient: linear-gradient(135deg, var(--color-primary-950) 0%, var(--color-primary-700) 50%, var(--color-primary-500) 100%);
            }

            ::selection {
                background: var(--selection-bg, rgba(120, 0, 0, 0.85));
                color: #ffffff;
            }

            .sidebar-gradient {
                background: linear-gradient(180deg, var(--sidebar-top) 0%, var(--sidebar-mid) 50%, var(--sidebar-bot) 100%);
            }

            body { 
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }
            .font-display {
                font-family: 'Poppins', 'Inter', sans-serif;
            }
            
            /* Sidebar Gradient - driven by theme CSS variables */
            .sidebar-gradient {
                background: linear-gradient(180deg, var(--sidebar-top) 0%, var(--sidebar-mid) 50%, var(--sidebar-bot) 100%);
            }
            
            /* Glass morphism for overlays */
            .glass-overlay {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            
            /* Smooth transitions */
            .smooth-transition {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Custom scrollbar for sidebar */
            .sidebar-scroll::-webkit-scrollbar {
                width: 4px;
            }
            .sidebar-scroll::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
            }
            .sidebar-scroll::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
            .sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
            }

            /* Animated gradient border */
            .gradient-border {
                position: relative;
            }
            .gradient-border::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                padding: 2px;
                background: linear-gradient(135deg, var(--color-primary-900), var(--color-primary-600), var(--color-primary-400));
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
            }

            /* Pulse notification */
            @keyframes pulse-ring {
                0% { transform: scale(0.8); opacity: 1; }
                100% { transform: scale(2); opacity: 0; }
            }
            .pulse-ring::before {
                content: '';
                position: absolute;
                inset: -4px;
                border-radius: 50%;
                border: 2px solid currentColor;
                animation: pulse-ring 1.5s ease-out infinite;
            }

            /* Loading Animation */
            @keyframes loading-spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            @keyframes loading-pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.5; transform: scale(0.95); }
            }
            @keyframes loading-bounce {
                0%, 80%, 100% { transform: scale(0); opacity: 0.5; }
                40% { transform: scale(1); opacity: 1; }
            }
            @keyframes loading-progress {
                0% { width: 0%; }
                50% { width: 70%; }
                100% { width: 100%; }
            }
            .loading-spinner {
                animation: loading-spin 1s linear infinite;
            }
            .loading-pulse {
                animation: loading-pulse 2s ease-in-out infinite;
            }
            .loading-dot {
                animation: loading-bounce 1.4s ease-in-out infinite;
            }
            .loading-dot:nth-child(1) { animation-delay: -0.32s; }
            .loading-dot:nth-child(2) { animation-delay: -0.16s; }
            .loading-dot:nth-child(3) { animation-delay: 0s; }
            .loading-progress {
                animation: loading-progress 2s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <!-- Page Loading Overlay -->
        <div x-data="{ loading: true }" 
             x-init="setTimeout(() => loading = false, 600)"
             x-show="loading"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] bg-gradient-to-br from-primary-950 via-primary-900 to-primary-700 flex items-center justify-center">
            <div class="text-center">
                <!-- Animated Logo -->
                <div class="relative inline-flex items-center justify-center mb-8">
                    <!-- Outer ring -->
                    <div class="absolute w-24 h-24 rounded-full border-4 border-primary-300/20"></div>
                    <!-- Spinning ring -->
                    <div class="absolute w-24 h-24 rounded-full border-4 border-transparent border-t-primary-400 border-r-accent-400 loading-spinner"></div>
                    <!-- Inner glow -->
                    <div class="w-16 h-16 flex items-center justify-center loading-pulse">
                        <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-14 h-14 object-contain drop-shadow-2xl">
                    </div>
                </div>
                <!-- Loading Text -->
                <div class="text-2xl font-display font-bold text-white mb-3">CSU-UFA</div>
                <p class="text-primary-200 text-sm mb-6">Loading, please wait...</p>
                <!-- Loading dots -->
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-2 h-2 bg-primary-400 rounded-full loading-dot"></div>
                    <div class="w-2 h-2 bg-accent-400 rounded-full loading-dot"></div>
                    <div class="w-2 h-2 bg-primary-400 rounded-full loading-dot"></div>
                </div>
            </div>
        </div>

        <div class="min-h-screen flex">
            <!-- Sidebar Navigation -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
                <!-- Top Navigation Bar -->
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-14 sm:top-16 z-30">
                        <div class="max-w-7xl mx-auto py-3 sm:py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Flash Messages with Animation -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 sm:mt-4 space-y-3">
                    @if(session('success'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-init="setTimeout(() => show = false, 5000)"
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-4"
                             class="relative overflow-hidden bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-lg shadow-emerald-100" 
                             role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <span class="material-icons-round text-emerald-600">check_circle</span>
                                </div>
                                <p class="ml-3 text-emerald-800 font-medium">{{ session('success') }}</p>
                                <button @click="show = false" class="ml-auto p-1 hover:bg-emerald-100 rounded-full transition-colors">
                                    <span class="material-icons text-emerald-600 text-xl">close</span>
                                </button>
                            </div>
                            <!-- Progress bar animation -->
                            <div class="absolute bottom-0 left-0 h-1 bg-emerald-400" x-ref="progressBar" 
                                 x-init="$refs.progressBar.style.width = '100%'; setTimeout(() => $refs.progressBar.style.width = '0%', 100)"
                                 style="transition: width 4900ms linear;"></div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-4"
                             class="relative overflow-hidden bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-xl shadow-lg shadow-red-100" 
                             role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <span class="material-icons-round text-red-600">error</span>
                                </div>
                                <p class="ml-3 text-red-800 font-medium">{{ session('error') }}</p>
                                <button @click="show = false" class="ml-auto p-1 hover:bg-red-100 rounded-full transition-colors">
                                    <span class="material-icons text-red-600 text-xl">close</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-4"
                             class="relative overflow-hidden bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-lg shadow-amber-100" 
                             role="alert">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                                    <span class="material-icons-round text-amber-600">warning</span>
                                </div>
                                <p class="ml-3 text-amber-800 font-medium">{{ session('warning') }}</p>
                                <button @click="show = false" class="ml-auto p-1 hover:bg-amber-100 rounded-full transition-colors">
                                    <span class="material-icons text-amber-600 text-xl">close</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Page Content with Animation -->
                <main class="flex-1 p-3 sm:p-4 md:p-6 lg:p-8">
                    <div class="animate-fade-in">
                        {{ $slot }}
                    </div>
                </main>

                <!-- Footer -->
                <footer class="bg-white/80 backdrop-blur-md border-t border-gray-100 mt-auto">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-6 h-6 object-contain">
                                <span class="font-semibold text-gray-700">CSU-UFA</span>
                            </div>
                            <p class="text-center text-gray-500 text-sm">
                                &copy; {{ date('Y') }} University Faculty Association. All rights reserved.
                            </p>
                            <div class="flex items-center space-x-4">
                                <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                    <span class="material-icons">help_outline</span>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                    <span class="material-icons">mail_outline</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Navigation Loading Bar -->
        <div x-data="{ navigating: false }"
             @click.window="if($event.target.tagName === 'A' && $event.target.href && !$event.target.href.includes('#') && !$event.target.hasAttribute('download')) { navigating = true }"
             x-show="navigating"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed top-0 left-0 right-0 z-[60] h-1 bg-primary-100">
            <div class="h-full bg-gradient-to-r from-primary-500 via-accent-500 to-primary-500 loading-progress rounded-full"></div>
        </div>

        <!-- Toast Notifications Container -->
        <div x-data="toast" 
             @toast.window="add($event.detail.message, $event.detail.type, $event.detail.duration)"
             class="fixed bottom-4 right-4 left-4 sm:left-auto z-50 space-y-2">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-full"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-full"
                     class="flex items-center p-4 rounded-xl shadow-xl w-full sm:min-w-80"
                     :class="{
                         'bg-emerald-500 text-white': toast.type === 'success',
                         'bg-red-500 text-white': toast.type === 'error',
                         'bg-amber-500 text-white': toast.type === 'warning',
                         'bg-blue-500 text-white': toast.type === 'info'
                     }">
                    <span class="material-icons mr-3" x-text="toast.type === 'success' ? 'check_circle' : toast.type === 'error' ? 'error' : toast.type === 'warning' ? 'warning' : 'info'"></span>
                    <span x-text="toast.message" class="font-medium"></span>
                    <button @click="remove(toast.id)" class="ml-auto p-1 hover:bg-white/20 rounded-full transition-colors">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </template>
        </div>
    </body>
</html>
