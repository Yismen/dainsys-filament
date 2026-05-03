
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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

            /* ── Light mode ─────────────────────────────── */
            :root {
                --bg-base:       #f4f2ff;
                --bg-surface:    #ffffff;
                --text-base:     #0f0c1e;
                --text-muted:    rgba(15,12,30,.55);
                --text-subtle:   rgba(15,12,30,.38);
                --card-bg:       rgba(255,255,255,.65);
                --card-border:   rgba(124,58,237,.15);
                --nav-bg:        rgba(244,242,255,.85);
                --nav-border:    rgba(124,58,237,.15);
                --strip-bg:      rgba(0,0,0,.03);
                --strip-border:  rgba(0,0,0,.07);
                --marquee-color: rgba(15,12,30,.28);
                --glow-opacity:  0.14;
            }

            /* ── Dark mode ──────────────────────────────── */
            .dark {
                --bg-base:       #0c0a14;
                --bg-surface:    #100e1f;
                --text-base:     #ffffff;
                --text-muted:    rgba(255,255,255,.55);
                --text-subtle:   rgba(255,255,255,.38);
                --card-bg:       rgba(255,255,255,.04);
                --card-border:   rgba(255,255,255,.09);
                --nav-bg:        rgba(12,10,20,.80);
                --nav-border:    rgba(255,255,255,.10);
                --strip-bg:      rgba(255,255,255,.03);
                --strip-border:  rgba(255,255,255,.10);
                --marquee-color: rgba(255,255,255,.25);
                --glow-opacity:  1;
            }

            body {
                background-color: var(--bg-base);
                color: var(--text-base);
                transition: background-color .3s, color .3s;
            }

            body, p, li, a, span, button, input, label {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            h1, h2, h3, h4 {
                font-family: 'Syne', sans-serif;
            }

            @keyframes marquee {
                from { transform: translateX(0); }
                to   { transform: translateX(-50%); }
            }
            .marquee-track {
                animation: marquee 22s linear infinite;
                color: var(--marquee-color);
            }

            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 18px 2px rgba(124,58,237,.45); }
                50%       { box-shadow: 0 0 36px 6px rgba(124,58,237,.75); }
            }
            .glow-violet { animation: pulse-glow 3s ease-in-out infinite; }

            @keyframes float-slow {
                0%, 100% { transform: translateY(0); }
                50%       { transform: translateY(-10px); }
            }
            .float-slow { animation: float-slow 5s ease-in-out infinite; }

            @keyframes wiggle {
                0%, 100% { transform: rotate(-3deg); }
                50%       { transform: rotate(3deg); }
            }
            .wiggle { animation: wiggle 2.5s ease-in-out infinite; }

            .noise-overlay::before {
                content: '';
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
                pointer-events: none;
                z-index: 9999;
                opacity: .35;
            }

            .card-glass {
                background: var(--card-bg);
                border: 1px solid var(--card-border);
                backdrop-filter: blur(12px);
                transition: background .3s, border-color .3s;
            }

            /* Muted text helpers driven by CSS vars */
            .text-muted  { color: var(--text-muted); }
            .text-subtle { color: var(--text-subtle); }

            .text-gradient-violet {
                background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 50%, #ec4899 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .text-gradient-lime {
                background: linear-gradient(135deg, #86efac 0%, #84cc16 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .btn-primary {
                background: linear-gradient(135deg, #7c3aed, #ec4899);
                color: #fff;
                font-weight: 700;
                border-radius: 9999px;
                transition: transform .2s, box-shadow .2s;
            }
            .btn-primary:hover {
                transform: scale(1.05) translateY(-2px);
                box-shadow: 0 10px 30px rgba(124,58,237,.5);
            }

            /* ── Nav uses CSS vars ──────────────────────── */
            nav[class*="sticky"], nav[class*="fixed"] {
                background: var(--nav-bg) !important;
                border-color: var(--nav-border) !important;
            }

            /* ── Strip uses CSS vars ────────────────────── */
            .marquee-strip {
                background: var(--strip-bg);
                border-color: var(--strip-border);
            }

            /* Light mode: darken ambient glow blobs */
            :root .glow-blob {
                mix-blend-mode: multiply;
            }
            .dark .glow-blob {
                mix-blend-mode: normal;
            }

            /* Light mode heading color override */
            :root h1 .text-white-hero { color: var(--text-base); }
            .dark  h1 .text-white-hero { color: #ffffff; }
            /* ── Light mode: override hardcoded white/opacity text ─── */
            :root:not(.dark) .text-white                 { color: #0f0c1e !important; }
            :root:not(.dark) .text-white\/70             { color: rgb(15 12 30 / 0.70) !important; }
            :root:not(.dark) .text-white\/60             { color: rgb(15 12 30 / 0.60) !important; }
            :root:not(.dark) .text-white\/55             { color: rgb(15 12 30 / 0.55) !important; }
            :root:not(.dark) .text-white\/50             { color: rgb(15 12 30 / 0.50) !important; }
            :root:not(.dark) .text-white\/45             { color: rgb(15 12 30 / 0.45) !important; }
            :root:not(.dark) .text-white\/40             { color: rgb(15 12 30 / 0.40) !important; }
            :root:not(.dark) .text-white\/35             { color: rgb(15 12 30 / 0.35) !important; }
            :root:not(.dark) .text-white\/30             { color: rgb(15 12 30 / 0.30) !important; }
            :root:not(.dark) .text-white\/25             { color: rgb(15 12 30 / 0.25) !important; }
            :root:not(.dark) .text-white\/20             { color: rgb(15 12 30 / 0.20) !important; }
            :root:not(.dark) .text-white\/10             { color: rgb(15 12 30 / 0.10) !important; }

            /* Light mode: small bg-white/XX overlays → subtle dark */
            :root:not(.dark) .bg-white\/5  { background-color: rgb(15 12 30 / 0.04) !important; }
            :root:not(.dark) .bg-white\/10 { background-color: rgb(15 12 30 / 0.06) !important; }

            /* Light mode: white border opacities → dark */
            :root:not(.dark) .border-white\/10 { border-color: rgb(15 12 30 / 0.10) !important; }
            :root:not(.dark) .border-white\/15 { border-color: rgb(15 12 30 / 0.12) !important; }
            :root:not(.dark) .border-white\/30 { border-color: rgb(15 12 30 / 0.25) !important; }

            /* CTA section inner panel — uses --bg-surface token */
            .cta-inner {
                background-color: var(--bg-surface);
                transition: background-color .3s;
            }

            /* Light mode: ambient glow blobs are more subtle */
            :root:not(.dark) .bg-violet-700\/25 { opacity: 0.5; }
            :root:not(.dark) .bg-pink-700\/20   { opacity: 0.4; }

            /* Footer — fully theme-aware */
            .footer-section {
                background-color: var(--bg-surface);
                border-color: var(--card-border);
                transition: background-color .3s, border-color .3s;
            }
            .footer-heading { color: var(--text-subtle); }
            .footer-link    { color: var(--text-muted); }
            .footer-link:hover { color: #7c3aed; }
            .footer-copy    { color: var(--text-subtle); }
            .footer-divider { border-color: var(--card-border); }
            .footer-badge-violet {
                background: rgb(124 58 237 / 0.12);
                color: #7c3aed;
                box-shadow: 0 0 0 1px rgb(124 58 237 / 0.25);
            }
            .dark .footer-badge-violet {
                color: #c4b5fd;
            }
            .footer-badge-lime {
                background: rgb(132 204 22 / 0.12);
                color: #4d7c0f;
                box-shadow: 0 0 0 1px rgb(132 204 22 / 0.25);
            }
            .dark .footer-badge-lime {
                color: #a3e635;
            }
        </style>
    </head>
    <body class="noise-overlay antialiased">
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

                <!-- Bottom bar -->
                <div class="footer-divider border-t pt-8 flex flex-col sm:flex-row gap-3 items-center justify-between">
                    <p class="footer-copy text-xs">&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
                    <p class="footer-copy text-xs">Made with 💜 for the team</p>
                </div>
            </div>
        </footer>

        @livewireScripts
        @filamentScripts
    </body>
</html>
