<x-guest-layout title="Confirm Logout">
    <div class="text-center">
        <span class="material-icons text-4xl sm:text-6xl text-gray-400 mb-4">logout</span>
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Log Out</h2>
        <p class="text-gray-600 mb-6">Are you sure you want to log out?</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                    <span class="material-icons mr-1 text-sm">logout</span>
                    Yes, Log Out
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
