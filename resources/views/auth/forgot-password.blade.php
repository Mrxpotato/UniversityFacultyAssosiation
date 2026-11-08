<x-guest-layout title="Forgot Password">
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? Please contact your Campus President or System Administrator to have your password reset.') }}
    </div>

    <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 mb-4">
        <div class="flex items-start gap-3">
            <span class="material-icons-round text-blue-600 flex-shrink-0" style="font-size: 20px;">info</span>
            <div class="text-sm text-blue-700">
                <p class="font-medium mb-1">How to reset your password:</p>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Contact your Campus President or System Administrator</li>
                    <li>Provide your Employee ID for verification</li>
                    <li>Your password will be reset and you will be notified</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            {{ __('Back to Login') }}
        </a>
    </div>
</x-guest-layout>
