<x-app-layout title="Claim Review">
    @section('page-title', 'Claim Details - Admin')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-primary-600">admin_panel_settings</span>
                {{ __('Claim Details') }}
            </h1>
            <a href="{{ route('admin.claims-monitoring') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                <span class="material-icons mr-2 text-lg">arrow_back</span>
                Back to Claims Monitoring
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Status Banner -->
        <div class="rounded-xl material-shadow overflow-hidden
            @if($claim->status === 'pending') bg-amber-500
            @elseif($claim->status === 'under_review') bg-blue-500
            @elseif($claim->status === 'approved') bg-green-500
            @elseif($claim->status === 'billing') bg-indigo-500
            @elseif($claim->status === 'collecting') bg-cyan-600
            @elseif($claim->status === 'check_prepared') bg-violet-500
            @elseif($claim->status === 'released') bg-emerald-600
            @elseif($claim->status === 'remanded') bg-orange-500
            @else bg-red-500
            @endif">
            <div class="px-4 sm:px-6 py-4 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center">
                        <span class="material-icons text-2xl sm:text-3xl mr-2 sm:mr-3">
                            @if($claim->status === 'pending') schedule
                            @elseif($claim->status === 'under_review') rate_review
                            @elseif($claim->status === 'approved') thumb_up
                            @elseif($claim->status === 'billing') receipt_long
                            @elseif($claim->status === 'collecting') account_balance
                            @elseif($claim->status === 'check_prepared') description
                            @elseif($claim->status === 'released') check_circle
                            @elseif($claim->status === 'remanded') replay
                            @else cancel
                            @endif
                        </span>
                        <div>
                            <p class="font-semibold text-base sm:text-lg">Status: {{ \App\Models\Claim::STATUSES[$claim->status] ?? ucfirst($claim->status) }}</p>
                            <p class="text-white/80 text-sm">
                                @if($claim->status === 'pending')
                                    This claim is awaiting review
                                @elseif($claim->status === 'under_review')
                                    Verifying claim through membership data and evidence
                                @elseif($claim->status === 'approved')
                                    Claim approved — proceeding to billing
                                @elseif($claim->status === 'billing')
                                    Preparing the billing and collection list
                                @elseif($claim->status === 'collecting')
                                    Monitoring collection of funds via Campus Cashiers
                                @elseif($claim->status === 'check_prepared')
                                    Check has been prepared and is ready for release
                                @elseif($claim->status === 'released')
                                    Claim has been released to recipient
                                @elseif($claim->status === 'remanded')
                                    Claim remanded — awaiting revision from member
                                @else
                                    Claim has been rejected
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-2xl sm:text-3xl font-bold">₱{{ number_format($claim->amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Claim Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">info</span>
                        Claim Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Claim Number</span>
                        <span class="font-medium font-mono">{{ $claim->claim_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Claim Type</span>
                        <span class="font-medium">{{ $claim->claimType->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Amount</span>
                        <span class="font-medium">₱{{ number_format($claim->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date Filed</span>
                        <span class="font-medium">{{ $claim->created_at->format('F d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Beneficiary</span>
                        <span class="font-medium">{{ $claim->beneficiary->full_name ?? 'Self' }}</span>
                    </div>
                    @if($claim->approved_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Approved Date</span>
                            <span class="font-medium">{{ $claim->approved_at->format('F d, Y') }}</span>
                        </div>
                    @endif
                    @if($claim->released_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Released Date</span>
                            <span class="font-medium">{{ $claim->released_at->format('F d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Claimant Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">person</span>
                        Claimant Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Name</span>
                        <span class="font-medium">{{ $claim->facultyProfile->display_name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Employee ID</span>
                        <span class="font-medium">{{ $claim->facultyProfile->employee_id ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Campus</span>
                        <span class="font-medium">{{ $claim->facultyProfile->campus->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">College</span>
                        <span class="font-medium">{{ $claim->facultyProfile->college->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($claim->description)
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">description</span>
                        Description / Remarks
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $claim->description }}</p>
                </div>
            </div>
        @endif

        <!-- Supporting Documents -->
        @if($claim->supporting_documents && count($claim->supporting_documents))
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">attach_file</span>
                        Supporting Documents
                        <span class="ml-2 text-sm font-normal text-gray-500">({{ count($claim->supporting_documents) }} file{{ count($claim->supporting_documents) > 1 ? 's' : '' }})</span>
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($claim->supporting_documents as $index => $docPath)
                            @php
                                $filename = basename($docPath);
                                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="flex items-center min-w-0">
                                    <span class="material-icons text-gray-400 mr-3 flex-shrink-0">
                                        @if($isImage) image
                                        @elseif($extension === 'pdf') picture_as_pdf
                                        @else description
                                        @endif
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-700 truncate">{{ $filename }}</p>
                                        <p class="text-xs text-gray-400 uppercase">{{ $extension }}</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $docPath) }}" target="_blank" rel="noopener noreferrer"
                                   class="ml-3 inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors flex-shrink-0">
                                    <span class="material-icons text-sm mr-1">open_in_new</span>
                                    View
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Admin Remarks -->
        @if($claim->admin_remarks)
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">comment</span>
                        Administrator Remarks
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-line">{{ $claim->admin_remarks }}</p>
                </div>
            </div>
        @endif

        <!-- Admin Actions -->
        @if((Auth::user()->canManageClaims() || Auth::user()->canReleaseClaims()) && !in_array($claim->status, ['released', 'rejected']))
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-primary-600">admin_panel_settings</span>
                        Administrative Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="admin_remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea id="admin_remarks" name="admin_remarks" rows="3" 
                                  class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Add any remarks or notes..."></textarea>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        {{-- Start Review button for pending/remanded claims --}}
                        @if(in_array($claim->status, ['pending', 'remanded']))
                            @can('startReview', $claim)
                                <form method="POST" action="{{ route('admin.claims.start-review', $claim) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <span class="material-icons mr-2">rate_review</span>
                                        Start Review
                                    </button>
                                </form>
                            @endcan
                        @endif

                        {{-- Approve button for under_review claims --}}
                        @if($claim->status === 'under_review')
                            @can('approve', $claim)
                                <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="remarks" class="admin-remarks-input">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <span class="material-icons mr-2">thumb_up</span>
                                        Approve
                                    </button>
                                </form>
                            @endcan
                            @can('reject', $claim)
                                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <span class="material-icons mr-2">thumb_down</span>
                                    Reject
                                </button>
                            @endcan
                            @can('remand', $claim)
                                <button type="button" onclick="document.getElementById('remandModal').classList.remove('hidden')"
                                        class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                    <span class="material-icons mr-2">replay</span>
                                    Remand for Revision
                                </button>
                            @endcan
                        @endif

                        {{-- Prepare Billing button for approved claims (Step 4) --}}
                        @if($claim->status === 'approved')
                            @can('prepareBilling', $claim)
                                <form method="POST" action="{{ route('admin.claims.prepare-billing', $claim) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                        <span class="material-icons mr-2">receipt_long</span>
                                        Prepare Billing
                                    </button>
                                </form>
                            @endcan
                        @endif

                        {{-- Start Collecting button for billing claims (Step 5) --}}
                        @if($claim->status === 'billing')
                            @can('startCollecting', $claim)
                                <form method="POST" action="{{ route('admin.claims.start-collecting', $claim) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                                        <span class="material-icons mr-2">account_balance</span>
                                        Mark as Collecting
                                    </button>
                                </form>
                            @endcan
                        @endif

                        {{-- Prepare Check button for collecting claims (Step 6) --}}
                        @if($claim->status === 'collecting')
                            @can('prepareCheck', $claim)
                                <form method="POST" action="{{ route('admin.claims.prepare-check', $claim) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                                        <span class="material-icons mr-2">description</span>
                                        Prepare Check
                                    </button>
                                </form>
                            @endcan
                        @endif

                        {{-- Release button for check_prepared claims (Step 7) --}}
                        @if($claim->status === 'check_prepared')
                            @can('release', $claim)
                                <form method="POST" action="{{ route('admin.claims.release', $claim) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="remarks" class="admin-remarks-input">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                        <span class="material-icons mr-2">check_circle</span>
                                        Release to Recipient
                                    </button>
                                </form>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('admin_remarks').addEventListener('input', function() {
                    document.querySelectorAll('.admin-remarks-input').forEach(input => {
                        input.value = this.value;
                    });
                });
            </script>
        @endif

        <!-- Timeline -->
        <div class="bg-white rounded-xl material-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-primary-600">timeline</span>
                    Claim Timeline
                </h3>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    <div class="space-y-6">
                        <!-- Step 1: Filed -->
                        <div class="relative pl-10">
                            <div class="absolute left-0 w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center">
                                <span class="material-icons text-white text-sm">add_circle</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Step 1: Claim Filed</p>
                                <p class="text-xs text-gray-400">Member informs UFA and submits evidence</p>
                                <p class="text-sm text-gray-500">{{ $claim->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Step 2: Under Review -->
                        @if($claim->date_reviewed)
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">rate_review</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Step 2: Under Review</p>
                                    <p class="text-xs text-gray-400">UFA verifies claim through membership data</p>
                                    <p class="text-sm text-gray-500">{{ $claim->date_reviewed->format('F d, Y') }}</p>
                                    @if($claim->reviewedByUser)
                                        <p class="text-xs text-gray-400">By: {{ $claim->reviewedByUser->name }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: Approved / Notification -->
                        @if($claim->date_approved)
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-green-600 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">thumb_up</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Step 3: Claim Approved</p>
                                    <p class="text-xs text-gray-400">UFA notifies member of approval</p>
                                    <p class="text-sm text-gray-500">{{ $claim->date_approved->format('F d, Y') }}</p>
                                    @if($claim->approvedByUser)
                                        <p class="text-xs text-gray-400">By: {{ $claim->approvedByUser->name }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Step 4: Billing & Collection -->
                        @if($claim->date_billing)
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">receipt_long</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Step 4: Billing & Collection</p>
                                    <p class="text-xs text-gray-400">UFA prepares billing for collection purposes</p>
                                    <p class="text-sm text-gray-500">{{ $claim->date_billing->format('F d, Y') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Step 5: Collection Monitoring -->
                        @if($claim->date_collecting)
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-cyan-600 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">account_balance</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Step 5: Collection Monitoring</p>
                                    <p class="text-xs text-gray-400">Monitoring receipt via Campus Cashiers</p>
                                    <p class="text-sm text-gray-500">{{ $claim->date_collecting->format('F d, Y') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Step 6: Check Prepared -->
                        @if($claim->date_check_prepared)
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-violet-500 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">description</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Step 6: Check Prepared</p>
                                    <p class="text-xs text-gray-400">UFA prepares check and notifies recipient</p>
                                    <p class="text-sm text-gray-500">{{ $claim->date_check_prepared->format('F d, Y') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Step 7: Released -->
                        @if($claim->date_released)
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">check_circle</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Step 7: Claim Released</p>
                                    <p class="text-xs text-gray-400">Recipient receives the check</p>
                                    <p class="text-sm text-gray-500">{{ $claim->date_released->format('F d, Y') }}</p>
                                    @if($claim->releasedByUser)
                                        <p class="text-xs text-gray-400">By: {{ $claim->releasedByUser->name }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Rejected -->
                        @if($claim->status === 'rejected')
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-red-600 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">cancel</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Claim Rejected</p>
                                    <p class="text-sm text-gray-500">{{ $claim->updated_at->format('F d, Y h:i A') }}</p>
                                    @if($claim->rejection_reason)
                                        <p class="text-sm text-red-600 mt-1">Reason: {{ $claim->rejection_reason }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Remanded -->
                        @if($claim->status === 'remanded')
                            <div class="relative pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center">
                                    <span class="material-icons text-white text-sm">replay</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Remanded for Revision</p>
                                    <p class="text-sm text-gray-500">{{ $claim->updated_at->format('F d, Y h:i A') }}</p>
                                    @if($claim->remand_reason)
                                        <p class="text-sm text-orange-600 mt-1">Feedback: {{ $claim->remand_reason }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    @can('reject', $claim)
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Claim</h3>
                <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                                  class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Please provide a reason for rejecting this claim..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Reject Claim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    {{-- Remand Modal --}}
    @can('remand', $claim)
    <div id="remandModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Remand Claim for Revision</h3>
                <form method="POST" action="{{ route('admin.claims.remand', $claim) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="remand_reason" class="block text-sm font-medium text-gray-700 mb-1">Feedback/Reason <span class="text-red-500">*</span></label>
                        <textarea id="remand_reason" name="remand_reason" rows="4" required
                                  class="w-full rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500"
                                  placeholder="Please provide feedback on what needs to be corrected..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('remandModal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                            Remand Claim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
</x-app-layout>
