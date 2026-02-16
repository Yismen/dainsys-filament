<nav class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200/40 dark:border-slate-700/40 shadow-sm dark:shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                {{-- <a href="/" class="flex items-center space-x-3 group">
                    <img src="{{ asset('images/ecco-logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto transition-transform duration-200 group-hover:scale-105">
                </a> --}}
                <div class="w-10 h-10 rounded-xl bg-linear-to-br from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 flex items-center justify-center shadow-lg px-8">
                    <span class="text-white font-bold text-sm">ECCO</span>
                </div>
                <a href="/" class="text-2xl font-bold bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-400 dark:to-blue-500 bg-clip-text text-transparent hover:opacity-80 transition-opacity">{{ config('app.name') }}</a>
            </div>
            <div class="flex items-center space-x-1 sm:space-x-2">
                @auth
                    <a href="{{ $authUrl ?? '#' }}" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">Dashboard</a>
                @else
                    @if ($showBackLink ?? false)
                        <a href="/" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">{{ $backLinkText ?? 'Back to Home' }}</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">Login</a>
                    @endif
                @endauth

                <button
                    data-theme-toggle
                    class="p-2 rounded-lg ml-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    aria-label="Toggle theme"
                    type="button"
                    style="cursor: pointer;"
                >
                    <!-- Sun Icon (shown in light mode) -->
                    <svg class="w-5 h-5 theme-icon-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: block;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1m-16 0H1m15.364 1.636l-.707.707M6.343 6.343l-.707-.707m12.728 0l-.707.707m-12.021 12.021l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <!-- Moon Icon (shown in dark mode) -->
                    <svg class="w-5 h-5 theme-icon-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
