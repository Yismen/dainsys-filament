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
    <!-- Hero Section -->
    <section class="relative overflow-hidden perspective">
        <!-- Decorative background elements with depth -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Primary blob -->
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <!-- Secondary blob with offset -->
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-200 dark:bg-indigo-900 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <!-- Additional depth layer -->
            <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-blue-300 dark:bg-blue-800 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-8 text-center lg:text-left">
                    <div class="space-y-4 animate-float">
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tighter">
                            {{-- <span class="block text-slate-900 dark:text-slate-100">Welcome, Ecco Outsourcing Group team.</span>
                            <span class="block text-slate-900 dark:text-slate-100">Your time, your pay, your wins.</span> --}}
                            <span class="block bg-linear-to-r from-blue-600 via-blue-500 to-indigo-600 dark:from-blue-400 dark:via-blue-300 dark:to-indigo-400 bg-clip-text text-transparent drop-shadow-2xl">{{ config('app.name') }}</span>
                        </h1>
                        <p class="text-xl sm:text-2xl text-slate-600 dark:text-slate-400 max-w-3xl lg:max-w-none mx-auto lg:mx-0 leading-relaxed drop-shadow-sm">
                            Built for you. Track your payroll hours, review incentives and commissions, and understand every line of your pay before payday.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @auth
                            <a href="{{ url($authUrl) }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                                Go to Dashboard
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                                Get Started
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        @endauth
                    </div>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6 text-sm text-slate-500 dark:text-slate-400">
                        <div class="inline-flex items-center gap-2">
                            <span class="inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                            Clear pay breakdowns
                        </div>
                        <div class="inline-flex items-center gap-2">
                            <span class="inline-flex h-2 w-2 rounded-full bg-indigo-500"></span>
                            Approval turnaround
                        </div>
                        <div class="inline-flex items-center gap-2">
                            <span class="inline-flex h-2 w-2 rounded-full bg-sky-500"></span>
                            Privacy standards
                        </div>
                    </div>
                </div>
                <div class="relative hidden lg:block">
                    <div class="absolute -inset-6 bg-linear-to-br from-blue-200/40 via-indigo-200/20 to-transparent dark:from-blue-800/30 dark:via-indigo-800/20 blur-3xl opacity-70"></div>
                    <div class="relative h-[420px] w-full max-w-lg mx-auto preserve-3d">
                        <div class="absolute left-0 right-6 top-0 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-depth-glow dark:shadow-slate-900 border border-slate-100/60 dark:border-slate-700/60 card-3d animate-float" style="transform: rotateX(8deg) rotateY(-12deg) translateZ(20px);">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Today</p>
                                    <p class="text-2xl font-semibold text-slate-900 dark:text-slate-100">124.5 hours</p>
                                </div>
                                <div class="h-12 w-12 rounded-xl bg-linear-to-br from-blue-100 dark:from-blue-900 to-blue-50 dark:to-blue-800 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 h-2 w-full rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                <div class="h-full w-2/3 bg-linear-to-r from-blue-500 to-indigo-500"></div>
                            </div>
                        </div>
                        <div class="absolute left-6 right-0 top-20 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-depth-glow dark:shadow-slate-900 border border-slate-100/60 dark:border-slate-700/60 card-3d animate-float-delayed" style="transform: rotateX(-6deg) rotateY(10deg) translateZ(40px);">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Payroll Run</p>
                                    <p class="text-2xl font-semibold text-slate-900 dark:text-slate-100">$86,420</p>
                                </div>
                                <div class="h-12 w-12 rounded-xl bg-linear-to-br from-indigo-100 dark:from-indigo-900 to-indigo-50 dark:to-indigo-800 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                                <span>Processing</span>
                                <span class="text-slate-700 dark:text-slate-200">64%</span>
                            </div>
                        </div>
                        <div class="absolute left-10 right-0 top-40 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-depth-glow dark:shadow-slate-900 border border-slate-100/60 dark:border-slate-700/60 card-3d" style="transform: rotateX(2deg) rotateY(-6deg) translateZ(60px);">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Team Wins</p>
                                    <p class="text-2xl font-semibold text-slate-900 dark:text-slate-100">+18%</p>
                                </div>
                                <div class="h-12 w-12 rounded-xl bg-linear-to-br from-sky-100 dark:from-sky-900 to-sky-50 dark:to-sky-800 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 grid grid-cols-3 gap-3 text-xs text-slate-500 dark:text-slate-400">
                                <div class="rounded-lg bg-slate-50 dark:bg-slate-700/60 px-3 py-2 text-center">Visibility</div>
                                <div class="rounded-lg bg-slate-50 dark:bg-slate-700/60 px-3 py-2 text-center">Trust</div>
                                <div class="rounded-lg bg-slate-50 dark:bg-slate-700/60 px-3 py-2 text-center">Momentum</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Strip -->
    <section class="py-16 sm:py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="rounded-2xl bg-white/80 dark:bg-slate-800/70 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Payroll clarity</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">See how every hour, rate, and incentive is calculated for your payroll.</p>
                </div>
                <div class="rounded-2xl bg-white/80 dark:bg-slate-800/70 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Wide visibility</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Approvals and edits are visible across your team, keeping trust intact.</p>
                </div>
                <div class="rounded-2xl bg-white/80 dark:bg-slate-800/70 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Privacy first</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Your data stays protected with role-based access and audit trails.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    {{-- <section class="py-24 sm:py-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 dark:text-slate-100">How it works at Ecco</h2>
                <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Simple steps for Ecco Outsourcing Group employees to keep time and pay aligned.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">Step 1</div>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900 dark:text-slate-100">Clock in for your Ecco shift</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Clock in and out from any device with clear records.</p>
                </div>
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">Step 2</div>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900 dark:text-slate-100">Review your Ecco day</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Check hours, notes, and approvals before they close.</p>
                </div>
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">Step 3</div>
                    <h3 class="mt-3 text-xl font-semibold text-slate-900 dark:text-slate-100">Confirm your Ecco pay</h3>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">See pay, bonuses, and deductions before payday.</p>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Features Section -->
    <section class="py-24 sm:py-32 relative" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 dark:text-slate-100">Features for you</h2>
                <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Complete visibility and transparency that brings our team together.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1: Hours Tracking -->
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-depth-glow dark:shadow-slate-900 hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 dark:border-slate-700/50 overflow-hidden card-3d">
                    <div class="absolute inset-0 bg-linear-to-br from-blue-50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 dark:from-blue-900 to-blue-50 dark:to-blue-800 group-hover:from-blue-200 dark:group-hover:from-blue-800 group-hover:to-blue-100 dark:group-hover:to-blue-700 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Hours tracking</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Complete transparency in time tracking. See your hours in real-time, ensuring accuracy and trust.</p>
                    </div>
                </div>

                <!-- Feature 2: Payroll Management -->
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-depth-glow dark:shadow-slate-900 hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 dark:border-slate-700/50 overflow-hidden card-3d" style="animation-delay: 0.1s">
                    <div class="absolute inset-0 bg-linear-to-br from-blue-50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 dark:from-blue-900 to-blue-50 dark:to-blue-800 group-hover:from-blue-200 dark:group-hover:from-blue-800 group-hover:to-blue-100 dark:group-hover:to-blue-700 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Pay transparency</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Crystal-clear payroll processing with full visibility into calculations, deductions, and earnings. Know your payment ahead of time.</p>
                    </div>
                </div>

                <!-- Feature 3: Incentives & Rewards -->
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-depth-glow dark:shadow-slate-900 hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 dark:border-slate-700/50 overflow-hidden card-3d" style="animation-delay: 0.2s">
                    <div class="absolute inset-0 bg-linear-to-br from-blue-50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 dark:from-blue-900 to-blue-50 dark:to-blue-800 group-hover:from-blue-200 dark:group-hover:from-blue-800 group-hover:to-blue-100 dark:group-hover:to-blue-700 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Recognition that feels fair</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Track bonuses and incentives transparently. Understand why incentives and wins were awarded.</p>
                    </div>
                </div>

                <!-- Feature 4: Production KPIs -->
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-depth-glow dark:shadow-slate-900 hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 dark:border-slate-700/50 overflow-hidden card-3d" style="animation-delay: 0.3s">
                    <div class="absolute inset-0 bg-linear-to-br from-blue-50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 dark:from-blue-900 to-blue-50 dark:to-blue-800 group-hover:from-blue-200 dark:group-hover:from-blue-800 group-hover:to-blue-100 dark:group-hover:to-blue-700 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Progress you can see</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Know where you stand with clear goals and metrics.</p>
                    </div>
                </div>

                <!-- Feature 5: Team Communication -->
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-depth-glow dark:shadow-slate-900 hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 dark:border-slate-700/50 overflow-hidden card-3d" style="animation-delay: 0.4s">
                    <div class="absolute inset-0 bg-linear-to-br from-blue-50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 dark:from-blue-900 to-blue-50 dark:to-blue-800 group-hover:from-blue-200 dark:group-hover:from-blue-800 group-hover:to-blue-100 dark:group-hover:to-blue-700 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Team Communication</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Open channels that bring everyone together. Share updates, celebrate achievements, and build a unified team culture through transparent communication.</p>
                    </div>
                </div>

                <!-- Feature 6: IT Support -->
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-depth-glow dark:shadow-slate-900 hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 dark:border-slate-700/50 overflow-hidden card-3d" style="animation-delay: 0.5s">
                    <div class="absolute inset-0 bg-linear-to-br from-blue-50 dark:from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 dark:from-blue-900 to-blue-50 dark:to-blue-800 group-hover:from-blue-200 dark:group-hover:from-blue-800 group-hover:to-blue-100 dark:group-hover:to-blue-700 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Help when you need it</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Reliable technical support when you need it. Get help quickly with dedicated IT assistance to keep your team running smoothly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Employee Story -->
    <section class="py-24 sm:py-32 relative">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 dark:text-slate-100">Feel the difference</h2>
                <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Real words from Ecco Outsourcing Group teammates.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <p class="text-lg text-slate-700 dark:text-slate-200 leading-relaxed">"I can see my hours, incentives and payments right away. If something is off, it gets fixed before payroll runs."</p>
                    <div class="mt-6">
                        <p class="font-semibold text-slate-900 dark:text-slate-100">Alex M.</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Outbound Agent</p>
                    </div>
                </div>
                <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-8 shadow-depth-glow dark:shadow-slate-900">
                    <p class="text-lg text-slate-700 dark:text-slate-200 leading-relaxed">"The payment breakdown makes sense now. I know exactly how incentives are calculated."</p>
                    <div class="mt-6">
                        <p class="font-semibold text-slate-900 dark:text-slate-100">Jordan R.</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Team Lead</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-24 sm:py-32 bg-linear-to-r from-blue-600 via-blue-700 to-indigo-700 dark:from-blue-900 dark:via-blue-800 dark:to-indigo-900 relative overflow-hidden"  id="security">
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/4 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
            <div class="absolute -bottom-1/2 -left-1/4 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center space-y-3">
                    <div class="text-4xl sm:text-5xl font-bold text-white">99.9%</div>
                    <p class="text-blue-100 font-medium">Payroll accuracy</p>
                </div>
                <div class="text-center space-y-3">
                    <div class="text-4xl sm:text-5xl font-bold text-white">2 min</div>
                    <p class="text-blue-100 font-medium">Approval turnaround</p>
                </div>
                <div class="text-center space-y-3">
                    <div class="text-4xl sm:text-5xl font-bold text-white">24/7</div>
                    <p class="text-blue-100 font-medium">Employee support</p>
                </div>
                <div class="text-center space-y-3">
                    <div class="text-4xl sm:text-5xl font-bold text-white">🔒</div>
                    <p class="text-blue-100 font-medium">Data privacy</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-24 sm:py-32 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center space-y-4 mb-16">
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 dark:text-slate-100">Questions Ecco employees ask</h2>
                <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Quick answers for teammates.</p>
            </div>
            <div class="space-y-4">
                <details class="group rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-6 shadow-depth-glow dark:shadow-slate-900">
                    <summary class="flex cursor-pointer list-none items-center justify-between text-lg font-semibold text-slate-900 dark:text-slate-100">
                        Can I see edits to my downtimes?
                        <span class="text-slate-400 group-open:rotate-180 transition-transform">+</span>
                    </summary>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Yes. Edits and approvals are visible with clear notes.</p>
                </details>
                <details class="group rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-6 shadow-depth-glow dark:shadow-slate-900">
                    <summary class="flex cursor-pointer list-none items-center justify-between text-lg font-semibold text-slate-900 dark:text-slate-100">
                        Who can see my information?
                        <span class="text-slate-400 group-open:rotate-180 transition-transform">+</span>
                    </summary>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Only you and authorized roles can access your details.</p>
                </details>
                <details class="group rounded-2xl bg-white dark:bg-slate-800 border border-slate-100/60 dark:border-slate-700/60 p-6 shadow-depth-glow dark:shadow-slate-900">
                    <summary class="flex cursor-pointer list-none items-center justify-between text-lg font-semibold text-slate-900 dark:text-slate-100">
                        What if something is wrong with my pay?
                        <span class="text-slate-400 group-open:rotate-180 transition-transform">+</span>
                    </summary>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">Submit a request ASAP and get next steps.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 sm:py-32 relative perspective">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative group animate-float-delayed">
                <div class="absolute -inset-1 bg-linear-to-r from-blue-600 to-indigo-600 dark:from-blue-500 dark:to-indigo-500 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                <!-- Depth layers for CTA -->
                <div class="absolute -inset-2 bg-linear-to-br from-blue-200 dark:from-blue-800 to-indigo-200 dark:to-indigo-800 rounded-2xl blur-xl opacity-10 -z-10"></div>
                <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-12 sm:p-16 space-y-8 shadow-depth-lg dark:shadow-slate-900 group-hover:shadow-depth card-3d">
                    <div class="text-center space-y-4 animate-float">
                        <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 dark:text-slate-100 drop-shadow-sm">Ready for your next shift?</h2>
                        <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed drop-shadow-sm">
                            This is the employee's hub in {{ config('app.name') }}. Sign in to review your hours, approvals, and pay details.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            <a href="{{ url($authUrl) }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                                Access Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                                Sign In Now
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
