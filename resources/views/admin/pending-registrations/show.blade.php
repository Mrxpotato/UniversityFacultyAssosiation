<x-app-layout title="Registration Review">
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.pending-registrations.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
                    <span class="material-icons-round text-gray-600">arrow_back</span>
                </a>
                <div>
                    <h1 class="font-display font-bold text-xl sm:text-2xl text-gray-900">
                        Registration Details
                    </h1>
                    <p class="text-gray-500 mt-1">Review application from {{ $registration->full_name }}</p>
                </div>
            </div>
            
            @if($registration->isPending())
                <div class="flex gap-3">
                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-4 py-2 bg-red-100 text-red-700 font-medium rounded-xl hover:bg-red-200 transition-colors flex items-center gap-2">
                        <span class="material-icons-round text-lg">close</span>
                        Reject
                    </button>
                    <form action="{{ route('admin.pending-registrations.approve', $registration) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2" onclick="return confirm('Are you sure you want to approve this registration?')">
                            <span class="material-icons-round text-lg">check</span>
                            Approve
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
                    <span class="material-icons-round text-emerald-600">check_circle</span>
                    <p class="text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3">
                    <span class="material-icons-round text-red-600">error</span>
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Employee ID Already Exists Warning -->
            @if(isset($employeeIdExists) && $employeeIdExists && $registration->isPending())
                <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                    <span class="material-icons-round text-amber-600 mt-0.5">warning</span>
                    <div>
                        <p class="font-medium text-amber-800">Employee ID Already in Use</p>
                        <p class="text-sm text-amber-700 mt-1">
                            The employee ID <strong class="font-mono">{{ $registration->employee_id_number }}</strong> is already assigned to an existing member. 
                            Approving this registration will fail. Please verify the employee ID or reject this registration.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Status Banner -->
            @if($registration->status !== 'pending')
                <div class="mb-6 p-4 rounded-xl {{ $registration->status === 'approved' ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-start gap-3">
                        <span class="material-icons-round {{ $registration->status === 'approved' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $registration->status === 'approved' ? 'check_circle' : 'cancel' }}
                        </span>
                        <div>
                            <p class="font-medium {{ $registration->status === 'approved' ? 'text-emerald-800' : 'text-red-800' }}">
                                This registration has been {{ $registration->status }}
                            </p>
                            @if($registration->reviewer)
                                <p class="text-sm {{ $registration->status === 'approved' ? 'text-emerald-600' : 'text-red-600' }} mt-1">
                                    By {{ $registration->reviewer->name }} on {{ $registration->reviewed_at->format('M d, Y h:i A') }}
                                </p>
                            @endif
                            @if($registration->rejection_reason)
                                <p class="text-sm text-red-600 mt-2">
                                    <strong>Reason:</strong> {{ $registration->rejection_reason }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Personal Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                <span class="material-icons-round text-primary-600">person</span>
                                Personal Information
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="mt-1 text-gray-900">{{ $registration->full_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Employee ID</dt>
                                    <dd class="mt-1 text-gray-900">{{ $registration->employee_id_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Campus</dt>
                                    <dd class="mt-1 text-gray-900">{{ $registration->campus->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Submitted On</dt>
                                    <dd class="mt-1 text-gray-900">{{ $registration->created_at->format('M d, Y h:i A') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Employee ID Cards -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                <span class="material-icons-round text-primary-600">badge</span>
                                Employee ID Verification
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Front ID -->
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-3">Front Side</p>
                                    @if($registration->employee_id_front)
                                        <a href="{{ Storage::url($registration->employee_id_front) }}" target="_blank" class="block group">
                                            <div class="relative overflow-hidden rounded-xl border-2 border-gray-200 hover:border-primary-300 transition-colors">
                                                <img src="{{ Storage::url($registration->employee_id_front) }}" alt="Employee ID Front" class="w-full h-36 sm:h-48 object-cover group-hover:scale-105 transition-transform">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                                    <span class="material-icons-round text-white opacity-0 group-hover:opacity-100 transition-opacity text-3xl">zoom_in</span>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="w-full h-36 sm:h-48 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                                            <span class="material-icons-round text-4xl mb-2">image_not_supported</span>
                                            <p class="text-sm">No image uploaded</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Back ID -->
                                <div>
                                    <p class="text-sm font-medium text-gray-500 mb-3">Back Side</p>
                                    @if($registration->employee_id_back)
                                        <a href="{{ Storage::url($registration->employee_id_back) }}" target="_blank" class="block group">
                                            <div class="relative overflow-hidden rounded-xl border-2 border-gray-200 hover:border-primary-300 transition-colors">
                                                <img src="{{ Storage::url($registration->employee_id_back) }}" alt="Employee ID Back" class="w-full h-36 sm:h-48 object-cover group-hover:scale-105 transition-transform">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                                    <span class="material-icons-round text-white opacity-0 group-hover:opacity-100 transition-opacity text-3xl">zoom_in</span>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="w-full h-36 sm:h-48 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                                            <span class="material-icons-round text-4xl mb-2">image_not_supported</span>
                                            <p class="text-sm">No image uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Profile Picture & Actions -->
                <div class="space-y-6">
                    <!-- Profile Picture -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                <span class="material-icons-round text-primary-600">account_circle</span>
                                Profile Picture
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            @if($registration->id_picture)
                                <a href="{{ Storage::url($registration->id_picture) }}" target="_blank" class="block group">
                                    <div class="relative overflow-hidden rounded-xl border-2 border-gray-200 hover:border-primary-300 transition-colors">
                                        <img src="{{ Storage::url($registration->id_picture) }}" alt="{{ $registration->full_name }}" class="w-full aspect-square object-cover group-hover:scale-105 transition-transform">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                            <span class="material-icons-round text-white opacity-0 group-hover:opacity-100 transition-opacity text-3xl">zoom_in</span>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="w-full aspect-square rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                                    <span class="material-icons-round text-6xl mb-2">person</span>
                                    <p class="text-sm">No picture uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if($registration->isPending())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                    <span class="material-icons-round text-primary-600">flash_on</span>
                                    Quick Actions
                                </h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <form action="{{ route('admin.pending-registrations.approve', $registration) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2" onclick="return confirm('Are you sure you want to approve this registration?')">
                                        <span class="material-icons-round">check_circle</span>
                                        Approve Registration
                                    </button>
                                </form>
                                
                                <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full px-4 py-3 bg-red-100 text-red-700 font-medium rounded-xl hover:bg-red-200 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-icons-round">cancel</span>
                                    Reject Registration
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.pending-registrations.reject', $registration) }}" method="POST">
                    @csrf
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                <span class="material-icons-round text-red-600">warning</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Reject Registration</h3>
                                <p class="text-sm text-gray-500">This action cannot be undone.</p>
                            </div>
                        </div>
                        
                        <div>
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="rejection_reason" 
                                id="rejection_reason" 
                                rows="4" 
                                required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none"
                                placeholder="Please provide a reason for rejecting this registration..."
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors">
                            Reject Registration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
