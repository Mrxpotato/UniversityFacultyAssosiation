<x-app-layout title="Claim Deduction Settings">
    @section('page-title', 'Claim Deduction Settings')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">percent</span>
                <div>
                    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Claim Deduction Settings') }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage salary grade–based deductions per claim type and academic rank</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success / Error Messages --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 flex items-center">
                    <span class="material-icons text-green-600 mr-3">check_circle</span>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif

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

            {{-- Info Card --}}
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4 sm:p-6 material-shadow mb-6 border border-indigo-100">
                <div class="flex items-start">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0 mr-3 sm:mr-4">
                        <span class="material-icons text-indigo-600">info</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-indigo-900 mb-1">Per Claim Type Deductions</h3>
                        <p class="text-sm text-indigo-700">
                            Configure the deduction applied to each claim based on the <strong>claim type</strong> and the faculty member's <strong>academic rank</strong>.
                            Select a claim type tab below, then set either a <strong>percentage</strong> of the claim amount or a <strong>fixed amount</strong> per rank.
                            Deductions are automatically computed when a claim is filed.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Claim Type Tabs --}}
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    @foreach($claimTypes as $claimType)
                        <a href="{{ route('admin.deductions.index', ['claim_type_id' => $claimType->id]) }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                                  {{ (int) $selectedClaimTypeId === $claimType->id
                                      ? 'bg-indigo-600 text-white material-shadow-sm'
                                      : 'bg-white text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 border border-gray-200' }}">
                            <span class="material-icons text-sm mr-1.5">
                                {{ (int) $selectedClaimTypeId === $claimType->id ? 'check_circle' : 'label' }}
                            </span>
                            {{ $claimType->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Deduction Settings Form for Selected Claim Type --}}
            @if($selectedClaimTypeId)
                <form method="POST" action="{{ route('admin.deductions.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="claim_type_id" value="{{ $selectedClaimTypeId }}">

                    <div class="bg-white rounded-xl material-shadow overflow-hidden">
                        {{-- Table Header --}}
                        <div class="px-3 sm:px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <span class="material-icons mr-2 text-indigo-600">tune</span>
                                Deduction per Academic Rank —
                                <span class="ml-2 text-indigo-600">{{ $claimTypes->firstWhere('id', (int) $selectedClaimTypeId)?->name }}</span>
                            </h3>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm font-medium">
                                <span class="material-icons mr-2 text-sm">save</span>
                                Save All Changes
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Rank</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Salary Grade</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Deduction Type</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $currentGroup = ''; @endphp
                                    @foreach($ranks as $index => $rank)
                                        @php
                                            if (str_starts_with($rank->name, 'Professor') && !str_starts_with($rank->name, 'University'))
                                                $group = 'Professor';
                                            elseif (str_starts_with($rank->name, 'Associate'))
                                                $group = 'Associate Professor';
                                            elseif (str_starts_with($rank->name, 'Assistant'))
                                                $group = 'Assistant Professor';
                                            elseif (str_starts_with($rank->name, 'Instructor'))
                                                $group = 'Instructor';
                                            else
                                                $group = 'Other';

                                            $setting = $settings->get($rank->id);
                                            $showGroupHeader = $group !== $currentGroup;
                                            $currentGroup = $group;

                                            $groupColors = [
                                                'Professor' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'Associate Professor' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Assistant Professor' => 'bg-teal-50 text-teal-700 border-teal-200',
                                                'Instructor' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Other' => 'bg-gray-50 text-gray-700 border-gray-200',
                                            ];
                                        @endphp

                                        @if($showGroupHeader)
                                            <tr>
                                                <td colspan="6" class="px-6 py-2 {{ $groupColors[$group] ?? 'bg-gray-50' }} border-l-4">
                                                    <span class="font-bold text-sm uppercase tracking-wide">{{ $group }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <input type="hidden" name="settings[{{ $index }}][academic_rank_id]" value="{{ $rank->id }}">
                                                <div class="flex items-center">
                                                    <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                                        <span class="text-indigo-700 font-bold text-xs">{{ $rank->level }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-800">{{ $rank->name }}</p>
                                                        <p class="text-xs text-gray-400">{{ $rank->code }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 text-center whitespace-nowrap">
                                                @if($rank->salary_grade)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                                        SG {{ $rank->salary_grade }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-sm">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3 text-center whitespace-nowrap">
                                                <select name="settings[{{ $index }}][deduction_type]"
                                                        aria-label="Deduction type for {{ $rank->name }}"
                                                        class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 w-32 text-center">
                                                    <option value="percentage" {{ ($setting?->deduction_type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>
                                                        Percentage (%)
                                                    </option>
                                                    <option value="fixed" {{ ($setting?->deduction_type ?? '') === 'fixed' ? 'selected' : '' }}>
                                                        Fixed Amount (₱)
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="px-6 py-3 text-center whitespace-nowrap">
                                                <input type="number"
                                                       name="settings[{{ $index }}][deduction_value]"
                                                       aria-label="Deduction value for {{ $rank->name }}"
                                                       value="{{ $setting?->deduction_value ?? '0.00' }}"
                                                       step="0.01"
                                                       min="0"
                                                       class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 w-28 text-center">
                                            </td>
                                            <td class="px-6 py-3 text-center whitespace-nowrap"
                                                x-data="{ enabled: {{ ($setting?->is_active ?? false) ? 'true' : 'false' }} }">
                                                <input type="hidden" name="settings[{{ $index }}][is_active]" :value="enabled ? '1' : '0'">
                                                <button type="button"
                                                        role="switch"
                                                        :aria-checked="enabled ? 'true' : 'false'"
                                                        aria-label="Enable deduction for {{ $rank->name }}"
                                                        @click="enabled = !enabled"
                                                        :class="enabled ? 'bg-indigo-600' : 'bg-gray-200'"
                                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                    <span :style="enabled ? 'transform: translateX(20px)' : 'transform: translateX(0px)'"
                                                          class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                                </button>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <input type="text"
                                                       name="settings[{{ $index }}][notes]"
                                                       aria-label="Notes for {{ $rank->name }}"
                                                       value="{{ $setting?->notes ?? '' }}"
                                                       placeholder="Optional note..."
                                                       class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 w-48">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Bottom Save Button --}}
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                            <p class="text-sm text-gray-500">
                                <span class="material-icons text-sm align-middle mr-1">schedule</span>
                                Changes take effect on new claims immediately.
                            </p>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm font-medium">
                                <span class="material-icons mr-2 text-sm">save</span>
                                Save All Changes
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="bg-white rounded-xl material-shadow p-12 text-center">
                    <span class="material-icons text-gray-300 text-6xl mb-4">category</span>
                    <p class="text-gray-500">No active claim types found. Please create claim types first.</p>
                </div>
            @endif

            {{-- Salary Grade Reference Table --}}
            <div class="mt-8 bg-white rounded-xl material-shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">menu_book</span>
                        Philippine Salary Grade Reference (2026)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Professors --}}
                        <div class="rounded-xl border border-purple-200 overflow-hidden">
                            <div class="bg-purple-50 px-4 py-2 border-b border-purple-200">
                                <h4 class="font-bold text-purple-800 text-sm">Professor</h4>
                            </div>
                            <div class="p-3 space-y-1.5 text-sm">
                                <div class="flex justify-between"><span class="text-gray-600">Professor VI</span><span class="font-semibold text-purple-700">SG 29</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Professor V</span><span class="font-semibold text-purple-700">SG 28</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Professor IV</span><span class="font-semibold text-purple-700">SG 27</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Professor III</span><span class="font-semibold text-purple-700">SG 26</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Professor II</span><span class="font-semibold text-purple-700">SG 25</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Professor I</span><span class="font-semibold text-purple-700">SG 24</span></div>
                            </div>
                        </div>

                        {{-- Associate Professors --}}
                        <div class="rounded-xl border border-blue-200 overflow-hidden">
                            <div class="bg-blue-50 px-4 py-2 border-b border-blue-200">
                                <h4 class="font-bold text-blue-800 text-sm">Associate Professor</h4>
                            </div>
                            <div class="p-3 space-y-1.5 text-sm">
                                <div class="flex justify-between"><span class="text-gray-600">Associate Prof V</span><span class="font-semibold text-blue-700">SG 23</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Associate Prof IV</span><span class="font-semibold text-blue-700">SG 22</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Associate Prof III</span><span class="font-semibold text-blue-700">SG 21</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Associate Prof II</span><span class="font-semibold text-blue-700">SG 20</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Associate Prof I</span><span class="font-semibold text-blue-700">SG 19</span></div>
                            </div>
                        </div>

                        {{-- Assistant Professors --}}
                        <div class="rounded-xl border border-teal-200 overflow-hidden">
                            <div class="bg-teal-50 px-4 py-2 border-b border-teal-200">
                                <h4 class="font-bold text-teal-800 text-sm">Assistant Professor</h4>
                            </div>
                            <div class="p-3 space-y-1.5 text-sm">
                                <div class="flex justify-between"><span class="text-gray-600">Asst Prof IV</span><span class="font-semibold text-teal-700">SG 18</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Asst Prof III</span><span class="font-semibold text-teal-700">SG 17</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Asst Prof II</span><span class="font-semibold text-teal-700">SG 16</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Asst Prof I</span><span class="font-semibold text-teal-700">SG 15</span></div>
                            </div>
                        </div>

                        {{-- Instructors --}}
                        <div class="rounded-xl border border-amber-200 overflow-hidden">
                            <div class="bg-amber-50 px-4 py-2 border-b border-amber-200">
                                <h4 class="font-bold text-amber-800 text-sm">Instructor</h4>
                            </div>
                            <div class="p-3 space-y-1.5 text-sm">
                                <div class="flex justify-between"><span class="text-gray-600">Instructor III</span><span class="font-semibold text-amber-700">SG 14</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Instructor II</span><span class="font-semibold text-amber-700">SG 13</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Instructor I</span><span class="font-semibold text-amber-700">SG 12</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
