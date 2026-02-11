<div class="w-full max-w-md space-y-6 rounded-3xl bg-white/90 dark:bg-slate-800/90 p-8 shadow-2xl backdrop-blur">
    <div class="space-y-2 text-center">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Welcome back</h1>
        <p class="text-slate-600 dark:text-slate-400">Sign in to access your dashboard</p>
    </div>

    <form class="space-y-5" wire:submit.prevent="login">
        <div class="space-y-2">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-300" for="email">Email</label>
            <input
                id="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-3 text-slate-900 dark:text-slate-100 shadow-sm outline-none transition focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900"
            />
            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="text-sm font-medium text-slate-700 dark:text-slate-300" for="password">Password</label>
            <input
                id="password"
                type="password"
                wire:model="password"
                autocomplete="current-password"
                class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 px-4 py-3 text-slate-900 dark:text-slate-100 shadow-sm outline-none transition focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900"
            />
            @error('password')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 text-blue-600 dark:text-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400"
                />
                Remember me
            </label>
            <a class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <div class="text-center">
            <a class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" href="/">Back to home</a>
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-linear-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-blue-500/30"
        >
            Sign In
        </button>
    </form>
</div>
