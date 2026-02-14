@extends('layouts.landing-page')

@section('content')
<!-- Policy Content -->
    <section class="py-24 sm:py-32 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl font-bold text-slate-900 dark:text-slate-100 mb-8">Cookie Policy</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">Last updated: {{ now()->format('F d, Y') }}</p>

            <div class="space-y-8 text-slate-600 dark:text-slate-400">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">What Are Cookies?</h2>
                    <p>Cookies are small files of letters and numbers that we store on your browser or the hard drive of your computer. They contain information about your browsing habits on the application.</p>
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">How We Use Cookies</h2>
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
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Types of Cookies We Use</h2>

                    <div class="mt-4 mb-4">
                        <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Essential Cookies</h3>
                        <p>These cookies are necessary for the operation of the application. They include session cookies and authentication cookies.</p>
                    </div>

                    <div class="mt-4 mb-4">
                        <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Performance Cookies</h3>
                        <p>These cookies help us understand how users interact with the application by collecting and reporting information anonymously.</p>
                    </div>

                    <div class="mt-4 mb-4">
                        <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Preference Cookies</h3>
                        <p>These cookies remember your choices to personalize your experience when you visit the application.</p>
                    </div>

                    <div class="mt-4 mb-4">
                        <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Marketing Cookies</h3>
                        <p>These cookies may be set through the application by our advertising partners to build a profile of your interests and show you relevant advertisements.</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Managing Cookies</h2>
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
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Third-Party Cookies</h2>
                    <p>Some cookies may be set by third-party service providers for analytics, advertising, and other purposes. We encourage you to review their privacy policies for more information.</p>
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Contact Us</h2>
                    <p class="mb-4">If you have any questions about our use of cookies, please contact us at:</p>
                    <p>
                        <strong class="text-slate-900 dark:text-slate-100">{{ config('app.name') }}</strong><br>
                        Email: {{ config('app.contact_email') }}<br>
                        <a href="/" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Back to {{ config('app.name') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
