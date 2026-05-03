@props([
    'navType' => 'sticky'
])
<nav class="{{ $navType }} top-0 z-50 border-b backdrop-blur-xl" style="background:var(--nav-bg);border-color:var(--nav-border)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="/" class="text-xl font-black tracking-tight text-gradient-violet hover:opacity-80 transition-opacity" style="font-family:'Syne',sans-serif">
                {{ config('app.name') }}
            </a>

            <!-- Right side -->
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ $authUrl ?? '#' }}" class="px-4 py-1.5 text-sm font-semibold hover:text-violet-500 transition-colors duration-200" style="color:var(--text-muted)">Dashboard</a>
                    <livewire:database-notifications />
                @else
                    @if ($showBackLink ?? false)
                        <a href="/" class="px-4 py-1.5 text-sm font-semibold hover:text-violet-500 transition-colors duration-200" style="color:var(--text-muted)">{{ $backLinkText ?? '← Home' }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2 text-sm font-bold">
                            Sign in ✨
                        </a>
                    @endif
                @endauth

                <!-- Theme toggle -->
                <button
                    data-theme-toggle
                    class="p-2 rounded-full hover:bg-black/10 dark:hover:bg-white/10 transition-all duration-200" style="color:var(--text-subtle)"
                    type="button"
                    aria-label="Toggle theme"
                    title="Toggle theme"
                >
                    <svg class="w-4 h-4 theme-icon-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: block;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1m-16 0H1m15.364 1.636l-.707.707M6.343 6.343l-.707-.707m12.728 0l-.707.707m-12.021 12.021l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg class="w-4 h-4 theme-icon-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="p-2 rounded-full hover:text-red-500 hover:bg-black/10 dark:hover:bg-white/10 transition-all duration-200" style="color:var(--text-subtle)"
                            aria-label="Logout"
                            title="Logout"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                            </svg>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>
