<x-app-layout title="Salary Deductions">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">assessment</span>
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Salary Deduction Monitoring') }}
                </h1>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(!$academicRank || !$salaryGrade)
                {{-- No Academic Rank Alert --}}
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg material-shadow-sm">
                    <div class="flex items-center">
                        <span class="material-icons text-yellow-600 mr-3">warning</span>
                        <div>
                            <p class="font-medium text-yellow-800">Incomplete Profile</p>
                            <p class="text-sm text-yellow-700">
                                Your academic rank or salary grade is not yet assigned. Please update your profile to see your salary breakdown.
                            </p>
                        </div>
                        <a href="{{ route('my-profile.edit') }}" class="ml-auto px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors whitespace-nowrap">
                            Update Profile
                        </a>
                    </div>
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 mb-6">
                {{-- Gross Monthly Salary --}}
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Gross Monthly Salary</p>
                            <p class="text-lg sm:text-2xl font-bold text-gray-800 truncate">₱{{ number_format($monthlySalary, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-1">SG {{ $salaryGrade ?? 'N/A' }} &middot; {{ config('salary_grades.label') }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-indigo-600 text-lg sm:text-2xl">paid</span>
                        </div>
                    </div>
                </div>

                {{-- Total Deductions --}}
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Total Deductions</p>
                            <p class="text-lg sm:text-2xl font-bold text-red-600 truncate">₱{{ number_format($totalDeductions, 2) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $deductions->count() }} item{{ $deductions->count() !== 1 ? 's' : '' }}</p>
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-red-600 text-lg sm:text-2xl">remove_circle_outline</span>
                        </div>
                    </div>
                </div>

                {{-- Net Take-Home --}}
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Net Take-Home</p>
                            <p class="text-lg sm:text-2xl font-bold {{ $netTakeHome >= 0 ? 'text-green-600' : 'text-red-600' }} truncate">
                                ₱{{ number_format(abs($netTakeHome), 2) }}
                            </p>
                            @if($netTakeHome < 0)
                                <p class="text-xs text-red-500 mt-1">Deductions exceed salary</p>
                            @else
                                <p class="text-xs text-gray-400 mt-1">After all deductions</p>
                            @endif
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full {{ $netTakeHome >= 0 ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined {{ $netTakeHome >= 0 ? 'text-green-600' : 'text-red-600' }} text-lg sm:text-2xl">account_balance</span>
                        </div>
                    </div>
                </div>

                {{-- Academic Rank --}}
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-xs sm:text-sm text-gray-500">Academic Rank</p>
                            <p class="text-sm sm:text-lg font-bold text-gray-800 truncate">{{ $academicRank->name ?? 'Not Assigned' }}</p>
                            @if($salaryGrade)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mt-1">
                                    SG {{ $salaryGrade }}
                                </span>
                            @endif
                        </div>
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-outlined text-purple-600 text-lg sm:text-2xl">school</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Salary Breakdown Bar --}}
            @if($monthlySalary > 0)
            <div class="bg-white rounded-xl material-shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-icons-outlined text-gray-400 mr-2">pie_chart</span>
                    Salary Breakdown
                </h3>

                @php
                    $deductionPercent = $monthlySalary > 0 ? min(($totalDeductions / $monthlySalary) * 100, 100) : 0;
                    $netPercent = 100 - $deductionPercent;
                @endphp

                <div class="w-full bg-gray-100 rounded-full h-8 overflow-hidden flex">
                    @if($netPercent > 0)
                    <div class="bg-gradient-to-r from-green-400 to-green-500 h-8 flex items-center justify-center text-white text-xs font-semibold transition-all duration-500"
                         style="width: {{ $netPercent }}%">
                        @if($netPercent > 15)
                            Net: {{ number_format($netPercent, 1) }}%
                        @endif
                    </div>
                    @endif
                    @if($deductionPercent > 0)
                    <div class="bg-gradient-to-r from-red-400 to-red-500 h-8 flex items-center justify-center text-white text-xs font-semibold transition-all duration-500"
                         style="width: {{ $deductionPercent }}%">
                        @if($deductionPercent > 15)
                            Deductions: {{ number_format($deductionPercent, 1) }}%
                        @endif
                    </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row justify-between mt-3 text-sm gap-2">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2 flex-shrink-0"></span>
                        <span class="text-gray-600 text-xs sm:text-sm">Net Take-Home: <strong class="text-green-600">₱{{ number_format(max($netTakeHome, 0), 2) }}</strong> ({{ number_format($netPercent, 1) }}%)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-500 mr-2 flex-shrink-0"></span>
                        <span class="text-gray-600 text-xs sm:text-sm">Total Deductions: <strong class="text-red-600">₱{{ number_format($totalDeductions, 2) }}</strong> ({{ number_format($deductionPercent, 1) }}%)</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Itemized Deductions Table --}}
            <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons-outlined text-gray-400 mr-2">format_list_bulleted</span>
                        Itemized Deductions
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">All amounts being deducted from your gross monthly salary</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deduction</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Description</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Category</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Computation</th>
                                <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Deduction Settings --}}
                            @foreach($deductions->where('category', 'setting') as $deduction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-{{ $deduction['color'] }}-100 flex items-center justify-center mr-2 sm:mr-3">
                                                <span class="material-icons-outlined text-{{ $deduction['color'] }}-600 text-xs sm:text-sm">{{ $deduction['icon'] }}</span>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="font-medium text-gray-800 text-sm">{{ $deduction['label'] }}</span>
                                                @if(!empty($deduction['notes']))
                                                    <p class="text-xs text-gray-400">{{ $deduction['notes'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-500 hidden md:table-cell">{{ $deduction['description'] }}</td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                            <span class="material-icons text-xs mr-1">tune</span> Rank-based
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $deduction['type'] === 'Percentage' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">{{ $deduction['type'] }}</span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                        <span class="font-semibold text-red-600">- ₱{{ number_format($deduction['amount'], 2) }}</span>
                                    </td>
                                </tr>
                            @endforeach

                            @if($deductions->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined text-gray-300 mb-2" style="font-size: 48px;">check_circle</span>
                                            <p class="text-gray-500">No deductions found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        @if($deductions->isNotEmpty())
                        <tfoot>
                            <tr class="bg-gray-50 border-t-2 border-gray-200">
                                <td colspan="4" class="px-3 sm:px-6 py-4 text-right font-semibold text-gray-700 uppercase text-xs sm:text-sm">Gross Monthly Salary</td>
                                <td class="px-3 sm:px-6 py-4 text-right">
                                    <span class="text-base sm:text-lg font-bold text-gray-800">₱{{ number_format($monthlySalary, 2) }}</span>
                                </td>
                            </tr>
                            <tr class="bg-red-50">
                                <td colspan="4" class="px-3 sm:px-6 py-4 text-right font-semibold text-gray-700 uppercase text-xs sm:text-sm">Total Deductions</td>
                                <td class="px-3 sm:px-6 py-4 text-right">
                                    <span class="text-base sm:text-lg font-bold text-red-600">- ₱{{ number_format($totalDeductions, 2) }}</span>
                                </td>
                            </tr>
                            <tr class="bg-green-50">
                                <td colspan="4" class="px-3 sm:px-6 py-4 text-right font-semibold text-gray-700 uppercase text-xs sm:text-sm">
                                    <div class="flex items-center justify-end">
                                        <span class="material-icons-outlined text-green-600 mr-2 text-sm">account_balance</span>
                                        Net Take-Home Pay
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 text-right">
                                    <span class="text-base sm:text-lg font-bold {{ $netTakeHome >= 0 ? 'text-green-600' : 'text-red-600' }}">₱{{ number_format(abs($netTakeHome), 2) }}</span>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Deduction Settings for Your Rank --}}
            @if($deductionSettings->isNotEmpty())
            <div class="bg-white rounded-xl material-shadow overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="material-icons-outlined text-gray-400 mr-2">tune</span>
                        Deduction Settings for {{ $academicRank->name ?? 'Your Rank' }}
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">SG {{ $salaryGrade }}</span>
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">These are the configured deduction rates applied to your salary based on your academic rank</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                    @foreach($deductionSettings as $setting)
                    <div class="border border-gray-200 rounded-xl p-4 hover:border-teal-300 hover:shadow-sm transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center mr-3">
                                    <span class="material-icons-outlined text-teal-600">{{ $setting->claimType ? 'description' : 'settings' }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $setting->claimType?->name ?? 'General / Default' }}</p>
                                    <p class="text-xs text-gray-400">{{ $setting->deduction_type === 'percentage' ? 'Percentage-based' : 'Fixed amount' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Rate / Value</span>
                                <span class="font-semibold text-gray-800">
                                    @if($setting->deduction_type === 'percentage')
                                        {{ number_format($setting->deduction_value, 2) }}%
                                    @else
                                        ₱{{ number_format($setting->deduction_value, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Computed Amount</span>
                                <span class="font-semibold text-red-600">
                                    - ₱{{ number_format(
                                        $setting->deduction_type === 'percentage'
                                            ? round($monthlySalary * ($setting->deduction_value / 100), 2)
                                            : (float) $setting->deduction_value,
                                        2
                                    ) }}
                                </span>
                            </div>
                            @if($setting->notes)
                            <div class="pt-2 border-t border-gray-100">
                                <p class="text-xs text-gray-400">{{ $setting->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- PSGR Salary Grade Reference --}}
            <div class="bg-white rounded-xl material-shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-icons-outlined text-gray-400 mr-2">table_chart</span>
                    PSGR Salary Grade Reference
                    <span class="ml-2 text-xs font-normal text-gray-400">({{ config('salary_grades.label') }})</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Instructor --}}
                    <div class="border border-amber-200 rounded-lg overflow-hidden">
                        <div class="bg-amber-50 px-4 py-2 border-b border-amber-200">
                            <h4 class="font-semibold text-amber-800 text-sm">Instructor</h4>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach([12 => 'Instructor I', 13 => 'Instructor II', 14 => 'Instructor III'] as $sg => $rank)
                            <div class="px-4 py-2 flex justify-between items-center text-sm {{ $salaryGrade == $sg ? 'bg-amber-50 font-semibold' : '' }}">
                                <span class="text-gray-700">SG {{ $sg }}</span>
                                <span class="text-gray-900">₱{{ number_format(config("salary_grades.amounts.{$sg}"), 0) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Assistant Professor --}}
                    <div class="border border-teal-200 rounded-lg overflow-hidden">
                        <div class="bg-teal-50 px-4 py-2 border-b border-teal-200">
                            <h4 class="font-semibold text-teal-800 text-sm">Assistant Professor</h4>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach([15 => 'Asst Prof I', 16 => 'Asst Prof II', 17 => 'Asst Prof III', 18 => 'Asst Prof IV'] as $sg => $rank)
                            <div class="px-4 py-2 flex justify-between items-center text-sm {{ $salaryGrade == $sg ? 'bg-teal-50 font-semibold' : '' }}">
                                <span class="text-gray-700">SG {{ $sg }}</span>
                                <span class="text-gray-900">₱{{ number_format(config("salary_grades.amounts.{$sg}"), 0) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Associate Professor --}}
                    <div class="border border-blue-200 rounded-lg overflow-hidden">
                        <div class="bg-blue-50 px-4 py-2 border-b border-blue-200">
                            <h4 class="font-semibold text-blue-800 text-sm">Associate Professor</h4>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach([19 => 'Assoc Prof I', 20 => 'Assoc Prof II', 21 => 'Assoc Prof III', 22 => 'Assoc Prof IV', 23 => 'Assoc Prof V'] as $sg => $rank)
                            <div class="px-4 py-2 flex justify-between items-center text-sm {{ $salaryGrade == $sg ? 'bg-blue-50 font-semibold' : '' }}">
                                <span class="text-gray-700">SG {{ $sg }}</span>
                                <span class="text-gray-900">₱{{ number_format(config("salary_grades.amounts.{$sg}"), 0) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Professor --}}
                    <div class="border border-purple-200 rounded-lg overflow-hidden">
                        <div class="bg-purple-50 px-4 py-2 border-b border-purple-200">
                            <h4 class="font-semibold text-purple-800 text-sm">Professor</h4>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach([24 => 'Professor I', 25 => 'Professor II', 26 => 'Professor III', 27 => 'Professor IV', 28 => 'Professor V', 29 => 'Professor VI', 30 => 'University Prof'] as $sg => $rank)
                            <div class="px-4 py-2 flex justify-between items-center text-sm {{ $salaryGrade == $sg ? 'bg-purple-50 font-semibold' : '' }}">
                                <span class="text-gray-700">SG {{ $sg }}</span>
                                <span class="text-gray-900">₱{{ number_format(config("salary_grades.amounts.{$sg}"), 0) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Information Box --}}
            <div class="bg-indigo-50 rounded-xl p-6 material-shadow-sm">
                <h4 class="font-semibold text-indigo-800 mb-3 flex items-center">
                    <span class="material-icons-outlined mr-2">info</span>
                    About Salary Deductions
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-indigo-700">
                    <div class="flex items-start">
                        <span class="material-icons text-indigo-500 mr-2 mt-0.5">looks_one</span>
                        <p><strong>Salary Deductions</strong> are automatically computed based on the deduction settings configured for your academic rank and are deducted from your salary before payout.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="material-icons text-indigo-500 mr-2 mt-0.5">looks_two</span>
                        <p><strong>Collected deductions</strong> fund the association's benefit claims. When you file a claim, the payout comes from the collected salary deductions pool.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="material-icons text-indigo-500 mr-2 mt-0.5">looks_3</span>
                        <p><strong>Salary amounts</strong> are based on the {{ config('salary_grades.label') }} rates per the Philippine Salary Standardization Law.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
