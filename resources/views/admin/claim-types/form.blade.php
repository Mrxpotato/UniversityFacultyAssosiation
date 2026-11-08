<x-app-layout title="Claim Type">
    @section('page-title', $claimType ? 'Edit Claim Type' : 'New Claim Type')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">{{ $claimType ? 'edit' : 'add_circle' }}</span>
                <div>
                    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $claimType ? 'Edit Claim Type' : 'New Claim Type' }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $claimType ? "Editing: {$claimType->name}" : 'Create a new claim type with deduction settings' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.claim-types.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <span class="material-icons-outlined mr-2 text-sm">arrow_back</span>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
                    <div class="flex items-center mb-2">
                        <span class="material-icons text-red-600 mr-3">error</span>
                        <p class="text-red-800 font-medium">Please fix the following errors:</p>
                    </div>
                    <ul class="list-disc list-inside text-red-700 text-sm ml-9">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ $claimType ? route('admin.claim-types.update', $claimType) : route('admin.claim-types.store') }}"
                  x-data="{
                      documents: {{ Js::from(old('required_documents', $claimType?->required_documents ?? [])) }},
                      newDoc: ''
                  }">
                @csrf
                @if($claimType)
                    @method('PUT')
                @endif

                <div class="bg-white rounded-xl material-shadow overflow-hidden">
                    {{-- Basic Information --}}
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                            <span class="material-icons mr-2 text-indigo-600">info</span>
                            Basic Information
                        </h3>
                    </div>

                    <div class="p-4 sm:p-6 space-y-6">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Claim Type Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $claimType?->name) }}"
                                   required
                                   placeholder="e.g. Medical Claim"
                                   class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Code --}}
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                                Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="code"
                                   name="code"
                                   value="{{ old('code', $claimType?->code) }}"
                                   required
                                   placeholder="e.g. MEDICAL"
                                   class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase"
                                   style="text-transform: uppercase;">
                            <p class="text-xs text-gray-400 mt-1">Unique identifier code. Use uppercase letters and underscores.</p>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description of this claim type..."
                                      class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $claimType?->description) }}</textarea>
                        </div>

                        {{-- Max Amount --}}
                        <div>
                            <label for="max_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Maximum Claimable Amount (₱) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₱</span>
                                <input type="number"
                                       id="max_amount"
                                       name="max_amount"
                                       value="{{ old('max_amount', $claimType?->max_amount) }}"
                                       required
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full pl-8 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Requires Beneficiary --}}
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-200">
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">Requires Beneficiary</p>
                                    <p class="text-xs text-gray-500">Claimant must select a beneficiary</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="requires_beneficiary" value="0">
                                    <input type="checkbox"
                                           name="requires_beneficiary"
                                           value="1"
                                           {{ old('requires_beneficiary', $claimType?->requires_beneficiary) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>

                            {{-- Is Active --}}
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-200">
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">Active</p>
                                    <p class="text-xs text-gray-500">Available for faculty to file claims</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $claimType?->is_active ?? true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Required Documents Section --}}
                    <div class="border-t border-gray-200">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                                <span class="material-icons mr-2 text-indigo-600">attach_file</span>
                                Required Documents
                            </h3>
                            <p class="text-sm text-gray-500 mt-0.5">Documents the claimant must submit</p>
                        </div>

                        <div class="p-6">
                            {{-- Document List --}}
                            <div class="space-y-2 mb-4">
                                <template x-for="(doc, index) in documents" :key="index">
                                    <div class="flex items-center gap-2">
                                        <input type="hidden" :name="'required_documents[' + index + ']'" :value="doc">
                                        <div class="flex-1 flex items-center p-3 rounded-lg bg-gray-50 border border-gray-200">
                                            <span class="material-icons text-gray-400 mr-2 text-sm">description</span>
                                            <span class="text-sm text-gray-700" x-text="doc"></span>
                                        </div>
                                        <button type="button"
                                                @click="documents.splice(index, 1)"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors flex-shrink-0">
                                            <span class="material-icons text-sm">close</span>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <div x-show="documents.length === 0" class="text-center py-4 text-gray-400 text-sm mb-4">
                                <span class="material-icons text-gray-300 mb-1" style="font-size: 32px;">folder_off</span>
                                <p>No required documents added yet</p>
                            </div>

                            {{-- Add Document Input --}}
                            <div class="flex items-center gap-2">
                                <input type="text"
                                       x-model="newDoc"
                                       @keydown.enter.prevent="if (newDoc.trim()) { documents.push(newDoc.trim()); newDoc = ''; }"
                                       placeholder="e.g. Medical Certificate (certified true copy)"
                                       class="flex-1 rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="button"
                                        @click="if (newDoc.trim()) { documents.push(newDoc.trim()); newDoc = ''; }"
                                        class="inline-flex items-center px-4 py-2.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors text-sm font-medium flex-shrink-0">
                                    <span class="material-icons text-sm mr-1">add</span>
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                        <a href="{{ route('admin.claim-types.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm font-medium">
                            <span class="material-icons mr-2 text-sm">save</span>
                            {{ $claimType ? 'Update Claim Type' : 'Create Claim Type' }}
                        </button>
                    </div>
                </div>
            </form>

            {{-- Info box for new types --}}
            @if(!$claimType)
                <div class="mt-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-5 border border-indigo-100">
                    <div class="flex items-start">
                        <span class="material-icons text-indigo-600 mr-3 mt-0.5">lightbulb</span>
                        <div class="text-sm text-indigo-700">
                            <p class="font-semibold mb-1">What happens when you create a claim type?</p>
                            <ul class="list-disc list-inside space-y-1 text-indigo-600">
                                <li>Default deduction settings (inactive, 0%) will be created for all academic ranks</li>
                                <li>You can configure deductions per rank from the <strong>Deduction Settings</strong> page</li>
                                <li>Faculty members will see this claim type when filing new claims</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
