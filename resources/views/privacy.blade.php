<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data @init="$store.theme.init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Privacy Policy - {{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="bg-linear-to-br from-slate-50 via-blue-50 to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 text-slate-900 dark:text-slate-100 antialiased">

        <x-navigation :show-back-link="true" back-link-text="Back to Home" />

        <!-- Policy Content -->
        <section class="py-24 sm:py-32 relative">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-5xl font-bold text-slate-900 dark:text-slate-100 mb-8">Privacy Policy</h1>
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">Last updated: {{ now()->format('F d, Y') }}</p>

                <div class="space-y-8 text-slate-600 dark:text-slate-400">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Introduction</h2>
                        <p class="mb-4">{{ config('app.name') }} ("we", "our", or "us") operates the DainSys application. This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service and the choices you have associated with that data.</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Information Collection and Use</h2>
                        <p class="mb-4">We collect several different types of information for various purposes to provide and improve our Service to you.</p>

                        <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mt-6 mb-3">Types of Data Collected:</h3>
                        <ul class="list-disc list-inside space-y-2 mb-4">
                            <li><strong>Personal Data:</strong> Email address, first name, last name, phone number, address, and other contact information.</li>
                            <li><strong>Usage Data:</strong> Browser type, IP address, pages visited, time and date of visits, and time spent on pages.</li>
                            <li><strong>Work Data:</strong> Hours tracked, payroll information, performance metrics, and team communication records.</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Use of Data</h2>
                        <p class="mb-4">We use the collected data for various purposes including:</p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>To provide and maintain our Service</li>
                            <li>To notify you about changes to our Service</li>
                            <li>To allow you to participate in interactive features of our Service</li>
                            <li>To provide customer support</li>
                            <li>To gather analysis or valuable information to improve our Service</li>
                            <li>To monitor the usage of our Service</li>
                            <li>To detect, prevent and address technical issues</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Data Security</h2>
                        <p>The security of your data is important to us but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.</p>
                    </div>

                    <div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-4">Contact Us</h2>
                        <p class="mb-4">If you have any questions about this Privacy Policy, please contact us at:</p>
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
