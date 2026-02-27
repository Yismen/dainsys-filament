<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data @init="$store.theme.init()" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} - Intranet</title>
        <link rel="icon" type="image/png" href="{{ asset('images/ecco-favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/ecco-logo.png') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @vite(['resources/css/filament/admin/theme.css'])
        @livewireStyles
        @filamentStyles
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class=" bg-linear-to-br from-slate-50 via-blue-50 to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 text-slate-900 dark:text-slate-100 antialiased">
        @php
            $panels = \Filament\Facades\Filament::getPanels();
            $panel = collect($panels)->first(function($panel) {
                $user = auth()->user();

                if ($user && method_exists($user, 'canAccessPanel')) {
                    return $user->canAccessPanel($panel);
                }

                return null;
            }) ?? null;

            $authUrl = $panel ? $panel->getUrl() : '/login';
        @endphp

        <x-navigation :auth-url="$authUrl" />

        @yield('content')
        {{ $slot ?? null }}

        <!-- Footer -->
        <footer class="bg-slate-900 dark:bg-slate-950 border-t border-slate-800 dark:border-slate-900 relative overflow-hidden">
            <!-- Subtle depth background -->
            <div class="absolute inset-0 bg-linear-to-t from-slate-950 dark:from-slate-1000 to-transparent opacity-50 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Company</h3>
                        <ul class="space-y-3">
                            <li><a href="https://eccocorpbpo.com/" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">About</a></li>
                            <li><a href="https://eccocorpbpo.com/apply-now/" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Careers</a></li>
                            <li><a href="https://eccocorpbpo.com/contact-us/" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Product</h3>
                        <ul class="space-y-3">
                            <li><a href="/#features" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Features</a></li>
                            <li><a href="/#security" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Security</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Resources</h3>
                        <ul class="space-y-3">
                            <li><a href="/blog" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Articles</a></li>
                            <li><a href="/docs/api" target="docs-api" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">API Documentation</a></li>
                            <li><a href="/support" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">IT Support</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Legal</h3>
                        <ul class="space-y-3">
                            <li><a href="/privacy" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Privacy</a></li>
                            <li><a href="/terms" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Terms</a></li>
                            <li><a href="/cookies" class="text-slate-400 dark:text-slate-500 hover:text-white dark:hover:text-slate-300 transition-colors duration-200">Cookies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-800 dark:border-slate-900 pt-8 flex flex-col sm:flex-row gap-4 items-center justify-between text-sm text-slate-400 dark:text-slate-500">
                    <p>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>

        @livewireScripts
        @filamentScripts
    </body>
</html>
