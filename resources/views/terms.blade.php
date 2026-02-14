@extends('layouts.landing-page')

@section('content')
<!-- Policy Content -->
    <section class="py-24 sm:py-32 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl font-bold text-slate-900 dark:text-slate-100 mb-8">Terms of Service</h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">Last updated: {{ now()->format('F d, Y') }}</p>

            <div class="space-y-8 text-slate-600 dark:text-slate-400">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">1. Acceptance of Terms</h2>
                    <p>By accessing and using the {{ config('app.name') }} application, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">2. Use License</h2>
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
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">3. Disclaimer</h2>
                    <p>The materials on {{ config('app.name') }} are provided on an "as is" basis. {{ config('app.name') }} makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">4. Limitations of Liability</h2>
                    <p>In no event shall {{ config('app.name') }} or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on {{ config('app.name') }}, even if {{ config('app.name') }} or an authorized representative has been notified orally or in writing of the possibility of such damage.</p>
                </div>

                <div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">5. User Conduct</h2>
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
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">6. Contact Us</h2>
                    <p class="mb-4">If you have any questions about these Terms of Service, please contact us at:</p>
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
