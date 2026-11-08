@auth
@php
    // Compute group active states for sidebar navigation
    $isClaimsGroupActive = request()->routeIs('admin.claims-monitoring')
        || request()->routeIs('admin.claim-types.*')
        || request()->routeIs('admin.dues.*')
        || request()->routeIs('admin.deductions.*')
        || request()->routeIs('admin.fund.*')
        || request()->routeIs('admin.monthly-deductions.*');

    $isMembersGroupActive = request()->routeIs('admin.faculty-profiles.*')
        || request()->routeIs('admin.pending-registrations.*')
        || request()->routeIs('admin.campuses.*');

    $isContentGroupActive = request()->routeIs('admin.transparency.*')
        || request()->routeIs('admin.trainings.*');

    $autoOpenGroup = '';
    if ($isClaimsGroupActive) $autoOpenGroup = 'claims';
    elseif ($isMembersGroupActive) $autoOpenGroup = 'members';
    elseif ($isContentGroupActive) $autoOpenGroup = 'content';
@endphp
<aside x-data="{
    mobileOpen: false,
    openGroup: '{{ $autoOpenGroup }}',
    hoverGroup: null,
    expanded: localStorage.getItem('sidebarExpanded') === 'true'
}"
    x-init="$watch('expanded', val => localStorage.setItem('sidebarExpanded', val))"
    @toggle-mobile-sidebar.window="
        if (window.innerWidth >= 1024) {
            expanded = !expanded;
        } else {
            mobileOpen = !mobileOpen;
        }
    "
    class="fixed inset-y-0 left-0 z-40 flex-shrink-0 transition-all duration-300 ease-out lg:sticky lg:bottom-auto lg:h-screen"
    :class="{
        'w-72': expanded || mobileOpen,
        'w-20': !expanded && !mobileOpen,
        'translate-x-0': mobileOpen,
        '-translate-x-full lg:translate-x-0': !mobileOpen
    }">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm lg:hidden z-30">
    </div>

    <!-- Sidebar Container -->
    <div class="h-full flex flex-col relative z-40 sidebar-gradient overflow-hidden transition-all duration-300 ease-out"
         :class="(expanded || mobileOpen) ? 'w-72' : 'w-20'">

        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-500/10 to-transparent rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-primary-400/5 to-transparent rounded-full translate-y-24 -translate-x-24"></div>

        <!-- Logo Section -->
        <div class="relative flex items-center p-4 h-[76px]">
            <a href="{{ route('dashboard') }}" class="flex items-center group flex-shrink-0">
                <div class="w-11 h-11 flex-shrink-0 transition-all duration-300 group-hover:scale-105">
                    <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-full h-full object-contain drop-shadow-lg">
                </div>
                <div class="ml-3 overflow-hidden transition-all duration-300 ease-out"
                     :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">
                    <span class="font-display font-bold text-xl text-white tracking-tight whitespace-nowrap">CSU-UFA</span>
                    <p class="text-xs text-white/60 font-medium whitespace-nowrap">Management System</p>
                </div>
            </a>
            <!-- Close Button (Mobile only) -->
            <button @click="mobileOpen = false"
                    class="lg:hidden ml-auto w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 text-white/60 transition-all duration-300">
                <span class="material-icons-round">close</span>
            </button>
        </div>

        <!-- User Quick Info (only when expanded) -->
        <div class="px-3 overflow-hidden transition-all duration-300 ease-out"
             :class="(expanded || mobileOpen) ? 'opacity-100 h-auto py-4' : 'opacity-0 h-0 py-0'">
            <div class="flex items-center space-x-3 p-3 rounded-xl bg-white/10 transition-colors duration-300">
                <div class="relative flex-shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-gray-900"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/50 truncate">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-3 px-3 space-y-1">

            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- MAIN SECTION -->
            <!-- ═══════════════════════════════════════════════════════════ -->

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-primary-500/15 text-white' : 'hover:bg-primary-500/10' }}"
               title="Dashboard">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-primary-400 to-primary-600 shadow-lg shadow-primary-500/30' : 'bg-white/10 group-hover:bg-primary-500/20' }}">
                    <span class="material-icons-round {{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/80' }}">dashboard</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Dashboard</span>
            </a>

            @if(Auth::user()->isOfficer())
            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- ADMINISTRATION SECTION -->
            <!-- ═══════════════════════════════════════════════════════════ -->
            <div class="pt-4 pb-1">
                <div class="overflow-hidden transition-all duration-300 ease-out"
                     :class="(expanded || mobileOpen) ? 'opacity-100' : 'opacity-0'">
                    <p class="px-2 text-[10px] font-bold text-white/40 uppercase tracking-widest">
                        @if(Auth::user()->isSuperAdmin())
                            Administration
                        @elseif(Auth::user()->isUniversityPresident())
                            President Panel
                        @elseif(Auth::user()->isUniversityTreasurer())
                            Treasurer Panel
                        @elseif(Auth::user()->isCampusPresident())
                            Campus Management
                        @endif
                    </p>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- CLAIMS & FINANCE GROUP -->
            <!-- ═══════════════════════════════════════════════════════════ -->
            <div class="relative"
                 @mouseenter="if (!(expanded || mobileOpen)) hoverGroup = 'claims'"
                 @mouseleave="hoverGroup = null">
                <!-- Group Header -->
                <button @click="if (expanded || mobileOpen) openGroup = openGroup === 'claims' ? null : 'claims'"
                        class="w-full group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ $isClaimsGroupActive ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
                        title="Claims & Finance">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ $isClaimsGroupActive ? 'bg-gradient-to-br from-primary-400 to-primary-600 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                        <span class="material-icons-round">payments</span>
                    </div>
                    <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out flex-1 text-left"
                          :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Claims & Finance</span>
                    <span class="material-icons-round text-white/40 text-lg transition-transform duration-300"
                          :class="{
                              'rotate-180': openGroup === 'claims',
                              'block': (expanded || mobileOpen),
                              'hidden': !(expanded || mobileOpen)
                          }">expand_more</span>
                </button>

                <!-- Sub-items (expanded sidebar) -->
                <div x-show="(expanded || mobileOpen) && openGroup === 'claims'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mt-0.5 ml-5 space-y-0.5 border-l border-primary-500/20 pl-2">

                    <a href="{{ route('admin.claims-monitoring') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.claims-monitoring') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.claims-monitoring') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">assessment</span>
                        </div>
                        <span class="ml-2 text-sm">Claims Monitoring</span>
                    </a>

                    @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.claim-types.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.claim-types.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.claim-types.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">category</span>
                        </div>
                        <span class="ml-2 text-sm">Claim Types</span>
                    </a>
                    @endif

                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isUniversityTreasurer())
                    <a href="{{ route('admin.dues.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.dues.*') || request()->routeIs('admin.monthly-deductions.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.dues.*') || request()->routeIs('admin.monthly-deductions.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">account_balance_wallet</span>
                        </div>
                        <span class="ml-2 text-sm">Deductions Collection</span>
                    </a>

                    <a href="{{ route('admin.deductions.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.deductions.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.deductions.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">percent</span>
                        </div>
                        <span class="ml-2 text-sm">Deduction Settings</span>
                    </a>

                    <a href="{{ route('admin.fund.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.fund.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.fund.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">savings</span>
                        </div>
                        <span class="ml-2 text-sm">Fund Monitoring</span>
                    </a>
                    @endif
                </div>

                <!-- Flyout (collapsed sidebar hover) -->
                <div x-show="hoverGroup === 'claims' && !(expanded || mobileOpen)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-x-2"
                     class="absolute left-full top-0 ml-3 w-56 z-50">
                    <div class="bg-gray-900/95 backdrop-blur-xl rounded-xl shadow-2xl border border-white/10 p-2 space-y-0.5">
                        <p class="px-3 py-1.5 text-[10px] font-bold text-primary-400 uppercase tracking-widest">Claims & Finance</p>

                        <a href="{{ route('admin.claims-monitoring') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.claims-monitoring') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">assessment</span>
                            <span class="text-sm">Claims Monitoring</span>
                        </a>

                        @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.claim-types.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.claim-types.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">category</span>
                            <span class="text-sm">Claim Types</span>
                        </a>
                        @endif

                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isUniversityTreasurer())
                        <a href="{{ route('admin.dues.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.dues.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">account_balance_wallet</span>
                            <span class="text-sm">Deductions Collection</span>
                        </a>

                        <a href="{{ route('admin.deductions.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.deductions.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">percent</span>
                            <span class="text-sm">Deduction Settings</span>
                        </a>

                        <a href="{{ route('admin.fund.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.fund.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">savings</span>
                            <span class="text-sm">Fund Monitoring</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- MEMBERS & CAMPUS GROUP -->
            <!-- ═══════════════════════════════════════════════════════════ -->
            @if(Auth::user()->isSuperAdmin() || Auth::user()->canManagePendingRegistrations())
            @php
                $pendingCount = \App\Models\PendingRegistration::where('status', 'pending')
                    ->when(Auth::user()->isCampusPresident(), function($query) {
                        return $query->where('campus_id', Auth::user()->campus_id);
                    })
                    ->count();
            @endphp
            <div class="relative"
                 @mouseenter="if (!(expanded || mobileOpen)) hoverGroup = 'members'"
                 @mouseleave="hoverGroup = null">
                <!-- Group Header -->
                <button @click="if (expanded || mobileOpen) openGroup = openGroup === 'members' ? null : 'members'"
                        class="w-full group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ $isMembersGroupActive ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
                        title="Members & Campus">
                    <div class="relative w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ $isMembersGroupActive ? 'bg-gradient-to-br from-primary-400 to-primary-600 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                        <span class="material-icons-round">groups</span>
                        @if($pendingCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                                {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                            </span>
                        @endif
                    </div>
                    <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out flex-1 text-left"
                          :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Members & Campus</span>
                    <span class="material-icons-round text-white/40 text-lg transition-transform duration-300"
                          :class="{
                              'rotate-180': openGroup === 'members',
                              'block': (expanded || mobileOpen),
                              'hidden': !(expanded || mobileOpen)
                          }">expand_more</span>
                </button>

                <!-- Sub-items (expanded sidebar) -->
                <div x-show="(expanded || mobileOpen) && openGroup === 'members'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mt-0.5 ml-5 space-y-0.5 border-l border-primary-500/20 pl-2">

                    @if(Auth::user()->canManageMembers())
                    <a href="{{ route('admin.faculty-profiles.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.faculty-profiles.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.faculty-profiles.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">person_search</span>
                        </div>
                        <span class="ml-2 text-sm">Faculty Members</span>
                    </a>
                    @endif

                    @if(Auth::user()->canManagePendingRegistrations())
                    <a href="{{ route('admin.pending-registrations.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.pending-registrations.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="relative w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.pending-registrations.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">how_to_reg</span>
                            @if($pendingCount > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                    {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                                </span>
                            @endif
                        </div>
                        <span class="ml-2 text-sm">Pending Registrations</span>
                    </a>
                    @endif

                    @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.campuses.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.campuses.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.campuses.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">location_city</span>
                        </div>
                        <span class="ml-2 text-sm">Campus Management</span>
                    </a>
                    @endif
                </div>

                <!-- Flyout (collapsed sidebar hover) -->
                <div x-show="hoverGroup === 'members' && !(expanded || mobileOpen)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-x-2"
                     class="absolute left-full top-0 ml-3 w-56 z-50">
                    <div class="bg-gray-900/95 backdrop-blur-xl rounded-xl shadow-2xl border border-white/10 p-2 space-y-0.5">
                        <p class="px-3 py-1.5 text-[10px] font-bold text-primary-400 uppercase tracking-widest">Members & Campus</p>

                        @if(Auth::user()->canManageMembers())
                        <a href="{{ route('admin.faculty-profiles.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.faculty-profiles.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">person_search</span>
                            <span class="text-sm">Faculty Members</span>
                        </a>
                        @endif

                        @if(Auth::user()->canManagePendingRegistrations())
                        <a href="{{ route('admin.pending-registrations.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.pending-registrations.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">how_to_reg</span>
                            <span class="text-sm">Pending Registrations</span>
                            @if($pendingCount > 0)
                                <span class="ml-auto w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                    {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                                </span>
                            @endif
                        </a>
                        @endif

                        @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.campuses.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.campuses.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">location_city</span>
                            <span class="text-sm">Campus Management</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- CONTENT GROUP (Super Admin Only) -->
            <!-- ═══════════════════════════════════════════════════════════ -->
            @if(Auth::user()->isSuperAdmin())
            <div class="relative"
                 @mouseenter="if (!(expanded || mobileOpen)) hoverGroup = 'content'"
                 @mouseleave="hoverGroup = null">
                <!-- Group Header -->
                <button @click="if (expanded || mobileOpen) openGroup = openGroup === 'content' ? null : 'content'"
                        class="w-full group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ $isContentGroupActive ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
                        title="Content Management">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ $isContentGroupActive ? 'bg-gradient-to-br from-primary-400 to-primary-600 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                        <span class="material-icons-round">library_books</span>
                    </div>
                    <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out flex-1 text-left"
                          :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Content</span>
                    <span class="material-icons-round text-white/40 text-lg transition-transform duration-300"
                          :class="{
                              'rotate-180': openGroup === 'content',
                              'block': (expanded || mobileOpen),
                              'hidden': !(expanded || mobileOpen)
                          }">expand_more</span>
                </button>

                <!-- Sub-items (expanded sidebar) -->
                <div x-show="(expanded || mobileOpen) && openGroup === 'content'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mt-0.5 ml-5 space-y-0.5 border-l border-primary-500/20 pl-2">

                    <a href="{{ route('admin.transparency.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.transparency.index') || request()->routeIs('admin.transparency.create') || request()->routeIs('admin.transparency.edit') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.transparency.index') || request()->routeIs('admin.transparency.create') || request()->routeIs('admin.transparency.edit') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">article</span>
                        </div>
                        <span class="ml-2 text-sm">Transparency Posts</span>
                    </a>

                    <a href="{{ route('admin.trainings.index') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.trainings.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.trainings.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">event</span>
                        </div>
                        <span class="ml-2 text-sm">Trainings Management</span>
                    </a>

                    <a href="{{ route('admin.transparency.dashboard') }}"
                       class="group flex items-center px-2 py-1.5 rounded-lg text-white/60 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.transparency.dashboard') || request()->routeIs('admin.transparency.memos.*') || request()->routeIs('admin.transparency.resolutions.*') ? 'bg-white/10 text-white' : 'hover:bg-white/5' }}">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0 {{ request()->routeIs('admin.transparency.dashboard') || request()->routeIs('admin.transparency.memos.*') || request()->routeIs('admin.transparency.resolutions.*') ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70' }}">
                            <span class="material-icons-round text-[18px]">dashboard_customize</span>
                        </div>
                        <span class="ml-2 text-sm">Transparency Wall</span>
                    </a>
                </div>

                <!-- Flyout (collapsed sidebar hover) -->
                <div x-show="hoverGroup === 'content' && !(expanded || mobileOpen)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 -translate-x-2"
                     class="absolute left-full top-0 ml-3 w-56 z-50">
                    <div class="bg-gray-900/95 backdrop-blur-xl rounded-xl shadow-2xl border border-white/10 p-2 space-y-0.5">
                        <p class="px-3 py-1.5 text-[10px] font-bold text-primary-400 uppercase tracking-widest">Content</p>

                        <a href="{{ route('admin.transparency.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.transparency.index') || request()->routeIs('admin.transparency.create') || request()->routeIs('admin.transparency.edit') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">article</span>
                            <span class="text-sm">Transparency Posts</span>
                        </a>

                        <a href="{{ route('admin.trainings.index') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.trainings.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">event</span>
                            <span class="text-sm">Trainings Management</span>
                        </a>

                        <a href="{{ route('admin.transparency.dashboard') }}"
                           class="flex items-center px-3 py-2 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200 {{ request()->routeIs('admin.transparency.dashboard') || request()->routeIs('admin.transparency.memos.*') || request()->routeIs('admin.transparency.resolutions.*') ? 'bg-white/10 text-white' : '' }}">
                            <span class="material-icons-round text-[18px] mr-2.5">dashboard_customize</span>
                            <span class="text-sm">Transparency Wall</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Officer Management -->
            <a href="{{ route('admin.officers.index') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.officers.*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="Officer Management">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('admin.officers.*') ? 'bg-gradient-to-br from-primary-500 to-primary-700 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">shield</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Officer Management</span>
            </a>

            <!-- System Settings (standalone) -->
            <a href="{{ route('admin.settings.index') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.members.*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="System Settings">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.members.*') ? 'bg-gradient-to-br from-primary-500 to-primary-700 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">settings</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">System Settings</span>
            </a>
            @endif
            @endif

            @if(!Auth::user()->isSuperAdmin())
            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- MY ACCOUNT SECTION -->
            <!-- ═══════════════════════════════════════════════════════════ -->
            <div class="pt-4 pb-1">
                <div class="overflow-hidden transition-all duration-300 ease-out"
                     :class="(expanded || mobileOpen) ? 'opacity-100' : 'opacity-0'">
                    <p class="px-2 text-[10px] font-bold text-white/40 uppercase tracking-widest">My Account</p>
                </div>
            </div>

            <a href="{{ route('my-profile') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('my-profile*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="My Profile">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('my-profile*') ? 'bg-gradient-to-br from-primary-300 to-primary-500 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">person</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">My Profile</span>
            </a>

            <a href="{{ route('beneficiaries.index') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('beneficiaries.*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="Beneficiaries">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('beneficiaries.*') ? 'bg-gradient-to-br from-primary-200 to-primary-400 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">family_restroom</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Beneficiaries</span>
            </a>

            <a href="{{ route('claims.index', Auth::user()->isOfficer() ? ['my_claims' => 1] : []) }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('claims.*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="My Claims">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('claims.*') ? 'bg-gradient-to-br from-primary-400 to-primary-600 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">receipt_long</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">My Claims</span>
            </a>

            <a href="{{ route('my-salary-deductions') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('my-salary-deductions') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="Salary Deductions">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('my-salary-deductions') ? 'bg-gradient-to-br from-primary-300 to-primary-500 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">assessment</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Salary Deductions</span>
            </a>
            @endif

            <!-- ═══════════════════════════════════════════════════════════ -->
            <!-- RESOURCES SECTION -->
            <!-- ═══════════════════════════════════════════════════════════ -->
            <div class="pt-4 pb-1">
                <div class="overflow-hidden transition-all duration-300 ease-out"
                     :class="(expanded || mobileOpen) ? 'opacity-100' : 'opacity-0'">
                    <p class="px-2 text-[10px] font-bold text-white/40 uppercase tracking-widest">Resources</p>
                </div>
            </div>

            <a href="{{ route('transparency.index') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('transparency.*') && !request()->routeIs('admin.transparency.*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="Transparency Wall">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('transparency.*') && !request()->routeIs('admin.transparency.*') ? 'bg-gradient-to-br from-primary-400 to-primary-600 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">visibility</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Transparency Wall</span>
            </a>

            <a href="{{ route('trainings.index') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('trainings.*') && !request()->routeIs('admin.trainings.*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="Trainings & Seminars">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('trainings.*') && !request()->routeIs('admin.trainings.*') ? 'bg-gradient-to-br from-primary-300 to-primary-500 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">event_note</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Trainings & Seminars</span>
            </a>

            <a href="{{ route('campuses.index') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('campuses.*') && !request()->routeIs('admin.campuses.*') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="Campuses">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('campuses.*') && !request()->routeIs('admin.campuses.*') ? 'bg-gradient-to-br from-primary-200 to-primary-400 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">apartment</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Campuses</span>
            </a>

            <a href="{{ route('organization') }}"
               class="group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white transition-all duration-300 {{ request()->routeIs('organization') ? 'bg-white/15 text-white' : 'hover:bg-white/10' }}"
               title="Officers">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300 flex-shrink-0 {{ request()->routeIs('organization') ? 'bg-gradient-to-br from-primary-300 to-primary-500 shadow-lg' : 'bg-white/10 group-hover:bg-white/20' }}">
                    <span class="material-icons-round">account_tree</span>
                </div>
                <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                      :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Officers</span>
            </a>
        </nav>

        <!-- ═══════════════════════════════════════════════════════════ -->
        <!-- FOOTER SECTION: Toggle & Logout -->
        <!-- ═══════════════════════════════════════════════════════════ -->
        <div>
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="p-3">
                @csrf
                <button type="submit"
                        class="w-full group flex items-center px-2 py-2.5 rounded-xl text-white/80 hover:text-white hover:bg-red-500/20 transition-all duration-300"
                        title="Sign Out">
                    <div class="w-10 h-10 rounded-lg bg-white/10 group-hover:bg-red-500/30 flex items-center justify-center transition-all duration-300 flex-shrink-0">
                        <span class="material-icons-round text-white/80 group-hover:text-red-300">logout</span>
                    </div>
                    <span class="ml-3 font-medium whitespace-nowrap overflow-hidden transition-all duration-300 ease-out"
                          :class="(expanded || mobileOpen) ? 'w-auto opacity-100' : 'w-0 opacity-0'">Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Sidebar Toggle Button -->
<button @click="$dispatch('toggle-mobile-sidebar')"
        x-data="{ open: window.innerWidth >= 1024 ? localStorage.getItem('sidebarExpanded') === 'true' : false }"
        @toggle-mobile-sidebar.window="open = !open"
        class="fixed bottom-6 left-6 z-50 w-14 h-14 bg-gradient-to-br from-primary-500 to-accent-600 text-white rounded-2xl shadow-xl shadow-primary-500/30 flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 safe-bottom touch-target">
    <span class="material-icons-round text-2xl transition-transform duration-300" :class="open ? 'rotate-180' : ''">arrow_forward</span>
</button>
@endauth
