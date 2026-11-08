<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) && $title ? $title.' — '.config('app.name', 'UFA Management System') : config('app.name', 'UFA Management System') }}</title>
        <meta name="description" content="{{ $description ?? 'CSU-UFA Faculty Association — benefits, claims, trainings, and financial transparency for faculty members.' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
        
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
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav x-data="{ mobileOpen: false, scrolled: false }" 
                 @scroll.window="scrolled = window.scrollY > 50"
                 :class="scrolled ? 'bg-white/80 backdrop-blur-xl shadow-lg shadow-gray-200/50' : 'bg-transparent'"
                 class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-20">
                        <!-- Logo -->
                        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                            <div class="w-12 h-12 transition-all duration-300 group-hover:scale-105">
                                <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-full h-full object-contain drop-shadow-lg">
                            </div>
                            <div :class="scrolled ? 'text-gray-900' : 'text-white'">
                                <div class="font-display font-bold text-lg transition-colors">CSU-UFA</div>
                                <div class="text-xs opacity-70 transition-colors">Faculty Association</div>
                            </div>
                        </a>
                        
                        <!-- Desktop Navigation -->
                        <div class="hidden md:flex items-center gap-1">
                            <a href="{{ route('transparency.index') }}" 
                               class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('transparency.*') ? 'bg-primary-500/10 text-primary-600' : '' }}"
                               :class="scrolled ? 'text-gray-600 hover:text-primary-600 hover:bg-gray-100' : 'text-white/90 hover:text-white hover:bg-white/10'">
                                Transparency
                            </a>
                            <a href="{{ route('trainings.index') }}" 
                               class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('trainings.*') ? 'bg-primary-500/10 text-primary-600' : '' }}"
                               :class="scrolled ? 'text-gray-600 hover:text-primary-600 hover:bg-gray-100' : 'text-white/90 hover:text-white hover:bg-white/10'">
                                Trainings
                            </a>
                            <a href="{{ route('campuses.index') }}" 
                               class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('campuses.*') ? 'bg-primary-500/10 text-primary-600' : '' }}"
                               :class="scrolled ? 'text-gray-600 hover:text-primary-600 hover:bg-gray-100' : 'text-white/90 hover:text-white hover:bg-white/10'">
                                Campuses
                            </a>
                            <a href="{{ route('organization') }}" 
                               class="px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 {{ request()->routeIs('organization') ? 'bg-primary-500/10 text-primary-600' : '' }}"
                               :class="scrolled ? 'text-gray-600 hover:text-primary-600 hover:bg-gray-100' : 'text-white/90 hover:text-white hover:bg-white/10'">
                                Officers
                            </a>
                        </div>
                        
                        <!-- Auth Buttons -->
                        <div class="hidden md:flex items-center gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-primary-300 rounded-xl font-semibold shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/30 hover:-translate-y-0.5 transition-all duration-300 border border-primary-500/20">
                                    <span class="material-icons-round text-lg">dashboard</span>
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="px-5 py-2.5 rounded-xl font-medium transition-all duration-300"
                                   :class="scrolled ? 'text-primary-700 hover:text-primary-800' : 'text-white hover:text-primary-300'">
                                    Login
                                </a>
                                @if(\App\Models\SystemSetting::isRegistrationEnabled())
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-primary-300 rounded-xl font-semibold shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/30 hover:-translate-y-0.5 transition-all duration-300 border border-primary-500/20">
                                    <span class="material-icons-round text-lg">person_add</span>
                                    Request Account
                                </a>
                                @endif
                            @endauth
                        </div>
                        
                        <!-- Mobile Menu Button -->
                        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-xl transition-colors"
                                :class="scrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'">
                            <span class="material-icons-round" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Menu -->
                <div x-show="mobileOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="md:hidden bg-white border-t border-gray-100 shadow-xl">
                    <div class="px-4 py-4 space-y-2">
                        <a href="{{ route('transparency.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors {{ request()->routeIs('transparency.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="material-icons-round text-xl">visibility</span>
                            Transparency Wall
                        </a>
                        <a href="{{ route('trainings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors {{ request()->routeIs('trainings.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="material-icons-round text-xl">school</span>
                            Trainings & Seminars
                        </a>
                        <a href="{{ route('campuses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors {{ request()->routeIs('campuses.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="material-icons-round text-xl">apartment</span>
                            Campuses
                        </a>
                        <a href="{{ route('organization') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors {{ request()->routeIs('organization') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <span class="material-icons-round text-xl">groups</span>
                            Officers
                        </a>
                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            @auth
                                <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-primary-300 rounded-xl font-semibold border border-primary-500/20">
                                    <span class="material-icons-round text-lg">dashboard</span>
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 border-2 border-primary-200 text-primary-700 rounded-xl font-semibold hover:bg-primary-50 transition-colors">
                                    Login
                                </a>
                                @if(\App\Models\SystemSetting::isRegistrationEnabled())
                                <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-primary-300 rounded-xl font-semibold border border-primary-500/20">
                                    <span class="material-icons-round text-lg">person_add</span>
                                    Request Account
                                </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="relative bg-gradient-to-br from-gray-900 via-primary-950 to-gray-900 text-white overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 left-0 w-96 h-96 bg-primary-500/10 rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2 hidden sm:block"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary-500/10 rounded-full filter blur-3xl translate-x-1/2 translate-y-1/2 hidden sm:block"></div>
                <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-primary-400/5 rounded-full filter blur-2xl -translate-x-1/2 -translate-y-1/2 hidden sm:block"></div>
                
                <div class="relative max-w-7xl mx-auto py-10 sm:py-16 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12">
                        <!-- Brand -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center gap-3 mb-4 sm:mb-6">
                                <div class="w-11 h-11 sm:w-14 sm:h-14">
                                    <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-full h-full object-contain drop-shadow-lg">
                                </div>
                                <div>
                                    <div class="font-display font-bold text-lg sm:text-xl">CSU Faculty Association</div>
                                    <div class="text-xs sm:text-sm text-primary-400/70">Cagayan State University</div>
                                </div>
                            </div>
                            <p class="text-gray-400 leading-relaxed max-w-md text-sm sm:text-base">
                                Dedicated to serving and empowering faculty members across all campuses. 
                                Together we build a stronger academic community with comprehensive benefits and support.
                            </p>
                            <div class="flex gap-3 mt-6">
                                <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors">
                                    <span class="material-icons text-lg">facebook</span>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors">
                                    <span class="material-icons-outlined text-lg">email</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div>
                            <h3 class="font-display font-semibold text-lg mb-6 text-primary-400">Quick Links</h3>
                            <ul class="space-y-3">
                                <li>
                                    <a href="{{ route('transparency.index') }}" class="flex items-center gap-2 text-gray-400 hover:text-primary-300 transition-colors group">
                                        <span class="material-icons-round text-sm text-primary-500 group-hover:translate-x-1 transition-transform">chevron_right</span>
                                        Transparency Wall
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('trainings.index') }}" class="flex items-center gap-2 text-gray-400 hover:text-primary-300 transition-colors group">
                                        <span class="material-icons-round text-sm text-primary-500 group-hover:translate-x-1 transition-transform">chevron_right</span>
                                        Trainings & Seminars
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('campuses.index') }}" class="flex items-center gap-2 text-gray-400 hover:text-primary-300 transition-colors group">
                                        <span class="material-icons-round text-sm text-primary-500 group-hover:translate-x-1 transition-transform">chevron_right</span>
                                        Our Campuses
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization') }}" class="flex items-center gap-2 text-gray-400 hover:text-primary-300 transition-colors group">
                                        <span class="material-icons-round text-sm text-primary-500 group-hover:translate-x-1 transition-transform">chevron_right</span>
                                        Officers
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Contact -->
                        <div>
                            <h3 class="font-display font-semibold text-lg mb-6 text-primary-400">Contact Us</h3>
                            <ul class="space-y-4">
                                <li>
                                    <a href="mailto:ufa@university.edu.ph" class="flex items-start gap-3 text-gray-400 hover:text-primary-300 transition-colors">
                                        <div class="w-10 h-10 rounded-xl bg-primary-800/50 flex items-center justify-center flex-shrink-0 border border-primary-500/10">
                                            <span class="material-icons-outlined text-lg text-primary-500">email</span>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500">Email</div>
                                            <div>ufa@university.edu.ph</div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:+6328123456" class="flex items-start gap-3 text-gray-400 hover:text-primary-300 transition-colors">
                                        <div class="w-10 h-10 rounded-xl bg-primary-800/50 flex items-center justify-center flex-shrink-0 border border-primary-500/10">
                                            <span class="material-icons-outlined text-lg text-primary-500">phone</span>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500">Phone</div>
                                            <div>(02) 8123-4567</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="flex items-start gap-3 text-gray-400">
                                    <div class="w-10 h-10 rounded-xl bg-primary-800/50 flex items-center justify-center flex-shrink-0 border border-primary-500/10">
                                        <span class="material-icons-outlined text-lg text-primary-500">location_on</span>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Address</div>
                                        <div>Main Campus, University Ave.</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Bottom Bar -->
                    <div class="mt-8 sm:mt-12 pt-6 sm:pt-8 border-t border-primary-800/50 flex flex-col md:flex-row items-center justify-between gap-4">
                        <p class="text-gray-500 text-xs sm:text-sm">
                            © {{ date('Y') }} University Faculty Association. All rights reserved.
                        </p>
                        <div class="flex items-center gap-6 text-sm text-gray-500">
                            <a href="#" class="inline-block py-1 hover:text-primary-400 transition-colors">Privacy Policy</a>
                            <a href="#" class="inline-block py-1 hover:text-primary-400 transition-colors">Terms of Service</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
