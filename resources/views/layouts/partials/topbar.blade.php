<header
    class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm border-b border-sky-100 h-16 flex items-center">
    <div class="max-w-6xl mx-auto w-full px-4 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
            <span class="text-lg font-semibold text-sky-600">ResvBAR</span>
        </div>

        <!-- User / Logout -->
        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-600 hidden sm:inline">
                Hello, {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="px-4 py-2 rounded-full text-sm font-medium text-white bg-gradient-to-br from-rose-500 to-pink-500 shadow hover:opacity-95 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
