<div
    class="w-full rounded-2xl p-8 md:p-10 shadow-sm backdrop-blur"
    style="background:var(--card-bg);border:1px solid var(--card-border);box-shadow:0 4px 24px rgba(0,0,0,.06)"
>
    <div class="mb-8 space-y-1.5 text-center">
        <h1 class="text-gradient-violet text-3xl font-black tracking-tight" style="font-family:'Syne',sans-serif">Welcome back</h1>
        <p style="color:var(--text-muted);font-family:'Plus Jakarta Sans',sans-serif" class="text-sm">Sign in to your account</p>
    </div>

    <form class="space-y-5" wire:submit.prevent="login">
        <div class="space-y-1.5">
            <label class="text-sm font-semibold" style="color:var(--text-muted)" for="email">Email</label>
            <input
                id="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                class="w-full rounded-xl border px-4 py-3 text-sm shadow-sm outline-none transition focus:ring-2"
                style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-base);caret-color:#7c3aed;--tw-ring-color:rgba(124,58,237,.30);--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color)"
            />
            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-semibold" style="color:var(--text-muted)" for="password">Password</label>
            <input
                id="password"
                type="password"
                wire:model="password"
                autocomplete="current-password"
                class="w-full rounded-xl border px-4 py-3 text-sm shadow-sm outline-none transition focus:ring-2"
                style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-base);caret-color:#7c3aed;--tw-ring-color:rgba(124,58,237,.30);--tw-ring-offset-shadow:var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);--tw-ring-shadow:var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color)"
            />
            @error('password')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex cursor-pointer items-center gap-2 text-sm" style="color:var(--text-muted)">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="h-4 w-4 rounded border transition"
                    style="border-color:var(--card-border);color:#7c3aed;--tw-ring-color:rgba(124,58,237,.30);accent-color:#7c3aed"
                />
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium transition" style="color:#7c3aed" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button
            type="submit"
            class="btn-gradient w-full px-4 py-3.5 text-sm font-bold shadow-lg"
        >
            Sign In
        </button>

        <div class="text-center">
            <a class="text-sm font-medium transition hover:opacity-70" style="color:var(--text-muted)" href="/">&larr; Back to home</a>
        </div>
    </form>
</div>
