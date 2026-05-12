@extends('layouts.landing-page')

@section('content')

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

    {{-- ═══════════════════════════════════════
         HERO — two-column with KPI cards
    ═══════════════════════════════════════ --}}
    <section class="relative overflow-hidden" style="min-height:100dvh;display:flex;flex-direction:column;justify-content:center">

        {{-- mesh gradient bg --}}
        <div class="pointer-events-none absolute inset-0 hero-mesh"></div>

        {{-- floating decorative blobs --}}
        <div class="hero-blob hero-blob-lg" style="width:80px;height:80px;background:#7c3aed22;top:80px;right:120px;border-radius:18px"></div>
        <div class="hero-blob hero-blob-sm1" style="width:18px;height:18px;background:#84cc1666;bottom:120px;left:60px;border-radius:50%"></div>
        <div class="hero-blob hero-blob-sm2" style="width:14px;height:14px;background:#ec489966;bottom:100px;right:80px;border-radius:50%"></div>

        {{-- content --}}
        <div class="relative w-full max-w-6xl mx-auto px-6 sm:px-10 lg:px-16 pt-28 pb-20 flex flex-col lg:grid lg:grid-cols-[1fr_360px] xl:grid-cols-[1fr_400px] lg:gap-20 lg:items-center">

            {{-- LEFT: copy --}}
            <div class="flex flex-col">

                {{-- eyebrow --}}
                <div class="lp-eyebrow mb-8 anim-fade-up" style="animation-delay:0s">
                    <span class="lp-dot"></span>
                    Your intranet is live &nbsp;🎉
                </div>

                {{-- headline --}}
                <h1 class="lp-hero-h1 mb-7 anim-fade-up" style="animation-delay:.15s">
                    <span class="block lp-word-base">Your pay</span>
                    <span class="block text-gradient-violet">You first</span>
                    {{-- <span class="block lp-word-base">Your wins.</span> --}}
                </h1>

                {{-- body --}}
                <p class="lp-hero-body mb-10 max-w-md anim-fade-up" style="animation-delay:.28s">
                    {{ config('app.name') }} is built for the team &mdash; see your payroll breakdown, track hours in real-time, and understand every incentive before payday. No guessing.
                </p>

                {{-- CTA --}}
                <div class="flex flex-wrap items-center gap-4 mb-10 anim-fade-up" style="animation-delay:.4s">
                    @auth
                        <a href="{{ url($authUrl) }}" class="btn-primary inline-flex items-center gap-3 px-8 py-3.5 text-base font-bold">
                            Go to Dashboard <span class="btn-arrow">&rarr;</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-3 px-8 py-3.5 text-base font-bold">
                            Sign in <span class="btn-arrow">&rarr;</span>
                        </a>
                        <a href="#features" class="lp-ghost-btn inline-flex items-center gap-2 px-6 py-3.5 text-base font-semibold">
                            Explore features
                        </a>
                    @endauth
                </div>

                {{-- inline feature tags --}}
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 anim-fade-up" style="animation-delay:.52s">
                    @foreach([
                        ['✅', 'Real-time hours'],
                        ['💸', 'Pay clarity'],
                        ['🔒', 'Role-based privacy'],
                    ] as [$icon, $label])
                        <span class="hero-inline-tag">{{ $icon }} {{ $label }}</span>
                    @endforeach
                </div>

            </div>

            {{-- RIGHT: KPI dashboard cards — desktop only --}}
            <div class="hidden lg:flex flex-col gap-3 hero-kpi-panel">

                {{-- Card 1: Hours --}}
                <div class="hero-kpi-card " style="animation-delay:.55s">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="hero-kpi-label">This period</p>
                            <p class="hero-kpi-value">124.5 <span class="hero-kpi-unit">hrs</span></p>
                        </div>
                        <div class="hero-kpi-icon" style="background:rgba(124,58,237,.12)">⏱</div>
                    </div>
                    <div class="hero-kpi-progress-track">
                        <div class="hero-kpi-progress-bar" style="width:78%;background:linear-gradient(to right,#7c3aed,#ec4899)"></div>
                    </div>
                    <p class="text-[10px] font-semibold lp-text-subtle mt-2">679 remaining this quarter</p>
                </div>

                {{-- Card 2: Payroll --}}
                <div class="hero-kpi-card " style="margin-left:1.5rem;animation-delay:.7s">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="hero-kpi-label">Payroll run</p>
                            <p class="hero-kpi-value">$86,420</p>
                        </div>
                        <div class="hero-kpi-icon" style="background:rgba(132,204,22,.15)">💰</div>
                    </div>
                </div>

                {{-- Card 3: Team wins --}}
                <div class="hero-kpi-card " style="animation-delay:.85s">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="hero-kpi-label">Team wins this month</p>
                            <p class="hero-kpi-value" style="color:#84cc16">+18%</p>
                        </div>
                        <div class="hero-kpi-icon" style="background:rgba(236,72,153,.12)">🏆</div>
                    </div>
                    <div class="flex gap-2">
                        <span class="hero-kpi-tab">Visibility</span>
                        <span class="hero-kpi-tab">Trust</span>
                        <span class="hero-kpi-tab hero-kpi-tab-active">Momentum</span>
                    </div>
                </div>

            </div>

        </div>

        {{-- scroll cue --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-40 pointer-events-none">
            <div class="lp-scroll-line"></div>
            <span class="text-[10px] tracking-[0.25em] uppercase font-semibold lp-text-subtle">scroll</span>
        </div>

    </section>

    {{-- ═══════════════════════════════════════
         TICKER
    ═══════════════════════════════════════ --}}
    <div class="overflow-hidden border-y lp-ticker-wrap py-3.5">
        <div class="marquee-track flex gap-10 whitespace-nowrap text-xs font-black uppercase tracking-[0.18em] lp-ticker-text">
            @foreach(range(1,2) as $_)
                @foreach(['⚡ Hours tracking', '·', '💸 Pay transparency', '·', '🎯 Real-time KPIs', '·', '🔒 Privacy first', '·', '💬 Communication', '·', '🛠️ IT support', '·', '🏆 Recognition', '·', '📊 Approvals', '·'] as $item)
                    <span>{{ $item }}</span>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         BENTO GRID — features
    ═══════════════════════════════════════ --}}
    <section class="py-28 sm:py-36 px-4 sm:px-6 lg:px-8" id="features">
        <div class="max-w-7xl mx-auto">

            {{-- Section header --}}
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-14">
                <div>
                    <p class="lp-section-label mb-3">What’s inside</p>
                    <h2 class="lp-section-h2">Built for <span class="text-gradient-violet">your shift</span></h2>
                </div>
                <div class="flex flex-col items-start sm:items-end gap-2">
                    <p class="lp-section-sub max-w-xs text-right hidden sm:block">Every tool a BPO agent actually needs — and nothing they don’t.</p>
                    <div class="bento-feature-count">06 features</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                {{-- Card 1: Hours --}}
                <div class="bento-card group flex flex-col p-8" style="--accent:#7c3aed">
                    <div class="flex items-center justify-between mb-6">
                        <div class="bento-icon-wrap bg-violet-500/15">⏱️</div>
                        <span class="bento-live-badge"><span class="bento-live-dot"></span> Live</span>
                    </div>
                    <h3 class="bento-title mb-2">Hours tracking</h3>
                    <p class="bento-body mb-8">Real-time logs with full edit history. Your hours, your records — always accurate and auditable.</p>
                    <div class="mt-auto">
                        <div class="flex items-end gap-1.5 h-10 mb-2">
                            @foreach([[40,'M'],[70,'T'],[55,'W'],[90,'T'],[65,'F'],[80,'S'],[45,'S'],[95,'M'],[60,'T'],[75,'W']] as [$h, $day])
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full rounded-sm" style="height:{{ $h }}%;background:linear-gradient(to top,#7c3aed,#a78bfa)"></div>
                                    <span class="bento-bar-day">{{ $day }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs lp-text-subtle font-semibold">124.5 hrs this period</span>
                            <span class="bento-progress-pill" style="color:#7c3aed;background:rgba(124,58,237,.1)">+3.2%</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Pay transparency --}}
                <div class="bento-card group flex flex-col p-8" style="--accent:#84cc16">
                    <div class="bento-icon-wrap bg-lime-500/15 mb-6">💸</div>
                    <h3 class="bento-title mb-2">Pay transparency</h3>
                    <p class="bento-body mb-8">See every component of your paycheck — base, incentives, deductions — before payday hits.</p>
                    <div class="mt-auto flex flex-col gap-3">
                        @foreach([
                            ['Base salary', 78, '#7c3aed'],
                            ['Incentives',  45, '#84cc16'],
                            ['Deductions',  18, '#ec4899'],
                        ] as [$name, $w, $color])
                            <div class="flex items-center gap-3">
                                <span class="bento-pay-label">{{ $name }}</span>
                                <div class="flex-1 h-1.5 rounded-full" style="background:var(--card-border)">
                                    <div class="h-full rounded-full" style="width:{{ $w }}%;background:{{ $color }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Card 3: Recognition --}}
                <div class="bento-card group flex flex-col p-8" style="--accent:#ec4899">
                    <div class="bento-icon-wrap bg-pink-500/15 mb-6">🏆</div>
                    <h3 class="bento-title mb-2">Fair recognition</h3>
                    <p class="bento-body mb-8">Bonuses earned transparently. No black boxes, no surprises. Know exactly how you earned it.</p>
                    <div class="mt-auto bento-award-row">
                        <div class="bento-award-avatar">JR</div>
                        <div>
                            <p class="text-xs font-bold" style="color:var(--text-base)">Jordan R.</p>
                            <p class="bento-pay-label">Top agent · Apr</p>
                        </div>
                        <span class="ml-auto bento-progress-pill" style="color:#f97316;background:rgba(249,115,22,.1)">★ 4.9</span>
                    </div>
                </div>

                {{-- Card 4: Approvals --}}
                <div class="bento-card group flex flex-col p-8" style="--accent:#f97316">
                    <div class="bento-icon-wrap bg-orange-500/15 mb-6">📋</div>
                    <h3 class="bento-title mb-2">Approval flow</h3>
                    <p class="bento-body mb-8">Request edits, corrections, and time-off. Track every approval with clear notes from start to finish.</p>
                    <div class="mt-auto flex flex-wrap items-center gap-1.5">
                        <span class="bento-flow-step bento-flow-done">Submitted</span>
                        <span class="bento-flow-arrow">→</span>
                        <span class="bento-flow-step bento-flow-done">Reviewed</span>
                        <span class="bento-flow-arrow">→</span>
                        <span class="bento-flow-step bento-flow-active">✓ Approved</span>
                    </div>
                </div>

                {{-- Card 5: Comms --}}
                <div class="bento-card group flex flex-col p-8" style="--accent:#0ea5e9">
                    <div class="flex items-center justify-between mb-6">
                        <div class="bento-icon-wrap bg-sky-500/15">💬</div>
                        <span class="bento-notif-badge">3 new</span>
                    </div>
                    <h3 class="bento-title mb-2">Team comms</h3>
                    <p class="bento-body">Open channels that build culture and keep everyone aligned. No lost messages, no silos.</p>
                </div>

                {{-- Card 6: Privacy --}}
                <div class="bento-card group flex flex-col p-8" style="--accent:#14b8a6">
                    <div class="bento-icon-wrap bg-teal-500/15 mb-6">🛡️</div>
                    <h3 class="bento-title mb-2">Privacy &amp; security</h3>
                    <p class="bento-body mb-8">Role-based access ensures only the right people see the right data. Full audit trails, always.</p>
                    <div class="mt-auto flex flex-wrap gap-2">
                        @foreach([
                            ['Role-based access', '#14b8a6'],
                            ['Audit trail',       '#7c3aed'],
                            ['Encrypted at rest', '#84cc16'],
                            ['Min. exposure',     '#ec4899'],
                        ] as [$item, $color])
                            <span class="bento-privacy-pill" style="border-color:{{ $color }}33;color:{{ $color }};background:{{ $color }}12">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         TESTIMONIALS — offset masonry
    ═══════════════════════════════════════ --}}
    <section class="py-28 sm:py-36 px-4 sm:px-6 lg:px-8 lp-alt-section">
        <div class="max-w-6xl mx-auto">
            <div class="mb-14">
                <p class="lp-section-label mb-2">Real teammates</p>
                <h2 class="lp-section-h2">People who <span class="text-gradient-violet">get it now</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="quote-card p-8 md:translate-y-8">
                    <p class="quote-text">"I can see my hours, incentives and payments right away. If something's off, it gets fixed before payroll runs. Love it."</p>
                    <div class="quote-author mt-6">
                        <div class="quote-avatar" style="background:linear-gradient(135deg,#7c3aed,#ec4899)">AM</div>
                        <div>
                            <p class="quote-name">Alex M.</p>
                            <p class="quote-role">Outbound Agent</p>
                        </div>
                    </div>
                </div>

                <div class="quote-card p-8">
                    <p class="quote-text">"Finally understand how my incentives are calculated. No more guessing, no more frustration at payday."</p>
                    <div class="quote-author mt-6">
                        <div class="quote-avatar" style="background:linear-gradient(135deg,#84cc16,#06b6d4)">JR</div>
                        <div>
                            <p class="quote-name">Jordan R.</p>
                            <p class="quote-role">Team Lead</p>
                        </div>
                    </div>
                </div>

                <div class="quote-card p-8 md:translate-y-8">
                    <p class="quote-text">"Approvals used to be a black hole. Now I get notified the second anything changes. Actually transparent."</p>
                    <div class="quote-author mt-6">
                        <div class="quote-avatar" style="background:linear-gradient(135deg,#ec4899,#f97316)">CL</div>
                        <div>
                            <p class="quote-name">Kamil L.</p>
                            <p class="quote-role">QA Analyst</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         FAQ — borderless accordion
    ═══════════════════════════════════════ --}}
    <section class="py-28 sm:py-36 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-14 text-center">
                <p class="lp-section-label mb-2">FAQ</p>
                <h2 class="lp-section-h2">Quick <span class="text-gradient-violet">answers</span></h2>
            </div>
            <div class="divide-y lp-faq-divider">
                @foreach([
                    ['Can I see edits to my downtimes?',          'Yes — all edits and approvals show clear notes so nothing is a mystery. Full history, always.'],
                    ['Who can see my information?',               'Only you and authorized roles. Role-based access keeps your data locked down tight.'],
                    ['What if something is wrong with my pay?',   'Submit a support request right away and get clear next steps. We fix it before payday.'],
                    ['Is my data encrypted?',                     'Absolutely. Data is encrypted at rest and in transit. Privacy is a core design principle.'],
                ] as [$q, $a])
                <details class="group py-6">
                    <summary class="lp-faq-q flex cursor-pointer list-none items-center justify-between">
                        {{ $q }}
                        <span class="lp-faq-plus group-open:rotate-45 transition-transform duration-200">+</span>
                    </summary>
                    <div class="lp-faq-a pt-4">{{ $a }}</div>
                </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         CTA — full-bleed with giant bg word
    ═══════════════════════════════════════ --}}
    <section class="relative overflow-hidden lp-cta-section py-36 px-4 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute inset-0 flex items-center justify-center lp-cta-bg-word select-none">
            <span>NOW</span>
        </div>
        <div class="relative max-w-4xl mx-auto text-center">
            <p class="lp-section-label mb-4">Ready?</p>
            <h2 class="lp-cta-h2 mb-6">
                Your next shift<br>
                <span class="text-gradient-violet">starts here.</span>
            </h2>
            <p class="lp-cta-body mx-auto mb-12 max-w-lg">
                Sign in to review your hours, approvals, and full pay details. Everything you need, nothing you don't.
            </p>
            @auth
                <a href="{{ url($authUrl) }}" class="btn-primary inline-flex items-center gap-3 px-10 py-4 text-lg font-bold">
                    Go to Dashboard <span class="btn-arrow">&rarr;</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-3 px-10 py-4 text-lg font-bold">
                    Sign in now <span class="btn-arrow">&rarr;</span>
                </a>
            @endauth
        </div>
    </section>


@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.hero-kpi-card');

    cards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const mouseX = e.clientX - centerX;
            const mouseY = e.clientY - centerY;

            const rotateX = (mouseY / (rect.height / 2)) * -10; // Adjust sensitivity
            const rotateY = (mouseX / (rect.width / 2)) * 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        card.addEventListener('mouseleave', function() {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
        });
    });
});
</script>
