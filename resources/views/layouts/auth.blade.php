<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data @init="$store.theme.init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Dainsys')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
        <style>
            [x-cloak] { display: none !important; }

            :root {
                --bg-base:         #f5f3ff;
                --text-base:       #0f0c1e;
                --text-muted:      rgba(15,12,30,.56);
                --text-subtle:     rgba(15,12,30,.36);
                --card-bg:         rgba(255,255,255,.70);
                --card-border:     rgba(109,40,217,.14);
            }

            .dark {
                --bg-base:         #0c0a14;
                --text-base:       #f8f6ff;
                --text-muted:      rgba(248,246,255,.56);
                --text-subtle:     rgba(248,246,255,.36);
                --card-bg:         rgba(255,255,255,.04);
                --card-border:     rgba(255,255,255,.08);
            }

            body {
                background-color: var(--bg-base);
                color: var(--text-base);
                transition: background-color .3s, color .3s;
            }
            body, p, li, a, span, button, input, label { font-family: 'Plus Jakarta Sans', sans-serif; }
            h1, h2, h3, h4 { font-family: 'Syne', sans-serif; }

            .text-gradient-violet {
                background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 55%, #ec4899 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            @keyframes blob-drift-lg {
                0%,100% { transform: rotate(12deg) translate(0,0); }
                30%     { transform: rotate(20deg) translate(8px,-12px); }
                65%     { transform: rotate(5deg)  translate(-5px,9px); }
            }
            @keyframes blob-drift-sm {
                0%,100% { transform: translate(0,0) scale(1); }
                50%     { transform: translate(-6px,10px) scale(1.18); }
            }
            @keyframes blob-drift-sm2 {
                0%,100% { transform: translate(0,0) scale(1); }
                50%     { transform: translate(7px,-8px) scale(1.12); }
            }
            @keyframes mesh-enter {
                from { opacity: 0; transform: scale(1.06); }
                to   { opacity: 1; transform: scale(1); }
            }
            @keyframes login-fade-up {
                from { opacity: 0; transform: translateY(28px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .login-blob-lg  { animation: blob-drift-lg   9s   ease-in-out infinite; }
            .login-blob-sm1 { animation: blob-drift-sm   6s   ease-in-out infinite; }
            .login-blob-sm2 { animation: blob-drift-sm2  7.5s ease-in-out 1s infinite; }
            .anim-fade-up   { animation: login-fade-up   0.75s cubic-bezier(0.25,1,0.5,1) both; }

            .btn-gradient {
                background: linear-gradient(135deg, #7c3aed, #ec4899);
                color: #fff;
                font-weight: 700;
                border-radius: 9999px;
                transition: transform .2s, box-shadow .2s, opacity .2s;
            }
            .btn-gradient:hover {
                transform: scale(1.04) translateY(-2px);
                box-shadow: 0 12px 32px rgba(124,58,237,.5);
            }
            .noise-overlay::before {
                content: '';
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
                pointer-events: none;
                z-index: 9999;
                opacity: .28;
            }
        </style>
    </head>
    <body class="noise-overlay antialiased min-h-screen flex items-center justify-center px-4 py-12">
        {{-- Mesh gradient background matching homepage hero --}}
        <div class="pointer-events-none fixed inset-0" style="
            background:
                radial-gradient(ellipse 70% 60% at 20% 40%, rgba(124,58,237,.18) 0%, transparent 70%),
                radial-gradient(ellipse 50% 50% at 80% 70%, rgba(236,72,153,.12) 0%, transparent 65%),
                radial-gradient(ellipse 40% 40% at 60% 10%, rgba(132,204,22,.08) 0%, transparent 60%);
            animation: mesh-enter 1.8s cubic-bezier(0.25,1,0.5,1) both;
        "></div>

        {{-- Floating decorative blobs --}}
        <div class="login-blob-lg pointer-events-none fixed" style="width:80px;height:80px;background:#7c3aed22;top:80px;right:120px;border-radius:18px"></div>
        <div class="login-blob-sm1 pointer-events-none fixed" style="width:18px;height:18px;background:#84cc1666;bottom:120px;left:60px;border-radius:50%"></div>
        <div class="login-blob-sm2 pointer-events-none fixed" style="width:14px;height:14px;background:#ec489966;bottom:100px;right:80px;border-radius:50%"></div>

        <div class="relative z-10 w-full max-w-md mx-auto anim-fade-up" style="animation-delay:.2s">
            {{-- Brand --}}
            <a href="/" class="mb-10 flex items-center justify-center gap-3 no-underline">
                <span class="text-2xl font-black" style="font-family:'Syne',sans-serif;color:var(--text-base)">Dainsys</span>
            </a>

            @yield('content')
        </div>

        @livewireScripts
    </body>
</html>
