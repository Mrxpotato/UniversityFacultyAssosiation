<x-app-layout title="Complete Your Profile">
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Your Profile') }}
        </h1>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-5 sm:p-8 text-center">
                    <div class="mb-4 sm:mb-6">
                        <span class="material-icons text-amber-500 text-5xl sm:text-7xl">account_circle</span>
                    </div>
                    
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Welcome to the Faculty Association!</h3>
                    
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        To access all features and benefits, please complete your faculty profile. This information helps us serve you better.
                    </p>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 sm:p-4 mb-6 sm:mb-8">
                        <div class="flex items-center justify-center text-amber-700">
                            <span class="material-icons mr-2">info</span>
                            <span>Your profile is required to:</span>
                        </div>
                        <ul class="mt-3 text-sm text-amber-600 space-y-1">
                            <li>• Submit and track benefit claims</li>
                            <li>• View and manage your dues</li>
                            <li>• Register for trainings and seminars</li>
                            <li>• Add beneficiaries for benefits</li>
                        </ul>
                    </div>

                    <a href="{{ route('my-profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        <span class="material-icons mr-2">edit</span>
                        Complete Your Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
