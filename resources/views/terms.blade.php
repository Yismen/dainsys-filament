<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Terms of Service - {{ config('app.name') }}</title>
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
                <h1 class="text-5xl font-bold text-slate-900 mb-8">Terms of Service</h1>
                <p class="text-lg text-slate-600 mb-8">Last updated: {{ now()->format('F d, Y') }}</p>

                <div class="space-y-8 text-slate-600">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">1. Acceptance of Terms</h2>
                        <p>By accessing and using the {{ config('app.name') }} application, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">2. Use License</h2>
                        <p class="mb-4">Permission is granted to temporarily download one copy of the materials (information or software) on {{ config('app.name') }} for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Modify or copy the materials</li>
                            <li>Use the materials for any commercial purpose or for any public display</li>
                            <li>Attempt to decompile or reverse engineer any software contained on the application</li>
                            <li>Remove any copyright or other proprietary notations from the materials</li>
                            <li>Transfer the materials to another person or "mirror" the materials on any other server</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">3. Disclaimer</h2>
                        <p>The materials on {{ config('app.name') }} are provided on an "as is" basis. {{ config('app.name') }} makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">4. Limitations of Liability</h2>
                        <p>In no event shall {{ config('app.name') }} or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on {{ config('app.name') }}, even if {{ config('app.name') }} or an authorized representative has been notified orally or in writing of the possibility of such damage.</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">5. User Conduct</h2>
                        <p>You agree that you will not use the application to:</p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Harass, threaten, embarrass, or cause distress or discomfort to any individual</li>
                            <li>Transmit obscene or offensive content</li>
                            <li>Disrupt the normal flow of dialogue within the application</li>
                            <li>Attempt to gain unauthorized access to our systems</li>
                            <li>Violate any applicable laws or regulations</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">6. Contact Us</h2>
                        <p class="mb-4">If you have any questions about these Terms of Service, please contact us at:</p>
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
