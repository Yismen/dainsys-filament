<div
    class="w-full rounded-2xl p-8 md:p-10 shadow-sm backdrop-blur"
    style="background:var(--card-bg);border:1px solid var(--card-border);box-shadow:0 4px 24px rgba(0,0,0,.06)"
>
    <div class="mb-8 space-y-1.5 text-center">
        <h1 class="text-gradient-violet text-3xl font-black tracking-tight" style="font-family:'Syne',sans-serif">Set a new password</h1>
        <p style="color:var(--text-muted);font-family:'Plus Jakarta Sans',sans-serif" class="text-sm">Choose a strong password to secure your account.</p>
    </div>

    @if ($passwordWasReset)
        <div class="rounded-xl border px-4 py-3 text-sm" style="background:rgba(16,185,129,.1);color:#059669;border-color:rgba(16,185,129,.2)">
            <p class="font-semibold">Password was reset successfully.</p>
            <p class="mt-1">This page can now be closed.</p>
        </div>
    @endif

    @if (! $passwordWasReset)
    <form class="space-y-5" wire:submit.prevent="resetPassword">
        <div class="space-y-1.5">
            <label class="text-sm font-semibold" style="color:var(--text-muted)" for="email">Email</label>
            <input
                id="email"
                type="email"
                wire:model="email"
                autocomplete="email"
                class="w-full rounded-xl border px-4 py-3 text-sm shadow-sm outline-none transition focus:ring-2"
                style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-base);caret-color:#7c3aed;--tw-ring-color:rgba(124,58,237,.30)"
                {{ $emailLocked ? 'readonly' : '' }}
            />
            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            @if ($emailLocked)
                <p class="text-xs" style="color:var(--text-muted)">This email was prefilled from your reset link.</p>
            @endif
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-semibold" style="color:var(--text-muted)" for="password">New password</label>
            <input
                autofocus
                id="password"
                type="password"
                wire:model="password"
                autocomplete="new-password"
                class="w-full rounded-xl border px-4 py-3 text-sm shadow-sm outline-none transition focus:ring-2"
                style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-base);caret-color:#7c3aed;--tw-ring-color:rgba(124,58,237,.30)"
            />
            @error('password')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-1.5">
            <label class="text-sm font-semibold" style="color:var(--text-muted)" for="password_confirmation">Confirm password</label>
            <input
                id="password_confirmation"
                type="password"
                wire:model="password_confirmation"
                autocomplete="new-password"
                class="w-full rounded-xl border px-4 py-3 text-sm shadow-sm outline-none transition focus:ring-2"
                style="background:var(--card-bg);border-color:var(--card-border);color:var(--text-base);caret-color:#7c3aed;--tw-ring-color:rgba(124,58,237,.30)"
            />
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="resetPassword"
            class="btn-gradient w-full px-4 py-3.5 text-sm font-bold shadow-lg cursor-pointer disabled:cursor-not-allowed disabled:opacity-70"
        >
            <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
            <span wire:loading wire:target="resetPassword" class="inline-flex items-center gap-2 whitespace-nowrap align-middle">
                <svg class="h-4 w-4 shrink-0 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Processing...
            </span>
        </button>
    </form>
    @endif
</div>
