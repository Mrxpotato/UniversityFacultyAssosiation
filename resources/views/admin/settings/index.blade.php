<x-app-layout title="Settings">
    <x-slot name="header">
        <div class="flex items-center">
            <span class="material-icons-outlined text-indigo-600 mr-3">settings</span>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('System Settings') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- System Mode / Processes Settings -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-amber-100 flex items-center justify-center mr-3">
                            <span class="material-icons-round text-amber-600">toggle_on</span>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">System Mode</h3>
                            <p class="text-sm text-gray-500">Control whether users can perform actions or only view records</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl {{ $processesEnabled ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
                        <div class="flex items-center">
                            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl {{ $processesEnabled ? 'bg-green-100' : 'bg-amber-100' }} flex items-center justify-center mr-4">
                                <span class="material-icons-round {{ $processesEnabled ? 'text-green-600' : 'text-amber-600' }} text-2xl">
                                    {{ $processesEnabled ? 'edit' : 'visibility' }}
                                </span>
                            </div>
                            <div>
                                <h4 class="font-semibold {{ $processesEnabled ? 'text-green-800' : 'text-amber-800' }}">
                                    System is in {{ $processesEnabled ? 'FULL ACCESS' : 'READ-ONLY' }} Mode
                                </h4>
                                <p class="text-sm {{ $processesEnabled ? 'text-green-600' : 'text-amber-600' }}">
                                    @if($processesEnabled)
                                        Users can add beneficiaries, file claims, and perform all actions.
                                    @else
                                        Users can only view existing records. All action buttons are hidden.
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.settings.toggle-processes') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 {{ $processesEnabled ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-semibold rounded-xl transition-all duration-300 shadow-lg {{ $processesEnabled ? 'shadow-amber-500/30 hover:shadow-amber-500/50' : 'shadow-green-500/30 hover:shadow-green-500/50' }}">
                                <span class="material-icons-round mr-2">
                                    {{ $processesEnabled ? 'visibility' : 'edit' }}
                                </span>
                                {{ $processesEnabled ? 'Switch to Read-Only' : 'Enable Full Access' }}
                            </button>
                        </form>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex">
                            <span class="material-icons-round text-blue-600 mr-3">info</span>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-1">When in Read-Only mode:</p>
                                <ul class="list-disc list-inside space-y-1 ml-2">
                                    <li>Users cannot add new beneficiaries</li>
                                    <li>Users cannot file new claims</li>
                                    <li>Users cannot edit their profile information</li>
                                    <li>Users cannot register for trainings</li>
                                    <li>All "Add", "Edit", "Delete" buttons will be hidden</li>
                                    <li>Users can still view all their existing records</li>
                                    <li>Admins can still perform all administrative tasks</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Settings -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-indigo-100 flex items-center justify-center mr-3">
                            <span class="material-icons-round text-indigo-600">person_add</span>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Member Registration</h3>
                            <p class="text-sm text-gray-500">Control public member registration access</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-xl {{ $registrationEnabled ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-center">
                            <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl {{ $registrationEnabled ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center mr-4">
                                <span class="material-icons-round {{ $registrationEnabled ? 'text-green-600' : 'text-red-600' }} text-2xl">
                                    {{ $registrationEnabled ? 'check_circle' : 'cancel' }}
                                </span>
                            </div>
                            <div>
                                <h4 class="font-semibold {{ $registrationEnabled ? 'text-green-800' : 'text-red-800' }}">
                                    Registration is currently {{ $registrationEnabled ? 'ENABLED' : 'DISABLED' }}
                                </h4>
                                <p class="text-sm {{ $registrationEnabled ? 'text-green-600' : 'text-red-600' }}">
                                    @if($registrationEnabled)
                                        New members can register through the public registration form.
                                    @else
                                        New members cannot register. Only admins can create new member accounts.
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.settings.toggle-registration') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 {{ $registrationEnabled ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-semibold rounded-xl transition-all duration-300 shadow-lg {{ $registrationEnabled ? 'shadow-red-500/30 hover:shadow-red-500/50' : 'shadow-green-500/30 hover:shadow-green-500/50' }}">
                                <span class="material-icons-round mr-2">
                                    {{ $registrationEnabled ? 'lock' : 'lock_open' }}
                                </span>
                                {{ $registrationEnabled ? 'Disable Registration' : 'Enable Registration' }}
                            </button>
                        </form>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex">
                            <span class="material-icons-round text-blue-600 mr-3">info</span>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-1">When registration is disabled:</p>
                                <ul class="list-disc list-inside space-y-1 ml-2">
                                    <li>The "Become a Member" button will be hidden from the homepage</li>
                                    <li>The "Create an Account" link will be hidden from the login page</li>
                                    <li>Direct access to the registration form will redirect users to login</li>
                                    <li>Admins can still register new members through the admin panel</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Member Registration Quick Access -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-emerald-100 flex items-center justify-center mr-3">
                            <span class="material-icons-round text-emerald-600">admin_panel_settings</span>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Admin Member Registration</h3>
                            <p class="text-sm text-gray-500">Register new members directly as an administrator</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6">
                    <p class="text-gray-600 mb-4">
                        As an administrator, you can register new members directly without requiring them to go through the public registration process. 
                        This allows you to set all professional details and activate their membership immediately.
                    </p>
                    
                    <a href="{{ route('admin.members.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:-translate-y-0.5">
                        <span class="material-icons-round mr-2">person_add</span>
                        Register New Member
                    </a>
                </div>
            </div>

            <!-- Color Theme Settings -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-accent-50">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-primary-100 flex items-center justify-center mr-3">
                            <span class="material-icons-round text-primary-600">palette</span>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">System Color Theme</h3>
                            <p class="text-sm text-gray-500">Change the look and feel of the entire system (3-color rule: primary, accent, neutral)</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <form method="POST" action="{{ route('admin.settings.update-theme') }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($availableThemes as $key => $theme)
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="color_theme" value="{{ $key }}"
                                           class="sr-only peer"
                                           {{ $currentTheme === $key ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <div class="p-4 rounded-xl border-2 transition-all duration-300
                                                peer-checked:border-primary-500 peer-checked:ring-2 peer-checked:ring-primary-200 peer-checked:bg-primary-50/50
                                                border-gray-200 hover:border-gray-300 hover:shadow-md">
                                        {{-- Color preview circles --}}
                                        <div class="flex items-center mb-3">
                                            <div class="flex -space-x-1">
                                                <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $theme['colors']['primary-500'] }}"></div>
                                                <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $theme['colors']['primary-700'] }}"></div>
                                                <div class="w-8 h-8 rounded-full border-2 border-white shadow-sm" style="background-color: {{ $theme['colors']['accent-500'] }}"></div>
                                            </div>
                                            @if($currentTheme === $key)
                                                <span class="ml-auto material-icons-round text-primary-500 text-xl">check_circle</span>
                                            @endif
                                        </div>
                                        {{-- Theme name + icon --}}
                                        <div class="flex items-center">
                                            <span class="material-icons-round text-gray-400 text-lg mr-2">{{ $theme['icon'] }}</span>
                                            <span class="font-semibold text-gray-800 text-sm">{{ $theme['name'] }}</span>
                                        </div>
                                        {{-- Mini sidebar preview --}}
                                        <div class="mt-3 h-2 rounded-full overflow-hidden flex">
                                            <div class="flex-1" style="background-color: {{ $theme['sidebar'][0] }}"></div>
                                            <div class="flex-1" style="background-color: {{ $theme['sidebar'][1] }}"></div>
                                            <div class="flex-1" style="background-color: {{ $theme['sidebar'][2] }}"></div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex">
                            <span class="material-icons-round text-gray-500 mr-3">info</span>
                            <div class="text-sm text-gray-600">
                                <p class="font-semibold mb-1">About Color Themes:</p>
                                <ul class="list-disc list-inside space-y-1 ml-2">
                                    <li>Each theme uses a 3-color rule: <strong>Primary</strong>, <strong>Accent</strong>, and <strong>Neutral</strong></li>
                                    <li>The change applies immediately across the entire system for all users</li>
                                    <li>Status colors (success, warning, danger) remain consistent across themes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
