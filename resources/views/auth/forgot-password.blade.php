<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data @init="$store.theme.init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password - DainSys</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="min-h-screen bg-linear-to-br from-slate-50 via-blue-50 to-slate-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 text-slate-900 dark:text-slate-100 antialiased">
        <main class="relative flex min-h-screen items-center justify-center px-4 py-16">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-24 -right-32 h-80 w-80 rounded-full bg-blue-200/40 dark:bg-blue-900/40 blur-3xl"></div>
                <div class="absolute -bottom-32 -left-32 h-80 w-80 rounded-full bg-indigo-200/40 dark:bg-indigo-900/40 blur-3xl"></div>
            </div>

            <div class="relative z-10 flex w-full max-w-lg flex-col items-center gap-8">
                <a class="flex items-center gap-3" href="/">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 shadow-lg">
                        <span class="text-sm font-bold text-white">DS</span>
                    </div>
                    <span class="text-2xl font-bold text-slate-900 dark:text-slate-100">DainSys</span>
                </a>

                @livewire('auth.forgot-password')
            </div>
        </main>

        @livewireScripts
    </body>
</html>
