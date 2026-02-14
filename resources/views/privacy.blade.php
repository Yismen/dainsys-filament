@extends('layouts.landing-page')

@section('content')
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
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Contact Us</h2>
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

@endsection
