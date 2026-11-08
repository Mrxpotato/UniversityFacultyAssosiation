<nav x-data="{ open: false, searchFocused: false, showNotifications: false }" class="bg-white/80 backdrop-blur-xl border-b border-gray-100/50 sticky top-0 z-40">
    <div class="max-w-full mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="flex justify-between h-14 sm:h-16">
            <div class="flex items-center">
                <!-- Page Title with animation -->
                <div class="flex items-center space-x-3">
                    <div class="hidden lg:flex items-center space-x-2">
                        <nav class="flex items-center text-sm" aria-label="Breadcrumb">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center p-1 -m-1 text-gray-400 hover:text-primary-600 transition-colors">
                                <span class="material-icons-round text-lg">home</span>
                            </a>
                            <span class="material-icons-round text-gray-300 text-lg mx-1">chevron_right</span>
                            <span class="text-gray-700 font-medium">@yield('page-title', 'Dashboard')</span>
                        </nav>
                    </div>
                    <span class="lg:hidden text-base sm:text-lg font-semibold text-gray-800 truncate max-w-[200px] sm:max-w-none">
                        @yield('page-title', 'Dashboard')
                    </span>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-1 sm:space-x-2 md:space-x-4">
                <!-- Search - Enhanced -->
                <div class="hidden md:block relative" x-data="{ query: '' }">
                    <div class="relative group">
                        <span class="material-icons-round absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">search</span>
                        <input type="text" 
                               x-model="query"
                               @focus="searchFocused = true"
                               @blur="setTimeout(() => searchFocused = false, 200)"
                               placeholder="Search anything..." 
                               aria-label="Search"
                               class="w-56 lg:w-72 pl-12 pr-4 py-2.5 rounded-xl bg-gray-50 border-0 focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all duration-300 text-sm placeholder-gray-400"
                               :class="searchFocused ? 'w-80 shadow-lg shadow-primary-500/10' : ''">
                        <kbd class="absolute right-3 top-1/2 -translate-y-1/2 hidden lg:inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-xs text-gray-400 font-mono">⌘K</kbd>
                    </div>
                    
                    <!-- Search Dropdown -->
                    <div x-show="searchFocused && query.length > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="p-3 text-sm text-gray-500">
                            <span class="material-icons-round text-sm align-middle mr-1">search</span>
                            Press Enter to search for "<span x-text="query" class="font-medium text-gray-700"></span>"
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                @if(\App\Models\SystemSetting::areProcessesEnabled())
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('claims.create') }}" 
                       class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200"
                       title="New Claim">
                        <span class="material-icons-round">add_circle</span>
                    </a>
                </div>
                @endif

                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="relative p-2.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200 group">
                        <span class="material-icons-round">notifications</span>
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white animate-pulse"></span>
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden z-50">
                        <div class="px-4 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white">
                            <h3 class="font-semibold">Notifications</h3>
                            <p class="text-xs text-white/80">You have 3 unread messages</p>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors border-b border-gray-50">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons-round text-emerald-600 text-lg">check_circle</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">Claim Approved</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Your medical claim has been approved</p>
                                    <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors border-b border-gray-50">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons-round text-blue-600 text-lg">event</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">New Training</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Leadership seminar scheduled for next week</p>
                                    <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                                </div>
                            </a>
                            <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons-round text-amber-600 text-lg">payments</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-800">Dues Reminder</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Monthly dues for January is due</p>
                                    <p class="text-xs text-gray-400 mt-1">3 days ago</p>
                                </div>
                            </a>
                        </div>
                        <a href="#" class="block px-4 py-3 bg-gray-50 text-center text-sm font-medium text-primary-600 hover:bg-gray-100 transition-colors">
                            View all notifications
                        </a>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="60">
                    <x-slot name="trigger">
                        <button class="flex items-center p-1.5 rounded-xl hover:bg-gray-50 transition-all duration-200 group">
                            <div class="relative">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-semibold text-sm shadow-lg shadow-primary-500/20 group-hover:shadow-primary-500/30 transition-all duration-300">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 rounded-full border-2 border-white"></div>
                            </div>
                            <div class="hidden md:block ml-3 text-left">
                                <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ Str::limit(Auth::user()->name, 15) }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->role_label }}</p>
                            </div>
                            <span class="material-icons-round text-gray-400 ml-2 hidden md:block text-lg group-hover:text-gray-600">expand_more</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->employee_id }}</p>
                                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium {{ Auth::user()->isOfficer() ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ Auth::user()->role_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="py-2">
                            @if(!Auth::user()->isSuperAdmin())
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <span class="material-icons-round text-gray-400 mr-3 text-lg">settings</span>
                                <span class="text-gray-700">{{ __('Account Settings') }}</span>
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('my-profile')" class="flex items-center px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <span class="material-icons-round text-gray-400 mr-3 text-lg">badge</span>
                                <span class="text-gray-700">{{ __('My Profile') }}</span>
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('claims.index')" class="flex items-center px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <span class="material-icons-round text-gray-400 mr-3 text-lg">receipt_long</span>
                                <span class="text-gray-700">{{ __('My Claims') }}</span>
                            </x-dropdown-link>
                            @endif

                            <x-dropdown-link href="#" class="flex items-center px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <span class="material-icons-round text-gray-400 mr-3 text-lg">help_outline</span>
                                <span class="text-gray-700">{{ __('Help & Support') }}</span>
                            </x-dropdown-link>
                        </div>

                        <div class="border-t border-gray-100 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="#"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="flex items-center px-4 py-2.5 text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors">
                                    <span class="material-icons-round mr-3 text-lg">logout</span>
                                    <span>{{ __('Sign Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all duration-200">
                    <span class="material-icons-round" x-text="open ? 'close' : 'menu'">menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" 
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="hidden md:hidden border-t border-gray-100 bg-white">
        
        <!-- Mobile Search -->
        <div class="p-4">
            <div class="relative">
                <span class="material-icons-round absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                <input type="text" 
                       placeholder="Search..." 
                       aria-label="Search"
                       class="w-full pl-12 pr-4 py-3 rounded-xl bg-gray-50 border-0 focus:ring-2 focus:ring-primary-500/20 text-sm">
            </div>
        </div>
        
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl">
                <span class="material-icons-round mr-3 text-lg">dashboard</span>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('claims.create')" class="rounded-xl">
                <span class="material-icons-round mr-3 text-lg">add_circle</span>
                {{ __('New Claim') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-100">
            <div class="px-4 flex items-center">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold shadow-lg">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="ml-3">
                    <div class="font-semibold text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-gray-500">{{ Auth::user()->employee_id }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-4">
                @if(!Auth::user()->isSuperAdmin())
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl">
                    <span class="material-icons-round mr-3 text-lg">settings</span>
                    {{ __('Account Settings') }}
                </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="#"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 hover:bg-red-50 rounded-xl">
                        <span class="material-icons-round mr-3 text-lg">logout</span>
                        {{ __('Sign Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
