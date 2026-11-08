<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) && $title ? $title.' — '.config('app.name', 'UFA Management System') : config('app.name', 'UFA Management System') }}</title>
        <meta name="description" content="{{ $description ?? 'CSU-UFA Faculty Association management system.' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex relative overflow-hidden">
            <!-- Left Side - Decorative Panel -->
            <div class="hidden lg:flex lg:w-1/2 relative">
                <!-- Animated Gradient Background -->
                <div class="absolute inset-0 gradient-animate"></div>
                
                <!-- Animated Orbs -->
                <div class="absolute top-20 left-10 w-64 h-64 bg-white/10 rounded-full filter blur-3xl animate-float"></div>
                <div class="absolute bottom-32 right-10 w-80 h-80 bg-white/10 rounded-full filter blur-3xl animate-float" style="animation-delay: -2s;"></div>
                <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-white/5 rounded-full filter blur-2xl animate-float" style="animation-delay: -4s;"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 flex flex-col items-center justify-center p-12 text-center">
                    <!-- Logo -->
                    <div class="mb-8 animate-fade-in-down">
                        <div class="w-28 h-28">
                            <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-full h-full object-contain drop-shadow-2xl">
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <div class="text-4xl font-display font-bold text-white mb-4 animate-fade-in-up">
                        CSU Faculty<br>
                        <span class="text-amber-300">Association</span>
                    </div>
                    
                    <p class="text-white/80 text-lg max-w-md mb-12 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <span class="font-extrabold text-white">C</span>ommitted to <span class="font-extrabold text-white">S</span>ervice, <span class="font-extrabold text-white">U</span>nited for <span class="font-extrabold text-white">U</span>pliftment, <span class="font-extrabold text-white">F</span>aculty in <span class="font-extrabold text-white">A</span>ction
                    </p>
                    
                    <!-- Features List -->
                    <div class="space-y-4 text-left animate-fade-in-up" style="animation-delay: 0.2s;">
                        <div class="flex items-center gap-4 text-white/90">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                <span class="material-icons-round text-emerald-300 text-xl">check_circle</span>
                            </div>
                            <span>Medical & Retirement Benefits</span>
                        </div>
                        <div class="flex items-center gap-4 text-white/90">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                <span class="material-icons-round text-emerald-300 text-xl">check_circle</span>
                            </div>
                            <span>Professional Development Programs</span>
                        </div>
                        <div class="flex items-center gap-4 text-white/90">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                                <span class="material-icons-round text-emerald-300 text-xl">check_circle</span>
                            </div>
                            <span>Transparent Financial Operations</span>
                        </div>
                    </div>
                    
                    <!-- Floating Cards -->
                    <div class="absolute bottom-24 left-8 w-48 glass rounded-2xl p-4 float-slow animate-fade-in opacity-90">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                <span class="material-icons-round text-white text-lg">groups</span>
                            </div>
                            <div>
                                <div class="text-white font-bold">500+</div>
                                <div class="text-white/70 text-xs">Active Members</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute top-32 right-8 w-44 glass rounded-2xl p-4 float-medium animate-fade-in opacity-90" style="animation-delay: 0.3s;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                                <span class="material-icons-round text-white text-lg">apartment</span>
                            </div>
                            <div>
                                <div class="text-white font-bold">9</div>
                                <div class="text-white/70 text-xs">Campuses</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Auth Form -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 relative">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-100/50 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-accent-100/50 rounded-full filter blur-3xl translate-y-1/2 -translate-x-1/2"></div>
                
                <div class="w-full max-w-md relative z-10">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                        <a href="/" class="inline-flex items-center gap-3 group">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-600 flex items-center justify-center shadow-xl shadow-primary-500/30 group-hover:scale-110 transition-transform">
                                <span class="material-icons-round text-white" style="font-size: 32px;">school</span>
                            </div>
                            <div class="text-left">
                                <div class="text-lg font-display font-bold text-gray-900">UFA</div>
                                <div class="text-xs text-gray-500">Faculty Association</div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Back to Home Link -->
                    <a href="/" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 mb-8 transition-colors group">
                        <span class="material-icons-round text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
                        <span class="text-sm font-medium">Back to Home</span>
                    </a>
                    
                    <!-- Auth Card -->
                    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 sm:p-10 border border-gray-100 animate-fade-in-up">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <div class="text-center mt-8 text-sm text-gray-500">
                        © {{ date('Y') }} University Faculty Association. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
