<x-app-layout title="Claim Types">
    @section('page-title', 'Claim Types Management')

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center">
                <span class="material-icons-outlined text-indigo-600 mr-3">category</span>
                <div>
                    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Claim Types Management') }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage claim types and their requirements</p>
                </div>
            </div>
            <a href="{{ route('admin.claim-types.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors material-shadow-sm text-sm">
                <span class="material-icons-outlined mr-2 text-sm">add</span>
                New Claim Type
            </a>
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

            @if(session('error'))
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 flex items-center">
                    <span class="material-icons text-red-600 mr-3">error</span>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div class="bg-white rounded-xl p-4 sm:p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Claim Types</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-indigo-600">category</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Active Types</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-green-600">check_circle</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 material-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">With Deduction Settings</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['with_deductions'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <span class="material-icons-outlined text-purple-600">percent</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Claim Types Table --}}
            <div class="bg-white rounded-xl material-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Max Amount</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Beneficiary</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Claims</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Required Docs</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($claimTypes as $type)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                                                <span class="material-icons-outlined text-indigo-600">description</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $type->name }}</p>
                                                @if($type->description)
                                                    <p class="text-xs text-gray-400 max-w-xs truncate">{{ $type->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $type->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="font-semibold text-gray-800">₱{{ number_format($type->max_amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($type->requires_beneficiary)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <span class="material-icons text-xs mr-0.5">person</span>
                                                Required
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="font-medium text-gray-800">{{ $type->claims_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php $docs = $type->required_documents ?? []; @endphp
                                        @if(count($docs) > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                {{ count($docs) }} doc{{ count($docs) > 1 ? 's' : '' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($type->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-icons text-xs mr-1">check_circle</span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="material-icons text-xs mr-1">pause_circle</span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.deductions.index', ['claim_type_id' => $type->id]) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors text-sm"
                                               title="Deduction Settings">
                                                <span class="material-icons-outlined text-sm mr-1">percent</span>
                                                Deductions
                                            </a>
                                            <a href="{{ route('admin.claim-types.edit', $type) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm"
                                               title="Edit">
                                                <span class="material-icons-outlined text-sm mr-1">edit</span>
                                                Edit
                                            </a>
                                            @if($type->claims_count === 0)
                                                <form action="{{ route('admin.claim-types.destroy', $type) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this claim type? This will also remove its deduction settings.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm"
                                                            title="Delete">
                                                        <span class="material-icons-outlined text-sm mr-1">delete</span>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="material-icons-outlined text-gray-300 mb-2" style="font-size: 48px;">category</span>
                                            <p class="text-gray-500">No claim types found</p>
                                            <a href="{{ route('admin.claim-types.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                                                Add your first claim type
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
