
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data @init="$store.theme.init()" class="scroll-smooth">
    @props([
        'navType' => 'sticky'
    ])
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
        <x-navigation :auth-url="$authUrl" navType="{{ $navType }}"/>

        @yield('content')
        {{ $slot ?? null }}

        <!-- Footer -->
        <footer class="footer-section relative overflow-hidden border-t">
            <!-- Decorative glow -->
            <div class="pointer-events-none absolute -top-32 left-1/2 h-64 w-96 -translate-x-1/2 rounded-full bg-violet-700/20 blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <!-- Top row: brand + links -->
                <div class="flex flex-col md:flex-row md:items-start gap-12 mb-12">
                    <!-- Brand -->
                    <div class="flex-1 max-w-xs">
                        <span class="text-2xl font-black text-gradient-violet" style="font-family:'Syne',sans-serif">{{ config('app.name') }}</span>
                        <p class="mt-3 text-sm footer-copy leading-relaxed">Your pay. Your hours. Your wins. All in one place — finally.</p>
                        <div class="mt-4 flex gap-3">
                            <span class="footer-badge-violet inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold">✨ Payroll clarity</span>
                            <span class="footer-badge-lime inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold">🔒 Privacy first</span>
                        </div>
                    </div>

                    <!-- Links grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-8 flex-1">
                        <div>
                            <h3 class="footer-heading text-xs font-bold uppercase tracking-widest mb-4">Company</h3>
                            <ul class="space-y-2.5">
                                <li><a href="https://eccocorpbpo.com/" class="footer-link text-sm transition-colors duration-200">About</a></li>
                                <li><a href="https://eccocorpbpo.com/apply-now/" class="footer-link text-sm transition-colors duration-200">Careers 🚀</a></li>
                                <li><a href="https://eccocorpbpo.com/contact-us/" class="footer-link text-sm transition-colors duration-200">Contact</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="footer-heading text-xs font-bold uppercase tracking-widest mb-4">Product</h3>
                            <ul class="space-y-2.5">
                                <li><a href="/#features" class="footer-link text-sm transition-colors duration-200">Features</a></li>
                                <li><a href="/#security" class="footer-link text-sm transition-colors duration-200">Security</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="footer-heading text-xs font-bold uppercase tracking-widest mb-4">Resources</h3>
                            <ul class="space-y-2.5">
                                <li><a href="/blog" class="footer-link text-sm transition-colors duration-200">Articles</a></li>
                                <li><a href="/docs/api" target="docs-api" class="footer-link text-sm transition-colors duration-200">API Docs</a></li>
                                <li><a href="/my-tickets-management" class="footer-link text-sm transition-colors duration-200">IT Support</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="footer-heading text-xs font-bold uppercase tracking-widest mb-4">Legal</h3>
                            <ul class="space-y-2.5">
                                <li><a href="/privacy" class="footer-link text-sm transition-colors duration-200">Privacy</a></li>
                                <li><a href="/terms" class="footer-link text-sm transition-colors duration-200">Terms</a></li>
                                <li><a href="/cookies" class="footer-link text-sm transition-colors duration-200">Cookies</a></li>
                            </ul>
                        </div>
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
