<x-public-layout title="Home">
    <!-- Hero Section - Premium Animated -->
    <section class="relative min-h-[100svh] flex items-center overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900"></div>
        
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <div class="container mx-auto px-5 sm:px-6 pt-24 pb-28 sm:py-20 lg:py-24 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-16">
                <!-- Left Content -->
                <div class="lg:w-1/2 text-center lg:text-left">
                    <!-- Badge -->
                    <div class="inline-flex items-center px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full bg-white/10 border border-white/20 mb-6 sm:mb-8 animate-fade-in-down">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
                        <span class="text-xs sm:text-sm text-white/90 font-medium">Empowering Faculty Since 2010</span>
                    </div>
                    
                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-4xl md:text-6xl lg:text-7xl font-display font-bold text-white mb-4 sm:mb-6 leading-[1.1]">
                        <span class="block animate-fade-in-up">University</span>
                        <span class="block animate-fade-in-up" style="animation-delay: 0.1s;">Faculty</span>
                        <span class="block bg-gradient-to-r from-primary-300 via-primary-400 to-primary-500 bg-clip-text text-transparent animate-fade-in-up" style="animation-delay: 0.2s;">Association</span>
                    </h1>
                    
                    <!-- Subheading -->
                    <p class="text-sm sm:text-xl md:text-2xl text-white/80 mb-8 sm:mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed animate-fade-in-up" style="animation-delay: 0.3s;">
                        <span class="font-extrabold text-white">C</span>ommitted to <span class="font-extrabold text-white">S</span>ervice, <span class="font-extrabold text-white">U</span>nited for <span class="font-extrabold text-white">U</span>pliftment, <span class="font-extrabold text-white">F</span>aculty in <span class="font-extrabold text-white">A</span>ction
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start animate-fade-in-up" style="animation-delay: 0.4s;">
                        @auth
                            <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 overflow-hidden rounded-2xl bg-white text-primary-700 font-semibold transition-all duration-300 hover:bg-primary-50 shadow-lg text-sm sm:text-base">
                                <span class="material-icons-round mr-2 relative text-xl">dashboard</span>
                                <span class="relative">Go to Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 overflow-hidden rounded-2xl bg-white text-primary-700 font-semibold transition-all duration-300 hover:bg-primary-50 shadow-lg text-sm sm:text-base">
                                <span class="material-icons-round mr-2 relative z-10 text-xl">login</span>
                                <span class="relative z-10">Member Login</span>
                            </a>
                            @if (Route::has('register') && \App\Models\SystemSetting::isRegistrationEnabled())
                                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 rounded-2xl bg-white/10 text-white font-semibold border border-white/20 transition-all duration-300 hover:bg-white/20 text-sm sm:text-base">
                                    <span class="material-icons-round mr-2 text-xl">person_add</span>
                                    Request Account
                                </a>
                            @endif
                        @endauth
                    </div>
                    
                    <!-- Trust Badges -->
                    <div class="mt-8 sm:mt-12 flex items-center justify-center lg:justify-start gap-4 sm:gap-8 animate-fade-in-up" style="animation-delay: 0.5s;">
                        <div class="flex -space-x-2.5 sm:-space-x-3">
                            @for($i = 0; $i < 4; $i++)
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 border-2 border-primary-800/50 flex items-center justify-center text-white text-[10px] sm:text-xs font-bold shadow-md">
                                    {{ ['JD', 'MK', 'AS', 'RC'][$i] }}
                                </div>
                            @endfor
                        </div>
                        <div class="text-white/80 text-xs sm:text-sm">
                            <span class="font-bold text-primary-300">500+</span> Faculty Members
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - Hero Visual (hidden on very small screens, compact on mobile) -->
                <div class="hidden sm:flex lg:w-1/2 justify-center animate-fade-in-left" style="animation-delay: 0.3s;">
                    <div class="relative">
                        <!-- Main Circle -->
                        <div class="w-48 h-48 sm:w-64 sm:h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 flex items-center justify-center">
                            <div class="w-40 h-40 sm:w-56 sm:h-56 md:w-72 md:h-72 lg:w-80 lg:h-80 flex items-center justify-center p-4 sm:p-6 md:p-8">
                                <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-full h-full object-contain drop-shadow-2xl">
                            </div>
                        </div>
                        
                        <!-- Corner Elements -->
                        <div class="absolute -top-4 -right-4 sm:-top-8 sm:-right-8 w-16 h-16 sm:w-20 sm:h-20 md:w-28 md:h-28 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg rotate-12 border border-primary-400/20">
                            <span class="material-icons-round text-white text-2xl sm:text-3xl md:text-5xl">groups</span>
                        </div>
                        
                        <div class="absolute -bottom-3 -left-3 sm:-bottom-6 sm:-left-6 w-14 h-14 sm:w-18 sm:h-18 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-lg -rotate-12">
                            <span class="material-icons-round text-white text-xl sm:text-2xl md:text-4xl">favorite</span>
                        </div>
                        
                        <div class="absolute top-1/2 -left-8 sm:-left-12 md:-left-16 w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center shadow-lg border border-primary-500/20">
                            <span class="material-icons-round text-primary-200 text-lg sm:text-xl md:text-3xl">payments</span>
                        </div>
                        
                        <div class="absolute top-4 -left-4 sm:top-8 sm:-left-8 w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg">
                            <span class="material-icons-round text-white text-base sm:text-lg md:text-2xl">event</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
            <div class="w-8 h-12 rounded-full border-2 border-white/30 flex items-start justify-center p-2">
                <div class="w-1 h-3 bg-white/60 rounded-full"></div>
            </div>
        </div>
        
        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- Stats Section - Animated Counters -->
    <section class="py-12 sm:py-20 bg-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-96 h-96 bg-primary-500 rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary-500 rounded-full filter blur-3xl translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="container mx-auto px-5 sm:px-6 relative">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-6 md:gap-8">
                <div class="text-center group reveal">
                    <div class="w-12 h-12 sm:w-20 sm:h-20 mx-auto mb-2 sm:mb-6 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center transition-colors duration-300">
                        <span class="material-icons-round text-primary-600 text-xl sm:text-4xl">badge</span>
                    </div>
                    <div class="text-2xl sm:text-4xl md:text-5xl font-display font-bold text-gray-900 mb-0.5 sm:mb-2" data-counter="500" data-suffix="+">0</div>
                    <div class="text-gray-600 font-medium text-[11px] sm:text-base">Active Members</div>
                </div>
                
                <div class="text-center group reveal">
                    <div class="w-12 h-12 sm:w-20 sm:h-20 mx-auto mb-2 sm:mb-6 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center transition-colors duration-300">
                        <span class="material-icons-round text-emerald-600 text-xl sm:text-4xl">location_city</span>
                    </div>
                    <div class="text-2xl sm:text-4xl md:text-5xl font-display font-bold text-gray-900 mb-0.5 sm:mb-2" data-counter="9">0</div>
                    <div class="text-gray-600 font-medium text-[11px] sm:text-base">Campuses</div>
                </div>
                
                <div class="text-center group reveal">
                    <div class="w-12 h-12 sm:w-20 sm:h-20 mx-auto mb-2 sm:mb-6 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center transition-colors duration-300 border border-primary-200">
                        <span class="material-icons-round text-primary-600 text-xl sm:text-4xl">payments</span>
                    </div>
                    <div class="text-2xl sm:text-4xl md:text-5xl font-display font-bold text-gray-900 mb-0.5 sm:mb-2" data-counter="2" data-prefix="₱" data-suffix="M+">0</div>
                    <div class="text-gray-600 font-medium text-[11px] sm:text-base">Claims Disbursed</div>
                </div>
                
                <div class="text-center group reveal">
                    <div class="w-12 h-12 sm:w-20 sm:h-20 mx-auto mb-2 sm:mb-6 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center transition-colors duration-300">
                        <span class="material-icons-round text-purple-600 text-xl sm:text-4xl">event</span>
                    </div>
                    <div class="text-2xl sm:text-4xl md:text-5xl font-display font-bold text-gray-900 mb-0.5 sm:mb-2" data-counter="50" data-suffix="+">0</div>
                    <div class="text-gray-600 font-medium text-[11px] sm:text-base">Trainings Held</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-16 sm:py-24 bg-gradient-to-br from-slate-50 via-white to-primary-50/30 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full opacity-30 hidden sm:block">
            <div class="absolute top-20 right-20 w-72 h-72 rounded-full border border-primary-200"></div>
            <div class="absolute top-40 right-40 w-48 h-48 rounded-full border border-primary-200"></div>
        </div>
        
        <div class="container mx-auto px-5 sm:px-6 relative">
            <div class="text-center mb-8 sm:mb-16 reveal">
                <span class="inline-flex items-center px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full bg-gradient-to-r from-primary-100 to-primary-100 text-primary-700 text-xs sm:text-sm font-semibold mb-3 sm:mb-4 border border-primary-200">
                    <span class="material-icons-round text-xs sm:text-sm mr-1.5 sm:mr-2 text-primary-600">stars</span>
                    Member Benefits
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-display font-bold text-gray-900 mb-3 sm:mb-4">
                    Comprehensive Support for
                    <span class="text-gradient-primary">You & Your Family</span>
                </h2>
                <p class="text-sm sm:text-xl text-gray-600 max-w-2xl mx-auto">
                    Join our community and enjoy exclusive benefits designed to support faculty members throughout their careers.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <div class="group card-premium reveal">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-red-400 to-rose-500 flex items-center justify-center mb-4 sm:mb-6 shadow-md transition-colors duration-300">
                        <span class="material-icons-round text-white text-2xl sm:text-[32px]">local_hospital</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-display font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors">Medical Assistance</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Financial support for hospitalization, medical procedures, and health emergencies for members and their beneficiaries.</p>
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-100">
                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                            <span class="material-icons-round text-emerald-500 mr-2 text-base sm:text-lg">check_circle</span>
                            Up to ₱50,000 coverage
                        </div>
                    </div>
                </div>
                
                <div class="group card-premium reveal">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-primary-200 to-primary-400 flex items-center justify-center mb-4 sm:mb-6 shadow-md transition-colors duration-300">
                        <span class="material-icons-round text-white text-2xl sm:text-[32px]">beach_access</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-display font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors">Retirement Benefits</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Celebrate your service with special retirement benefits recognizing your dedication to education.</p>
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-100">
                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                            <span class="material-icons-round text-emerald-500 mr-2 text-base sm:text-lg">check_circle</span>
                            Special retirement package
                        </div>
                    </div>
                </div>
                
                <div class="group card-premium reveal">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-primary-300 to-primary-500 flex items-center justify-center mb-4 sm:mb-6 shadow-md transition-colors duration-300">
                        <span class="material-icons-round text-white text-2xl sm:text-[32px]">trending_up</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-display font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors">Professional Development</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Free seminars, trainings, and workshops to enhance your skills and advance your career in academia.</p>
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-100">
                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                            <span class="material-icons-round text-emerald-500 mr-2 text-base sm:text-lg">check_circle</span>
                            50+ training programs annually
                        </div>
                    </div>
                </div>
                
                <div class="group card-premium reveal">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-gray-500 to-slate-600 flex items-center justify-center mb-4 sm:mb-6 shadow-md transition-colors duration-300">
                        <span class="material-icons-round text-white text-2xl sm:text-[32px]">volunteer_activism</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-display font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors">Death Aid</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Compassionate financial support for families during difficult times, covering funeral and related expenses.</p>
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-100">
                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                            <span class="material-icons-round text-emerald-500 mr-2 text-base sm:text-lg">check_circle</span>
                            Immediate assistance
                        </div>
                    </div>
                </div>
                
                <div class="group card-premium reveal">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-primary-300 to-primary-500 flex items-center justify-center mb-4 sm:mb-6 shadow-md transition-colors duration-300">
                        <span class="material-icons-round text-white text-2xl sm:text-[32px]">visibility</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-display font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors">Financial Transparency</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Complete visibility into organization finances, disbursements, and operations through our transparency wall.</p>
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-100">
                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                            <span class="material-icons-round text-emerald-500 mr-2 text-base sm:text-lg">check_circle</span>
                            Real-time financial reports
                        </div>
                    </div>
                </div>
                
                <div class="group card-premium reveal">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center mb-4 sm:mb-6 shadow-md transition-colors duration-300">
                        <span class="material-icons-round text-white text-2xl sm:text-[32px]">diversity_3</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-display font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors">Strong Community</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">Be part of a supportive network of educators across all campuses, sharing experiences and building connections.</p>
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-100">
                        <div class="flex items-center text-xs sm:text-sm text-gray-500">
                            <span class="material-icons-round text-emerald-500 mr-2 text-base sm:text-lg">check_circle</span>
                            Multi-campus network
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 sm:py-24 bg-white relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 sm:mb-20 reveal">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary-100 to-primary-50 text-primary-700 text-sm font-semibold mb-4 border border-primary-200">
                    <span class="material-icons-round text-sm mr-2">route</span>
                    Simple Process
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-display font-bold text-gray-900 mb-4">
                    How It <span class="text-gradient-primary">Works</span>
                </h2>
                <p class="text-base sm:text-xl text-gray-600 max-w-2xl mx-auto">Simple steps to access your member benefits</p>
            </div>
            
            <div class="relative">
                <div class="hidden md:block absolute top-24 left-0 right-0 h-0.5 bg-gradient-to-r from-primary-300 via-primary-400 to-emerald-400"></div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                    <div class="relative text-center group reveal">
                        <div class="relative z-10 mx-auto w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-display font-bold text-primary-300 shadow-lg transition-all duration-300 mb-4 sm:mb-6 border border-primary-500/20">1</div>
                        <h3 class="text-sm sm:text-base md:text-lg font-display font-bold text-gray-900 mb-2">Request Account</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">Submit a request and complete your faculty profile with all required information.</p>
                    </div>
                    
                    <div class="relative text-center group reveal">
                        <div class="relative z-10 mx-auto w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-600 flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-display font-bold text-white shadow-lg transition-all duration-300 mb-4 sm:mb-6">2</div>
                        <h3 class="text-sm sm:text-base md:text-lg font-display font-bold text-gray-900 mb-2">Pay Monthly Dues</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">Stay active by maintaining your membership through regular due payments.</p>
                    </div>
                    
                    <div class="relative text-center group reveal">
                        <div class="relative z-10 mx-auto w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-2xl sm:text-3xl md:text-4xl font-display font-bold text-primary-900 shadow-lg transition-all duration-300 mb-4 sm:mb-6 border border-primary-300/50">3</div>
                        <h3 class="text-sm sm:text-base md:text-lg font-display font-bold text-gray-900 mb-2">File Claims</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">Submit claims online when you need assistance - retirement, medical, or death aid.</p>
                    </div>
                    
                    <div class="relative text-center group reveal">
                        <div class="relative z-10 mx-auto w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-primary-300 to-primary-500 flex items-center justify-center shadow-lg transition-all duration-300 mb-4 sm:mb-6">
                            <span class="material-icons-round text-white text-2xl sm:text-3xl md:text-5xl">check</span>
                        </div>
                        <h3 class="text-sm sm:text-base md:text-lg font-display font-bold text-gray-900 mb-2">Receive Benefits</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">Get your approved claims disbursed quickly and efficiently.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Campuses Section -->
    <section class="py-16 sm:py-24 bg-gradient-to-br from-primary-50 via-white to-primary-50 relative overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-10 sm:mb-16 reveal">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-primary-100 to-primary-100 text-primary-700 text-sm font-semibold mb-4 border border-primary-200">
                    <span class="material-icons-round text-sm mr-2 text-primary-600">apartment</span>
                    Campus Network
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-display font-bold text-gray-900 mb-4">
                    Our <span class="text-gradient-primary">Campuses</span>
                </h2>
                <p class="text-base sm:text-xl text-gray-600 max-w-2xl mx-auto">Serving faculty members across multiple campuses</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($campuses as $campus)
                    <div class="group bg-white rounded-2xl p-4 sm:p-6 text-center shadow-md hover:shadow-lg transition-all duration-300 reveal border border-primary-100/50">
                        <div class="w-10 h-10 sm:w-14 sm:h-14 mx-auto rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center mb-3 sm:mb-4 group-hover:bg-gradient-to-br group-hover:from-primary-500 group-hover:to-primary-500 transition-all duration-500 border border-primary-200/50">
                            <span class="material-icons-round text-primary-600 group-hover:text-white transition-colors text-xl sm:text-3xl">apartment</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-700 group-hover:text-primary-600 transition-colors">{{ $campus->name }}</p>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12 reveal">
                <a href="{{ route('campuses.index') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-white text-primary-600 font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                    View All Campuses
                    <span class="material-icons-round ml-2">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900"></div>
        
        <div class="container mx-auto px-4 sm:px-6 text-center relative z-10">
            <div class="max-w-3xl mx-auto reveal">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 text-white text-sm font-medium mb-6 sm:mb-8">
                    <span class="material-icons-round text-sm mr-2">celebration</span>
                    Join Our Community Today
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-display font-bold text-white mb-4 sm:mb-6">Ready to Join Our Community?</h2>
                <p class="text-base sm:text-lg md:text-xl text-white/80 mb-6 sm:mb-10 max-w-2xl mx-auto">Become a member today and enjoy all the benefits of being part of the University Faculty Association.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 overflow-hidden rounded-2xl bg-white text-primary-700 font-semibold transition-all duration-300 hover:bg-primary-50 shadow-lg">
                            <span class="material-icons-round mr-2 relative z-10">how_to_reg</span>
                            <span class="relative z-10">Request Account</span>
                        </a>
                        <a href="{{ route('transparency.index') }}" class="inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 rounded-2xl bg-white/10 text-white font-semibold border border-white/20 transition-all duration-300 hover:bg-white/20">
                            <span class="material-icons-round mr-2">visibility</span>
                            View Transparency Wall
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 overflow-hidden rounded-2xl bg-white text-primary-700 font-semibold transition-all duration-300 hover:bg-primary-50 shadow-lg">
                            <span class="material-icons-round mr-2">dashboard</span>
                            Go to Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Links Section -->
    <section class="py-12 sm:py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                <a href="{{ route('transparency.index') }}" class="group reveal">
                    <div class="flex items-center p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-primary-300 to-primary-500 flex items-center justify-center shadow-md mr-3 sm:mr-4 flex-shrink-0">
                            <span class="material-icons-round text-white text-xl sm:text-[28px]">visibility</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-display font-bold text-sm sm:text-base text-gray-900 group-hover:text-emerald-600 transition-colors">Transparency Wall</h3>
                            <p class="text-xs sm:text-sm text-gray-500">View financial reports & updates</p>
                        </div>
                        <span class="material-icons-round text-gray-300 group-hover:text-emerald-500 group-hover:translate-x-2 transition-all">chevron_right</span>
                    </div>
                </a>
                
                <a href="{{ route('trainings.index') }}" class="group reveal">
                    <div class="flex items-center p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-primary-300 to-primary-500 flex items-center justify-center shadow-md mr-3 sm:mr-4 flex-shrink-0">
                            <span class="material-icons-round text-white text-xl sm:text-[28px]">school</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-display font-bold text-sm sm:text-base text-gray-900 group-hover:text-blue-600 transition-colors">Trainings & Seminars</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Upcoming professional development</p>
                        </div>
                        <span class="material-icons-round text-gray-300 group-hover:text-blue-500 group-hover:translate-x-2 transition-all">chevron_right</span>
                    </div>
                </a>
                
                <a href="{{ route('campuses.index') }}" class="group reveal">
                    <div class="flex items-center p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 shadow-md hover:shadow-lg transition-all duration-300">
                        <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-md mr-3 sm:mr-4 flex-shrink-0">
                            <span class="material-icons-round text-white text-xl sm:text-[28px]">location_city</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-display font-bold text-sm sm:text-base text-gray-900 group-hover:text-purple-600 transition-colors">Campus Directory</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Find your campus information</p>
                        </div>
                        <span class="material-icons-round text-gray-300 group-hover:text-purple-500 group-hover:translate-x-2 transition-all">chevron_right</span>
                    </div>
                </a>
            </div>
        </div>
    </section>
</x-public-layout>
