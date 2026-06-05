
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

            /* ══════════════════════════════════════════════
               DESIGN TOKENS — light / dark
            ══════════════════════════════════════════════ */
            :root {
                /* Surface */
                --bg-base:         #f5f3ff;
                --bg-surface:      #ffffff;
                --bg-alt:          #ede9fe;
                /* Text */
                --text-base:       #0f0c1e;
                --text-muted:      rgba(15,12,30,.56);
                --text-subtle:     rgba(15,12,30,.36);
                /* Card */
                --card-bg:         rgba(255,255,255,.70);
                --card-border:     rgba(109,40,217,.14);
                /* Nav */
                --nav-bg:          rgba(245,243,255,.88);
                --nav-border:      rgba(109,40,217,.14);
                /* Ticker / strip */
                --strip-bg:        rgba(0,0,0,.03);
                --strip-border:    rgba(0,0,0,.08);
                --ticker-text:     rgba(15,12,30,.28);
                /* Stat bar */
                --stat-bg:         #ffffff;
                --stat-border:     rgba(109,40,217,.14);
                --stat-divider:    rgba(109,40,217,.10);
                /* Alt section */
                --alt-bg:          #ede9fe;
                /* CTA */
                --cta-bg:          #f5f3ff;
                --cta-bg-word:     rgba(109,40,217,.05);
            }

            .dark {
                /* Surface */
                --bg-base:         #0c0a14;
                --bg-surface:      #100e1f;
                --bg-alt:          #13102b;
                /* Text */
                --text-base:       #f8f6ff;
                --text-muted:      rgba(248,246,255,.56);
                --text-subtle:     rgba(248,246,255,.36);
                /* Card */
                --card-bg:         rgba(255,255,255,.04);
                --card-border:     rgba(255,255,255,.08);
                /* Nav */
                --nav-bg:          rgba(12,10,20,.85);
                --nav-border:      rgba(255,255,255,.09);
                /* Ticker / strip */
                --strip-bg:        rgba(255,255,255,.03);
                --strip-border:    rgba(255,255,255,.09);
                --ticker-text:     rgba(255,255,255,.22);
                /* Stat bar */
                --stat-bg:         #100e1f;
                --stat-border:     rgba(255,255,255,.08);
                --stat-divider:    rgba(255,255,255,.08);
                /* Alt section */
                --alt-bg:          #100e1f;
                /* CTA */
                --cta-bg:          #0c0a14;
                --cta-bg-word:     rgba(124,58,237,.07);
            }

            /* ══════════════════════════════════════════════
               BASE
            ══════════════════════════════════════════════ */
            body {
                background-color: var(--bg-base);
                color: var(--text-base);
                transition: background-color .3s, color .3s;
            }
            body, p, li, a, span, button, input, label { font-family: 'Plus Jakarta Sans', sans-serif; }
            h1, h2, h3, h4 { font-family: 'Syne', sans-serif; }

            /* ══════════════════════════════════════════════
               NOISE OVERLAY
            ══════════════════════════════════════════════ */
            .noise-overlay::before {
                content: '';
                position: fixed;
                inset: 0;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
                pointer-events: none;
                z-index: 9999;
                opacity: .28;
            }

            /* ══════════════════════════════════════════════
               ANIMATIONS
            ══════════════════════════════════════════════ */
            @keyframes marquee {
                from { transform: translateX(0); }
                to   { transform: translateX(-50%); }
            }
            .marquee-track { animation: marquee 28s linear infinite; }

            @keyframes pulse-glow {
                0%,100% { box-shadow: 0 0 18px 2px rgba(124,58,237,.4); }
                50%      { box-shadow: 0 0 36px 8px rgba(124,58,237,.7); }
            }
            .glow-violet { animation: pulse-glow 3s ease-in-out infinite; }

            /* ── Hero entrance ── */
            @keyframes hero-fade-up {
                from { opacity: 0; transform: translateY(28px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes hero-slide-right {
                from { opacity: 0; transform: translateX(40px); }
                to   { opacity: 1; transform: translateX(0); }
            }
            /* ── Mesh entrance ── */
            @keyframes mesh-enter {
                from { opacity: 0; transform: scale(1.06); }
                to   { opacity: 1; transform: scale(1); }
            }
            /* ── Blob drifts ── */
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
            /* ── KPI panel depth float ── */
            @keyframes panel-float {
                0%,100% { transform: perspective(900px) rotateY(-1.5deg) translateY(0px); }
                50%     { transform: perspective(900px) rotateY(-0.5deg) translateY(-8px); }
            }
            /* ── Progress bar grow ── */
            @keyframes hero-bar-grow {
                from { width: 0%; }
            }
            /* ── Scroll line pulse ── */
            @keyframes scroll-pulse {
                0%,100% { transform: scaleY(0.3); opacity: 0; }
                50%     { transform: scaleY(1); opacity: 1; }
            }
            /* ── Entrance utilities ── */
            .anim-fade-up     { animation: hero-fade-up    0.75s cubic-bezier(0.25,1,0.5,1) both; }
            .anim-slide-right { animation: hero-slide-right 0.75s cubic-bezier(0.25,1,0.5,1) both; }
            /* ── Blob animation classes ── */
            .hero-blob-lg  { animation: blob-drift-lg   9s   ease-in-out infinite; }
            .hero-blob-sm1 { animation: blob-drift-sm   6s   ease-in-out infinite; }
            .hero-blob-sm2 { animation: blob-drift-sm2  7.5s ease-in-out 1s infinite; }
            /* ── KPI panel continuous float ── */
            .hero-kpi-panel { animation: panel-float 6s ease-in-out 0.5s infinite; will-change: transform; }

            /* ══════════════════════════════════════════════
               GRADIENTS
            ══════════════════════════════════════════════ */
            .text-gradient-violet {
                background: linear-gradient(135deg, #a78bfa 0%, #7c3aed 55%, #ec4899 100%);
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

            /* ══════════════════════════════════════════════
               BUTTONS
            ══════════════════════════════════════════════ */
            .btn-primary {
                background: linear-gradient(135deg, #7c3aed, #ec4899);
                color: #fff;
                font-weight: 700;
                border-radius: 9999px;
                transition: transform .2s, box-shadow .2s, opacity .2s;
            }
            .btn-primary:hover {
                transform: scale(1.04) translateY(-2px);
                box-shadow: 0 12px 32px rgba(124,58,237,.5);
            }
            .btn-arrow { display: inline-block; transition: transform .2s; }
            .btn-primary:hover .btn-arrow { transform: translateX(4px); }

            .lp-ghost-btn {
                border: 1px solid var(--card-border);
                border-radius: 9999px;
                color: var(--text-muted);
                transition: border-color .2s, color .2s, background .2s;
            }
            .lp-ghost-btn:hover {
                border-color: rgba(124,58,237,.5);
                color: #7c3aed;
                background: rgba(124,58,237,.06);
            }

            /* ══════════════════════════════════════════════
               NAV
            ══════════════════════════════════════════════ */
            nav[class*="sticky"], nav[class*="fixed"] {
                background: var(--nav-bg) !important;
                border-color: var(--nav-border) !important;
            }

            /* ══════════════════════════════════════════════
               HERO
            ══════════════════════════════════════════════ */
            .hero-mesh {
                background:
                    radial-gradient(ellipse 70% 60% at 20% 40%, rgba(124,58,237,.18) 0%, transparent 70%),
                    radial-gradient(ellipse 50% 50% at 80% 70%, rgba(236,72,153,.12) 0%, transparent 65%),
                    radial-gradient(ellipse 40% 40% at 60% 10%, rgba(132,204,22,.08) 0%, transparent 60%);
                animation: mesh-enter 1.8s cubic-bezier(0.25,1,0.5,1) both;
            }
            .dark .hero-mesh {
                background:
                    radial-gradient(ellipse 70% 60% at 20% 40%, rgba(124,58,237,.28) 0%, transparent 70%),
                    radial-gradient(ellipse 50% 50% at 80% 70%, rgba(236,72,153,.18) 0%, transparent 65%),
                    radial-gradient(ellipse 40% 40% at 60% 10%, rgba(132,204,22,.10) 0%, transparent 60%);
            }

            .hero-bg-number {
                font-family: 'Syne', sans-serif;
                font-size: clamp(280px, 35vw, 480px);
                font-weight: 900;
                line-height: 1;
                color: transparent;
                -webkit-text-stroke: 1px rgba(124,58,237,.08);
                text-stroke: 1px rgba(124,58,237,.08);
                letter-spacing: -0.04em;
                user-select: none;
            }
            .dark .hero-bg-number {
                -webkit-text-stroke: 1px rgba(124,58,237,.12);
            }

            .lp-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                color: var(--text-subtle);
                border: 1px solid var(--card-border);
                border-radius: 9999px;
                padding: 6px 14px;
                background: var(--card-bg);
                width: fit-content;
            }
            .lp-dot {
                width: 7px; height: 7px;
                border-radius: 50%;
                background: #7c3aed;
                box-shadow: 0 0 6px #7c3aed;
                animation: pulse-glow 2.5s ease-in-out infinite;
                flex-shrink: 0;
            }

            .lp-hero-h1 {
                font-family: 'Syne', sans-serif;
                font-size: clamp(3.5rem, 8vw, 7rem);
                font-weight: 900;
                line-height: 0.92;
                letter-spacing: -0.03em;
            }
            .lp-word-base { color: var(--text-base); }

            .lp-hero-body {
                font-size: 1.0625rem;
                line-height: 1.75;
                color: var(--text-muted);
            }

            .lp-pill {
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                padding: 5px 12px;
                border-radius: 9999px;
                border: 1px solid var(--card-border);
                color: var(--text-subtle);
                background: var(--card-bg);
            }

            /* ── Stat bar ── */
            .lp-stat-bar {
                background: var(--stat-bg);
                border-color: var(--stat-border);
                transition: background .3s, border-color .3s;
                min-width: 220px;
            }
            .lp-stat-bar-border { border-color: var(--stat-border); }
            .lp-stat-divider    { border-color: var(--stat-divider); }
            .lp-bar-label {
                position: absolute;
                top: 2rem; left: 50%; transform: translateX(-50%) rotate(0deg);
                font-family: 'Syne', sans-serif;
                font-size: 0.6rem;
                font-weight: 900;
                letter-spacing: 0.3em;
                color: var(--text-subtle);
                white-space: nowrap;
            }
            .lp-stat-num {
                font-family: 'Syne', sans-serif;
                font-size: 2rem;
                font-weight: 900;
                line-height: 1;
                letter-spacing: -0.02em;
            }
            .lp-stat-label {
                font-size: 0.7rem;
                font-weight: 600;
                letter-spacing: 0.04em;
                color: var(--text-subtle);
                margin-top: 4px;
            }

            /* ── Hero KPI cards (right column) ── */
            .hero-blob {
                position: absolute;
                pointer-events: none;
                user-select: none;
            }
            .hero-kpi-card {
                background: var(--bg-surface);
                border: 1px solid var(--card-border);
                border-radius: 20px;
                padding: 20px 22px;
                box-shadow: 0 4px 24px rgba(0,0,0,.06);
                transition: box-shadow .25s, border-color .25s;
            }
            .dark .hero-kpi-card {
                background: var(--card-bg);
                box-shadow: 0 4px 24px rgba(0,0,0,.25);
            }
            .hero-kpi-label {
                font-size: 0.62rem;
                font-weight: 800;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: var(--text-subtle);
                margin-bottom: 6px;
            }
            .hero-kpi-value {
                font-family: 'Syne', sans-serif;
                font-size: 1.75rem;
                font-weight: 900;
                line-height: 1;
                letter-spacing: -0.02em;
                color: var(--text-base);
            }
            .hero-kpi-unit {
                font-size: 0.9rem;
                font-weight: 700;
                color: var(--text-subtle);
            }
            .hero-kpi-icon {
                width: 40px; height: 40px;
                border-radius: 12px;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.25rem;
                flex-shrink: 0;
            }
            .hero-kpi-progress-track {
                height: 5px;
                border-radius: 9999px;
                background: var(--card-border);
                overflow: hidden;
            }
            .hero-kpi-progress-bar {
                height: 100%;
                border-radius: 9999px;
            }
            .hero-kpi-tab {
                font-size: 0.68rem;
                font-weight: 600;
                padding: 5px 10px;
                border-radius: 9999px;
                border: 1px solid var(--card-border);
                color: var(--text-subtle);
                background: transparent;
                cursor: default;
                white-space: nowrap;
            }
            .hero-kpi-tab-active {
                background: rgba(124,58,237,.12);
                color: #7c3aed;
                border-color: rgba(124,58,237,.25);
            }
            .hero-inline-tag {
                font-size: 0.72rem;
                font-weight: 600;
                color: var(--text-subtle);
            }

            /* ── Stat row (horizontal cards below CTA) ── */
            .hero-stat-row {
                display: flex;
                flex-wrap: wrap;
                gap: 0;
                border: 1px solid var(--card-border);
                border-radius: 16px;
                background: var(--card-bg);
                backdrop-filter: blur(12px);
                overflow: hidden;
                width: fit-content;
                max-width: 100%;
            }
            .hero-stat-item {
                display: flex;
                flex-direction: column;
                gap: 5px;
                padding: 20px 28px;
                border-right: 1px solid var(--card-border);
            }
            .hero-stat-item:last-child { border-right: none; }
            .hero-stat-num {
                font-family: 'Syne', sans-serif;
                font-size: 1.625rem;
                font-weight: 900;
                line-height: 1;
                letter-spacing: -0.02em;
            }
            .hero-stat-label {
                font-size: 0.68rem;
                font-weight: 600;
                letter-spacing: 0.04em;
                color: var(--text-subtle);
                white-space: nowrap;
            }

            /* ── Scroll cue ── */
            .lp-scroll-line {
                width: 1px; height: 32px;
                background: linear-gradient(to bottom, rgba(124,58,237,.6), transparent);
                border-radius: 9999px;
                animation: scroll-pulse 2.2s ease-in-out infinite;
                transform-origin: top;
            }

            /* ══════════════════════════════════════════════
               TICKER
            ══════════════════════════════════════════════ */
            .lp-ticker-wrap {
                background: var(--strip-bg);
                border-color: var(--strip-border);
                animation: hero-bar-grow 1.4s cubic-bezier(0.25,1,0.5,1) 1s both;
            }
            .lp-ticker-text { color: var(--ticker-text); }

            /* ══════════════════════════════════════════════
               SECTION LABELS
            ══════════════════════════════════════════════ */
            .lp-section-label {
                font-size: 0.68rem;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                color: #7c3aed;
            }
            .lp-section-h2 {
                font-family: 'Syne', sans-serif;
                font-size: clamp(2.5rem, 5vw, 3.75rem);
                font-weight: 900;
                line-height: 1.0;
                letter-spacing: -0.025em;
                color: var(--text-base);
            }
            .lp-section-sub {
                font-size: 0.9rem;
                color: var(--text-muted);
                line-height: 1.65;
            }

            /* ══════════════════════════════════════════════
               BENTO CARDS
            ══════════════════════════════════════════════ */
            .bento-card {
                background: var(--card-bg);
                border: 1px solid var(--card-border);
                border-radius: 20px;
                backdrop-filter: blur(12px);
                position: relative;
                overflow: hidden;
                transition: border-color .25s, transform .25s, box-shadow .25s;
            }
            .bento-card::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 20px;
                border-top: 2px solid transparent;
                transition: border-color .25s;
                pointer-events: none;
            }
            .bento-card:hover {
                border-color: color-mix(in srgb, var(--accent, #7c3aed) 35%, transparent);
                transform: translateY(-3px);
                box-shadow: 0 16px 48px color-mix(in srgb, var(--accent, #7c3aed) 12%, transparent);
            }
            .bento-card:hover::before {
                border-top-color: color-mix(in srgb, var(--accent, #7c3aed) 60%, transparent);
            }
            .bento-icon-wrap {
                width: 48px; height: 48px;
                border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.5rem;
                flex-shrink: 0;
            }
            .bento-title {
                font-family: 'Syne', sans-serif;
                font-size: 1.05rem;
                font-weight: 800;
                color: var(--text-base);
            }
            .bento-body {
                font-size: 0.85rem;
                line-height: 1.65;
                color: var(--text-muted);
            }
            /* Section header count badge */
            .bento-feature-count {
                font-size: 0.62rem;
                font-weight: 800;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: var(--text-subtle);
                border: 1px solid var(--card-border);
                border-radius: 9999px;
                padding: 5px 12px;
                background: var(--card-bg);
            }
            /* LIVE badge inside hours card */
            .bento-live-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                font-size: 0.62rem;
                font-weight: 800;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #16a34a;
                background: rgba(22,163,74,.1);
                border: 1px solid rgba(22,163,74,.2);
                border-radius: 9999px;
                padding: 4px 8px;
            }
            .bento-live-dot {
                width: 6px; height: 6px;
                border-radius: 50%;
                background: #16a34a;
                box-shadow: 0 0 5px #16a34a;
                animation: pulse-glow 2s ease-in-out infinite;
                display: inline-block;
            }
            /* Day labels below bar chart */
            .bento-bar-day {
                font-size: 0.5rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                color: var(--text-subtle);
                text-transform: uppercase;
            }
            /* Small progress pill */
            .bento-progress-pill {
                font-size: 0.62rem;
                font-weight: 700;
                padding: 3px 8px;
                border-radius: 9999px;
            }
            /* Pay breakdown label */
            .bento-pay-label {
                font-size: 0.65rem;
                font-weight: 600;
                color: var(--text-subtle);
                white-space: nowrap;
                min-width: 80px;
            }
            /* Recognition award row */
            .bento-award-row {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                border-radius: 12px;
                border: 1px solid var(--card-border);
                background: var(--bg-surface);
            }
            .bento-award-avatar {
                width: 30px; height: 30px;
                border-radius: 50%;
                background: linear-gradient(135deg,#7c3aed,#ec4899);
                display: flex; align-items: center; justify-content: center;
                font-size: 0.6rem; font-weight: 800; color: white;
                flex-shrink: 0;
            }
            /* Approval flow steps */
            .bento-flow-step {
                font-size: 0.62rem;
                font-weight: 700;
                padding: 4px 8px;
                border-radius: 9999px;
                border: 1px solid var(--card-border);
                color: var(--text-subtle);
                background: transparent;
                white-space: nowrap;
            }
            .bento-flow-done {
                color: #7c3aed;
                border-color: rgba(124,58,237,.25);
                background: rgba(124,58,237,.08);
            }
            .bento-flow-active {
                color: #16a34a;
                border-color: rgba(22,163,74,.3);
                background: rgba(22,163,74,.1);
            }
            .bento-flow-arrow {
                font-size: 0.6rem;
                color: var(--text-subtle);
            }
            /* Comms notification badge */
            .bento-notif-badge {
                font-size: 0.62rem;
                font-weight: 800;
                padding: 4px 9px;
                border-radius: 9999px;
                color: #0ea5e9;
                background: rgba(14,165,233,.12);
                border: 1px solid rgba(14,165,233,.2);
            }
            /* Privacy pill badges */
            .bento-privacy-pill {
                font-size: 0.65rem;
                font-weight: 700;
                padding: 5px 10px;
                border-radius: 9999px;
                border: 1px solid;
            }

            /* ══════════════════════════════════════════════
               ALT SECTION (testimonials bg)
            ══════════════════════════════════════════════ */
            .lp-alt-section {
                background: var(--alt-bg);
                transition: background .3s;
            }

            /* ══════════════════════════════════════════════
               QUOTE CARDS
            ══════════════════════════════════════════════ */
            .quote-card {
                background: var(--bg-surface);
                border: 1px solid var(--card-border);
                border-radius: 20px;
                transition: border-color .25s, transform .25s;
            }
            .quote-card:hover { border-color: rgba(124,58,237,.3); }
            .quote-text {
                font-size: 0.9rem;
                line-height: 1.7;
                color: var(--text-muted);
                font-style: italic;
            }
            .quote-author { display: flex; align-items: center; gap: 12px; }
            .quote-avatar {
                width: 40px; height: 40px;
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                font-size: 0.75rem; font-weight: 800; color: #fff;
                flex-shrink: 0;
            }
            .quote-name { font-weight: 700; font-size: 0.85rem; color: var(--text-base); }
            .quote-role { font-size: 0.72rem; color: var(--text-subtle); margin-top: 1px; }

            /* ══════════════════════════════════════════════
               FAQ
            ══════════════════════════════════════════════ */
            .lp-faq-divider > * { border-color: var(--card-border); }
            .lp-faq-q {
                font-family: 'Syne', sans-serif;
                font-size: 1rem;
                font-weight: 700;
                color: var(--text-base);
                transition: color .2s;
            }
            .lp-faq-q:hover { color: #7c3aed; }
            .lp-faq-plus {
                font-size: 1.5rem;
                font-weight: 300;
                color: var(--text-subtle);
                line-height: 1;
                flex-shrink: 0;
            }
            .lp-faq-a {
                font-size: 0.875rem;
                line-height: 1.7;
                color: var(--text-muted);
            }

            /* ══════════════════════════════════════════════
               CTA SECTION
            ══════════════════════════════════════════════ */
            .lp-cta-section {
                background: var(--cta-bg);
                transition: background .3s;
            }
            .lp-cta-bg-word {
                font-family: 'Syne', sans-serif;
                font-size: clamp(160px, 30vw, 380px);
                font-weight: 900;
                color: transparent;
                -webkit-text-stroke: 1.5px rgba(124,58,237,.10);
                text-stroke: 1.5px rgba(124,58,237,.10);
                letter-spacing: -0.02em;
                white-space: nowrap;
                overflow: hidden;
                width: 100%;
                justify-content: center;
            }
            .dark .lp-cta-bg-word {
                -webkit-text-stroke: 1.5px rgba(124,58,237,.14);
            }
            .lp-cta-h2 {
                font-family: 'Syne', sans-serif;
                font-size: clamp(2.8rem, 6vw, 5rem);
                font-weight: 900;
                line-height: 1.0;
                letter-spacing: -0.025em;
                color: var(--text-base);
            }
            .lp-cta-body {
                font-size: 1rem;
                line-height: 1.7;
                color: var(--text-muted);
            }

            /* ══════════════════════════════════════════════
               MISC HELPERS
            ══════════════════════════════════════════════ */
            .lp-text-subtle { color: var(--text-subtle); }
            .lp-check-item  { display: flex; align-items: center; }
            .card-glass {
                background: var(--card-bg);
                border: 1px solid var(--card-border);
                backdrop-filter: blur(12px);
                transition: background .3s, border-color .3s;
            }

            /* ══════════════════════════════════════════════
               FOOTER
            ══════════════════════════════════════════════ */
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
                background: rgb(124 58 237 / 0.10);
                color: #6d28d9;
                box-shadow: 0 0 0 1px rgb(124 58 237 / 0.20);
            }
            .dark .footer-badge-violet { color: #c4b5fd; }
            .footer-badge-lime {
                background: rgb(132 204 22 / 0.10);
                color: #4d7c0f;
                box-shadow: 0 0 0 1px rgb(132 204 22 / 0.20);
            }
            .dark .footer-badge-lime { color: #a3e635; }

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
                                <li><a href="/articles" class="footer-link text-sm transition-colors duration-200">Articles</a></li>
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
