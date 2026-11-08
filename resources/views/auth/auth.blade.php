<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="{{ $themeColors['primary-900'] ?? '#1e3a8a' }}">

        <title>{{ $isLogin ? 'Sign In' : 'Request Account' }} - {{ config('app.name', 'UFA') }}</title>
        <meta name="description" content="{{ $isLogin ? 'Sign in to the CSU-UFA Faculty Association management system.' : 'Request an account for the CSU-UFA Faculty Association management system.' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet">

        <!-- Cloudflare Turnstile -->
        @if(config('services.cloudflare.turnstile_site_key'))
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif

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
            }
        </style>

        <style>
            /* Sliding animations */
            .slide-enter-from-right {
                animation: slideInFromRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .slide-enter-from-left {
                animation: slideInFromLeft 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .slide-exit-to-left {
                animation: slideOutToLeft 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .slide-exit-to-right {
                animation: slideOutToRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes slideInFromRight {
                from { opacity: 0; transform: translateX(100px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes slideInFromLeft {
                from { opacity: 0; transform: translateX(-100px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes slideOutToLeft {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(-100px); }
            }
            @keyframes slideOutToRight {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(100px); }
            }

            /* Floating animation */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            .animate-float-delay {
                animation: float 6s ease-in-out infinite;
                animation-delay: -3s;
            }

            /* Background gradient animation */
            .bg-animated {
                background: linear-gradient(-45deg, var(--color-primary-950), var(--color-primary-800), var(--color-primary-900), var(--color-primary-600), var(--color-primary-800));
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
            }
            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased" x-data="authPage()" x-init="init()">
        <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-animated">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Floating Orbs -->
                <div class="absolute top-20 left-20 w-72 h-72 bg-white/5 rounded-full filter blur-3xl animate-float"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-white/5 rounded-full filter blur-3xl animate-float-delay"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary-500/10 rounded-full filter blur-3xl"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 50px 50px;"></div>
                </div>
            </div>

            <!-- Main Auth Container -->
            <div class="relative z-10 w-full max-w-md mx-4">
                <!-- Back to Home Link -->
                <a href="/" class="inline-flex items-center gap-2 text-white/70 hover:text-white mb-6 transition-colors group">
                    <span class="material-icons-round text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    <span class="text-sm font-medium">Back to Home</span>
                </a>

                <!-- Auth Card -->
                <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/20 overflow-hidden border border-white/20">
                    <!-- Logo Header -->
                    <div class="text-center pt-8 pb-4 px-8">
                        <a href="/" class="inline-flex flex-col items-center group">
                            <div class="w-16 h-16 group-hover:scale-110 transition-transform mb-3">
                                <img src="{{ asset('images/csulogo.png') }}" alt="CSU Logo" class="w-full h-full object-contain drop-shadow-lg">
                            </div>
                            <div class="font-display font-bold text-xl text-gray-900">CSU-UFA</div>
                            <div class="text-xs text-gray-500">Cagayan State University Faculty Association</div>
                        </a>
                    </div>

                    <!-- Form Container with Sliding Animation -->
                    <div class="relative px-8 pb-8 min-h-[400px] overflow-hidden">
                        <!-- Login Form -->
                        <div x-show="currentView === 'login'"
                             x-transition:enter="slide-enter-from-left"
                             x-transition:leave="slide-exit-to-left"
                             class="w-full">
                            
                            <div class="text-center mb-6">
                                <h1 class="text-2xl font-display font-bold text-gray-900 mb-1">Welcome Back</h1>
                                <p class="text-gray-500 text-sm">Sign in to access your account</p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <!-- Success Message (from registration) -->
                            @if (session('success'))
                                <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-3">
                                    <span class="material-icons-round text-emerald-600 flex-shrink-0">check_circle</span>
                                    <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                @csrf
                                
                                <!-- Employee ID -->
                                <div>
                                    <label for="login-employee_id" class="text-sm font-medium text-gray-700 mb-2 block">Employee ID</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="material-icons-outlined text-gray-400 text-xl">badge</span>
                                        </div>
                                        <input type="text" id="login-employee_id" name="employee_id" value="{{ old('employee_id') }}" required autofocus autocomplete="username"
                                            class="block w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"
                                            placeholder="Enter your Employee ID" />
                                    </div>
                                    @error('employee_id')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div x-data="{ show: false }">
                                    <label for="login-password" class="text-sm font-medium text-gray-700 mb-2 block">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="material-icons-outlined text-gray-400 text-xl">lock</span>
                                        </div>
                                        <input :type="show ? 'text' : 'password'" id="login-password" name="password" required autocomplete="current-password"
                                            class="block w-full pl-12 pr-12 py-3.5 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"
                                            placeholder="Enter your password" />
                                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                            <span class="material-icons-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-800">Remember me</span>
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="inline-block py-1 text-sm font-medium text-primary-600 hover:text-primary-700">
                                            Forgot password?
                                        </a>
                                    @endif
                                </div>

                                <!-- Cloudflare Turnstile (Login) -->
                                @if(config('services.cloudflare.turnstile_site_key'))
                                <div class="flex justify-center">
                                    <div class="cf-turnstile" data-sitekey="{{ config('services.cloudflare.turnstile_site_key') }}" data-theme="light"></div>
                                </div>
                                @error('cf-turnstile-response')
                                    <p class="text-sm text-red-500 text-center">{{ $message }}</p>
                                @enderror
                                @endif

                                <!-- Submit -->
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all duration-300">
                                    <span class="material-icons-round text-xl">login</span>
                                    Sign In
                                </button>
                            </form>

                            <!-- Switch to Register -->
                            @if (Route::has('register') && \App\Models\SystemSetting::isRegistrationEnabled())
                            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                                <p class="text-gray-500 text-sm mb-3">Don't have an account?</p>
                                <button type="button" @click="switchTo('register')" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:text-primary-700 transition-colors">
                                    <span class="material-icons-round text-lg">person_add</span>
                                    Request an Account
                                    <span class="material-icons-round text-lg">arrow_forward</span>
                                </button>
                            </div>
                            @endif
                        </div>

                        <!-- Register Form -->
                        <div x-show="currentView === 'register'"
                             x-transition:enter="slide-enter-from-right"
                             x-transition:leave="slide-exit-to-right"
                             class="w-full">
                            
                            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="registrationForm()" x-init="initAddress()">                                
                                @csrf

                                <div class="text-center mb-6">
                                    <h2 class="text-2xl font-display font-bold text-gray-900 mb-1">Request Account</h2>
                                    <p class="text-gray-500 text-sm">Join the Faculty Association</p>
                                </div>

                                <!-- Progress Steps - Now 4 steps -->
                                <div class="flex items-center justify-center gap-1 mb-6">
                                    <template x-for="i in 4" :key="i">
                                        <div class="flex items-center">
                                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-semibold text-xs transition-all duration-300"
                                                :class="step >= i ? 'bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-gray-100 text-gray-400'"
                                                x-text="i"></div>
                                            <div x-show="i < 4" class="w-6 h-1 mx-0.5 rounded-full transition-all duration-300" :class="step > i ? 'bg-primary-500' : 'bg-gray-200'"></div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Step 1: Personal Info -->
                                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                                    <div class="flex items-center gap-2 p-3 rounded-xl bg-primary-50 border border-primary-100 mb-4">
                                        <span class="material-icons-round text-primary-600">person</span>
                                        <span class="text-sm font-medium text-gray-700">Personal Information</span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label for="reg-prefix" class="text-xs font-medium text-gray-700 mb-1 block">Title</label>
                                            <select id="reg-prefix" name="prefix" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                                <option value="">None</option>
                                                <option value="Dr." {{ old('prefix') == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                                <option value="Prof." {{ old('prefix') == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                                                <option value="Engr." {{ old('prefix') == 'Engr.' ? 'selected' : '' }}>Engr.</option>
                                                <option value="Mr." {{ old('prefix') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                                <option value="Ms." {{ old('prefix') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                                <option value="Mrs." {{ old('prefix') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="reg-surname" class="text-xs font-medium text-gray-700 mb-1 block">Surname *</label>
                                            <input type="text" id="reg-surname" name="surname" x-model="fields.surname" value="{{ old('surname') }}" required placeholder="Doe" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                        <div>
                                            <label for="reg-first_name" class="text-xs font-medium text-gray-700 mb-1 block">First Name *</label>
                                            <input type="text" id="reg-first_name" name="first_name" x-model="fields.first_name" value="{{ old('first_name') }}" required placeholder="John" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label for="reg-middle_name" class="text-xs font-medium text-gray-700 mb-1 block">Middle Name</label>
                                            <input type="text" id="reg-middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                        <div>
                                            <label for="reg-extension_name" class="text-xs font-medium text-gray-700 mb-1 block">Ext. Name</label>
                                            <input type="text" id="reg-extension_name" name="extension_name" value="{{ old('extension_name') }}" placeholder="Jr., Sr." class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label for="reg-civil_status" class="text-xs font-medium text-gray-700 mb-1 block">Civil Status *</label>
                                            <select id="reg-civil_status" name="civil_status" x-model="fields.civil_status" required class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                                <option value="">Select</option>
                                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                                <option value="separated" {{ old('civil_status') == 'separated' ? 'selected' : '' }}>Separated</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="reg-gender" class="text-xs font-medium text-gray-700 mb-1 block">Gender *</label>
                                            <select id="reg-gender" name="gender" x-model="fields.gender" required class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                                <option value="">Select</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label for="reg-birth_date" class="text-xs font-medium text-gray-700 mb-1 block">Birth Date *</label>
                                            <input type="date" id="reg-birth_date" name="birth_date" x-model="fields.birth_date" value="{{ old('birth_date') }}" required class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                        <div>
                                            <label for="reg-birth_place" class="text-xs font-medium text-gray-700 mb-1 block">Birth Place *</label>
                                            <input type="text" id="reg-birth_place" name="birth_place" x-model="fields.birth_place" value="{{ old('birth_place') }}" required placeholder="City, Province" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Campus & Employee Info -->
                                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                                    <div class="flex items-center gap-2 p-3 rounded-xl bg-primary-50 border border-primary-100 mb-4">
                                        <span class="material-icons-round text-primary-600">business</span>
                                        <span class="text-sm font-medium text-gray-700">Campus & Employment</span>
                                    </div>

                                    <div>
                                        <label for="reg-campus_id" class="text-xs font-medium text-gray-700 mb-1 block">Select Your Campus *</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-icons-outlined text-gray-400 text-lg">location_city</span>
                                            </div>
                                            <select id="reg-campus_id" name="campus_id" x-model="fields.campus_id" required class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                                <option value="">-- Select Campus --</option>
                                                @isset($campuses)
                                                @foreach($campuses as $campus)
                                                    <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                                @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Your registration will be sent to your Campus President for approval</p>
                                        @error('campus_id')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-xs font-medium text-gray-700 mb-1 block">Employee ID (Front) *</label>
                                            <div class="relative" x-data="{ fileName: 'Upload front side' }">
                                                <input id="employee_id_front" type="file" name="employee_id_front" accept="image/*" class="hidden" required @change="fileName = $event.target.files[0]?.name || 'Upload front side'; fields.hasIdFront = $event.target.files.length > 0" />
                                                <label for="employee_id_front" class="flex items-center justify-center gap-2 w-full px-3 py-3 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50/50 transition-all duration-300">
                                                    <span class="material-icons-round text-primary-500 text-xl">credit_card</span>
                                                    <div class="text-left flex-1 min-w-0">
                                                        <p class="text-xs font-medium text-gray-700 truncate" x-text="fileName"></p>
                                                    </div>
                                                </label>
                                            </div>
                                            @error('employee_id_front')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-700 mb-1 block">Employee ID (Back) *</label>
                                            <div class="relative" x-data="{ fileName: 'Upload back side' }">
                                                <input id="employee_id_back" type="file" name="employee_id_back" accept="image/*" class="hidden" required @change="fileName = $event.target.files[0]?.name || 'Upload back side'; fields.hasIdBack = $event.target.files.length > 0" />
                                                <label for="employee_id_back" class="flex items-center justify-center gap-2 w-full px-3 py-3 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50/50 transition-all duration-300">
                                                    <span class="material-icons-round text-primary-500 text-xl">credit_card</span>
                                                    <div class="text-left flex-1 min-w-0">
                                                        <p class="text-xs font-medium text-gray-700 truncate" x-text="fileName"></p>
                                                    </div>
                                                </label>
                                            </div>
                                            @error('employee_id_back')
                                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200">
                                        <div class="flex gap-2">
                                            <span class="material-icons-round text-amber-600 text-lg flex-shrink-0">info</span>
                                            <p class="text-xs text-amber-800">Please upload clear photos of your employee ID for verification purposes. This helps the Campus President validate your employment.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Contact Info & Photo -->
                                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                                    <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-50 border border-emerald-100 mb-4">
                                        <span class="material-icons-round text-emerald-600">contact_mail</span>
                                        <span class="text-sm font-medium text-gray-700">Contact & Photo</span>
                                    </div>

                                    {{-- Philippine Address Cascading Dropdowns --}}
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label for="reg-address_province" class="text-xs font-medium text-gray-700 mb-1 block">Province *</label>
                                            <select id="reg-address_province" name="address_province" x-model="fields.address_province" @change="onProvinceChange()" required class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                                <option value="">-- Select Province --</option>
                                                <template x-for="p in addressData.provinces" :key="p.id">
                                                    <option :value="p.name" x-text="p.name"></option>
                                                </template>
                                            </select>
                                            @error('address_province') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="reg-address_city" class="text-xs font-medium text-gray-700 mb-1 block">City/Municipality *</label>
                                            <select id="reg-address_city" name="address_city" x-model="fields.address_city" @change="onCityChange()" required :disabled="addressData.cities.length === 0" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                                <option value="">-- Select City --</option>
                                                <template x-for="c in addressData.cities" :key="c.id">
                                                    <option :value="c.name" x-text="c.name"></option>
                                                </template>
                                            </select>
                                            @error('address_city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label for="reg-address_barangay" class="text-xs font-medium text-gray-700 mb-1 block">Barangay *</label>
                                            <template x-if="addressData.barangays.length > 0">
                                                <select id="reg-address_barangay" name="address_barangay" x-model="fields.address_barangay" required class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                                    <option value="">-- Select Barangay --</option>
                                                    <template x-for="b in addressData.barangays" :key="b.id">
                                                        <option :value="b.name" x-text="b.name"></option>
                                                    </template>
                                                </select>
                                            </template>
                                            <template x-if="addressData.barangays.length === 0">
                                                <input type="text" id="reg-address_barangay" name="address_barangay" x-model="fields.address_barangay" required placeholder="Enter barangay" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                            </template>
                                            @error('address_barangay') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="reg-address_street" class="text-xs font-medium text-gray-700 mb-1 block">Street / House No.</label>
                                            <input type="text" id="reg-address_street" name="address_street" x-model="fields.address_street" value="{{ old('address_street') }}" placeholder="House no., Street, Purok" class="block w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                            @error('address_street') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    {{-- Hidden combined home_address --}}
                                    <input type="hidden" name="home_address" :value="computedAddress">

                                    <div>
                                        <label for="reg-phone_number" class="text-xs font-medium text-gray-700 mb-1 block">Phone Number *</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-icons-outlined text-gray-400 text-lg">phone</span>
                                            </div>
                                            <input type="text" id="reg-phone_number" name="phone_number" x-model="fields.phone_number" value="{{ old('phone_number') }}" required placeholder="09xxxxxxxxx" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-700 mb-1 block">Profile Photo (2x2 ID Picture) *</label>
                                        <div class="relative" x-data="{ fileName: 'Upload your 2x2 ID photo' }">
                                            <input id="id_picture" type="file" name="id_picture" accept="image/*" class="hidden" required @change="fileName = $event.target.files[0]?.name || 'Upload your 2x2 ID photo'; fields.hasIdPicture = $event.target.files.length > 0" />
                                            <label for="id_picture" class="flex items-center justify-center gap-3 w-full px-4 py-4 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-emerald-400 hover:bg-emerald-50/50 transition-all duration-300">
                                                <span class="material-icons-round text-emerald-500 text-2xl">account_circle</span>
                                                <div class="text-left">
                                                    <p class="text-xs font-medium text-gray-700" x-text="fileName"></p>
                                                    <p class="text-xs text-gray-400">PNG, JPG up to 2MB</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 4: Account Credentials -->
                                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-4">
                                    <div class="flex items-center gap-2 p-3 rounded-xl bg-purple-50 border border-purple-100 mb-4">
                                        <span class="material-icons-round text-purple-600">security</span>
                                        <span class="text-sm font-medium text-gray-700">Account Credentials</span>
                                    </div>

                                    <div>
                                        <label for="reg-employee_id" class="text-xs font-medium text-gray-700 mb-1 block">Employee ID *</label>
                                        <p class="text-xs text-gray-400 mb-1">This will be your login credential</p>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-icons-outlined text-gray-400 text-lg">badge</span>
                                            </div>
                                            <input type="text" id="reg-employee_id" name="employee_id" value="{{ old('employee_id') }}" required autocomplete="username" placeholder="e.g., EMP-2024-0001" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                        </div>
                                        @error('employee_id')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="reg-password" class="text-xs font-medium text-gray-700 mb-1 block">Password *</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-icons-outlined text-gray-400 text-lg">lock</span>
                                            </div>
                                            <input :type="showPass ? 'text' : 'password'" id="reg-password" name="password" required autocomplete="new-password" placeholder="Create password" class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <span class="material-icons-outlined text-lg" x-text="showPass ? 'visibility_off' : 'visibility'"></span>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="reg-password_confirmation" class="text-xs font-medium text-gray-700 mb-1 block">Confirm Password *</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-icons-outlined text-gray-400 text-lg">lock</span>
                                            </div>
                                            <input :type="showConfirm ? 'text' : 'password'" id="reg-password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password" class="block w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" />
                                            <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <span class="material-icons-outlined text-lg" x-text="showConfirm ? 'visibility_off' : 'visibility'"></span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                        <label class="flex items-start gap-2 cursor-pointer">
                                            <input type="checkbox" required class="mt-0.5 w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                            <span class="text-xs text-gray-600">I agree to the <a href="#" class="text-primary-600 hover:underline font-medium">Terms of Service</a> and <a href="#" class="text-primary-600 hover:underline font-medium">Privacy Policy</a></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Cloudflare Turnstile (Register) -->
                                <div x-show="step === 4" class="mt-4 flex justify-center">
                                    @if(config('services.cloudflare.turnstile_site_key'))
                                    <div>
                                        <div class="cf-turnstile" data-sitekey="{{ config('services.cloudflare.turnstile_site_key') }}" data-theme="light"></div>
                                        @error('cf-turnstile-response')
                                            <p class="text-sm text-red-500 text-center mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @endif
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                                    <button type="button" x-show="step > 1" @click="step--" class="inline-flex items-center gap-1 px-4 py-2 text-gray-600 font-medium rounded-xl hover:bg-gray-100 transition-all text-sm">
                                        <span class="material-icons-round text-lg">arrow_back</span>
                                        Back
                                    </button>
                                    <div x-show="step === 1"></div>
                                    
                                    <div class="flex items-center gap-2">
                                        <button type="button" x-show="step < 4" @click="validateStep(step) && step++" :disabled="!isStepValid(step)" :class="isStepValid(step) ? 'bg-gradient-to-r from-primary-600 to-primary-700 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:-translate-y-0.5' : 'bg-gray-300 cursor-not-allowed'" class="inline-flex items-center gap-1 px-5 py-2.5 text-white font-semibold rounded-xl transition-all text-sm">
                                            Continue
                                            <span class="material-icons-round text-lg">arrow_forward</span>
                                        </button>
                                        
                                        <button type="submit" x-show="step === 4" class="inline-flex items-center gap-1 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all text-sm">
                                            <span class="material-icons-round text-lg">how_to_reg</span>
                                            Submit Application
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Switch to Login -->
                            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                                <p class="text-gray-500 text-sm mb-2">Already have an account?</p>
                                <button type="button" @click="switchTo('login')" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:text-primary-700 transition-colors">
                                    <span class="material-icons-round text-lg">arrow_back</span>
                                    Back to Sign In
                                    <span class="material-icons-round text-lg">login</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6 text-sm text-white/60">
                    © {{ date('Y') }} University Faculty Association
                </div>
            </div>
        </div>

        <script>
            function authPage() {
                return {
                    currentView: '{{ $isLogin ? "login" : "register" }}',
                    transitioning: false,
                    
                    init() {
                        // Check URL for initial view
                        if (window.location.pathname.includes('register')) {
                            this.currentView = 'register';
                        }
                    },
                    
                    switchTo(view) {
                        if (this.transitioning) return;
                        this.transitioning = true;
                        
                        // Update URL without reload
                        const newUrl = view === 'login' ? '{{ route("login") }}' : '{{ route("register") }}';
                        window.history.pushState({}, '', newUrl);
                        
                        // Switch view
                        this.currentView = view;
                        
                        setTimeout(() => {
                            this.transitioning = false;
                        }, 500);
                    }
                }
            }

            function registrationForm() {
                return {
                    step: {{ $errors->hasAny(['employee_id', 'password', 'password_confirmation', 'cf-turnstile-response']) ? 4 : ($errors->hasAny(['address_province', 'address_city', 'address_barangay', 'home_address', 'phone_number', 'id_picture']) ? 3 : ($errors->hasAny(['campus_id', 'employee_id_front', 'employee_id_back', 'employee_id_number']) ? 2 : 1)) }},
                    showPass: false,
                    showConfirm: false,
                    addressData: { provinces: [], cities: [], barangays: [] },
                    fields: {
                        surname: '{{ old("surname", "") }}',
                        first_name: '{{ old("first_name", "") }}',
                        civil_status: '{{ old("civil_status", "") }}',
                        gender: '{{ old("gender", "") }}',
                        birth_date: '{{ old("birth_date", "") }}',
                        birth_place: '{{ old("birth_place", "") }}',
                        campus_id: '{{ old("campus_id", "") }}',
                        hasIdFront: false,
                        hasIdBack: false,
                        address_province: '{{ old("address_province", "") }}',
                        address_city: '{{ old("address_city", "") }}',
                        address_barangay: '{{ old("address_barangay", "") }}',
                        address_street: '{{ old("address_street", "") }}',
                        phone_number: '{{ old("phone_number", "") }}',
                        hasIdPicture: false,
                    },

                    get computedAddress() {
                        let parts = [];
                        if (this.fields.address_street.trim()) parts.push(this.fields.address_street.trim());
                        if (this.fields.address_barangay) parts.push(this.fields.address_barangay);
                        if (this.fields.address_city) parts.push(this.fields.address_city);
                        if (this.fields.address_province) parts.push(this.fields.address_province);
                        return parts.join(', ');
                    },

                    async initAddress() {
                        try {
                            const res = await fetch('/api/ph-address/provinces');
                            this.addressData.provinces = await res.json();
                        } catch(e) {}
                        
                        if (this.fields.address_province) {
                            const p = this.addressData.provinces.find(x => x.name === this.fields.address_province);
                            if (p) {
                                const res = await fetch(`/api/ph-address/provinces/${p.id}/cities`);
                                this.addressData.cities = await res.json();
                                if (this.fields.address_city) {
                                    const c = this.addressData.cities.find(x => x.name === this.fields.address_city);
                                    if (c) {
                                        const res2 = await fetch(`/api/ph-address/cities/${c.id}/barangays`);
                                        this.addressData.barangays = await res2.json();
                                    }
                                }
                            }
                        }
                    },

                    async onProvinceChange() {
                        this.fields.address_city = '';
                        this.fields.address_barangay = '';
                        this.addressData.cities = [];
                        this.addressData.barangays = [];
                        const p = this.addressData.provinces.find(x => x.name === this.fields.address_province);
                        if (p) {
                            const res = await fetch(`/api/ph-address/provinces/${p.id}/cities`);
                            this.addressData.cities = await res.json();
                        }
                    },

                    async onCityChange() {
                        this.fields.address_barangay = '';
                        this.addressData.barangays = [];
                        const c = this.addressData.cities.find(x => x.name === this.fields.address_city);
                        if (c) {
                            const res = await fetch(`/api/ph-address/cities/${c.id}/barangays`);
                            this.addressData.barangays = await res.json();
                        }
                    },

                    isStepValid(step) {
                        if (step === 1) {
                            return this.fields.surname.trim() !== '' &&
                                   this.fields.first_name.trim() !== '' &&
                                   this.fields.civil_status !== '' &&
                                   this.fields.gender !== '' &&
                                   this.fields.birth_date !== '' &&
                                   this.fields.birth_place.trim() !== '';
                        }
                        if (step === 2) {
                            return this.fields.campus_id !== '' &&
                                   this.fields.hasIdFront &&
                                   this.fields.hasIdBack;
                        }
                        if (step === 3) {
                            return this.fields.address_province !== '' &&
                                   this.fields.address_city !== '' &&
                                   this.fields.address_barangay.trim() !== '' &&
                                   this.fields.phone_number.trim() !== '' &&
                                   this.fields.hasIdPicture;
                        }
                        return true;
                    },

                    validateStep(step) {
                        return this.isStepValid(step);
                    }
                }
            }
        </script>
    </body>
</html>
