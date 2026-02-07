<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cookie Policy - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-linear-to-br from-slate-50 via-blue-50 to-slate-50 text-slate-900 antialiased">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-linear-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-sm">DS</span>
                        </div>
                        <a href="/" class="text-2xl font-bold bg-linear-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent hover:opacity-80 transition-opacity">{{ config('app.name') }}</a>
                    </div>
                    <a href="/" class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors duration-200">Back to Home</a>
                </div>
            </div>
        </nav>

        <!-- Policy Content -->
        <section class="py-24 sm:py-32 relative">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-5xl font-bold text-slate-900 mb-8">Cookie Policy</h1>
                <p class="text-lg text-slate-600 mb-8">Last updated: {{ now()->format('F d, Y') }}</p>

                <div class="space-y-8 text-slate-600">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">What Are Cookies?</h2>
                        <p>Cookies are small files of letters and numbers that we store on your browser or the hard drive of your computer. They contain information about your browsing habits on the application.</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">How We Use Cookies</h2>
                        <p class="mb-4">We use cookies to:</p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Remember your preferences and settings</li>
                            <li>Understand how you use the application</li>
                            <li>Improve your user experience</li>
                            <li>Analyze application performance and usage patterns</li>
                            <li>Provide targeted content and advertising</li>
                            <li>Maintain session information</li>
                            <li>Enhance security</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Types of Cookies We Use</h2>

                        <div class="mt-4 mb-4">
                            <h3 class="text-2xl font-semibold text-slate-800 mb-2">Essential Cookies</h3>
                            <p>These cookies are necessary for the operation of the application. They include session cookies and authentication cookies.</p>
                        </div>

                        <div class="mt-4 mb-4">
                            <h3 class="text-2xl font-semibold text-slate-800 mb-2">Performance Cookies</h3>
                            <p>These cookies help us understand how users interact with the application by collecting and reporting information anonymously.</p>
                        </div>

                        <div class="mt-4 mb-4">
                            <h3 class="text-2xl font-semibold text-slate-800 mb-2">Preference Cookies</h3>
                            <p>These cookies remember your choices to personalize your experience when you visit the application.</p>
                        </div>

                        <div class="mt-4 mb-4">
                            <h3 class="text-2xl font-semibold text-slate-800 mb-2">Marketing Cookies</h3>
                            <p>These cookies may be set through the application by our advertising partners to build a profile of your interests and show you relevant advertisements.</p>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Managing Cookies</h2>
                        <p class="mb-4">Most web browsers allow you to control cookies through their settings preferences. However, if you delete all cookies, some features of the application may not function properly.</p>
                        <p>You can typically find cookie settings in:</p>
                        <ul class="list-disc list-inside space-y-2 mt-2">
                            <li>Chrome: Settings > Privacy and security > Cookies and other site data</li>
                            <li>Firefox: Options > Privacy & Security > Cookies and Site Data</li>
                            <li>Safari: Preferences > Privacy > Cookies and website data</li>
                            <li>Edge: Settings > Privacy, search, and services > Cookies and other site data</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Third-Party Cookies</h2>
                        <p>Some cookies may be set by third-party service providers for analytics, advertising, and other purposes. We encourage you to review their privacy policies for more information.</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Contact Us</h2>
                        <p class="mb-4">If you have any questions about our use of cookies, please contact us at:</p>
                        <p>
                            <strong>{{ config('app.name') }}</strong><br>
                            Email: {{ config('app.contact_email') }}<br>
                            <a href="/" class="text-blue-600 hover:text-blue-700">Back to {{ config('app.name') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 border-t border-slate-800 relative overflow-hidden mt-12">
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
                            <li><a href="/#features" class="text-slate-400 hover:text-white transition-colors duration-200">Features</a></li>
                            <li><a href="/#security" class="text-slate-400 hover:text-white transition-colors duration-200">Security</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-4">Resources</h3>
                        <ul class="space-y-3">
                            <li><a href="/api/docs" class="text-slate-400 hover:text-white transition-colors duration-200">Documentation</a></li>
                            <li><a href="/support" class="text-slate-400 hover:text-white transition-colors duration-200">Support</a></li>
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
                    <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} | PHP v{{ PHP_VERSION }}</p>
                </div>
            </div>
        </footer>
    </body>
</html>
