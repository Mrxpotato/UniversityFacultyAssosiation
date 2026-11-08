<x-app-layout title="File a Claim">
    @section('page-title', 'New Claim')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h1 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight flex items-center">
                <span class="material-icons mr-2 text-indigo-600">add_circle</span>
                {{ __('Submit New Claim') }}
            </h1>
            <a href="{{ route('claims.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                <span class="material-icons mr-2 text-lg">arrow_back</span>
                Back to Claims
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- How to Claim Process Flow -->
        <div class="bg-white rounded-xl material-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50 cursor-pointer" onclick="document.getElementById('processFlow').classList.toggle('hidden'); this.querySelector('.expand-icon').textContent = document.getElementById('processFlow').classList.contains('hidden') ? 'expand_more' : 'expand_less';">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center justify-between">
                    <span class="flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">info</span>
                        How to Claim — Process Flow
                    </span>
                    <span class="material-icons expand-icon text-gray-400">expand_less</span>
                </h3>
            </div>
            <div id="processFlow" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 sm:gap-x-6 md:gap-x-8 gap-y-1">
                    <!-- Step 1 -->
                    <div class="flex items-start space-x-3 py-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">1</div>
                        <div>
                            <p class="font-semibold text-gray-800">Member Informs UFA</p>
                            <p class="text-sm text-gray-500">Member informs UFA for possible claim and submits evidence / supporting documents.</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex items-start space-x-3 py-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">2</div>
                        <div>
                            <p class="font-semibold text-gray-800">UFA Verifies Claim</p>
                            <p class="text-sm text-gray-500">UFA verifies claim request through membership data and submitted evidence.</p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="flex items-start space-x-3 py-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">3</div>
                        <div>
                            <p class="font-semibold text-gray-800">Notification to Member</p>
                            <p class="text-sm text-gray-500">UFA notifies member for additional evidence as needed or for approval.</p>
                        </div>
                    </div>
                    <!-- Step 4 -->
                    <div class="flex items-start space-x-3 py-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">4</div>
                        <div>
                            <p class="font-semibold text-gray-800">Billing & Collection</p>
                            <p class="text-sm text-gray-500">UFA prepares list of claims &amp; billing for collection purposes.</p>
                        </div>
                    </div>
                    <!-- Step 5 -->
                    <div class="flex items-start space-x-3 py-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">5</div>
                        <div>
                            <p class="font-semibold text-gray-800">Collection Monitoring</p>
                            <p class="text-sm text-gray-500">UFA monitors receipt of collected claims via Campus Cashiers.</p>
                        </div>
                    </div>
                    <!-- Step 6 -->
                    <div class="flex items-start space-x-3 py-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">6</div>
                        <div>
                            <p class="font-semibold text-gray-800">Check Preparation</p>
                            <p class="text-sm text-gray-500">UFA prepares check for the recipient and notifies the recipient.</p>
                        </div>
                    </div>
                    <!-- Step 7 -->
                    <div class="flex items-start space-x-3 py-3 md:col-span-2">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-bold">7</div>
                        <div>
                            <p class="font-semibold text-gray-800">Recipient Receives Check</p>
                            <p class="text-sm text-gray-500">The recipient receives the check. Claim is complete.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Required Documents Reference -->
        <div class="bg-white rounded-xl material-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-yellow-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-amber-600">folder_open</span>
                    Required Documents per Claim Type
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($claimTypes ?? [] as $type)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                            <div class="flex items-center mb-2">
                                <span class="material-icons text-indigo-600 mr-2">
                                    @if(str_contains(strtolower($type->name), 'death'))
                                        sentiment_very_dissatisfied
                                    @elseif(str_contains(strtolower($type->name), 'medical'))
                                        local_hospital
                                    @elseif(str_contains(strtolower($type->name), 'nuptial'))
                                        favorite
                                    @elseif(str_contains(strtolower($type->name), 'retirement'))
                                        elderly
                                    @else
                                        receipt_long
                                    @endif
                                </span>
                                <h4 class="font-semibold text-gray-800">{{ $type->name }}</h4>
                            </div>
                            @if($type->required_documents && count($type->required_documents))
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($type->required_documents as $doc)
                                        <li class="flex items-start">
                                            <span class="material-icons text-green-500 text-sm mr-1 mt-0.5">check_circle</span>
                                            {{ $doc }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-400 italic">No specific documents required</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('claims.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Claim Information -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">receipt_long</span>
                        Claim Information
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Faculty Profile Selection --}}
                    @if(auth()->user()->isOfficer())
                        <div>
                            <label for="faculty_profile_id" class="block text-sm font-medium text-gray-700 mb-1">Faculty Member *</label>
                            <select name="faculty_profile_id" id="faculty_profile_id" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('faculty_profile_id') border-red-500 @enderror">
                                <option value="">Select Faculty Member</option>
                                @foreach($facultyProfiles as $profile)
                                    <option value="{{ $profile->id }}" {{ old('faculty_profile_id', $selectedProfileId) == $profile->id ? 'selected' : '' }}>
                                        {{ $profile->user->name ?? $profile->full_name }} — {{ $profile->campus->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_profile_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="faculty_profile_id" value="{{ $selectedProfileId }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Claimant</label>
                            <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                    <span class="material-icons text-primary-600">person</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $facultyProfiles->first()?->campus->name ?? '' }} · {{ $facultyProfiles->first()?->college->name ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="claim_type_id" class="block text-sm font-medium text-gray-700 mb-1">Claim Type *</label>
                        <select name="claim_type_id" id="claim_type_id" required
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('claim_type_id') border-red-500 @enderror">
                            <option value="">Select Claim Type</option>
                            @foreach($claimTypes ?? [] as $type)
                                <option value="{{ $type->id }}" {{ old('claim_type_id') == $type->id ? 'selected' : '' }}
                                        data-max-amount="{{ $type->max_amount ?? 0 }}"
                                        data-requires-beneficiary="{{ $type->requires_beneficiary ? '1' : '0' }}"
                                        data-documents='@json($type->required_documents ?? [])'>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('claim_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Dynamic max amount & required documents hint -->
                        <div id="claimTypeInfo" class="mt-2 space-y-2">
                            <div id="maxAmountHint" class="hidden">
                                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 flex items-center gap-2">
                                    <span class="material-icons text-emerald-600 text-lg">payments</span>
                                    <div>
                                        <span class="text-sm font-medium text-emerald-800">Maximum Claimable Amount:</span>
                                        <span id="maxAmountDisplay" class="text-sm font-bold text-emerald-900 ml-1"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="requiredDocsHint" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <p class="text-sm font-medium text-blue-800 flex items-center">
                                        <span class="material-icons text-blue-600 mr-1 text-sm">info</span>
                                        Required Documents:
                                    </p>
                                    <ul id="requiredDocsList" class="mt-1 text-sm text-blue-700 list-disc list-inside"></ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="beneficiary_id" class="block text-sm font-medium text-gray-700 mb-1">Beneficiary</label>
                        <select name="beneficiary_id" id="beneficiary_id"
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('beneficiary_id') border-red-500 @enderror">
                            <option value="">Self ({{ auth()->user()->name }})</option>
                            @foreach($beneficiaries ?? [] as $beneficiary)
                                <option value="{{ $beneficiary->id }}" {{ old('beneficiary_id') == $beneficiary->id ? 'selected' : '' }}>
                                    {{ $beneficiary->full_name }} ({{ $beneficiary->relationship_label }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Select a beneficiary, or leave as "Self" if the claim is for yourself</p>
                        @error('beneficiary_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₱) *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">₱</span>
                            <input type="number" step="0.01" min="0" name="amount" id="amount" 
                                   value="{{ old('amount') }}" required
                                   class="w-full pl-8 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror">
                        </div>
                        <p id="amountMaxNote" class="mt-1 text-sm text-gray-500 hidden">
                            <span class="material-icons text-xs align-middle mr-0.5">info</span>
                            Maximum allowed: <span id="amountMaxLabel" class="font-semibold"></span>
                        </p>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_applied" class="block text-sm font-medium text-gray-700 mb-1">Date Applied *</label>
                        <input type="date" name="date_applied" id="date_applied"
                               value="{{ old('date_applied', now()->format('Y-m-d')) }}" required
                               class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('date_applied') border-red-500 @enderror">
                        @error('date_applied')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description/Remarks</label>
                        <textarea name="description" id="description" rows="4"
                                  placeholder="Provide additional details about your claim..."
                                  class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Supporting Documents -->
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">attach_file</span>
                        Supporting Documents
                    </h3>
                </div>
                <div class="p-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition-colors">
                        <span class="material-icons text-4xl text-gray-400">cloud_upload</span>
                        <p class="mt-2 text-sm text-gray-600">Drag and drop files here, or click to browse</p>
                        <input type="file" name="supporting_documents[]" multiple 
                               class="mt-4 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <p class="mt-2 text-xs text-gray-500">PDF, JPG, PNG, DOC up to 5MB each</p>
                    </div>
                    @error('supporting_documents')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('supporting_documents.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                <div class="flex">
                    <span class="material-icons text-amber-500 mr-3">info</span>
                    <div>
                        <h4 class="font-medium text-amber-800">Important Notes</h4>
                        <ul class="mt-2 text-sm text-amber-700 list-disc list-inside space-y-1">
                            <li>Ensure all information provided is accurate and complete</li>
                            <li>Submit the <strong>required supporting documents</strong> for your claim type</li>
                            <li>UFA will verify your claim through membership data and submitted evidence</li>
                            <li>You will be notified if additional evidence is needed or when your claim is approved</li>
                            <li>Approved claims are collected via Campus Cashiers and released by check</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('claims.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <span class="material-icons mr-2">cancel</span>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm">
                    <span class="material-icons mr-2">send</span>
                    Submit Claim
                </button>
            </div>
        </form>
    </div>

    <script>
        const claimTypeSelect = document.getElementById('claim_type_id');
        const facultyProfileSelect = document.getElementById('faculty_profile_id');
        const beneficiarySelect = document.getElementById('beneficiary_id');
        const requiredDocsHint = document.getElementById('requiredDocsHint');
        const requiredDocsList = document.getElementById('requiredDocsList');
        const maxAmountHint = document.getElementById('maxAmountHint');
        const maxAmountDisplay = document.getElementById('maxAmountDisplay');
        const amountInput = document.getElementById('amount');
        const amountMaxNote = document.getElementById('amountMaxNote');
        const amountMaxLabel = document.getElementById('amountMaxLabel');

        let currentMaxAmount = null;

        function formatCurrency(value) {
            return '₱' + parseFloat(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        claimTypeSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];

            if (!this.value) {
                maxAmountHint.classList.add('hidden');
                requiredDocsHint.classList.add('hidden');
                amountMaxNote.classList.add('hidden');
                amountInput.removeAttribute('max');
                currentMaxAmount = null;
                return;
            }

            // Handle max amount
            const maxAmount = parseFloat(selected.dataset.maxAmount) || 0;
            if (maxAmount > 0) {
                currentMaxAmount = maxAmount;
                maxAmountDisplay.textContent = formatCurrency(maxAmount);
                maxAmountHint.classList.remove('hidden');
                amountInput.setAttribute('max', maxAmount);
                amountMaxLabel.textContent = formatCurrency(maxAmount);
                amountMaxNote.classList.remove('hidden');

                // Auto-fill amount to max if empty
                if (!amountInput.value) {
                    amountInput.value = maxAmount;
                }
            } else {
                currentMaxAmount = null;
                maxAmountHint.classList.add('hidden');
                amountInput.removeAttribute('max');
                amountMaxNote.classList.add('hidden');
            }

            // Show required documents hint
            try {
                const docs = JSON.parse(selected.dataset.documents || '[]');
                if (docs.length > 0) {
                    requiredDocsList.innerHTML = '';
                    docs.forEach(function(doc) {
                        const li = document.createElement('li');
                        li.textContent = doc;
                        requiredDocsList.appendChild(li);
                    });
                    requiredDocsHint.classList.remove('hidden');
                } else {
                    requiredDocsHint.classList.add('hidden');
                }
            } catch(e) {
                requiredDocsHint.classList.add('hidden');
            }
        });

        // Enforce max amount on input
        amountInput.addEventListener('input', function() {
            if (currentMaxAmount !== null && parseFloat(this.value) > currentMaxAmount) {
                this.value = currentMaxAmount;
            }
        });

        amountInput.addEventListener('change', function() {
            if (currentMaxAmount !== null && parseFloat(this.value) > currentMaxAmount) {
                this.value = currentMaxAmount;
            }
        });

        // When faculty profile changes (for officers), reload beneficiaries
        if (facultyProfileSelect) {
            facultyProfileSelect.addEventListener('change', function() {
                if (this.value) {
                    fetch('{{ route("claims.beneficiaries", ":profileId") }}'.replace(':profileId', this.value))
                        .then(response => response.json())
                        .then(data => {
                            beneficiarySelect.innerHTML = '<option value="">Self (Faculty Member)</option>';
                            data.forEach(beneficiary => {
                                const option = document.createElement('option');
                                option.value = beneficiary.id;
                                option.textContent = beneficiary.full_name + ' (' + beneficiary.relationship.charAt(0).toUpperCase() + beneficiary.relationship.slice(1) + ')';
                                beneficiarySelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading beneficiaries:', error));
                } else {
                    beneficiarySelect.innerHTML = '<option value="">Self (Faculty Member)</option>';
                }
            });
        }

        // Trigger on page load if claim type is pre-selected
        if (claimTypeSelect.value) {
            claimTypeSelect.dispatchEvent(new Event('change'));
        }
    </script>
</x-app-layout>
