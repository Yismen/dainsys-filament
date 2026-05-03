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

    <!-- HERO -->
    <section class="relative min-h-screen flex flex-col justify-center overflow-hidden px-4 sm:px-6 lg:px-8">
        <!-- Ambient glows -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 left-1/4 h-[520px] w-[520px] rounded-full bg-violet-700/25 blur-[120px]"></div>
            <div class="absolute bottom-0 right-1/4 h-[400px] w-[400px] rounded-full bg-pink-700/20 blur-[100px]"></div>
            <div class="absolute top-1/2 left-0 h-[300px] w-[300px] -translate-y-1/2 rounded-full bg-lime-600/10 blur-[80px]"></div>
        </div>

        <!-- Decorative floating shapes -->
        <div class="pointer-events-none absolute right-8 top-24 h-16 w-16 rounded-2xl bg-violet-500/20 rotate-12 float-slow hidden lg:block"></div>
        <div class="pointer-events-none absolute left-12 bottom-32 h-10 w-10 rounded-full bg-lime-400/30 float-slow hidden lg:block" style="animation-delay:.8s"></div>
        <div class="pointer-events-none absolute right-24 bottom-48 h-8 w-8 rounded-full bg-pink-400/25 wiggle hidden lg:block"></div>

        <div class="relative max-w-7xl mx-auto w-full py-28 sm:py-36">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Left: copy -->
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 rounded-full border border-violet-500/30 bg-violet-500/10 px-4 py-1.5 text-sm font-semibold text-violet-300">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-violet-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-violet-500"></span>
                        </span>
                        Your intranet is live 🎉
                    </div>

                    <div class="space-y-2">
                        <h1 class="text-6xl sm:text-7xl lg:text-8xl font-black leading-[0.9] tracking-tighter">
                            <span class="block text-white">Your pay.</span>
                            <span class="block text-gradient-violet">Your hours.</span>
                            <span class="block text-white">Your wins.</span>
                        </h1>
                    </div>

                    <p class="text-lg text-white/55 max-w-lg leading-relaxed">
                        {{ config('app.name') }} is built for the team — see your payroll breakdown, track hours in real-time, and understand every incentive before payday. No guessing.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        @auth
                            <a href="{{ url($authUrl) }}" class="btn-primary inline-flex items-center gap-2.5 px-8 py-3.5 text-base font-bold">
                                Go to Dashboard
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-2.5 px-8 py-3.5 text-base font-bold">
                                Get Started ✨
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                            <a href="/#features" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-semibold text-white/60 hover:text-white transition-colors rounded-full border border-white/15 hover:border-white/30">
                                See features
                            </a>
                        @endauth
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <span class="text-xs font-semibold text-white/40">✅ Real-time hours</span>
                        <span class="text-xs font-semibold text-white/40">💸 Pay clarity</span>
                        <span class="text-xs font-semibold text-white/40">🛡️ Role-based privacy</span>
                    </div>
                </div>

                <!-- Right: stat cards -->
                <div class="relative hidden lg:flex items-center justify-center h-[480px]">
                    <div class="card-glass absolute top-0 left-6 right-16 rounded-2xl p-5 float-slow shadow-2xl shadow-violet-900/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">This period</p>
                                <p class="mt-1 text-3xl font-black text-white">124.5 <span class="text-sm font-semibold text-white/40">hrs</span></p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/20 text-2xl">⏱️</div>
                        </div>
                        <div class="mt-4 h-1.5 w-full rounded-full bg-white/10">
                            <div class="h-full w-2/3 rounded-full bg-gradient-to-r from-violet-500 to-pink-500"></div>
                        </div>
                        <p class="mt-1 text-xs text-white/30">67% of target reached</p>
                    </div>

                    <div class="card-glass absolute top-24 left-16 right-0 rounded-2xl p-5 float-slow shadow-2xl shadow-pink-900/20" style="animation-delay:.6s">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Payroll run</p>
                                <p class="mt-1 text-3xl font-black text-white">$86,420</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-lime-500/20 text-2xl">💰</div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-lime-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            Processing · 64% complete
                        </div>
                    </div>

                    <div class="card-glass absolute top-48 left-10 right-10 rounded-2xl p-5 float-slow shadow-2xl" style="animation-delay:1.1s">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-white/40 uppercase tracking-wider">Team wins this month</p>
                                <p class="mt-1 text-3xl font-black text-gradient-lime">+18%</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-pink-500/20 text-2xl">🏆</div>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <div class="rounded-lg bg-white/5 py-1.5 text-center text-xs font-semibold text-white/50">Visibility</div>
                            <div class="rounded-lg bg-white/5 py-1.5 text-center text-xs font-semibold text-white/50">Trust</div>
                            <div class="rounded-lg bg-violet-500/20 py-1.5 text-center text-xs font-bold text-violet-300">Momentum</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
            <span class="text-xs text-white/20 font-medium tracking-widest uppercase">Scroll</span>
            <div class="h-8 w-0.5 rounded-full bg-gradient-to-b from-violet-500/60 to-transparent"></div>
        </div>
    </section>

    <!-- MARQUEE STRIP -->
    <div class="overflow-hidden border-y marquee-strip py-4" style="background:var(--strip-bg);border-color:var(--strip-border)">
        <div class="marquee-track flex gap-12 whitespace-nowrap text-sm font-bold text-white/25 uppercase tracking-widest">
            @foreach(range(1,2) as $_)
                <span>⚡ Hours Tracking</span>
                <span>•</span>
                <span>💸 Pay Transparency</span>
                <span>•</span>
                <span>🎯 Real-time KPIs</span>
                <span>•</span>
                <span>🔒 Privacy First</span>
                <span>•</span>
                <span>💬 Team Communication</span>
                <span>•</span>
                <span>🛠️ IT Support</span>
                <span>•</span>
                <span>🏆 Recognition</span>
                <span>•</span>
                <span>📊 Approvals</span>
                <span>•</span>
            @endforeach
        </div>
    </div>

    <!-- TRUST STRIP -->
    <section class="py-20 sm:py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="card-glass rounded-2xl p-7 hover:border-violet-500/30 transition-all duration-300">
                    <div class="mb-4 text-3xl">📋</div>
                    <h3 class="text-lg font-bold text-white mb-2">Payroll clarity</h3>
                    <p class="text-sm text-white/45 leading-relaxed">See exactly how every hour, rate, and incentive feeds into your paycheck. Zero surprises.</p>
                </div>
                <div class="card-glass rounded-2xl p-7 hover:border-lime-500/30 transition-all duration-300">
                    <div class="mb-4 text-3xl">👀</div>
                    <h3 class="text-lg font-bold text-white mb-2">Wide visibility</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Approvals and edits are visible across the team — keeping trust intact and drama out.</p>
                </div>
                <div class="card-glass rounded-2xl p-7 hover:border-pink-500/30 transition-all duration-300">
                    <div class="mb-4 text-3xl">🛡️</div>
                    <h3 class="text-lg font-bold text-white mb-2">Privacy first</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Role-based access and audit trails mean your data is only seen by those who need to.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="py-24 sm:py-32 px-4 sm:px-6 lg:px-8 relative" id="features">
        <div class="pointer-events-none absolute top-1/2 left-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-700/10 blur-[120px]"></div>
        <div class="relative max-w-7xl mx-auto">
            <div class="mb-16 text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-violet-400 mb-3">Everything you need</p>
                <h2 class="text-5xl sm:text-6xl font-black text-white">Features for <span class="text-gradient-violet">you</span></h2>
                <p class="mt-4 text-base text-white/40 max-w-xl mx-auto">Complete visibility and transparency that brings our team together.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="card-glass group rounded-2xl p-7 hover:border-violet-500/40 transition-all duration-300 hover:-translate-y-1">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/15 text-2xl group-hover:scale-110 transition-transform duration-300">⏱️</div>
                    <h3 class="text-lg font-bold text-white mb-2">Hours tracking</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Real-time time tracking with full transparency. Your hours, your records, always accurate.</p>
                    <div class="mt-5 h-0.5 w-0 bg-gradient-to-r from-violet-500 to-pink-500 group-hover:w-full transition-all duration-500 rounded-full"></div>
                </div>

                <div class="card-glass group rounded-2xl p-7 hover:border-lime-500/40 transition-all duration-300 hover:-translate-y-1" style="transition-delay:.05s">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-lime-500/15 text-2xl group-hover:scale-110 transition-transform duration-300">💸</div>
                    <h3 class="text-lg font-bold text-white mb-2">Pay transparency</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Crystal-clear payroll calculations. Know your payment, deductions, and earnings ahead of time.</p>
                    <div class="mt-5 h-0.5 w-0 bg-gradient-to-r from-lime-500 to-teal-500 group-hover:w-full transition-all duration-500 rounded-full"></div>
                </div>

                <div class="card-glass group rounded-2xl p-7 hover:border-pink-500/40 transition-all duration-300 hover:-translate-y-1" style="transition-delay:.1s">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-pink-500/15 text-2xl group-hover:scale-110 transition-transform duration-300">🏆</div>
                    <h3 class="text-lg font-bold text-white mb-2">Recognition that's fair</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Bonuses and incentives tracked transparently. Understand exactly why you won.</p>
                    <div class="mt-5 h-0.5 w-0 bg-gradient-to-r from-pink-500 to-red-500 group-hover:w-full transition-all duration-500 rounded-full"></div>
                </div>

                <div class="card-glass group rounded-2xl p-7 hover:border-orange-500/40 transition-all duration-300 hover:-translate-y-1" style="transition-delay:.15s">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-orange-500/15 text-2xl group-hover:scale-110 transition-transform duration-300">📈</div>
                    <h3 class="text-lg font-bold text-white mb-2">Progress you can see</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Clear goals and KPIs so you always know where you stand. No more flying blind.</p>
                    <div class="mt-5 h-0.5 w-0 bg-gradient-to-r from-orange-500 to-yellow-500 group-hover:w-full transition-all duration-500 rounded-full"></div>
                </div>

                <div class="card-glass group rounded-2xl p-7 hover:border-sky-500/40 transition-all duration-300 hover:-translate-y-1" style="transition-delay:.2s">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/15 text-2xl group-hover:scale-110 transition-transform duration-300">💬</div>
                    <h3 class="text-lg font-bold text-white mb-2">Team communication</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Open channels that bring everyone together. Celebrate wins, share updates, build culture.</p>
                    <div class="mt-5 h-0.5 w-0 bg-gradient-to-r from-sky-500 to-blue-500 group-hover:w-full transition-all duration-500 rounded-full"></div>
                </div>

                <div class="card-glass group rounded-2xl p-7 hover:border-teal-500/40 transition-all duration-300 hover:-translate-y-1" style="transition-delay:.25s">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-teal-500/15 text-2xl group-hover:scale-110 transition-transform duration-300">🛠️</div>
                    <h3 class="text-lg font-bold text-white mb-2">Help when you need it</h3>
                    <p class="text-sm text-white/45 leading-relaxed">Dedicated IT support to keep you running. Submit a ticket, get a fix, move on.</p>
                    <div class="mt-5 h-0.5 w-0 bg-gradient-to-r from-teal-500 to-green-500 group-hover:w-full transition-all duration-500 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="py-24 sm:py-32 px-4 sm:px-6 lg:px-8 relative overflow-hidden" id="security" style="background:var(--bg-surface)">
        <div class="absolute inset-0 bg-gradient-to-br from-violet-900/30 via-transparent to-pink-900/20"></div>
        <div class="absolute inset-0 border-y border-white/10"></div>
        <div class="relative max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-5xl sm:text-6xl font-black text-white">Numbers <span class="text-gradient-lime">that hit</span></h2>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="card-glass rounded-2xl p-8 text-center hover:border-violet-500/40 transition-all duration-300">
                    <div class="text-5xl font-black text-gradient-violet mb-2">99.9%</div>
                    <p class="text-sm font-semibold text-white/40">Payroll accuracy</p>
                </div>
                <div class="card-glass rounded-2xl p-8 text-center hover:border-lime-500/40 transition-all duration-300">
                    <div class="text-5xl font-black text-gradient-lime mb-2">2 min</div>
                    <p class="text-sm font-semibold text-white/40">Approval turnaround</p>
                </div>
                <div class="card-glass rounded-2xl p-8 text-center hover:border-pink-500/40 transition-all duration-300">
                    <div class="text-5xl font-black mb-2" style="background:linear-gradient(135deg,#f97316,#ec4899);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">24/7</div>
                    <p class="text-sm font-semibold text-white/40">Employee support</p>
                </div>
                <div class="card-glass rounded-2xl p-8 text-center hover:border-sky-500/40 transition-all duration-300">
                    <div class="text-5xl font-black mb-2">🔒</div>
                    <p class="text-sm font-semibold text-white/40">Data privacy</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="py-24 sm:py-32 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <p class="text-xs font-bold uppercase tracking-widest text-pink-400 mb-3">Real teammates</p>
                <h2 class="text-5xl sm:text-6xl font-black text-white">Feel the <span class="text-gradient-violet">difference</span></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card-glass rounded-2xl p-8 hover:border-violet-500/30 transition-all duration-300">
                    <div class="text-3xl mb-5">💬</div>
                    <p class="text-base text-white/70 leading-relaxed italic">"I can see my hours, incentives and payments right away. If something's off, it gets fixed before payroll runs. Love it."</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-violet-500 to-pink-500 flex items-center justify-center text-sm font-bold">AM</div>
                        <div>
                            <p class="font-bold text-white text-sm">Alex M.</p>
                            <p class="text-xs text-white/35">Outbound Agent</p>
                        </div>
                    </div>
                </div>
                <div class="card-glass rounded-2xl p-8 hover:border-lime-500/30 transition-all duration-300">
                    <div class="text-3xl mb-5">🙌</div>
                    <p class="text-base text-white/70 leading-relaxed italic">"The payment breakdown finally makes sense. I know exactly how incentives are calculated — no more guessing."</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-lime-500 to-teal-500 flex items-center justify-center text-sm font-bold">JR</div>
                        <div>
                            <p class="font-bold text-white text-sm">Jordan R.</p>
                            <p class="text-xs text-white/35">Team Lead</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-24 sm:py-32 px-4 sm:px-6 lg:px-8 relative">
        <div class="pointer-events-none absolute left-0 top-1/2 h-96 w-96 -translate-y-1/2 rounded-full bg-violet-700/10 blur-[80px]"></div>
        <div class="relative max-w-3xl mx-auto">
            <div class="text-center mb-14">
                <p class="text-xs font-bold uppercase tracking-widest text-violet-400 mb-3">Quick answers</p>
                <h2 class="text-5xl sm:text-6xl font-black text-white">Got <span class="text-gradient-violet">questions?</span></h2>
            </div>
            <div class="space-y-3">
                <details class="group card-glass rounded-2xl overflow-hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between px-6 py-5 text-base font-bold text-white hover:text-violet-300 transition-colors">
                        Can I see edits to my downtimes?
                        <span class="text-white/30 group-open:rotate-45 transition-transform duration-200 text-xl font-light">+</span>
                    </summary>
                    <div class="px-6 pb-5 text-sm text-white/45 leading-relaxed border-t border-white/10 pt-4">
                        Yes! All edits and approvals are visible with clear notes so nothing is a mystery.
                    </div>
                </details>
                <details class="group card-glass rounded-2xl overflow-hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between px-6 py-5 text-base font-bold text-white hover:text-violet-300 transition-colors">
                        Who can see my information?
                        <span class="text-white/30 group-open:rotate-45 transition-transform duration-200 text-xl font-light">+</span>
                    </summary>
                    <div class="px-6 pb-5 text-sm text-white/45 leading-relaxed border-t border-white/10 pt-4">
                        Only you and authorized roles can access your personal details. Role-based access keeps everything locked down.
                    </div>
                </details>
                <details class="group card-glass rounded-2xl overflow-hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between px-6 py-5 text-base font-bold text-white hover:text-violet-300 transition-colors">
                        What if something is wrong with my pay?
                        <span class="text-white/30 group-open:rotate-45 transition-transform duration-200 text-xl font-light">+</span>
                    </summary>
                    <div class="px-6 pb-5 text-sm text-white/45 leading-relaxed border-t border-white/10 pt-4">
                        Submit a support request ASAP and you'll get clear next steps. We fix it before payday — that's the goal.
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 sm:py-32 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="relative rounded-3xl overflow-hidden p-[1px] bg-gradient-to-br from-violet-500 via-pink-500 to-orange-400 glow-violet">
                <div class="rounded-3xl cta-inner px-10 py-16 sm:px-16 text-center relative">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-violet-600/15 via-transparent to-pink-600/10"></div>
                    <div class="relative">
                        <div class="mb-6 text-5xl wiggle inline-block">🚀</div>
                        <h2 class="text-4xl sm:text-6xl font-black text-white mb-4">Ready for your next shift?</h2>
                        <p class="text-base text-white/45 max-w-xl mx-auto mb-10 leading-relaxed">
                            Sign in to review your hours, approvals, and full pay details — all in one place.
                        </p>
                        @auth
                            <a href="{{ url($authUrl) }}" class="btn-primary inline-flex items-center gap-2.5 px-10 py-4 text-base font-bold">
                                Access Dashboard 🎉
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary inline-flex items-center gap-2.5 px-10 py-4 text-base font-bold">
                                Sign In Now ✨
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
