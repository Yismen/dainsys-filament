<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} - Intranet</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-linear-to-br from-slate-50 via-blue-50 to-slate-50 text-slate-900 antialiased">
        @php
            $panels = \Filament\Facades\Filament::getPanels();
            $panel = collect($panels)->first(function($panel) {
                $user = auth()->user();

                if ($user && method_exists($user, 'canAccessPanel')) {
                    return $user->canAccessPanel($panel);
                }

                return null;
            }) ?? null;

            $authUrl = $panel ? $panel->getUrl() : '/support';
        @endphp
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-linear-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-sm">DS</span>
                        </div>
                        <span class="text-2xl font-bold bg-linear-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent">{{ config('app.name') }}</span>
                    </div>
                    <div class="flex items-center space-x-1 sm:space-x-2">
                        @auth
                            <a href="{{ url($authUrl) }}" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors duration-200">Dashboard</a>
                            {{-- <form method="POST" action="{{ route('logout') }}" class="inline ">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-red-600 transition-colors duration-200 cursor-pointer" >Logout</button>
                            </form> --}}
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors duration-200">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden perspective">
            <!-- Decorative background elements with depth -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Primary blob -->
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <!-- Secondary blob with offset -->
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
                <!-- Additional depth layer -->
                <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-float"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
                <div class="text-center space-y-8">
                    <div class="space-y-4 animate-float">
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tighter">
                            <span class="block text-slate-900">Welcome to</span>
                            <span class="block bg-linear-to-r from-blue-600 via-blue-500 to-indigo-600 bg-clip-text text-transparent drop-shadow-2xl">{{ config('app.name') }}</span>
                        </h1>
                        <p class="text-xl sm:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed drop-shadow-sm">
                            Bringing visibility and transparency to your workforce. Track hours, manage payroll, celebrate achievements, and bring your team together with powerful collaboration tools.
                        </p>
                    </div>
                    @auth
                        <a href="{{ url($authUrl) }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                            Go to Dashboard
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                            Get Started
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-24 sm:py-32 relative" id="features">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center space-y-4 mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-slate-900">Key Features</h2>
                    <p class="text-xl text-slate-600 max-w-2xl mx-auto">Complete visibility and transparency that brings your team together</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1: Hours Tracking -->
                    <div class="group relative bg-white rounded-2xl p-8 shadow-depth-glow hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 overflow-hidden card-3d">
                        <div class="absolute inset-0 bg-linear-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 to-blue-50 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Hours Tracking</h3>
                            <p class="text-slate-600 leading-relaxed">Complete transparency in time tracking. Everyone sees their hours in real-time, ensuring accuracy and trust across the entire team.</p>
                        </div>
                    </div>

                    <!-- Feature 2: Payroll Management -->
                    <div class="group relative bg-white rounded-2xl p-8 shadow-depth-glow hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 overflow-hidden card-3d" style="animation-delay: 0.1s">
                        <div class="absolute inset-0 bg-linear-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 to-blue-50 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Payroll Management</h3>
                            <p class="text-slate-600 leading-relaxed">Crystal-clear payroll processing with full visibility into calculations, deductions, and earnings. Everyone knows exactly how their compensation works.</p>
                        </div>
                    </div>

                    <!-- Feature 3: Incentives & Rewards -->
                    <div class="group relative bg-white rounded-2xl p-8 shadow-depth-glow hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 overflow-hidden card-3d" style="animation-delay: 0.2s">
                        <div class="absolute inset-0 bg-linear-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 to-blue-50 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Incentives & Rewards</h3>
                            <p class="text-slate-600 leading-relaxed">Recognize excellence openly and fairly. Track bonuses and incentives transparently, motivating the entire team through visible recognition.</p>
                        </div>
                    </div>

                    <!-- Feature 4: Production KPIs -->
                    <div class="group relative bg-white rounded-2xl p-8 shadow-depth-glow hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 overflow-hidden card-3d" style="animation-delay: 0.3s">
                        <div class="absolute inset-0 bg-linear-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 to-blue-50 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Production KPIs</h3>
                            <p class="text-slate-600 leading-relaxed">Real-time performance metrics visible to everyone. Track progress together, celebrate wins together, and drive continuous improvement as one team.</p>
                        </div>
                    </div>

                    <!-- Feature 5: Team Communication -->
                    <div class="group relative bg-white rounded-2xl p-8 shadow-depth-glow hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 overflow-hidden card-3d" style="animation-delay: 0.4s">
                        <div class="absolute inset-0 bg-linear-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 to-blue-50 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">Team Communication</h3>
                            <p class="text-slate-600 leading-relaxed">Open channels that bring everyone together. Share updates, celebrate achievements, and build a unified team culture through transparent communication.</p>
                        </div>
                    </div>

                    <!-- Feature 6: IT Support -->
                    <div class="group relative bg-white rounded-2xl p-8 shadow-depth-glow hover:shadow-depth-lg stacked-card transition-all duration-300 border border-slate-100/50 overflow-hidden card-3d" style="animation-delay: 0.5s">
                        <div class="absolute inset-0 bg-linear-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative z-10 space-y-4">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-linear-to-br from-blue-100 to-blue-50 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-300 transform group-hover:scale-110 group-hover:-translate-y-1">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">IT Support</h3>
                            <p class="text-slate-600 leading-relaxed">Reliable technical support when you need it. Get help quickly with dedicated IT assistance to keep your team running smoothly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-24 sm:py-32 bg-linear-to-r from-blue-600 via-blue-700 to-indigo-700 relative overflow-hidden"  id="security">
            <!-- Decorative elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-1/2 -right-1/4 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
                <div class="absolute -bottom-1/2 -left-1/4 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center space-y-3">
                        <div class="text-4xl sm:text-5xl font-bold text-white">100%</div>
                        <p class="text-blue-100 font-medium">Uptime Guaranteed</p>
                    </div>
                    <div class="text-center space-y-3">
                        <div class="text-4xl sm:text-5xl font-bold text-white">24/7</div>
                        <p class="text-blue-100 font-medium">Expert Support</p>
                    </div>
                    <div class="text-center space-y-3">
                        <div class="text-4xl sm:text-5xl font-bold text-white">🔒</div>
                        <p class="text-blue-100 font-medium">Enterprise-Grade</p>
                    </div>
                    <div class="text-center space-y-3">
                        <div class="text-4xl sm:text-5xl font-bold text-white">⚡</div>
                        <p class="text-blue-100 font-medium">Lightning Fast</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 sm:py-32 relative perspective">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative group animate-float-delayed">
                    <div class="absolute -inset-1 bg-linear-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                    <!-- Depth layers for CTA -->
                    <div class="absolute -inset-2 bg-linear-to-br from-blue-200 to-indigo-200 rounded-2xl blur-xl opacity-10 -z-10"></div>
                    <div class="relative bg-white rounded-2xl p-12 sm:p-16 space-y-8 shadow-depth-lg group-hover:shadow-depth card-3d">
                        <div class="text-center space-y-4 animate-float">
                            <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 drop-shadow-sm">Ready to transform your team?</h2>
                            <p class="text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed drop-shadow-sm">
                                Join thousands of organizations already streamlining their workforce management with {{ config('app.name') }}.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            @auth
                                <a href="{{ url($authUrl) }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                                    Access Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-linear-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:shadow-2xl hover:shadow-blue-500/30 transition-all duration-300 transform hover:scale-105">
                                    Sign In Now
                                </a>
                            @endauth
                            {{-- <a href="#" class="inline-flex items-center justify-center px-8 py-4 border-2 border-slate-300 text-slate-900 font-semibold rounded-xl hover:border-slate-400 hover:bg-slate-50 transition-all duration-300">
                                Learn More
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 border-t border-slate-800 relative overflow-hidden">
            <!-- Subtle depth background -->
            <div class="absolute inset-0 bg-linear-to-t from-slate-950 to-transparent opacity-50 pointer-events-none"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Company</h3>
                        <ul class="space-y-3">
                            <li><a href="https://eccocorpbpo.com/" class="text-slate-400 hover:text-white transition-colors duration-200">About</a></li>
                            <li><a href="https://eccocorpbpo.com/apply-now/" class="text-slate-400 hover:text-white transition-colors duration-200">Careers</a></li>
                            <li><a href="https://eccocorpbpo.com/contact-us/" class="text-slate-400 hover:text-white transition-colors duration-200">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Product</h3>
                        <ul class="space-y-3">
                            <li><a href="#features" class="text-slate-400 hover:text-white transition-colors duration-200">Features</a></li>
                            {{-- <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200">Pricing</a></li> --}}
                            <li><a href="#security" class="text-slate-400 hover:text-white transition-colors duration-200">Security</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Resources</h3>
                        <ul class="space-y-3">
                            <li><a href="/docs/api" target="docs-api" class="text-slate-400 hover:text-white transition-colors duration-200">Documentation</a></li>
                            <li><a href="/support" class="text-slate-400 hover:text-white transition-colors duration-200">Support</a></li>
                            {{-- <li><a href="#" class="text-slate-400 hover:text-white transition-colors duration-200">Blog</a></li> --}}
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Legal</h3>
                        <ul class="space-y-3">
                            <li><a href="/privacy" class="text-slate-400 hover:text-white transition-colors duration-200">Privacy</a></li>
                            <li><a href="/terms" class="text-slate-400 hover:text-white transition-colors duration-200">Terms</a></li>
                            <li><a href="/cookies" class="text-slate-400 hover:text-white transition-colors duration-200">Cookies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row gap-4 items-center justify-between text-sm text-slate-400">
                    <p>&copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
                    {{-- <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} | PHP v{{ PHP_VERSION }}</p> --}}
                </div>
            </div>
        </footer>
    </body>
</html>
